@extends('layouts.app')
@section('title', 'Mi carrito')
@section('body-class', 'cart-page')
@section('content')

<div class="container py-5">

    <div class="row">

        <div class="col-12">

            <div class="customer-content">

                <x-clienteperfil.header
                    title="Mi carrito"
                    subtitle="Revisa los productos seleccionados antes de finalizar tu compra." />

                <div class="row g-4">

                    {{-- Productos --}}
                    <div class="col-lg-8">

                        <x-cart.items />

                    </div>

                    {{-- Resumen --}}
                    <div class="col-lg-4">

                        <div class="cart-summary-column">

                            <x-cart.summary />

                        </div>

                    </div>

                </div>

                {{-- Productos recomendados --}}
                <section
                    id="cart-recommendations"
                    class="mt-5">
                </section>

            </div>

        </div>

    </div>

</div>

@endsection