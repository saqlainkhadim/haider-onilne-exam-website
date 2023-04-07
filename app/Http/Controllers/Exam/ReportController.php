<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\User;
use App\Traits\UserAnswersReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    use UserAnswersReport;
    public function index(Request $request)
    {
        if (isset($request->exam_id) && isset($request->student_id)) {
            $data['result'] = $this->getReport(decode($request->exam_id), decode($request->student_id));
        }
        $data['exams'] = Exam::orderBy('name', 'ASC')->get();
        $data['exam'] = Exam::find(decode($request->exam_id));
        $data['student'] = User::find(decode($request->student_id));
        return view('reporting.index', $data);
    }
    public function getStudents(Request $request)
    {
        $students  = ExamStudent::with('student')->where('exam_id', decode($request->exam_id))->get();
        $data  = [];
        foreach ($students as $s) {
            $data[] = [
                'id' => encode($s->student->id),
                'name' => $s->student->first_name . " " . $s->student->last_name
            ];
        }
        return response()->json(['success' => true, 'students' => $data]);
    }
}
