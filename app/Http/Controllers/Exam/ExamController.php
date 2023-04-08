<?php

namespace App\Http\Controllers\Exam;

use Exception;
use DataTables;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamFile;
use App\Models\Question;
use App\Models\ExamResult;
use App\Models\ExamSection;
use App\Models\ExamStudent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Else_;
use App\Models\ExamResultDetail;
use App\Http\Services\UploadFile;
use App\Jobs\CreateExamQuestions;
use App\Models\ExamSectionOption;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailTutorAndAdmins;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Exam::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn  = '';
                    //$btn .= '<a title="Register Exam" href="' . route('admin.exams.assign_student', ['id' => encode($row->id)]) . '"><i class="bi bi-person-check m-1 text-primary cursor-pointer"></i></a>';
                    $btn .= '<a  title="Edit Exam" href="' . route('admin.exams.edit', ['id' => encode($row->id)]) . '"><i class="bi bi-pen text-primary m-1 cursor-pointer"></i></a>';
                    $btn .= '<i class="bi bi-trash3 text-primary cursor-pointer m-1 delete-record" data-id="' . encode($row->id) . '"></i>';
                    return  $btn;
                    //return $btn;
                })
                //     ->addColumn('type', function($row){
                //         switch($row->type){
                //            case config('constants.USER_TYPE.ADMIN'):
                //              $label  = "Admin";
                //              break;
                //              case config('constants.USER_TYPE.TEACHER'):
                //                 $label  = "Tutor";
                //                 break;
                //                 case config('constants.USER_TYPE.STUDENT'):
                //                     $label  = "Student";
                //                     break;
                //                     default:
                //                         $label  = "-";
                //                         break;
                //         }
                //         return $label;
                //    })
                ->rawColumns(['action', 'type'])
                ->make(true);
        }
        return view('exams.index');
    }
    public function edit($id)
    {
        $data['exam']  = Exam::with('sections')->findOrFail(decode($id));
        $data['tutors']  = $this->getTutors();
        return view('exams.edit', $data);
    }
    public function create()
    {
        $data['tutors']  = $this->getTutors();
        return view('exams.create', $data);
    }
    private function getTutors()
    {
        return User::where('type', '=', config('constants.USER_TYPE.TEACHER'))->select('id', 'first_name', 'last_name')->get();
    }
    public function store(Request $request)
    {
        $exam = Exam::create([
            'name' => $request->name,
            'description' => $request->description,
            // 'teacher_id' => decode($request->tutor_id),
            // 'student_id' => decode($request->student_id),
        ]);
        // Upload File
        $fileUpload  = new UploadFile;
        $exam_sections = [];
        $files = $request->file('files');
        $question_no = 1;
        foreach ($request->sections as $key => $s) {
            // $path = $fileUpload->fileUpload($files[$key]);
            $fileName = Str::random(10) . time() . '.' . $files[$key]->getClientOriginalExtension();
            $files[$key]->move(public_path('/uploads'), $fileName);
            // $filename = pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME);
            $exam_sections = [
                // 'exam_id' => decode($exam->id),
                'exam_id' => $exam->id,
                'section_name' => $s,
                'questions' => $request->questions[$key],
                'time' => $request->time_limit[$key],
                'break_duration' => $request->breaks[$key],
                'file' => $fileName,
                'question_type' => $request->question_types[$key],
                'free_textboxes' => $request->free_textbox[$key],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $ex_section = ExamSection::create($exam_sections);

            foreach($request->option_types[$key] as $option_type){
                ExamSectionOption::create(
                    [
                        'exam_section_id' => $ex_section->id,
                        'option' => $option_type,
                    ]
                );
            }




            dispatch(new CreateExamQuestions(['exam_id' => ($exam->id), 'section_id' => $ex_section->id, 'total_questions' => $request->questions[$key],
            'free_textboxes' => $request->free_textbox[$key],
            'question_type' => $request->question_types[$key],
            'option_types' => $request->option_types[$key] ,

                ]));
        }



        Session::flash('success_message', 'Exam added successfully!');
        return redirect()->back();
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $exam  = Exam::where('id', decode($request->exam_id))->first();
        $exam->name  = $request->name;
        $exam->description = $request->description;
        // $exam->teacher_id = decode($request->tutor_id);
        $exam->refresh();
        // Upload File
        $fileUpload  = new UploadFile;
        $exam_sections = [];
        $files = $request->file('files');
        foreach ($request->sections as $key => $s) {
            // $filename = pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME);
            // check if want to create or update the section
            $action = isset($request->section_id[$key]) ? 'edit' : 'create';
            $section_id = isset($request->section_id[$key]) ? $request->section_id[$key] : null;
            // dd($s,$action);
            Log::info($action);

            if ($action == "create") {
                // $path = $fileUpload->fileUpload($files[$key]);

                $fileName = Str::random(10) . time() . '.' . $files[$key]->getClientOriginalExtension();
                $files[$key]->move(public_path('/uploads'), $fileName);

                $exam_sections = [
                    'exam_id' => $exam->id,
                    'section_name' => $s,
                    'questions' => $request->questions[$key],
                    'time' => $request->time_limit[$key],
                    'break_duration' => $request->breaks[$key],
                    'file' => $fileName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $ex_section = ExamSection::create($exam_sections);
                dispatch(new CreateExamQuestions(['exam_id' => $exam->id, 'section_id' => $ex_section->id, 'total_questions' => $request->questions[$key]]));
            } else {
                $file = isset($files[$section_id]) ? $files[$section_id] : null;
                $exam_sections = [
                    'exam_id' => $exam->id,
                    'section_name' => $s,
                    'questions' => $request->questions[$key],
                    'time' => $request->time_limit[$key],
                    'break_duration' => $request->breaks[$key],
                    //'file' => $path,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                // check if file is set if yes then delete old file and upload new path and store new path
                if ($file <> null) {
                    // upload new file to the server
                    $exam_sections['file'] =  $fileName = Str::random(10) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads'), $fileName);
                    // delete old file from server

                    if (isset($request->old_file[$section_id]) && file_exists(public_path('/uploads/' . $request->old_file[$section_id]))) {
                        unlink(public_path('/uploads/' . $request->old_file[$section_id]));
                    }
                }
                ExamSection::whereId(decode($section_id))->update($exam_sections);
            }
        }

        // //send email to all students
        // $details = [
        // 	'subject' => 'Exam Invitation for '.$exam->name,
        //     'exam_name' => $exam->name,
        //     'exam_id' => $exam->id,
        //     'teacher_id' => decode($request->tutor_id),
        //     'student_id' => decode($request->student_id),
        // ];
        // dispatch(new \App\Jobs\SendQueueEmail($details));
        Session::flash('success_message', 'Exam updated successfully!');
        return redirect()->back();
    }
    public function attendExam(Request $request, $exam_id, $user_id)
    {
        $current_section_id = isset($request->section) && !empty($request->section) ? decode($request->section) : null;
        $data['exam'] = $exam = Exam::with('sections')->findOrFail(decode($exam_id));
        $examStudent = ExamStudent::where('student_id', decode($user_id))->where('exam_id', decode($exam_id))->first();
        if ($examStudent->status  == 1) {
            // already completed
            Session::flash('exam_success', 'This exam has already been taken.');
            return redirect()->route('exam.result');
        }


        $data['total_sections'] = $exam->sections;

        if (null <> $current_section_id) {
            $collection = collect($exam->sections);
            $data['section'] = $section = $collection->filter(function ($item) use ($current_section_id) {
                return $item->id == $current_section_id;
            })->first();
        } else {
            // first section
            $data['section'] = $section = $exam->sections->first();
        }

        // Create Exam Seection
        $examResult = ExamResult::firstOrCreate([
            'user_id' => decode($user_id),
            'section_id' => $section->id,
            'exam_id' => $exam->id
        ], [
            'user_id' => decode($user_id),
            'section_id' => $section->id,
            'exam_id' =>  $exam->id,
            'start_time' => null,
            // 'start_time' => Carbon::now('UTC')->format('Y-m-d H:i:s'),
        ]);
        $exam_result = ExamResult::with('exam_result_details')->where(['user_id' => decode($user_id), 'exam_id' => decode($exam_id), 'section_id' => $section->id])->first();
        $section_total_minutes = ExamSection::select('time')->where('id', $section->id)->first();
        $data['show_popup'] = $exam_result->start_time == null ? true : false; // show pop up only first time
        if ($examResult->wasRecentlyCreated) {

            // Get
            $data['section_remaining_time'] = $section_total_minutes->time . ":00";
        } else {

            $data['section_start_time'] = $section_start_time =  isset($exam_result) ? Carbon::parse($exam_result->start_time) : null;
            // Get section time


            $final_diff = null;
            if ($section_total_minutes) {
                $section_end_time = Carbon::parse($exam_result->start_time)->addMinutes($section_total_minutes->time);
                if (Carbon::now() < $section_end_time) {
                    $remainingSeconds = $section_end_time->diffInSeconds(Carbon::now());
                    $remainingSeconds = $remainingSeconds + 1; // Add

                    $data['section_remaining_time'] = $final_diff = gmdate("i:s", $remainingSeconds);
                } else {
                    $data['section_remaining_time'] = $final_diff = "00:00";
                }
            }
        }

        // find next section ID
        //dd($section);


        $data['next_section'] = $next_section = $this->findNextSection($exam->sections, $section->id);
        // check if this section has already been submitted if so user can not submit it again

        if ((isset($next_section) && isset($exam_result) && $exam_result->status == 1)) {
            $to =  route('exam.attend', ['exam_id' => $exam_id, 'user_id' => $user_id]) . "?section=" . encode($next_section->id);
            return redirect()->to($to);
        }
        if ($next_section <> null) {
            $data['next_section'] = route('exam.attend', ['exam_id' => $exam_id, 'user_id' => $user_id]) . "?section=" . encode($next_section->id);
        }
        // find prev section ID
        //    $data['prev_section'] = $prev_section = collect($exam->sections)->filter(function ($item) use($section){
        //     return $item->id  < $section->id;
        //    })->first();

        // if($prev_section <> null){
        //     $data['prev_section'] = route('exam.attend',['exam_id' => $exam_id,'user_id' => $user_id])."?section=".encode($prev_section->id);
        // }

        $data['questions'] = Question::where('exam_id', $exam->id)->where('section_id', $section->id)->with('active_options')->limit($section->questions)->get();
        $data['exam_id'] = $exam_id;
        $data['user_id'] = $user_id;
        // User is visiting so entery in examResults
        $data['section'] = $section;
        $data['exam_result'] = $exam_result;
        return view('exams.attend', $data);
    }
    private function findNextSection($sections, $current_section_id)
    {
        return collect($sections)->filter(function ($item) use ($current_section_id) {
            return $item->id  > $current_section_id;
        })->first();
    }
    public function submitExamSection(Request $request)
    {
        $examResult = ExamResult::where('section_id', decode($request->section_id))
            ->where('exam_id', decode($request->exam_id))
            ->where('user_id', decode($request->student_id))
            ->first();
        if ($examResult) {
            $examResult->status = 1; // SUBMITTED
            $examResult->save();
        }
        return redirect()->to($request->next_section);
    }
    public function saveExam(Request $request)
    {
        $question = Question::find(decode($request->question_id));

        $examResult = ExamResult::where([
            'user_id' => decode($request->user_id),
            'section_id' => decode($request->section_id),
            'exam_id' => decode($request->exam_id),
        ])->first();

        //    $examResult = ExamResult::firstOrCreate([
        //     'user_id' => decode($request->user_id),
        //     'section_id' => decode($request->section_id),
        //     'exam_id' => decode($request->exam_id)
        // ], [
        //     'user_id' => decode($request->user_id),
        //     'section_id' => decode($request->section_id),
        //     'exam_id' => decode($request->exam_id),
        //     'time_remaining' => 0, // Just for now will change in future
        // ]);
        // check the action
        if ($request->action == "remove") {
            if ($examResult) {
                ExamResultDetail::where('exam_result_id', $examResult->id)
                    ->where('question_id', decode($request->question_id))
                    ->where('option_id', decode($request->option_id))
                    ->delete();
                return response()->json(['success' => true, 'message' => 'OK']);
            }
        }
        if ($examResult) {
            $toBeUpdated = [
                'exam_result_id' => $examResult->id,
                'question_id' => decode($request->question_id),
                'option_id' => 0,
            ];

            if ($question->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO')) {
                $toBeUpdated['option_id'] = decode($request->option_id);
            }
            if ($question->question_type == config('constants.EXAM_QUESTION_TYPE.TEXT')) {
                $toBeUpdated['text_answer'] = $request->option_id;
            }
            ExamResultDetail::updateOrCreate(
                ['exam_result_id' => $examResult->id, 'question_id' => decode($request->question_id)],
                $toBeUpdated
            );
            return response()->json(['success' => true, 'message' => 'OK']);
        }
        return response()->json(['success' => false, 'message' => 'Exam details not found!']);
    }
    public function submitExam(Request $request)
    {
        $examResult  = Exam::find(decode($request->exam_id));

        if ($examResult) {
            Session::flash('exam_success', 'Thanks for submitting the Exam.');
            // save exam result section also
            $examSection  = ExamResult::where('exam_id', $examResult->id)->where('section_id', decode($request->section_id))
                ->where('user_id', decode($request->student_id))->first();
            if ($examSection) {
                $examSection->status  = 1;
                $examSection->save();
            }


            // save Exam student also change status to 1
            $examSec = ExamStudent::where('exam_id', $examResult->id)->where('student_id', decode($request->student_id))->first();
            if ($examSec) {
                $examSec->status  = 1;
                $examSec->save();
            }

            // send email tutor and admins
            $details = [
                "exam_id" => $examResult->id,
                'student_id' => decode($request->student_id),
            ];
            dispatch(new \App\Jobs\SendEmailTutorAndAdmins($details));
            return redirect()->route('exam.result');
        }
        Session::flash('error_message', 'Something went wrong!');
        return redirect()->back();
    }
    public function examResult()
    {
        //if(!Session::has('exam_success')){abort(404);}
        return view('exams.result');
    }
    public function delete(Request $request)
    {
        $exam =  Exam::find(decode($request->id));
        $exam_id  = $exam->id;
        $exam->delete();
        // delete exam sections
        $sections  = ExamSection::where('exam_id', $exam_id)->get();
        foreach ($sections as $se) {
            if (file_exists(public_path('/uploads/' . $se->file))) {
                unlink(public_path('/uploads/' . $se->file));
                $se->delete();
            }
        }
        // exam students
        try {
            ExamStudent::where('exam_id', $exam_id)->delete();
            // Exam Result
            ExamResult::where('exam_id', $exam_id)->delete();
        } catch (Exception $ex) {
        }

        Session::flash('success_message', 'Exam Deleted!');
        return response()->json(['success' => true, 'message' => 'Exam deleted successfully!']);
    }
    public function examSectionStart(Request $request)
    {

        $examResult =  ExamResult::where('user_id', decode($request->user_id))
            ->where('section_id', decode($request->section_id))
            ->where('exam_id', decode($request->exam_id))
            ->first();
        if ($examResult) {
            $examResult->start_time = Carbon::now('UTC')->format('Y-m-d H:i:s');
            $examResult->save();
            return response()->json(['success' => true, 'message' => 'OK']);
        }
        return response()->json(['success' => false, 'message' => 'No detail found']);
    }
}
