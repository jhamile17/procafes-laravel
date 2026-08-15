<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Página principal --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Productos --}}
    @foreach($products as $product)

        <url>
            <loc>{{ url('/productos/' . $product->slug) }}</loc>

            @if($product->updated_at)
                <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            @endif

            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>

    @endforeach

</urlset>