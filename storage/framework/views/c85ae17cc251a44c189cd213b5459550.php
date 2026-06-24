<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo $__env->yieldContent('title', 'PROCAFES'); ?></title>

<link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

<?php echo $__env->yieldPushContent('styles'); ?><?php /**PATH D:\Pagina-web-\resources\views/components/layout/head.blade.php ENDPATH**/ ?>