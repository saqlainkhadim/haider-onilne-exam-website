<?php

namespace App\Jobs;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateExamQuestions implements ShouldQueue
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
        //
        $exam_id  = $this->details['exam_id'];
        $section_id  = $this->details['section_id'];
        $total_questions = $this->details['total_questions'];
        $options  = ['A','B','C','D'];
        for($i = 1;$i <= $total_questions;$i++){
            $q =  Question::create([
                'exam_id' => $exam_id,
                'section_id' => $section_id,
                'question_type' => 1,
                'question' => $i,
                'option_type' => 'ABCD',// By default 
            ]);
             // Create 4 options 
             foreach($options as $op){
                  Option::create([
                      'question_id' => $q->id,
                      'option' => $op,
                     
                  ]);
             }
        }
    }
}
