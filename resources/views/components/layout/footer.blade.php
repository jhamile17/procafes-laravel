<footer class="footer">

    <div class="container">

        <div class="row gy-3 align-items-start">

            {{-- Marca --}}
            <div class="col-lg-4">

                <div class="footer-brand">

                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Procafes"
                        class="footer-logo">

                    <h4 class="footer-title">
                        PROCAFES
                    </h4>

                    <p class="footer-description">
                        Pasion por el café, compromiso con la calidad y
                        la sostenibilidad.
                    </p>

                </div>

            </div>

            {{-- Navegación --}}
            <div class="col-lg-2 col-md-6">

                <h5 class="footer-heading">
                    Explorar
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

            {{-- Contacto --}}
            <div class="col-lg-3 col-md-6">

                <h5 class="footer-heading">
                    Contáctanos
                </h5>

                <ul class="footer-contact">

                    <li>

                        <i class="bi bi-geo-alt-fill"></i>

                        <span>
                            Chimbote, Perú
                        </span>

                    </li>

                    <li>

                        <i class="bi bi-telephone-fill"></i>

                        <span>
                            +51 999 999 999
                        </span>

                    </li>

                    <li>

                        <i class="bi bi-envelope-fill"></i>

                        <span>
                            contacto@procafes.pe
                        </span>

                    </li>

                </ul>

            </div>

            {{-- Redes Sociales --}}
            <div class="col-lg-3">

                <h5 class="footer-heading">
                    Síguenos
                </h5>

                <p class="footer-description mb-3">
                    Mantente conectado con nosotros y conoce nuestras
                    promociones y novedades.
                </p>

                <div class="footer-social">

                    <a href="#" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="#" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="#" aria-label="TikTok">
                        <i class="bi bi-tiktok"></i>
                    </a>

                    <a href="#" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>

                </div>

            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">

            <p class="footer-copy">

                © {{ date('Y') }} PROCAFES.

                Todos los derechos reservados.

            </p>

            <div class="footer-bottom-links">

                <a href="#">
                    Política de privacidad
                </a>

                <a href="#">
                    Términos y condiciones
                </a>

            </div>

        </div>

    </div>

</footer>