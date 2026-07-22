@if ($paginator->hasPages())

<nav class="paginacion" role="navigation" aria-label="Paginación">

    <ul class="paginacion-lista">

        {{-- Página anterior --}}
        @if ($paginator->onFirstPage())

            <li class="paginacion-item deshabilitado">

                <span class="paginacion-enlace" aria-hidden="true">
                    &laquo;
                </span>

            </li>

        @else

            <li class="paginacion-item">

                <a
                    class="paginacion-enlace"
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    aria-label="Página anterior">

                    &laquo;

                </a>

            </li>

        @endif


        {{-- Números --}}
        @foreach ($elements as $element)

            @if (is_string($element))

                <li class="paginacion-item deshabilitado">

                    <span class="paginacion-enlace">

                        {{ $element }}

                    </span>

                </li>

            @endif


            @if (is_array($element))

                @foreach ($element as $page => $url)

                    @if ($page == $paginator->currentPage())

                        <li class="paginacion-item activa">

                            <span class="paginacion-enlace">

                                {{ $page }}

                            </span>

                        </li>

                    @else

                        <li class="paginacion-item">

                            <a
                                class="paginacion-enlace"
                                href="{{ $url }}">

                                {{ $page }}

                            </a>

                        </li>

                    @endif

                @endforeach

            @endif

        @endforeach


        {{-- Página siguiente --}}
        @if ($paginator->hasMorePages())

            <li class="paginacion-item">

                <a
                    class="paginacion-enlace"
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    aria-label="Página siguiente">

                    &raquo;

                </a>

            </li>

        @else

            <li class="paginacion-item deshabilitado">

                <span class="paginacion-enlace" aria-hidden="true">

                    &raquo;

                </span>

            </li>

        @endif

    </ul>

</nav>

@endif