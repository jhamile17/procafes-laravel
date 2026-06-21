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
<body>
    <?php if (isset($component)) { $__componentOriginal7a1851460580b016997ecb03412ebcac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a1851460580b016997ecb03412ebcac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $attributes = $__attributesOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__attributesOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a1851460580b016997ecb03412ebcac)): ?>
<?php $component = $__componentOriginal7a1851460580b016997ecb03412ebcac; ?>
<?php unset($__componentOriginal7a1851460580b016997ecb03412ebcac); ?>
<?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo e($slot ?? ''); ?>

    </main>

    <?php if (isset($component)) { $__componentOriginal4766510e0268a7a5917e77b146281554 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4766510e0268a7a5917e77b146281554 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4766510e0268a7a5917e77b146281554)): ?>
<?php $attributes = $__attributesOriginal4766510e0268a7a5917e77b146281554; ?>
<?php unset($__attributesOriginal4766510e0268a7a5917e77b146281554); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4766510e0268a7a5917e77b146281554)): ?>
<?php $component = $__componentOriginal4766510e0268a7a5917e77b146281554; ?>
<?php unset($__componentOriginal4766510e0268a7a5917e77b146281554); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal14e186768dc9a76fc491a58308a9717d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14e186768dc9a76fc491a58308a9717d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ecommerce.whatsapp','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ecommerce.whatsapp'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14e186768dc9a76fc491a58308a9717d)): ?>
<?php $attributes = $__attributesOriginal14e186768dc9a76fc491a58308a9717d; ?>
<?php unset($__attributesOriginal14e186768dc9a76fc491a58308a9717d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14e186768dc9a76fc491a58308a9717d)): ?>
<?php $component = $__componentOriginal14e186768dc9a76fc491a58308a9717d; ?>
<?php unset($__componentOriginal14e186768dc9a76fc491a58308a9717d); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginalc19463f803328a22a9f39d42203ec2bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc19463f803328a22a9f39d42203ec2bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chat.button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('chat.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc19463f803328a22a9f39d42203ec2bf)): ?>
<?php $attributes = $__attributesOriginalc19463f803328a22a9f39d42203ec2bf; ?>
<?php unset($__attributesOriginalc19463f803328a22a9f39d42203ec2bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc19463f803328a22a9f39d42203ec2bf)): ?>
<?php $component = $__componentOriginalc19463f803328a22a9f39d42203ec2bf; ?>
<?php unset($__componentOriginalc19463f803328a22a9f39d42203ec2bf); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal48a084be812150cc0b2a0422777d5c65 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a084be812150cc0b2a0422777d5c65 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chat.modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('chat.modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a084be812150cc0b2a0422777d5c65)): ?>
<?php $attributes = $__attributesOriginal48a084be812150cc0b2a0422777d5c65; ?>
<?php unset($__attributesOriginal48a084be812150cc0b2a0422777d5c65); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a084be812150cc0b2a0422777d5c65)): ?>
<?php $component = $__componentOriginal48a084be812150cc0b2a0422777d5c65; ?>
<?php unset($__componentOriginal48a084be812150cc0b2a0422777d5c65); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginal02fb4485cd3af492e3a5c9160e853aa2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal02fb4485cd3af492e3a5c9160e853aa2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ecommerce.cart-offcanvas','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ecommerce.cart-offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal02fb4485cd3af492e3a5c9160e853aa2)): ?>
<?php $attributes = $__attributesOriginal02fb4485cd3af492e3a5c9160e853aa2; ?>
<?php unset($__attributesOriginal02fb4485cd3af492e3a5c9160e853aa2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal02fb4485cd3af492e3a5c9160e853aa2)): ?>
<?php $component = $__componentOriginal02fb4485cd3af492e3a5c9160e853aa2; ?>
<?php unset($__componentOriginal02fb4485cd3af492e3a5c9160e853aa2); ?>
<?php endif; ?>

    <script>
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>',
            routes: {
                index: '<?php echo e(route('cart.index')); ?>',
                add: '<?php echo e(route('cart.add')); ?>',
                base: '<?php echo e(url('/cart')); ?>',
                clear: '<?php echo e(route('cart.clear')); ?>',
            }
        };

        window.App = {
            isAuth: <?php echo json_encode(auth()->check(), 15, 512) ?>,
            routes: {
                login: '<?php echo e(route('login')); ?>',
                checkout: '<?php echo e(route('checkout')); ?>',
            }
        };
    </script>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH E:\Pagina-web-\resources\views/layouts/app.blade.php ENDPATH**/ ?>