<button
    type="button"
    class="btn btn-dark rounded-circle shadow position-fixed"
    style="width: 60px; height: 60px; bottom: 100px; right: 24px; z-index: 1050;"
    data-bs-toggle="modal"
    data-bs-target="#chatbotModal"
    aria-label="Abrir asistente PROCAFES"
>
    <i class="bi bi-robot fs-4"></i>
</button>

<div
    class="modal fade"
    id="chatbotModal"
    tabindex="-1"
    aria-labelledby="chatbotModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="chatbotModalLabel">
                    Asistente PROCAFES
                </h2>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            <div class="modal-body">
                <?php if (isset($component)) { $__componentOriginal8383d4726d81d7f17c9b63a24953a4ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8383d4726d81d7f17c9b63a24953a4ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.chat.window','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('chat.window'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8383d4726d81d7f17c9b63a24953a4ee)): ?>
<?php $attributes = $__attributesOriginal8383d4726d81d7f17c9b63a24953a4ee; ?>
<?php unset($__attributesOriginal8383d4726d81d7f17c9b63a24953a4ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8383d4726d81d7f17c9b63a24953a4ee)): ?>
<?php $component = $__componentOriginal8383d4726d81d7f17c9b63a24953a4ee; ?>
<?php unset($__componentOriginal8383d4726d81d7f17c9b63a24953a4ee); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\Pagina-web-\resources\views/components/chat/button.blade.php ENDPATH**/ ?>