<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top border-bottom">
    <div class="container">

        
        <a class="navbar-brand fw-bold" href="<?php echo e(route('home')); ?>">
            PROCAFES
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#nav"
            aria-controls="nav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active fw-bold' : ''); ?>"
                       href="<?php echo e(route('home')); ?>">
                        Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('products') ? 'active fw-bold' : ''); ?>"
                       href="<?php echo e(route('products')); ?>">
                        Productos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('nosotros') ? 'active fw-bold' : ''); ?>"
                       href="<?php echo e(route('nosotros')); ?>">
                        Nosotros
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('ubicanos') ? 'active fw-bold' : ''); ?>"
                       href="<?php echo e(route('ubicanos')); ?>">
                        Ubícanos
                    </a>
                </li>
            </ul>

            
            <form class="d-flex mx-lg-3 my-3 my-lg-0" action="<?php echo e(route('products')); ?>" method="GET">
                <input
                    class="form-control me-2"
                    type="search"
                    name="search"
                    placeholder="Buscar café..."
                    aria-label="Buscar">

                <button class="btn btn-outline-dark" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            
            <div class="me-3">
                <?php if (isset($component)) { $__componentOriginalc39244f752f365a0e02be45887ff4fca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc39244f752f365a0e02be45887ff4fca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ecommerce.cart-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ecommerce.cart-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc39244f752f365a0e02be45887ff4fca)): ?>
<?php $attributes = $__attributesOriginalc39244f752f365a0e02be45887ff4fca; ?>
<?php unset($__attributesOriginalc39244f752f365a0e02be45887ff4fca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc39244f752f365a0e02be45887ff4fca)): ?>
<?php $component = $__componentOriginalc39244f752f365a0e02be45887ff4fca; ?>
<?php unset($__componentOriginalc39244f752f365a0e02be45887ff4fca); ?>
<?php endif; ?>
            </div>

            
           <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>

<div class="dropdown">

    <button
        class="btn btn-outline-dark dropdown-toggle"
        data-bs-toggle="dropdown">

        <?php echo e(Auth::user()->name); ?>

    </button>

    <ul class="dropdown-menu dropdown-menu-end">

        <li>
            <a class="dropdown-item"
               href="<?php echo e(route('customer.dashboard')); ?>">
                Mi panel
            </a>
        </li>

        <li>
            <a class="dropdown-item"
               href="<?php echo e(route('profile')); ?>">
                Perfil
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item">
                    Cerrar sesión
                </button>
            </form>
        </li>

    </ul>

</div>

<?php else: ?>

<div class="d-flex gap-2">

    <a href="<?php echo e(route('login')); ?>"
       class="btn btn-outline-dark">
        Iniciar sesión
    </a>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
        <a href="<?php echo e(route('register')); ?>"
           class="btn btn-dark">
            Registrarse
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

    </div>
</nav><?php /**PATH E:\Pagina-web-\resources\views/components/layout/navbar.blade.php ENDPATH**/ ?>