@if ($paginator->hasPages())

    <nav
        aria-label="Paginación de pedidos"
        class="mt-4"
    >

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

            {{-- =====================================================
                 INFORMACIÓN
            ====================================================== --}}

            <div class="text-muted small">

                Mostrando

                <strong class="text-dark">
                    {{ $paginator->firstItem() }}
                </strong>

                a

                <strong class="text-dark">
                    {{ $paginator->lastItem() }}
                </strong>

                de

                <strong class="text-dark">
                    {{ $paginator->total() }}
                </strong>

                pedidos

            </div>


            {{-- =====================================================
                 PAGINACIÓN
            ====================================================== --}}

            <ul class="pagination pagination-sm mb-0">

                {{-- ANTERIOR --}}

                @if ($paginator->onFirstPage())

                    <li class="page-item disabled">

                        <span class="page-link">

                            ← Anterior

                        </span>

                    </li>

                @else

                    <li class="page-item">

                        <a
                            class="page-link"
                            href="{{ $paginator->previousPageUrl() }}"
                            rel="prev"
                            aria-label="Página anterior"
                        >

                            ← Anterior

                        </a>

                    </li>

                @endif


                {{-- NÚMEROS --}}

                @foreach ($elements as $element)

                    {{-- Separador ... --}}

                    @if (is_string($element))

                        <li class="page-item disabled">

                            <span class="page-link">

                                {{ $element }}

                            </span>

                        </li>

                    @endif


                    {{-- Páginas --}}

                    @if (is_array($element))

                        @foreach ($element as $page => $url)

                            @if ($page == $paginator->currentPage())

                                <li
                                    class="page-item active"
                                    aria-current="page"
                                >

                                    <span
                                        class="page-link bg-danger border-danger"
                                    >

                                        {{ $page }}

                                    </span>

                                </li>

                            @else

                                <li class="page-item">

                                    <a
                                        class="page-link text-danger"
                                        href="{{ $url }}"
                                    >

                                        {{ $page }}

                                    </a>

                                </li>

                            @endif

                        @endforeach

                    @endif

                @endforeach


                {{-- SIGUIENTE --}}

                @if ($paginator->hasMorePages())

                    <li class="page-item">

                        <a
                            class="page-link text-danger"
                            href="{{ $paginator->nextPageUrl() }}"
                            rel="next"
                            aria-label="Página siguiente"
                        >

                            Siguiente →

                        </a>

                    </li>

                @else

                    <li class="page-item disabled">

                        <span class="page-link">

                            Siguiente →

                        </span>

                    </li>

                @endif

            </ul>

        </div>

    </nav>

@endif