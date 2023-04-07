
<?php $__env->startSection('content'); ?>

<section class="section dashboard">
  <div class="row">
    <div class="col-xxl-6 col-md-6">
      <div class="card info-card sales-card">
        <div class="card-body">
          <h5 class="card-title">Users <span>| All</span></h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-person"></i>
            </div>
            <div class="ps-3">
              <h6><?php echo e($users_count); ?></h6>
              <span class="text-success small pt-1 fw-bold">12%</span>
              <span class="text-muted small pt-2 ps-1">Active</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xxl-6 col-md-6">
      <div class="card info-card revenue-card">
        <div class="card-body">
          <h5 class="card-title">Exams <span>| All</span></h5>
          <div class="d-flex align-items-center">
            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
              <i class="bi bi-pencil-square"></i>
            </div>
            <div class="ps-3">
              <h6><?php echo e($exams_count); ?></h6>
              <span class="text-success small pt-1 fw-bold">12%</span>
              <span class="text-muted small pt-2 ps-1">Completed</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/index.blade.php ENDPATH**/ ?>