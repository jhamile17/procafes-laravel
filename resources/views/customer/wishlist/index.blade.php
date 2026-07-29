@extends('layouts.app')

@section('title', 'Mis Favoritos')

@section('content')

<div class="customer-dashboard py-5">

    <div class="container">

        <div class="row g-4">

            {{-- Sidebar --}}
            <div class="col-lg-3">

                <x-clienteperfil.sidebar
                    :user="$user"/>
            </div>
            {{-- Contenido --}}
            <div class="col-lg-9">
                
                <x-clienteperfil.wishlist
                    :products="$products"
                    :count="$count"/>
            </div>
        </div>
    </div>
</div>

@endsection