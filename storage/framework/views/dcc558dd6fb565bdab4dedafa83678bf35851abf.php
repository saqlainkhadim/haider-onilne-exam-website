<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/exam.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="https://www.jqueryscript.net/demo/Simple-Step-By-Step-Site-Tour-Plugin-Intro-js/example/css/demo.css" rel="stylesheet">
    <link href="https://www.jqueryscript.net/demo/Simple-Step-By-Step-Site-Tour-Plugin-Intro-js/introjs.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>
<div class="">

    <nav class="navbar navbar-expand-lg navbar-color">
        <div class="container-fluid test">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <button data-step="1" data-intro="Start Here to Click on this" data-position='left' class="btn btn-outline-light" type="submit">Time remaining <span id="counter"><?php echo e($section_remaining_time); ?></span></button>
            </div>
        </div>
    </nav>
    <?php if(count($exam->sections) > 1): ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-end">

                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>

    <?php endif; ?>
    <div class="container-fuild">
        <div class="row col-12">
            <div class="col-lg-3 scroll-col" data-step="2" data-intro="Please select one from multiple options" data-position='right'>
                <?php
                    $collection = isset($exam_result->exam_result_details) ? $exam_result->exam_result_details:[];
                    $section_id = isset($exam_result) ? $exam_result->section_id : null;

                ?>
                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-around pt-3">
                        <span><?php echo e($q->question); ?></span>
                        <?php if($q->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO')): ?>
                            <?php $__currentLoopData = $q->active_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php

                                    $checked = false;
                                    if(isset($collection) && !empty($collection)){
                                    $checked = $collection->contains(function ($item, $key) use($q,$op,$section,$section_id){
                                    return $item->question_id == $q->id && $item->option_id == $op->id && $section->id == $section_id;
                                    });
                                    }



                                ?>

                                <div>
                                    <input type="radio" class="answers" name="answer_<?php echo e(encode($q->id)); ?>" value="<?php echo e(encode($op->id)); ?>" <?php echo e($checked ? 'checked':''); ?>>
                                    <label for="a"><?php echo e($op->option); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif($q->question_type == config('constants.EXAM_QUESTION_TYPE.TEXT')): ?>
                            <?php
                                $input_value = '';
                                if(isset($collection) && !empty($collection)){
                                $exam_details = $collection->filter(function ($item, $key) use($q,$exam_result){
                                return $item->question_id == $q->id && $item->exam_result_id == $exam_result->id;
                                })->first();
                                $input_value = isset($exam_details) ? $exam_details->text_answer : '';

                                }
                            ?>

                            <div>
                                <input type="text" class="answer-text form-control" name="answer_<?php echo e(encode($q->id)); ?>" value="<?php echo e($input_value); ?>">

                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="btn btn-primary m-4 justify-content-center m-2" data-step="3" data-intro="Finish This on submit button" data-position='right' <?php if($next_section !=null): ?> style="display:none;" <?php endif; ?> id="submit-exam">SUBMIT</button>
                <button type="button" class="btn btn-primary m-2" id="next-btn" <?php if($next_section==null): ?> style="display:none;" <?php endif; ?>>SUBMIT</button>
            </div>
            <div class="col-lg-9 mt-3">
                <div class="accordion" id="accordionExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo e(encode($section->id)); ?>" aria-expanded="true" aria-controls="collapseOne">
                                <?php echo e($section->section_name); ?>

                            </button>
                        </h2>
                        <div id="collapse-<?php echo e(encode($section->id)); ?>" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <iframe src="<?php echo e(route('file.view')); ?>" data-file="<?php echo e(asset('public/uploads/'.$section->file)); ?>" width="900" height="700"></iframe>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


</div>
<form id="submit-exam-form" method="POST" action="<?php echo e(route('exam.submit')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="exam_id" value="<?php echo e(encode($exam->id)); ?>" />
    <input type="hidden" name="section_id" value="<?php echo e(encode($section->id)); ?>" />
    <input type="hidden" name="student_id" value="<?php echo e($user_id); ?>" />
</form>
<form id="submit-section-form" method="POST" action="<?php echo e(route('exam.section.submit')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="exam_id" value="<?php echo e(encode($exam->id)); ?>" />
    <input type="hidden" name="section_id" value="<?php echo e(encode($section->id)); ?>" />
    <input type="hidden" name="next_section" value="<?php echo e($next_section); ?>" />
    <input type="hidden" name="student_id" value="<?php echo e($user_id); ?>" />
</form>
<div class="modal modal-dialog-centered" tabindex="-1" id="confirm-modal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to submit this exam?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" onclick="submitExam();">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-dialog-centered" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="welcome-modal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome</h5>
            </div>
            <div class="modal-body">
                <p>Please refrain from using your browser's navigation buttons during the test as it will invalidate your answers.</p>
                <p>You are taking the <?php echo e($exam->name); ?>.</p>
                <p>During the test, you'll find the answer choices on the left. You can submit your answers with the submit button, or they will be automatically submitted at the end of the allowed time.</p>
                <p>The next section lasts <?php echo e($section->time); ?> minutes and has <?php echo e($section->questions); ?> questions.</p>
                <p>You have <span id="start-timer">10:00 </span>until the next section starts automatically.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="continue">Continue</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo e(URL::asset('public/assets/js/intro.js')); ?>"></script>
<script type="text/javascript">
    (function(global) {
        introJs().start();
        introJs.setOption(option, value);
        if (typeof(global) === "undefined") {
            throw new Error("window is undefined");
        }

        var _hash = "!";
        var noBackPlease = function() {
            global.location.href += "#";

            // Making sure we have the fruit available for juice (^__^)
            global.setTimeout(function() {
                global.location.href += "!";
            }, 50);
        };

        global.onhashchange = function() {
            if (global.location.hash !== _hash) {
                global.location.hash = _hash;
            }
        };

        global.onload = function() {
            noBackPlease();

            // Disables backspace on page except on input fields and textarea..
            document.body.onkeydown = function(e) {
                var elm = e.target.nodeName.toLowerCase();
                if (e.which === 8 && (elm !== 'input' && elm !== 'textarea')) {
                    e.preventDefault();
                }
                // Stopping the event bubbling up the DOM tree...
                e.stopPropagation();
            };
        }
    })(window);
</script>
<script>
    var TimerStart = false;
    var startTimer = "01:00";
    const SHOW_POPUP = "<?php echo e($show_popup); ?>";
    $(function() {
        if (SHOW_POPUP) {
            $("#welcome-modal").modal('show');
        } else {
            startExamTimer();
            TimerStart = true;
        }




        // disable radios
        // $(":radio").click(function() {
        //     var radioName = $(this).attr("name"); //Get radio name
        //     $(":radio[name='" + radioName + "']").attr("disabled", true); //Disable all with the same name
        // });
        // disable input text
        // $(".answers-text").blur(function() {
        //     //$(this).attr("disabled", true);
        // });
        // check selected
        // $("input[type=radio]:checked").each(function() {
        //     const radioName = $(this).attr('name');
        //     $(":radio[name='" + radioName + "']").attr("disabled", true);

        // });

    });
    var EXAM_ID = "<?php echo e($exam_id); ?>";
    var USER_ID = "<?php echo e($user_id); ?>";
    var timer2 = "<?php echo e($section_remaining_time); ?>";
    var SECTION_ID = "<?php echo e(encode($section->id)); ?>";

    var intervalId = window.setInterval(function() {
        var timer = startTimer.split(':');
        //by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer[0], 10);
        var seconds = parseInt(timer[1], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        //minutes = (minutes < 10) ?  minutes : minutes;
        $('#start-timer').html(minutes + ':' + seconds + " ");
        if (minutes < 0) {
            clearInterval(intervalId);
        }
        //check if both minutes and seconds are 0
        if ((seconds <= 0) && (minutes <= 0)) {
            clearInterval(intervalId);
            // Check the next section existance
            startSection();
            $("#welcome-modal").modal('hide');
        };
        startTimer = minutes + ':' + seconds;

    }, 1000);


    //
    $(function() {
        $(document).on('click', '#continue', function() {
            startSection();
        });
        $(document).on('change', '.answers', handleChange);
        $(document).on('blur', '.answer-text', handleChange);
        $(document).on('click', '#submit-exam', showConfirmModel);
        $(document).on('click', '#next-btn', function() {
            submitSection();
        });
        $(document).on('dblclick', '.answers', function() {
            const element = $(this);
            const name = $(this).attr('name');
            $(":radio[name='" + name + "']").prop("checked", false);
            var fields = element.attr('name').split('_');
            var optionId = element.val();
            var questionId = fields[1];

            var data = {
                option_id: optionId,
                question_id: questionId,
                exam_id: EXAM_ID,
                user_id: USER_ID,
                section_id: SECTION_ID,
                action: 'remove',
            };
            ajaxCall(data);

        });
    });

    function startExamTimer() {
        var interval = setInterval(function() {
            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#counter').html(minutes + ':' + seconds);
            if (minutes < 0) {
                clearInterval(interval);
            }
            //check if both minutes and seconds are 0
            if ((seconds <= 0) && (minutes <= 0)) {
                clearInterval(interval);
                // Check the next section existance
                if ($('#next-btn').css('display') != 'none') {
                    document.getElementById("next-btn").click();
                } else if ($('#next-btn').css('display') == 'none') {
                    submitExam();
                }
            };
            timer2 = minutes + ':' + seconds;
        }, 1000);
    }

    function showConfirmModel() {
        $("#confirm-modal").modal('show');
    }

    function submitExam() {
        $("#submit-exam-form").submit();
    }

    function submitSection() {
        $("#submit-section-form").submit();
    }

    function handleChange() {
        var element = $(this);
        // Check element type
        var element_type = element.attr('type');
        var name = $(this).attr('name');

        var fields = element.attr('name').split('_');
        var optionId = element.val();
        var questionId = fields[1];

        var data = {
            option_id: optionId,
            question_id: questionId,
            exam_id: EXAM_ID,
            user_id: USER_ID,
            section_id: SECTION_ID,
            action: 'save',
        };

        ajaxCall(data);
    }

    function ajaxCall(data) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "<?php echo e(route('exam.result.save')); ?>",
            data: data
        });
    }
    function startSection()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "<?php echo e(route('exam.continue.section')); ?>",
            data: {
                exam_id: EXAM_ID,
                user_id: USER_ID,
                section_id: SECTION_ID
            },
            success: function(response) {
                if (response.success) {
                    startExamTimer();
                    TimerStart = true;
                } else {
                    alert('Sorry,Something went wrong Please try again later');
                }

            }
        });
    }
</script>

</body>
<script>

</script>
</html>
<?php /**PATH /var/www/html/resources/views/exams/attend.blade.php ENDPATH**/ ?>