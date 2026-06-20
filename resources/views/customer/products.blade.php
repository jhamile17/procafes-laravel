@extends('layouts.app')

@section('title','Mis Productos | PROCAFES')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
          <h3 class="mb-3">Mis productos</h3>
          <p class="text-muted mb-4">Aquí verás los productos que has publicado o marcado como disponibles para venta.</p>

          <div class="alert alert-info">Aún no hay productos publicados en tu cuenta.</div>

          <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mt-3">Ir al panel de gestión</a>
          <a href="{{ route('products') }}" class="btn btn-primary mt-3 ms-2">Ver catálogo público</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
