<?php

namespace App\Jobs\Exams;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamRegisterStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $student_id = $this->details['student_id'];
        $exam_id = $this->details['exam_id'];
        $exam = Exam::find($exam_id);
        $subject = 'Invitation for Online Mock Exam: ' . $exam->name;

        // Get Student
        $student = User::find($student_id);
        $input['name'] = $student->first_name;
        $input['subject'] = $subject;
        $input['email'] = $student->email;
        $input['exam_name'] = $exam->name;
        $input['link'] = route('exam.attend', ['exam_id' => encode($exam_id), 'user_id' => encode($student->id)]);
        $input['message'] = "Hi Student! You have been registered for an online mock " . $exam->name;
        $input['btn_text'] = 'Attend Online Exam';

        \Mail::send('mail.exam_email_student', ['data' => $input], function ($message) use ($input, $student) {
            $message->to($input['email'], $input['name'])->cc(explode(';', $student->cc_mails))
                ->subject($input['subject']);
        });
    }
}
