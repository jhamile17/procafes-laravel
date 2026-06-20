@extends('layouts.app')

@section('title', 'Productos - PROCAFES')

@section('content')
@php
  // Esta vista usa exclusivamente la colección/paginador `$products`
  // provisto por `ProductController@index`.
  // No incluir lógica de negocio aquí.
  $selected = request('categoria');
@endphp

<div class="container py-4">
  <div class="row g-4">
    {{-- Sidebar de categorías --}}
    <aside class="col-lg-3">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-procafes">
          <strong>CATEGORÍAS</strong>
        </div>
        <div class="list-group list-group-flush">

          {{-- Todas --}}
          <a href="{{ route('home') }}"
             class="list-group-item list-group-item-action @if(!$selected) active @endif">
            Todas
            <span class="badge bg-secondary rounded-pill float-end">{{ $products->total() }}</span>
          </a>

          {{-- Iterar categorías --}}
          @foreach($categories as $cat)
            <a href="{{ route('home', ['categoria' => $cat->slug]) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                      @if($selected === $cat->slug) active @endif">
              <span>{{ $cat->name }}</span>
              <span class="badge bg-secondary rounded-pill">{{ $counts[$cat->slug] ?? 0 }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </aside>

    {{-- Grilla de productos --}}
    <section class="col-lg-9">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h5 mb-0">Todos los productos</h1>
        <span class="text-muted">{{ $products->total() }} resultado(s)</span>
      </div>

      @if($products->isEmpty())
        <div class="alert alert-info">No hay productos disponibles en este momento.</div>
      @else
        <div class="row g-3">
          @foreach($products as $product)
            <div class="col-6 col-md-4">
              <x-ecommerce.product-card :product="$product" />
            </div>
          @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
          {{ $products->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </section>
  </div>
</div>
@endsection

@push('styles')
  {{-- Bootstrap Icons (si aún no lo cargas en tu layout) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush

@push('scripts')
<script>
/**
 * Favoritos DEMO sin BD:
 * - Guarda claves en localStorage ('demoFavorites').
 * - Cambia color/texto/ícono del botón.
 * Cuando tengas productos reales con ID:
 *  - quita este script
 *  - y usa el toggle AJAX que te pasé antes contra /wishlist/toggle
 */
(function(){
  const storageKey = 'demoFavorites';

  function loadSet() {
    try {
      const raw = localStorage.getItem(storageKey);
      return new Set(raw ? JSON.parse(raw) : []);
    } catch { return new Set(); }
  }
  function saveSet(set) {
    localStorage.setItem(storageKey, JSON.stringify(Array.from(set)));
  }

  function paintButton(btn, active) {
    const icon = btn.querySelector('i');
    if (active) {
      btn.classList.remove('btn-outline-danger');
      btn.classList.add('btn-danger','active');
      if (icon) icon.className = 'bi bi-heart-fill me-1';
      btn.innerHTML = '<i class="bi bi-heart-fill me-1"></i> En favoritos';
    } else {
      btn.classList.add('btn-outline-danger');
      btn.classList.remove('btn-danger','active');
      if (icon) icon.className = 'bi bi-heart me-1';
      btn.innerHTML = '<i class="bi bi-heart me-1"></i> Añadir a favoritos';
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const set = loadSet();

    // Pintar estado inicial
    document.querySelectorAll('.btn-wishlist').forEach(btn => {
      const key = btn.getAttribute('data-key');
      paintButton(btn, set.has(key));
    });

    // Toggle al click
    document.body.addEventListener('click', (ev) => {
      const btn = ev.target.closest('.btn-wishlist');
      if (!btn) return;

      const key = btn.getAttribute('data-key');
      if (!key) return;

      const setNow = loadSet();
      const active = setNow.has(key);
      if (active) setNow.delete(key); else setNow.add(key);
      saveSet(setNow);
      paintButton(btn, !active);
    });
  });
})();
</script>
@endpush
