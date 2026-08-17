<div class="customer-card checkout-summary">

    {{-- ===========================
        HEADER
    ============================ --}}

    <div class="customer-card-header">

        <span class="customer-card-badge">
            Resumen
        </span>

        <h2 class="customer-card-title">
            Tu pedido
        </h2>

        <p class="customer-card-subtitle">
            Revisa tu compra antes de confirmarla.
        </p>

    </div>

    {{-- ===========================
        BODY
    ============================ --}}

    <div class="customer-card-body">

        {{-- Productos --}}
        <div class="checkout-summary-products">

            @foreach($items as $item)

                <div class="checkout-summary-item">

                    <img
                        src="{{ $item->product->image_url }}"
                        alt="{{ $item->product->name }}"
                        class="checkout-summary-thumb">

                    <div class="checkout-summary-details">

                        <h6>
                            {{ $item->product->name }}
                        </h6>

                        <small>
                            x{{ $item->quantity }}
                        </small>

                    </div>

                    <strong>
                        S/ {{ number_format($item->subtotal,2) }}
                    </strong>

                </div>

            @endforeach

        </div>

        <hr>

        <div class="checkout-summary-row">
            <span>Productos</span>
            <strong>{{ $cantidad }}</strong>
        </div>

        <div class="checkout-summary-row">
            <span>Subtotal</span>
            <strong>S/ {{ number_format($subtotal,2) }}</strong>
        </div>

        <div class="checkout-summary-row">
            <span>IGV</span>
            <strong>S/ {{ number_format($igv,2) }}</strong>
        </div>

        <hr>

        <div class="checkout-summary-total">
            <span>Total</span>
            <strong>S/ {{ number_format($total,2) }}</strong>
        </div>

        <div class="checkout-summary-note">
            <i class="bi bi-check-circle-fill"></i>
            Todos los precios incluyen IGV.
        </div>
    </div>

</div>