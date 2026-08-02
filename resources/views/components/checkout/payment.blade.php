<div class="customer-card checkout-card mt-4">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">

                Pago

            </span>

            <h2 class="customer-card-title">

                Método de pago

            </h2>

            <p class="customer-card-subtitle">

                Selecciona cómo deseas realizar el pago de tu pedido.

            </p>

        </div>

    </div>

    <div class="customer-card-body">

        @forelse($paymentMethods as $method)

            @php
                $nombre = strtolower(trim($method->nombre));
            @endphp

            <label class="checkout-payment-option">

                <input
                    type="radio"
                    name="payment_method_id"
                    value="{{ $method->id }}"
                    class="checkout-payment-radio"
                    {{ $loop->first ? 'checked' : '' }}>

                <div class="checkout-payment-content">

                    <div class="checkout-payment-icon">

                        @switch($nombre)

                            @case('mercado pago')

                                <i class="bi bi-credit-card-fill"></i>

                                @break

                            @case('pago en tienda')

                                <i class="bi bi-shop"></i>

                                @break

                            @default

                                <i class="bi bi-wallet2"></i>

                        @endswitch

                    </div>

                    <div class="checkout-payment-info">

                        <h6 class="checkout-payment-title">

                            {{ $method->nombre }}

                        </h6>

                        @if(!empty($method->descripcion))

                            <small class="checkout-payment-description">

                                {{ $method->descripcion }}

                            </small>

                        @endif

                    </div>

                </div>

            </label>

        @empty

            <div class="alert alert-warning mb-0">

                <i class="bi bi-exclamation-triangle me-2"></i>

                No existen métodos de pago disponibles.

            </div>

        @endforelse

    </div>

</div>