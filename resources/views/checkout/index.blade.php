@extends('layouts.app')

@section('title', 'Finalizar compra | PROCAFES')

@push('styles')

<style>

.checkout-container{
    max-width:1280px;
    margin:20px auto;
    padding:0 15px;
}

.checkout-header{
    margin-bottom:18px;
}

.checkout-title{
    font-size:1.8rem;
    font-weight:700;
    color:#473C2B;
    margin-bottom:.25rem;
}

.checkout-subtitle{
    color:#6c757d;
    font-size:.95rem;
    margin:0;
}

.checkout-card,
.summary-card{
    background:#fff;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    overflow:hidden;
}

.checkout-body{
    padding:22px;
}

.checkout-section-title{
    display:flex;
    align-items:center;
    gap:.6rem;
    font-size:1.05rem;
    font-weight:700;
    color:#473C2B;
    margin-bottom:15px;
}

.checkout-section-title i{
    font-size:1rem;
    color:#8B5E3C;
}

.form-label{
    font-size:.92rem;
    font-weight:600;
    color:#473C2B;
    margin-bottom:6px;
}

.form-control,
.form-select{
    min-height:40px;
    border-radius:10px;
    border:1px solid #d8d8d8;
    padding:.45rem .80rem;
    font-size:.92rem;
    transition:.25s;
}

.form-control:focus,
.form-select:focus{
    border-color:#8B5E3C;
    box-shadow:0 0 0 .15rem rgba(139,94,60,.15);
}

textarea.form-control{
    min-height:70px;
    resize:none;
}

.payment-card{
    display:flex;
    align-items:flex-start;
    gap:14px;
    border:1px solid #e5e5e5;
    border-radius:12px;
    padding:14px;
    margin-bottom:10px;
    cursor:pointer;
    transition:.25s;
}

.payment-card:hover{
    background:#faf7f2;
    border-color:#8B5E3C;
}

.payment-card input{
    margin-top:4px;
}

.payment-title{
    font-size:1rem;
    font-weight:700;
    color:#473C2B;
}

.payment-description{
    margin-top:2px;
    font-size:.84rem;
    color:#777;
}

.summary-card{
    position:sticky;
    top:95px;
}

.summary-header{
    padding:18px 22px;
    border-bottom:1px solid #ececec;
}

.summary-header h5{
    margin:0;
    font-size:1.35rem;
    font-weight:700;
    color:#473C2B;
}

.summary-body{
    padding:20px;
}

.summary-product{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:12px;
}

.summary-product-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.summary-product img{
    width:52px;
    height:52px;
    object-fit:cover;
    border-radius:10px;
}

.summary-product-name{
    margin:0;
    font-size:.95rem;
    font-weight:600;
    color:#473C2B;
}

.summary-product-qty{
    font-size:.80rem;
    color:#888;
}

.summary-line{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
    font-size:.95rem;
}

.summary-total{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:15px;
    padding-top:15px;
    border-top:1px solid #e9e9e9;
    font-size:1.30rem;
    font-weight:700;
    color:#473C2B;
}

.btn-confirm{
    width:100%;
    height:46px;
    margin-top:18px;
    border:none;
    border-radius:12px;
    background:#473C2B;
    color:#fff;
    font-size:.95rem;
    font-weight:700;
    transition:.25s;
}

.btn-confirm:hover{
    background:#2E2418;
}

hr{
    margin:20px 0;
}

@media(max-width:991px){

    .checkout-container{
        margin:10px auto;
    }

    .checkout-card{
        margin-bottom:20px;
    }

    .summary-card{
        position:relative;
        top:0;
    }

}

</style>

@endpush

@section('content')

@php

    $money = fn($amount)=>number_format((float)$amount,2);

@endphp

<div class="container checkout-container">

    <div class="checkout-header">

        <h1 class="checkout-title">

            Finalizar compra

        </h1>

        <p class="checkout-subtitle">

            Complete la información para finalizar su pedido.

        </p>

    </div>

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('checkout.store') }}" method="POST">

        @csrf

        <div class="row">

            <div class="col-lg-8">

                <div class="checkout-card">

                    <div class="checkout-body">

                        <h5 class="checkout-section-title">

                            <i class="bi bi-geo-alt-fill"></i>

                            Dirección de entrega

                        </h5>
                        <div class="mb-4">

                            <label class="form-label">

                                Dirección

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Ej.: Av. Los Cafetales 123"
                                required
                            >

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Ciudad

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="city"
                                    value="{{ old('city') }}"
                                    required
                                >

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Departamento

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="state"
                                    value="{{ old('state') }}"
                                    required
                                >

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                País

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="country"
                                value="{{ old('country','Perú') }}"
                                required
                            >

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Referencia

                            </label>

                            <textarea
                                class="form-control"
                                name="reference"
                                placeholder="Ej.: Frente al parque, casa color blanco"
                            >{{ old('reference') }}</textarea>

                        </div>

                        <hr class="my-5">

                        <h5 class="checkout-section-title">

                            <i class="bi bi-truck"></i>

                            Tipo de entrega

                        </h5>

                        <label class="payment-card d-flex">

                            <input
                                class="form-check-input me-3"
                                type="radio"
                                name="delivery_type"
                                value="delivery"
                                @checked(old('delivery_type','delivery')=='delivery')
                            >

                            <div>

                                <div class="payment-title">

                                    Delivery

                                </div>

                                <div class="payment-description">

                                    Envío hasta la dirección registrada.

                                </div>

                            </div>

                        </label>

                        <label class="payment-card d-flex">

                            <input
                                class="form-check-input me-3"
                                type="radio"
                                name="delivery_type"
                                value="pickup"
                                @checked(old('delivery_type')=='pickup')
                            >

                            <div>

                                <div class="payment-title">

                                    Recojo en tienda

                                </div>

                                <div class="payment-description">

                                    Recoge tu pedido en PROCÁFES.

                                </div>

                            </div>

                        </label>

                        <hr class="my-5">

                        <h5 class="checkout-section-title">

                            <i class="bi bi-credit-card"></i>

                            Método de pago

                        </h5>

                        <label class="payment-card d-flex">

                            <input
                                class="form-check-input me-3"
                                type="radio"
                                name="payment_method_id"
                                value="7"
                                @checked(old('payment_method_id',7)==7)
                            >

                            <div>

                                <div class="payment-title">

                                    Mercado Pago

                                </div>

                                <div class="payment-description">

                                    Tarjetas de crédito, débito, Yape y Plin.

                                </div>

                            </div>

                        </label>

                        <label class="payment-card d-flex">

                            <input
                                class="form-check-input me-3"
                                type="radio"
                                name="payment_method_id"
                                value="1"
                                @checked(old('payment_method_id')==1)
                            >

                            <div>

                                <div class="payment-title">

                                    Pago en efectivo

                                </div>

                                <div class="payment-description">

                                    Disponible para pagos en tienda.

                                </div>

                            </div>

                        </label>

                    </div>

                </div>

            </div>
                        <div class="col-lg-4">

                <div class="summary-card">

                    <div class="summary-header">

                        <h5>

                            Resumen del pedido

                        </h5>

                    </div>

                    <div class="summary-body">

                        @foreach($items as $item)

                            <div class="summary-product">

                                <div class="summary-product-info">

                                    @if(!empty($item['image']))

                                        <img
                                            src="{{ $item['image'] }}"
                                            alt="{{ $item['name'] }}"
                                        >

                                    @else

                                        <div
                                            class="d-flex align-items-center justify-content-center bg-light rounded"
                                            style="width:60px;height:60px;"
                                        >
                                            <i class="bi bi-cup-hot fs-4 text-secondary"></i>
                                        </div>

                                    @endif

                                    <div>

                                        <p class="summary-product-name">

                                            {{ $item['name'] }}

                                        </p>

                                        <span class="summary-product-qty">

                                            Cantidad: {{ $item['quantity'] }}

                                        </span>

                                    </div>

                                </div>

                                <strong>

                                    S/ {{ $money($item['subtotal']) }}

                                </strong>

                            </div>

                        @endforeach

                        <hr>

                        <div class="summary-line">

                            <span>

                                Subtotal

                            </span>

                            <strong>

                                S/ {{ $money($sub_total) }}

                            </strong>

                        </div>

                        <div class="summary-line">

                            <span>

                                IGV (18%)

                            </span>

                            <strong>

                                S/ {{ $money($igv) }}

                            </strong>

                        </div>

                        <div class="summary-total">

                            <span>

                                Total

                            </span>

                            <span>

                                S/ {{ $money($total) }}

                            </span>

                        </div>

                        <button
                            type="submit"
                            class="btn-confirm"
                        >

                            <i class="bi bi-lock-fill me-2"></i>

                            Confirmar compra

                        </button>

                        <div class="text-center mt-3">

                            <small class="text-muted">

                                Al continuar serás redirigido a la plataforma
                                segura de pago para completar tu compra.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection