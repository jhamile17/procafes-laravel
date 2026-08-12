@extends('layouts.app')
@section('title', 'Detalle del pedido')
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
                        title="Detalle del pedido"
                        subtitle="Consulta la información completa de tu compra." />
                    <x-clientepedidos.detail
                        :order="$order" />

                </div>

            </div>

        </div>

    </div>

</section>

@endsection