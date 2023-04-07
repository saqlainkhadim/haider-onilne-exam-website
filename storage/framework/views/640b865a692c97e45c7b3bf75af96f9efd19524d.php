<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('constants.APP_NAME')); ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?php echo e(asset('assets/img/favicon.png')); ?>" rel="icon">
    <link href="<?php echo e(asset('assets/img/apple-touch-icon.png')); ?>" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendor/boxicons/css/boxicons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendor/quill/quill.snow.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendor/quill/quill.bubble.css')); ?>" rel="stylesheet">
    <!-- TOAST library -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/libraries/toast/css/simple-notify.min.css')); ?>" />

    <!-- Template Main CSS File -->
    <link href="<?php echo e(asset('assets/css/style.css')); ?>" rel="stylesheet">
    <style>
      input.error {
          border-color:red;
          box-shadow:0 0 0 2px tomato
      }
      select.error {
          border-color:red;
          box-shadow:0 0 0 2px tomato
      }
       .error{
        color: tomato !important;
        font-weight: bold;
       }
       textarea.error{
        border-color:red;
          box-shadow:0 0 0 2px tomato
       }
       .cursor-pointer{
        cursor: pointer;
       }

    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>

<body>

    <!-- ======= Header ======= -->
      <?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
     <?php echo $__env->make('includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

         <?php echo $__env->yieldContent('content'); ?>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo e(asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/quill/quill.min.js')); ?>"></script>

    <!-- Template Main JS File -->
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- TOAST library -->
   <script src="<?php echo e(asset('assets/libraries/toast/js/simple-notify.min.js')); ?>"></script>
   <?php echo $__env->make('includes.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('scripts'); ?>

</body>

</html>
<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>