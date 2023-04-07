
<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <h1>Reporting | Basic Summary</h1>
    <nav class="d-flex justify-content-between pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Reporting</li>
        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="<?php echo e(route('admin.reporting.index')); ?>" method="get">
                        <div class="card-body mt-2">
                            <div class="row justify-content-center">
                                <div class="col-6">
                                    <label for="select-exam" class="form-label">Select Exam</label>
                                    <select class="form-control" id="select-exam" name="exam_id">
                                        <option select="">Select Exam</option>
                                        <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(encode($ex->id)); ?>"><?php echo e($ex->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                </div>
                                <div class="col-6 m-2">
                                    <label for="select-student" class="form-label">Select Student</label>
                                    <select class="form-control" id="select-student" name="student_id">
                                        <option value="">Select Student</option>
                                    </select>

                                </div>
                                <div class="col-6 m-2 text-center">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>

                            </div>
                        </div>
                        </from>
                </div>

            </div>
        </div>
        <?php if(isset($result)): ?>
        <div class="row">
            <div class="card">
                <div class="card-body mt-2">
                    <?php if(isset($exam) && isset($student)): ?>
                    <div class="col-6 text-center">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Exam Name <span class=""><?php echo e($exam->name); ?></span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Student Name <span class=""><?php echo e($student->first_name." ".$student->last_name); ?></span></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-12">



                        <table class="table table-striped data-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sections = [];
                                ?>
                                <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!in_array($detail['section_name'],$sections)): ?>
                                <tr>
                                    <th colspan="2"><?php echo e($detail['section_name']); ?></th>
                                </tr>
                                <?php array_push($sections,$detail['section_name']) ?>


                                <?php endif; ?>

                                <tr>
                                    <td><?php echo e($detail['question']); ?></td>
                                    <td><?php echo e($detail['user_answer']); ?></td>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>
<?php echo $__env->make('includes.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script>
    $(function() {
        $(document).on('change', '#select-exam', loadStudents);
    });

    function loadStudents() {
        var exam_id = $(this).val();
        if (exam_id == "") {
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "<?php echo e(route('admin.reporting.get_students')); ?>",
            data: {
                exam_id
            },
            success: function(response) {
                $("#select-student").find('option').not(':first').remove();
                // add students 
                var mySelect = $('#select-student');
                $.each(response.students, function(val, text) {
                    mySelect.append(
                        $('<option></option>').val(text.id).html(text.name)
                    );
                });

            },
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/reporting/index.blade.php ENDPATH**/ ?>