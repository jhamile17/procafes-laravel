@extends('layouts.app')

@section('title', 'Mis pedidos')

@section('content')

<section class="customer-dashboard py-5">

    <div class="container">

        <div class="row g-4 align-items-start">

            <div class="col-lg-3">

                <x-clienteperfil.sidebar />

            </div>

            <div class="col-lg-9">

                <div class="customer-content">

                    <x-clienteperfil.header
                        title="Mis pedidos"
                        subtitle="Consulta el estado de tus compras y revisa el historial de pedidos."
                    />
                    @if($orders->isNotEmpty())

                        <div class="orders-header">
                           
                            <div>Pedido</div>
                            <div>Fecha</div>
                            <div>Estado</div>
                            <div>Producto</div>
                            <div>Total</div>
                            <div></div>
  
                        </div>

                        <div class="customer-orders">
                            @foreach($orders as $order)
                                <x-clientepedidos.card :order="$order"/>
                            @endforeach
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>

                    @else

                        <x-clientepedidos.empty />

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection