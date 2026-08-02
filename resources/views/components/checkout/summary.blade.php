<div class="customer-card checkout-summary sticky-top">

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">

                Resumen

            </span>

            <h2 class="customer-card-title">

                Tu pedido

            </h2>

            <p class="customer-card-subtitle">

                Revisa el detalle antes de confirmar la compra.

            </p>

        </div>

    </div>

    <div class="customer-card-body">

        {{-- ===========================
            Productos
        ============================ --}}

        <div class="checkout-summary-products">

            @foreach($items as $item)

                <div class="checkout-summary-item">

                    <div class="checkout-summary-image">

                        <img
                            src="{{ $item->product->image_url }}"
                            alt="{{ $item->product->name }}">

                    </div>

                    <div class="checkout-summary-info">

                        <h6>

                            {{ $item->product->name }}

                        </h6>

                        <small>

                            {{ $item->quantity }}
                            ×
                            S/
                            {{ number_format($item->unit_price,2) }}

                        </small>

                    </div>

                    <strong>

                        S/
                        {{ number_format($item->subtotal,2) }}

                    </strong>

                </div>

            @endforeach

        </div>

        <hr>

        {{-- ===========================
            Totales
        ============================ --}}

        <div class="checkout-summary-row">

            <span>

                Productos

            </span>

            <strong>

                {{ $cantidad }}

            </strong>

        </div>

        <div class="checkout-summary-row">

            <span>

                Subtotal

            </span>

            <strong>

                S/
                {{ number_format($subtotal,2) }}

            </strong>

        </div>

        <div class="checkout-summary-row">

            <span>

                IGV (18%)

            </span>

            <strong>

                S/
                {{ number_format($igv,2) }}

            </strong>

        </div>

        <hr>

        <div class="checkout-summary-total">

            <span>

                Total

            </span>

            <strong>

                S/
                {{ number_format($total,2) }}

            </strong>

        </div>

        {{-- ===========================
            Botón
        ============================ --}}

        <button
            type="submit"
            class="btn btn-primary btn-lg w-100 mt-4">

            <i class="bi bi-bag-check-fill me-2"></i>

            Confirmar compra

        </button>

    </div>

</div>