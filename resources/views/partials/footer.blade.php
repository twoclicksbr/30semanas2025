<footer id="contact" class="bg-dark ">
    <div class="container pb-13 pb-md-15">
        <div class="card image-wrapper bg-full bg-image bg-overlay mt-n50p mx-md-5"
            data-image-src="{{ asset('/assets/img/photos/bg27.jpg') }}"
            style="background-image: url('/assets/img/photos/bg27.jpg');">
            <div
                class="card-body p-6 p-md-11 d-lg-flex flex-row align-items-lg-center justify-content-md-between text-center text-lg-start">

                <img src="{{ asset('/assets/img/rede_inspire.png') }}" width="150">

                <div class="row">
                    <h4 class="display-6 mb-6 mb-lg-0 pe-lg-10 pe-xl-5 pe-xxl-18 text-white" style="margin-left: 20px;">
                        A <mark class="text-orange">agência de recursos</mark> que a sua Igreja precisa!
                    </h4>
                    <p class="text-white" style="margin-left: 20px;">Experimente gratuitamente nossos materiais durante
                        7 dias!</p>
                </div>
                <a href="https://redeinspire.com" target="_blank"
                    class="btn btn-lg btn-orange rounded mb-0 text-nowrap">Conhecer</a>

            </div>
        </div>

        <div class="text-inverse mx-md-5 mt-n15 mt-lg-0">
            <div class="row gy-6 gy-lg-0">
                <div class="col-md-4 col-lg-3">
                    <div class="widget">

                        <img class="mb-4" src="{{ asset('/assets/img/footer-logo.png') }}"
                            srcset="{{ asset('/assets/img/footer-logo.png') }}" alt="">

                        <p class="mb-4">© 2025 | 30 Semanas. <br class="d-none d-lg-block">Todos os Direitos
                            Reservados.</p>

                        <img class="mb-4" width="200"
                            src="{{ asset('assets/img/footer_logo_ic_branco.png') }}"
                            srcset="{{ asset('/assets/img/footer_logo_ic_branco.png') }}"
                            alt="">

                        <nav class="nav social social-white">
                            <a href="#"><i class="uil uil-twitter"></i></a>
                            <a href="#"><i class="uil uil-facebook-f"></i></a>
                            <a href="#"><i class="uil uil-dribbble"></i></a>
                            <a href="#"><i class="uil uil-instagram"></i></a>
                            <a href="#"><i class="uil uil-youtube"></i></a>
                        </nav>
                    </div>
                </div>

                <div class="col-md-4 col-lg-6">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">Entrar em Contato</h4>

                        <address class="pe-xl-15 pe-xxl-17">
                            Igreja da Cidade em São José dos Campos - SP
                            <br>
                            <a href="https://maps.app.goo.gl/6D7SmtkWZQB8GD7z5" target="_blank">
                                <i class="uil uil-directions"></i> Rua Todas as Nações (acesso pela Via Cambuí)
                                <br>
                                <div style="margin-left: 20px;">
                                    Vila Tatetuba.
                                    <br>
                                    CEP: 12 4009-4300
                                </div>
                            </a>
                        </address>


                        <a href="tel:01240094300"><i class="uil uil-outgoing-call"></i> (12) 4009-4300</a>
                        <br>
                        <a href="https://wa.me/5512996539066" target="_blank"><i class="uil uil-whatsapp"></i> (12)
                            99653-9066</a>
                        <br>
                        <a href="mailto:30semanas@igrejadacidade.org.br" target="_blank"><i
                                class="uil uil-fast-mail"></i> 30semanas@igrejadacidade.org.br</a>
                        <br>
                        <a href="mailto:atendimento@igrejadacidade.org.br" target="_blank"><i
                                class="uil uil-fast-mail"></i> atendimento@igrejadacidade.org.br</a>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title text-white mb-3">30 Semanas</h4>
                        <ul class="list-unstyled  mb-0">
                            <li>
                                @php
                                    $urlDecision =
                                        request()->path() === '/' || request()->path() === 'home'
                                            ? '#decision'
                                            : '/home#decision';
                                @endphp
                                <a href="{{ $urlDecision }}">Decisões</a>
                            </li>
                            <li>
                                @php
                                    $urlShare =
                                        request()->path() === '/' || request()->path() === 'home'
                                            ? '#share'
                                            : '/home#share';
                                @endphp
                                <a href="{{ $urlShare }}">Partilhas</a>
                            </li>
                            <li>
                                @php
                                    $urlCelebration =
                                        request()->path() === '/' || request()->path() === 'home'
                                            ? '#celebration'
                                            : '/home#celebration';
                                @endphp
                                <a href="{{ $urlCelebration }}">Celebrações</a>
                            </li>

                            <li>    
                                @php
                                    $urlContact =
                                        request()->path() === '/' || request()->path() === 'home'
                                            ? '#contact'
                                            : '/home#contact';
                                @endphp
                                <a href="{{ $urlContact }}">Contato</a>
                            </li>
                            {{-- <li><a href="#">Sou Líder</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
