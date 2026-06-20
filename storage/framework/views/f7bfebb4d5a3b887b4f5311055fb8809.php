<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Illuminate\Support\Facades\Storage;

    // Compatibilidad: preferir accessor `image_url`, luego `image` almacenada
    if (!empty($product->image_url)) {
        $image = $product->image_url;
    } elseif (!empty($product->image)) {
        $image = Storage::url($product->image);
    } else {
        $image = 'https://via.placeholder.com/600x600?text=PROCAFES';
    }
?>

<div class="card border-0 shadow-sm h-100 product-card">

    
    <div class="ratio ratio-1x1 overflow-hidden">

        <img
            src="<?php echo e($image); ?>"
            alt="<?php echo e($product->name); ?>"
            class="w-100 h-100 object-fit-cover">

    </div>

    
    <div class="card-body d-flex flex-column">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($product->category)): ?>
            <small class="text-muted d-block">
                <?php echo e($product->category->name); ?>

            </small>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($product->brand)): ?>
            <small class="text-muted">
                <?php echo e($product->brand->name); ?>

            </small>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <h6 class="fw-semibold mt-1 mb-2">
            <?php echo e($product->name); ?>

        </h6>

        <div class="mt-auto">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <span class="fs-5 fw-bold">
                    S/ <?php echo e(number_format($product->price,2)); ?>

                </span>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock > 0): ?>

                    <span class="badge bg-success">
                        Disponible
                    </span>

                <?php else: ?>

                    <span class="badge bg-secondary">
                        Sin stock
                    </span>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

            <div class="d-grid gap-2">

                <button
                    class="btn btn-dark btn-add-to-cart"
                    data-id="<?php echo e($product->id); ?>"
                    <?php echo e($product->stock <= 0 ? 'disabled' : ''); ?>>
                    🛒 Agregar al carrito
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>

                    <button
                        class="btn btn-outline-danger btn-wishlist"
                        data-id="<?php echo e($product->id); ?>">
                        ❤️ Favoritos
                    </button>

                <?php else: ?>

                    <a
                        href="<?php echo e(route('login')); ?>"
                        class="btn btn-outline-danger">
                        ❤️ Favoritos
                    </a>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>

        </div>

    </div>

</div>

<style>
.product-card{
    transition:.25s ease;
}

.product-card:hover{
    transform:translateY(-6px);
}
</style><?php /**PATH E:\Pagina-web-\resources\views/components/ecommerce/product-card.blade.php ENDPATH**/ ?>