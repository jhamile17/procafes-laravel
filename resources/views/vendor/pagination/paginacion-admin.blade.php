@if ($paginator->hasPages())

<nav class="paginacion-admin" role="navigation" aria-label="Paginación">

    <ul class="paginacion-admin-lista">

        {{-- Página anterior --}}
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
                    class="paginacion-admin-enlace">

                    ← Anterior

                </a>

            </li>

        @endif

        {{-- Números --}}
        @foreach ($elements as $element)

            @if (is_string($element))

                <li class="paginacion-admin-item deshabilitado">

                    <span class="paginacion-admin-enlace">

                        {{ $element }}

                    </span>

                </li>

            @endif

            @if (is_array($element))

                @foreach ($element as $page => $url)

                    @if ($page == $paginator->currentPage())

                        <li class="paginacion-admin-item activa">

                            <span class="paginacion-admin-enlace">

                                {{ $page }}

                            </span>

                        </li>

                    @else

                        <li class="paginacion-admin-item">

                            <a
                                href="{{ $url }}"
                                class="paginacion-admin-enlace">

                                {{ $page }}

                            </a>

                        </li>

                    @endif

                @endforeach

            @endif

        @endforeach

        {{-- Página siguiente --}}
        @if ($paginator->hasMorePages())

            <li class="paginacion-admin-item">

                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    class="paginacion-admin-enlace">

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