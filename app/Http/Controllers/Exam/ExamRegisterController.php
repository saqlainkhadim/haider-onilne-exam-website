<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\AssignStudentRequest;
use App\Http\Resources\ExamResource;
use App\Jobs\Exams\ExamRegisterAdmins;
use App\Jobs\Exams\ExamRegisterStudent;
use App\Jobs\Exams\ExamRegisterTutor;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\ExamTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use DataTables;

class ExamRegisterController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ExamStudent::with('exam')->with('student')->with('teacher')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('exam_name', function ($row) {
                    return isset($row->exam) ? $row->exam->name : '';
                })
                ->addColumn('student_name', function ($row) {
                    return isset($row->student) ? $row->student->first_name . " " . $row->student->last_name : '';
                })
                ->addColumn('teacher_name', function ($row) {
                    return isset($row->teacher) ? $row->teacher->first_name . " " . $row->teacher->last_name : '';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? 'Finished' : 'Open';
                })

                ->rawColumns(['status', 'exam_name', 'student_name'])
                ->make(true);
        }
        return view('exams.register_exam');
    }
    public function registerExam()
    {
        $data['exams'] = Exam::latest()->get();
        return view('exams.assign_student', $data);
    }
    public function assignStudent(AssignStudentRequest $request)
    {
        ExamStudent::create([
            'exam_id' => decode($request->exam_id),
            'student_id' => decode($request->student_id),
            'teacher_id' => decode($request->teacher_id),
        ]);

        $details = [
            "exam_id" => decode($request->exam_id),
            "student_id" => decode($request->student_id),
            'teacher_id' => decode($request->teacher_id),
        ];
        // Todo:: Email to student
        dispatch(new ExamRegisterStudent($details));
        // Email to Tutor
        dispatch(new ExamRegisterTutor($details));
        // Email to Admins
        dispatch(new ExamRegisterAdmins($details));
        Session::flash('success_message', 'Student registerd for the exam successfully!');
        return redirect()->route('admin.exams.index');
    }
    public function loadStudents(Request $request)
    {
        $sql = 'SELECT u.id,u.first_name,u.last_name FROM users u WHERE id NOT IN (SELECT student_id FROM exam_students es WHERE exam_id={exam_id}) AND u.type={user_type}';
        $sql = str_replace(array('{exam_id}', '{user_type}'), array(decode($request->exam_id), config('constants.USER_TYPE.STUDENT')), $sql);
        $students = DB::select($sql);
        // Students
        $data = [];
        foreach ($students as $s) {
            $data[] = [
                'id' => encode($s->id),
                'name' => $s->first_name . " " . $s->last_name
            ];
        }
        // Teachers
        $sql = 'SELECT u.id,u.first_name,u.last_name FROM users u WHERE u.type={user_type}';
        $sql = str_replace(array('{exam_id}', '{user_type}'), array(decode($request->exam_id), config('constants.USER_TYPE.TEACHER')), $sql);
        $teachersData = DB::select($sql);
        $teachers = [];
        foreach ($teachersData as $s) {
            $teachers[] = [
                'id' => encode($s->id),
                'name' => $s->first_name . " " . $s->last_name
            ];
        }
        return response()->json(['success' => true, 'students' => $data, 'teachers' => $teachers]);
    }

    public function get_exams(Request $request)
    {
        $search_text = $request->input('search_text');
        return  ExamResource::collection(Exam::where('name', 'LIKE', "%$search_text%")->get());
    }
}
