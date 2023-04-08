<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateQuestionOptions;
use App\Models\ExamSection;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExamQuestionController extends Controller
{
    //
    public function edit($exam_id, $section_id)
    {
        $data['section'] = ExamSection::with('exam')->where('id', decode($section_id))->first();
        $data['questions'] = $questions = Question::with('options')->where('exam_id', decode($exam_id))->where('section_id', decode($section_id))->limit( $data['section']->questions )->get();
        if ($questions->count() == 0) {
            Session::flash('error_message', 'No Questions found for this section.');
            return redirect()->back();
        }
        
        return view('exams.questions.edit', $data);
    }

    public function update(Request $request)
    {
        dispatch(new UpdateQuestionOptions([
            'question_types' => $request->question_types,
            'option_types'   => $request->option_types,
            'question_id'    => decode($request->question_id),
        ]));
        Session::flash('success_message', 'Question types Updated');
        return redirect()->route('admin.exams.edit', ['id' => $request->exam_id]);
    }
}
