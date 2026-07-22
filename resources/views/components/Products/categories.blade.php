@props([
    'categories',
    'selected',
])

<section class="product-categories">

    <h2 class="categories-title">
        Categorías
    </h2>

    <div class="categories-list">

        <a href="{{ route('products') }}"
           class="category-button {{ !$selected ? 'active' : '' }}">

            Todas

        </a>

        @foreach($categories as $category)

            <a href="{{ route('products', ['categoria' => $category->id]) }}"
               class="category-button {{ (string)$selected === (string)$category->id ? 'active' : '' }}">

                {{ $category->name }}

            </a>

        @endforeach

    </div>

</section>