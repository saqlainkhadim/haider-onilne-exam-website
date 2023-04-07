
<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <h1>Registered Exams</h1>
    <nav class="d-flex justify-content-between   pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Register Exams</li>
        </ol>
        
        <a href="<?php echo e(route('admin.exams.register_exam')); ?>" class="d-flex btn btn-primary float-right">Register Exam</a>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-2">
                        <table class="table table-striped data-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Student Name</th>
                                    <th>Tutor Name</th>
                                    <th width="100px">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<?php echo $__env->make('includes.confirm_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function() {

     

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('admin.exams.register_index')); ?>",
            columns: [{
                    data: 'exam_name',
                    name: 'exam_name'
                },
                {
                    data: 'student_name',
                    name: 'student_name'
                },
                {
                    data: 'teacher_name',
                    name: 'teacher_name'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ]
        });
    });

    function showConfirm() {
        const id = $(this).attr('data-id');
        const modal = $("#confirm-modal");
        modal.find("#record-id").val(id);
        modal.modal('show');
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
                if(response.success){
                    $(".data-table").DataTable().ajax.reload();
                    new Notify ({
                                title: 'Success',
                                text: response.message,
                                status: 'success', // can be warning, success , error
                                autoclose: true,
                    });
                }else if(response.success == false){
                    new Notify ({
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/exams/register_exam.blade.php ENDPATH**/ ?>