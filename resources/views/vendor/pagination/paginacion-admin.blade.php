@if ($paginator->hasPages())

    <nav
        class="paginacion-admin"
        role="navigation"
        aria-label="Paginación"
    >

        <ul class="paginacion-admin-lista">

            {{-- =====================================================
                 PÁGINA ANTERIOR
            ====================================================== --}}

            @if ($paginator->onFirstPage())

                <li class="paginacion-admin-item deshabilitado">

                    <span class="paginacion-admin-enlace">

                        ← Anterior

                    </span>

                </li>

            @else

                <li class="paginacion-admin-item">

                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        rel="prev"
                        class="paginacion-admin-enlace"
                        aria-label="Página anterior"
                    >

                        ← Anterior

                    </a>

                </li>

            @endif


            {{-- =====================================================
                 NÚMEROS DE PÁGINA
            ====================================================== --}}

            @foreach ($elements as $element)

                {{-- Separador ... --}}

                @if (is_string($element))

                    <li class="paginacion-admin-item deshabilitado">

                        <span class="paginacion-admin-enlace">

                            {{ $element }}

                        </span>

                    </li>

                @endif


                {{-- Páginas --}}

                @if (is_array($element))

                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())

                            {{-- Página actual --}}

                            <li class="paginacion-admin-item activa">

                                <span
                                    class="paginacion-admin-enlace"
                                    aria-current="page"
                                >

                                    {{ $page }}

                                </span>

                            </li>

                        @else

                            {{-- Página disponible --}}

                            <li class="paginacion-admin-item">

                                <a
                                    href="{{ $url }}"
                                    class="paginacion-admin-enlace"
                                    aria-label="Ir a página {{ $page }}"
                                >

                                    {{ $page }}

                                </a>

                            </li>

                        @endif

                    @endforeach

                @endif

            @endforeach


            {{-- =====================================================
                 PÁGINA SIGUIENTE
            ====================================================== --}}

            @if ($paginator->hasMorePages())

                <li class="paginacion-admin-item">

                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        rel="next"
                        class="paginacion-admin-enlace"
                        aria-label="Página siguiente"
                    >

                        Siguiente →

                    </a>

                </li>

            @else

                <li class="paginacion-admin-item deshabilitado">

                    <span class="paginacion-admin-enlace">

                        Siguiente →

                    </span>

                </li>

            @endif

        </ul>

    </nav>

@endif