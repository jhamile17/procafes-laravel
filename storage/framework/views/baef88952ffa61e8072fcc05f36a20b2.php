<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo $__env->yieldContent('title', 'PROCAFES'); ?></title>

<link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

<?php echo $__env->yieldPushContent('styles'); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<?php echo $__env->yieldPushContent('scripts'); ?><?php /**PATH E:\Pagina-web-\resources\views/components/layout/head.blade.php ENDPATH**/ ?>