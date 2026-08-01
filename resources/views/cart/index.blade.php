@extends('layouts.app')

@section('title', 'Mi carrito')

@section('body-class', 'cart-page')

@section('content')

<section class="customer-dashboard py-5">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="customer-content">

                    <x-clienteperfil.header
                        title="Mi carrito"
                        subtitle="Revisa los productos seleccionados antes de finalizar tu compra." />

                    <div class="row g-4">

                        <div class="col-lg-8">

                            <x-cart.items />

                        </div>

                        <div class="col-lg-4">

                            <x-cart.summary />

                        </div>

                    </div>

                    {{-- Recomendaciones --}}
                    <div id="cart-recommendations" class="mt-5"></div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection