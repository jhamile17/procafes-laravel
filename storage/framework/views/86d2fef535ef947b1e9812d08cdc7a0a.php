<?php $__env->startSection('title', 'Productos - PROCAFES'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $selected = request('categoria');
    $search = request('search');
?>

<div class="container py-4">
    <div class="row g-4">

        <aside class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-procafes">
                    <strong>Categorías</strong>
                </div>

                <div class="list-group list-group-flush">
                    <a href="<?php echo e(route('products')); ?>"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo e(!$selected ? 'active' : ''); ?>">
                        <span>Todas</span>
                        <span class="badge bg-secondary rounded-pill"><?php echo e($products->total()); ?></span>
                    </a>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('products', ['categoria' => $cat->id])); ?>"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo e((string) $selected === (string) $cat->id ? 'active' : ''); ?>">
                            <span><?php echo e($cat->name); ?></span>
                            <span class="badge bg-secondary rounded-pill"><?php echo e($counts[$cat->id] ?? 0); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                <div>
                    <h1 class="h4 mb-1">Todos los productos</h1>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                        <p class="text-muted mb-0">
                            Resultados para: <strong><?php echo e($search); ?></strong>
                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <span class="text-muted"><?php echo e($products->total()); ?> resultado(s)</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->isEmpty()): ?>
                <div class="alert alert-info">
                    No hay productos disponibles con esos filtros.
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-4">
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

                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($products->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Pagina-web-\resources\views/products.blade.php ENDPATH**/ ?>