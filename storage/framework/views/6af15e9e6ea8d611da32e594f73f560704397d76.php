<?php if(Session::has('success_message')): ?>
<script>
     new Notify ({
        title: 'Success',
        text: "<?php echo e(Session::get('success_message')); ?>",
        status: 'success', // can be warning, success , error
        autoclose: true,
    });
</script>
<?php endif; ?>
<?php if(Session::has('error_message')): ?>
<script>
     new Notify ({
        title: 'Error',
        text: "<?php echo e(Session::get('error_message')); ?>",
        status: 'error', // can be warning, success , error
        autoclose: true,
    });
</script>
<?php endif; ?>

<?php if(Session::has('warning_message')): ?>
<script>
     new Notify ({
        title: 'Warning',
        text: "<?php echo e(Session::get('warning_message')); ?>",
        status: 'warning', // can be warning, success , error
        autoclose: true,
    });
</script>
<?php endif; ?><?php /**PATH /var/www/html/resources/views/includes/messages.blade.php ENDPATH**/ ?>