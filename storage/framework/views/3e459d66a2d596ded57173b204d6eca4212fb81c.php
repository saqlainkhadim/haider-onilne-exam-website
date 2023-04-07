
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>">Users</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mt-3" enctype="multipart/form-data" name="edit_user_form" method="post" action="<?php echo e(route('admin.users.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e(encode($user->id)); ?>" />
                            <div class="col-12">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo e($user->first_name); ?>">
                            </div>
                            <div class="col-12">
                            <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo e($user->last_name); ?>">
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" value="<?php echo e($user->email); ?>">
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            <div class="col-12">
                                <label for="type" class="form-label">Role</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="">Select User Role</option>
                                    <option value="<?php echo e(config('constants.USER_TYPE.TEACHER')); ?>" <?php echo e($user->type == config('constants.USER_TYPE.TEACHER') ? 'selected':''); ?>>Tutor</option>
                                    <option value="<?php echo e(config('constants.USER_TYPE.STUDENT')); ?>" <?php echo e($user->type == config('constants.USER_TYPE.STUDENT') ? 'selected':''); ?>>Student</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="pic" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" name="profile_pic" id="pic">
                            </div>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js" integrity="sha512-6S5LYNn3ZJCIm0f9L6BCerqFlQ4f5MwNKq+EthDXabtaJvg3TuFLhpno9pcm+5Ynm6jdA9xfpQoMz2fcjVMk9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var USER_ID = "<?php echo e(encode($user->id)); ?>";
    $.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param * 1000000)
}, 'Image size must be less than {0} MB.');
 $(function() {
  $("form[name='edit_user_form']").validate({
    // Specify validation rules
    rules: {
        first_name: {
        required:true,
        maxlength:40,
        minlength:2
     },
     last_name: {
        required:true,
        maxlength:40,
        minlength:2
     },
      email: {
        required: true,
        email: true,
        remote: {
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"<?php echo e(route('admin.users.email.exist')); ?>",
            type:'POST',
            data:{email:this.email,action:'edit',user_id:USER_ID}
     },
      },
      type:{
        required:true,
      },
      password: {
        required: false,
        minlength: 8,
        maxlength:50
      },
      profile_pic:{
        required:false,
        extension: "jpg,jpeg, png",
        filesize:3 // 3 MB
      }
    },
    // Specify validation error messages
    messages: {
         email:{
            remote:"Email already exists!"
         },
         profile_pic:{
            extension:"Please upload jpg or png type image only."
         }
     },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/users/edit.blade.php ENDPATH**/ ?>