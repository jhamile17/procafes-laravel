@extends('layouts.app')

@section('title', 'Editar Perfil')

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
                        title="Editar Perfil"
                        subtitle="Actualiza la información de tu cuenta." />

                    <x-clienteperfil.information-edit
                        :user="$user" />

                    <x-clienteperfil.security />

                </div>

            </div>

        </div>

    </div>

</div>

@endsection