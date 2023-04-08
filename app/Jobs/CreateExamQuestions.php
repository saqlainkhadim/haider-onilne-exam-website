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

        Question::where('exam_id',$exam_id)->delete();
        $total_questions = $this->details['total_questions'];
        $free_textboxes = $this->details['free_textboxes'];
        $question_types = $this->details['question_type'];
        $option_types = $this->details['option_types'];
        $rem_q = $total_questions - $free_textboxes;

        
        $options  = ['A','B','C','D'];
        for($i = 1;$i <= $total_questions;$i++){

            if($i <= $rem_q ){
                $question_type="1";
            }else{
                $question_type="2";
            }

            // NOt Same Pattern
            if($question_types != "Same Pattern"){
                if( count( $option_types ) >= 2){
                    $option_type =  $option_types[ $i % 2 ];
                }else if( count( $option_types ) == 1){
                    $option_type =  $option_types[ 0 ];
                }else{
                    $option_type =  'ABCD';
                }
            }

            $q =  Question::create([
                'exam_id' => $exam_id,
                'section_id' => $section_id,
                'question_type' => $question_type,
                'question' => $i,
                'option_type' => isset($option_type)?$option_type:'ABCD',
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
