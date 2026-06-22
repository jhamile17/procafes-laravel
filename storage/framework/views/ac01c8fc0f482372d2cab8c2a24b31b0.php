<!doctype html>
<html lang="es">
<head>
    <?php if (isset($component)) { $__componentOriginal30de8f84a3b53438fc10f8a5d367579a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30de8f84a3b53438fc10f8a5d367579a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.head','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30de8f84a3b53438fc10f8a5d367579a)): ?>
<?php $attributes = $__attributesOriginal30de8f84a3b53438fc10f8a5d367579a; ?>
<?php unset($__attributesOriginal30de8f84a3b53438fc10f8a5d367579a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30de8f84a3b53438fc10f8a5d367579a)): ?>
<?php $component = $__componentOriginal30de8f84a3b53438fc10f8a5d367579a; ?>
<?php unset($__componentOriginal30de8f84a3b53438fc10f8a5d367579a); ?>
<?php endif; ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-light">
    <main class="min-vh-100 d-flex align-items-center py-4">
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo e($slot ?? ''); ?>

        </div>
    </main>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH E:\Pagina-web-\resources\views/layouts/auth.blade.php ENDPATH**/ ?>