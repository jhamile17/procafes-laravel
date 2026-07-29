<footer class="footer">

    <div class="container">

        <div class="row gy-4 justify-content-between">

            {{-- =====================================================
                EMPRESA
            ====================================================== --}}

            <div class="col-12 col-md-6 col-lg-4">

                <div class="footer-brand">

                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="{{ $configuracion?->nombre_empresa ?? 'PROCÁFES' }}"
                        class="footer-logo">

                    <h4 class="footer-title">
                        {{ $configuracion?->nombre_empresa ?? 'PROCÁFES' }}
                    </h4>

                    <p class="footer-description">
                        Descubre el auténtico sabor del café peruano con productos
                        seleccionados para disfrutar cada momento.
                    </p>

                </div>

            </div>

            {{-- =====================================================
                NAVEGACIÓN
            ====================================================== --}}

            <div class="col-6 col-md-3 col-lg-2">

                <h5 class="footer-heading">
                    Navegación
                </h5>

                <ul class="footer-links">

                    <li>
                        <a href="{{ route('home') }}">
                            Inicio
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('products') }}">
                            Productos
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('nosotros') }}">
                            Nosotros
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('ubicanos') }}">
                            Ubícanos
                        </a>
                    </li>

                </ul>

            </div>

            {{-- =====================================================
                CONTACTO
            ====================================================== --}}

            <div class="col-6 col-md-3 col-lg-4">

                <h5 class="footer-heading">
                    Contáctanos
                </h5>

                <address class="footer-contact">

                    <div class="footer-contact-item">

                        <i class="bi bi-geo-alt-fill"></i>

                        <span>

                            {{ $configuracion?->direccion ?? 'Dirección no registrada' }}

                        </span>

                    </div>

                    <div class="footer-contact-item">

                        <i class="bi bi-telephone-fill"></i>

                        <span>

                            {{ $configuracion?->telefono ?? 'Sin teléfono' }}

                        </span>

                    </div>

                    <div class="footer-contact-item">

                        <i class="bi bi-envelope-fill"></i>

                        <span>

                            {{ $configuracion?->correo ?? 'Sin correo electrónico' }}

                        </span>

                    </div>

                </address>

                @if(
                    $configuracion?->facebook ||
                    $configuracion?->instagram ||
                    $configuracion?->tiktok ||
                    $configuracion?->whatsapp
                )

                    <div class="footer-social">

                        @if($configuracion?->facebook)

                            <a
                                href="{{ $configuracion->facebook }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Facebook">

                                <i class="bi bi-facebook"></i>

                            </a>

                        @endif

                        @if($configuracion?->instagram)

                            <a
                                href="{{ $configuracion->instagram }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Instagram">

                                <i class="bi bi-instagram"></i>

                            </a>

                        @endif

                        @if($configuracion?->tiktok)

                            <a
                                href="{{ $configuracion->tiktok }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="TikTok">

                                <i class="bi bi-tiktok"></i>

                            </a>

                        @endif

                        @if($configuracion?->whatsapp)

                            <a
                                href="https://wa.me/{{ preg_replace('/\D/', '', $configuracion->whatsapp) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="WhatsApp"
                                class="social-whatsapp">

                                <i class="bi bi-whatsapp"></i>

                            </a>

                        @endif

                    </div>

                @endif

            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">

            <p class="footer-copy">

                © {{ date('Y') }}
                {{ $configuracion?->nombre_empresa ?? 'PROCÁFES' }}.
                Todos los derechos reservados.

            </p>

            {{-- Descomentar cuando existan las páginas --}}

            {{--
            <div class="footer-bottom-links">

                <a href="{{ route('politica-privacidad') }}">
                    Política de Privacidad
                </a>

                <a href="{{ route('terminos-condiciones') }}">
                    Términos y Condiciones
                </a>

                <a href="{{ route('libro-reclamaciones') }}">
                    Libro de Reclamaciones
                </a>

            </div>
            --}}

        </div>

    </div>

</footer>