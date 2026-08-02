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
                    data-payment="{{ \Illuminate\Support\Str::slug($method->nombre) }}"
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

                            <p class="checkout-payment-description">

                                {{ $method->descripcion }}

                            </p>

                        @endif

                        @if($nombre === 'mercado pago')

                            <span class="customer-badge customer-badge-info">

                                <i class="bi bi-shield-lock-fill"></i>

                                Pago seguro

                            </span>

                        @elseif($nombre === 'pago en tienda')

                            <span class="customer-badge customer-badge-warning">

                                <i class="bi bi-shop"></i>

                                Pago presencial

                            </span>

                        @endif

                    </div>

                </div>

            </label>

        @empty

            <div class="customer-notice customer-notice-warning">

                <div class="customer-notice-icon">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                </div>

                <div class="customer-notice-content">

                    <h6>

                        Métodos de pago no disponibles

                    </h6>

                    <p>

                        En este momento no existen métodos de pago habilitados para completar la compra.

                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>

