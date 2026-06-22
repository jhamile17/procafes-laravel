@extends('layouts.app')

@section('title', 'Mi perfil | PROCAFES')

@section('content')
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <h1 class="h3 mb-4">Mi perfil</h1>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <livewire:profile.update-password-form />
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection