@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')

<div class="customer-dashboard py-5">

    <div class="container">

        <div class="row g-4">

            {{-- Sidebar --}}
            <div class="col-lg-3">

                <x-clienteperfil.sidebar
                    :user="$user" />

            </div>

            {{-- Contenido --}}
            <div class="col-lg-9">

                <div class="customer-content">

                    <x-clienteperfil.header
                        title="Mi Perfil"
                        subtitle="Administra la información de tu cuenta y mantén tus datos actualizados." />

                    <x-clienteperfil.information />

                </div>

            </div>

        </div>

    </div>

</div>

@endsection