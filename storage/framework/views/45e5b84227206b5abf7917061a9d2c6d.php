<a href="<?php echo e(route('cart.index')); ?>"
   class="btn btn-outline-dark position-relative rounded-pill">

    <i class="bi bi-cart3"></i>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('cart_count')): ?>
        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
            <?php echo e(session('cart_count')); ?>

        </span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</a><?php /**PATH E:\Pagina-web-\resources\views/components/ecommerce/cart-button.blade.php ENDPATH**/ ?>