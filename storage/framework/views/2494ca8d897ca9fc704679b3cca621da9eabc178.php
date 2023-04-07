
<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <h1>Edit Question Types</h1>
    <nav class="d-flex justify-content-between   pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.exams.index')); ?>">Exams</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.exams.edit',['id' => encode($section->exam->id)])); ?>">Edit Exam</a></li>
            <li class="breadcrumb-item">Edit <?php echo e($section->section_name); ?> Questions</li>

        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mt-3" id="edit-options-form" name="edit-options-form" method="post" action="<?php echo e(route('admin.exam_question.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="exam_id" value="<?php echo e(encode($section->exam->id)); ?>" />
                            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="col-12 question-container">
                                <div class="d-flex justify-content-around pt-3">
                                    <strong>Q: <?php echo e($q->question); ?></strong>
                                    <div>
                                        <input type="radio" class="answers cursor-pointer" name="question_types[<?php echo e(encode($q->id)); ?>]" value="<?php echo e(config('constants.EXAM_QUESTION_TYPE.RADIO')); ?>" <?php echo e(config('constants.EXAM_QUESTION_TYPE.RADIO') == $q->question_type ? 'checked':''); ?>>
                                        <label for="a">Options</label>
                                    </div>
                                    <div>
                                        <input type="radio" class="answers cursor-pointer" name="question_types[<?php echo e(encode($q->id)); ?>]" value="<?php echo e(config('constants.EXAM_QUESTION_TYPE.TEXT')); ?>" <?php echo e(config('constants.EXAM_QUESTION_TYPE.TEXT') == $q->question_type ? 'checked':''); ?>>
                                        <label for="a">Text Box</label>
                                    </div>

                                </div>

                            </div>
                            <div class="col-12 text-center justify-content-center">
                                <select name="option_types[<?php echo e(encode($q->id)); ?>]" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="ABCD" <?php echo e($q->option_type == 'ABCD' ? 'selected':''); ?>>ABCD</option>
                                    <option value="EFGH" <?php echo e($q->option_type == 'EFGH' ? 'selected':''); ?>>EFGH</option>
				    <option value="ABCDE" <?php echo e($q->option_type == 'ABCDE' ? 'selected':''); ?>>ABCDE</option>
				    <option value="FGHJ" <?php echo e($q->option_type == 'FGHJ' ? 'selected':''); ?>>FGHJ </option>
                                    <option value="FGHJK" <?php echo e($q->option_type == 'FGHJK' ? 'selected':''); ?>>FGHJK</option>
                                </select>
                            </div>
                            <hr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-center">
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form><!-- Vertical Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<?php echo $__env->make('includes.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script>
    $(function() {
        hideOptions();
        $(document).on('change','input[type=radio]',handleChange);
    });
    function handleChange()
    {
          var value = $(this).val();
          if(value == 2){
            $(this).closest('.question-container').next().hide();
          }else{
            $(this).closest('.question-container').next().show();
          }
    }

    function hideOptions() {
        $("#edit-options-form input[type=radio]:checked").each(function() {
            if ($(this).val() == 2) {
                 // Hide Element
                 $(this).closest('.question-container').next().hide();
            }
        });
    }

    function deleteRecord() {
        const id = $("#confirm-modal").find("#record-id").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "<?php echo e(route('admin.exams.delete')); ?>",
            type: 'POST',
            data: {
                "id": id,
            },
            success: function(response) {
                $("#confirm-modal").modal('hide');
                if (response.success) {
                    $(".data-table").DataTable().ajax.reload();
                    new Notify({
                        title: 'Success',
                        text: response.message,
                        status: 'success', // can be warning, success , error
                        autoclose: true,
                    });
                } else if (response.success == false) {
                    new Notify({
                        title: 'Error',
                        text: response.message,
                        status: 'error', // can be warning, success , error
                        autoclose: true,
                    });
                }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/exams/questions/edit.blade.php ENDPATH**/ ?>