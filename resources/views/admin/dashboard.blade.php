@extends('layouts.admin')

@section('title','Dashboard')

@section('content')

<x-admin.dashboard.cards
    :productos="$productos"
    :clientes="$clientes"
    :pedidos="$pedidos"
    :ventas="$ventas"/>

<div class="dashboard-grid mt-4">

    <div class="dashboard-left">

        <x-admin.dashboard.chart/>

    </div>

    <div class="dashboard-right">

        <x-admin.dashboard.top-products
            :productos="$topProductos"/>

    </div>

</div>

<div class="dashboard-grid-bottom mt-4">

    <x-admin.dashboard.stock-low
        :productos="$productosStockBajo"/>

    <x-admin.dashboard.reports/>

    <x-admin.dashboard.activity/>

</div>

@endsection