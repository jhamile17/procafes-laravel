<?php $__env->startSection('title', 'Finalizar compra | PROCAFES'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        padding: 2rem 1rem;
    }

    .checkout-section,
    .summary-section {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        background: #fff;
    }

    .summary-section {
        background: #fafafa;
        border: 1px solid #eee;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .btn-confirm {
        width: 100%;
        background: #473C2B;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        padding: 0.9rem;
        transition: background .3s;
    }

    .btn-confirm:hover {
        background: #2d2418;
    }

    .summary-header {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 1px solid #ddd;
        padding-bottom: .5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: .4rem;
    }

    .summary-total {
        border-top: 1px solid #ddd;
        margin-top: .8rem;
        padding-top: .8rem;
        font-size: 1.1rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $money = fn ($amount) => number_format((float) $amount, 2);
?>

<div class="checkout-container">
    <div class="checkout-section">
        <h4 class="mb-3">Finalizar compra</h4>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('checkout.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label for="address" class="form-label">
                    Dirección de entrega
                </label>

                <input
                    id="address"
                    type="text"
                    name="address"
                    value="<?php echo e(old('address')); ?>"
                    class="form-control"
                    placeholder="Ej.: Calle Los Cafetales 123"
                    required
                >
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">Ciudad</label>

                    <input
                        id="city"
                        type="text"
                        name="city"
                        value="<?php echo e(old('city')); ?>"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">Departamento / región</label>

                    <input
                        id="state"
                        type="text"
                        name="state"
                        value="<?php echo e(old('state')); ?>"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="zip_code" class="form-label">Código postal</label>

                    <input
                        id="zip_code"
                        type="text"
                        name="zip_code"
                        value="<?php echo e(old('zip_code')); ?>"
                        class="form-control"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">País</label>

                    <input
                        id="country"
                        type="text"
                        name="country"
                        value="<?php echo e(old('country', 'Perú')); ?>"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label d-block mb-2">Método de pago</label>

                <div class="form-check mb-2">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="payment_method"
                        id="payment_mercadopago"
                        value="mercadopago"
                        <?php if(old('payment_method', 'mercadopago') === 'mercadopago'): echo 'checked'; endif; ?>
                    >

                    <label class="form-check-label" for="payment_mercadopago">
                        Tarjeta / Yape / Plin (Mercado Pago)
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="payment_method"
                        id="payment_bank_transfer"
                        value="bank_transfer"
                        <?php if(old('payment_method') === 'bank_transfer'): echo 'checked'; endif; ?>
                    >

                    <label class="form-check-label" for="payment_bank_transfer">
                        Transferencia bancaria
                    </label>
                </div>

                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="payment_method"
                        id="payment_cash"
                        value="cash"
                        <?php if(old('payment_method') === 'cash'): echo 'checked'; endif; ?>
                    >

                    <label class="form-check-label" for="payment_cash">
                        Efectivo
                    </label>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <button type="submit" class="btn-confirm">
                Confirmar compra
            </button>
        </form>
    </div>

    <aside class="summary-section">
        <div class="summary-header">Resumen del pedido</div>

        <ul class="list-group mb-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['image_url'])): ?>
                            <img
                                src="<?php echo e($item['image_url']); ?>"
                                alt="<?php echo e($item['name']); ?>"
                                style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px;"
                            >
                        <?php else: ?>
                            <div
                                class="bg-light rounded d-flex align-items-center justify-content-center text-muted"
                                style="width: 56px; height: 56px;"
                            >
                                <i class="bi bi-cup-hot"></i>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div>
                            <div class="fw-semibold"><?php echo e($item['name']); ?></div>
                            <small class="text-muted">
                                x<?php echo e($item['quantity']); ?>

                            </small>
                        </div>
                    </div>

                    <span class="fw-semibold">
                        S/ <?php echo e($money($item['subtotal'])); ?>

                    </span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>

        <div class="summary-item">
            <span>Subtotal</span>
            <strong>S/ <?php echo e($money($subtotal)); ?></strong>
        </div>

        <div class="summary-item">
            <span>IGV (18%)</span>
            <strong>S/ <?php echo e($money($igv)); ?></strong>
        </div>

        <div class="summary-total d-flex justify-content-between">
            <span>Total</span>
            <span>S/ <?php echo e($money($total)); ?></span>
        </div>
    </aside>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Pagina-web-\resources\views/checkout/index.blade.php ENDPATH**/ ?>