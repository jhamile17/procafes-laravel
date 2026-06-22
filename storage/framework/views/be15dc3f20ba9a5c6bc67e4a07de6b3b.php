<?php $__env->startSection('title','Inicio | PROCAFES'); ?>

<?php $__env->startSection('content'); ?>

<section class="hero">
    ...
</section>

<section class="container py-5">

    <div class="text-center mb-5">

        <h2 class="fw-bold">
            Productos Destacados
        </h2>

        <p class="text-muted">
            Descubre nuestros mejores cafés
        </p>

    </div>

    <div class="row g-4">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="col-6 col-md-4 col-lg-3">

                <?php if (isset($component)) { $__componentOriginale342533e8356f784b08c4143dc514056 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale342533e8356f784b08c4143dc514056 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ecommerce.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ecommerce.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale342533e8356f784b08c4143dc514056)): ?>
<?php $attributes = $__attributesOriginale342533e8356f784b08c4143dc514056; ?>
<?php unset($__attributesOriginale342533e8356f784b08c4143dc514056); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale342533e8356f784b08c4143dc514056)): ?>
<?php $component = $__componentOriginale342533e8356f784b08c4143dc514056; ?>
<?php unset($__componentOriginale342533e8356f784b08c4143dc514056); ?>
<?php endif; ?>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Pagina-web-\resources\views/home.blade.php ENDPATH**/ ?>