<div class="customer-card checkout-summary sticky-top">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="customer-card-header">

        <div>

            <span class="customer-card-badge">

                Resumen

            </span>

            <h2 class="customer-card-title">

                Tu pedido

            </h2>

            <p class="customer-card-subtitle">

                Revisa el detalle antes de confirmar tu compra.

            </p>

        </div>

    </div>

    {{-- ==========================================================
        BODY
    =========================================================== --}}

    <div class="customer-card-body">

        {{-- ==========================================================
            PRODUCTOS
        =========================================================== --}}

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

        {{-- ==========================================================
            RESUMEN
        =========================================================== --}}

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

        {{-- ==========================================================
            IGV
        =========================================================== --}}
        <div class="checkout-summary-note">
            <i class="bi bi-check-circle-fill"></i>
            <span>
                Incluyen IGV.
            </span>
        </div>
        {{-- ==========================================================
            ACCIÓN
        =========================================================== --}}

        <button
            id="checkoutSubmitButton"
            type="submit"
            class="customer-btn w-100 mt-4">

            <i class="bi bi-bag-check-fill"></i>

            Confirmar pedido

        </button>

    </div>

</div>