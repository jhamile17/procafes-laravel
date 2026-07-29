@extends('layouts.app')

@section('title', 'Configuración')

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
                        title="Configuración"
                        subtitle="Administra la seguridad y las opciones de tu cuenta." />

                    <x-clienteperfil.security
                        :user="$user" />

                </div>

            </div>

        </div>

    </div>

</div>

@endsection