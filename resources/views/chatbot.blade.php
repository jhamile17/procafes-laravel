@extends('layouts.app')

@section('title', 'Asistente PROCAFES')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h1 class="h5 mb-0">Asistente Virtual PROCAFES</h1>
                </div>

                <div class="card-body">
                    <x-chat.window />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection