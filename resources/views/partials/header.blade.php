<header class="wrapper">

    <nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-dark">
        <div class="container flex-lg-row flex-nowrap align-items-center">

            <div class="navbar-brand w-100">
                <a href="{{ url('/') }}" class="logo">
                    <img class="logo-light" src="{{ asset('assets/img/logo.png') }}"
                        srcset="{{ asset('assets/img/logo@2x.png 2x') }}" alt="Logo">
                    <img class="logo-dark" src="{{ asset('assets/img/logo-light.png') }}"
                        srcset="{{ asset('assets/img/logo-light@2x.png 2x') }}" alt="Logo">
                </a>
            </div>

            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header d-lg-none">
                    <h3 class="text-white fs-30 mb-0">Sandbox</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">

                    <ul class="navbar-nav">

                        @php
                            $urlDecision =
                                request()->path() === '/' || request()->path() === 'home'
                                    ? '#decision'
                                    : '/home#decision';
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ $urlDecision }}">Decisões</a>
                        </li>

                        @php
                            $urlShare =
                                request()->path() === '/' || request()->path() === 'home' ? '#share' : '/home#share';
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ $urlShare }}">Partilhas</a>
                        </li>

                        @php
                            $urlCelebration =
                                request()->path() === '/' || request()->path() === 'home'
                                    ? '#celebration'
                                    : '/home#celebration';
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ $urlCelebration }}">Celebrações</a>
                        </li>

                        @php
                            $urlContact =
                                request()->path() === '/' || request()->path() === 'home'
                                    ? '#contact'
                                    : '/home#contact';
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ $urlContact }}">Contato</a>
                        </li>

                        @if (!session()->has('auth_id_person'))
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="/cadastro">Ciclo 2025. Cadastre-se</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle show" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="true">Meu Perfil</a>
                                <ul class="dropdown-menu show" data-bs-popper="static">

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="https://30semanas.com.br/entrar/editar"><i
                                                class="uil uil-user"></i> Editar meus Dados</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="https://30semanas.com.br/entrar/presenca"><i
                                                class="uil uil-user-check"></i> Minhas Presenças</a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="dropdown-item" href="https://30semanas.com.br/entrar/editar_senha"><i
                                                class="uil uil-data-sharing"></i> Alterar Senha</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ route('logout') }}"><i
                                                class="uil uil-signout"></i> Sair da Partilha</a>
                                    </li>

                                </ul>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle show" href="#" data-bs-toggle="dropdown"
                                    aria-expanded="true">Administrar</a>
                                <ul class="dropdown-menu show" data-bs-popper="static">

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="#">
                                            <i class="uil uil-users-alt"></i>
                                            Participantes / Líderes
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="#">
                                            <i class="uil uil-location-point"></i>
                                            Igrejas
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="#">
                                            <i class="uil uil-youtube"></i>
                                            Celebrações
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="dropdown-item" href="https://30semanas.com.br/partilha">
                                            <i class="uil uil-shield-check"></i>
                                            Salas de Partilhas
                                        </a>
                                    </li>

                                    @if (session('auth_adm-global') || session('auth_lider'))
                                        <li class="dropdown dropdown-submenu dropend">
                                            <a class="dropdown-item dropdown-toggle" href="#"
                                                data-bs-toggle="dropdown"><i class="uil uil-bright"></i>
                                                Cadastros</a>
                                            <ul class="dropdown-menu">

                                                @if (session('auth_adm-global') || session('auth_adm-igreja'))
                                                    <li class="nav-item">
                                                        <a class="dropdown-item" href="#">
                                                            Grupos
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="dropdown-item" href="#">
                                                            Gêneros
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="dropdown-item" href="#">
                                                            Tipos de Contatos
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="dropdown-item" href="/admin/type_participation">
                                                            Tipos de Participação
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="dropdown-item" href="#">
                                                            Tipos de Usuários
                                                        </a>
                                                    </li>
                                                @endif



                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- @if (session()->has('auth_id_person'))
                        @endif --}}


                    </ul>

                    {{-- <div class="offcanvas-footer d-lg-none">
                        <div>
                            <a href="mailto:30semanas@igrejadacidade.org.br" class="link-inverse">30semanas@igrejadacidade.org.br</a>
                            <a href="mailto:atendimento@igrejadacidade.org.br" class="link-inverse">atendimento@igrejadacidade.org.br</a>
                            <br /> (12) 4009-4300
                            <br /> (12) 99653-9066 <br />
                            <nav class="nav social social-white mt-4">
                                <a href="#"><i class="uil uil-twitter"></i></a>
                                <a href="#"><i class="uil uil-facebook-f"></i></a>
                                <a href="#"><i class="uil uil-dribbble"></i></a>
                                <a href="#"><i class="uil uil-instagram"></i></a>
                                <a href="#"><i class="uil uil-youtube"></i></a>
                            </nav>
                        </div>
                    </div> --}}
                </div>
            </div>


            <div class="navbar-other w-100 d-flex ms-auto">
                <ul class="navbar-nav flex-row align-items-center ms-auto">

                    <li class="nav-item">

                        @if (session()->has('auth_id_person'))
                            <a href="/share" class="btn btn-orange btn-icon btn-icon-start rounded share-btn">
                                <i class="uil uil-shield-check"></i>
                                <span class="desktop-text">{{ explode(' ', session('auth_name'))[0] }}, vamos
                                    partilhar?</span>
                                <span class="mobile-text">Partilhar</span>
                            </a>
                        @else
                            <a href="/login" class="btn btn-orange btn-icon btn-icon-start rounded"
                                style="background-color: #f78b77; border-color: #f78b77;">
                                <i class="uil uil-signin"></i>
                                <span class="desktop-text">Entrar e Partilhar?</span>
                                <span class="mobile-text">Entrar</span>
                            </a>
                        @endif

                    </li>

                    <li class="nav-item d-lg-none">
                        <button class="hamburger offcanvas-nav-btn"><span></span></button>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
            </div>
        </div>
    </nav>




    <div class="offcanvas offcanvas-end text-inverse" id="offcanvas-info" data-bs-scroll="true">
        <div class="offcanvas-header">
            <h3 class="text-white fs-30 mb-0">Sandbox</h3>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pb-6">
            <div class="widget mb-8">
                <p>Sandbox is a multipurpose HTML5 template with various layouts which will be a great solution for your
                    business.</p>
            </div>
            <div class="widget mb-8">
                <h4 class="widget-title text-white mb-3">Contact Info</h4>
                <address> Moonshine St. 14/05 <br /> Light City, London </address>
                <a href="mailto:first.last@email.com">info@email.com</a><br /> 00 (123) 456 78 90
            </div>
            <div class="widget mb-8">
                <h4 class="widget-title text-white mb-3">Learn More</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Our Story</a>
                    </li>
                    <li><a href="#">Terms of Use</a>
                    </li>
                    <li><a href="#">Privacy Policy</a>
                    </li>
                    <li><a href="#">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div class="widget">
                <h4 class="widget-title text-white mb-3">Follow Us</h4>
                <nav class="nav social social-white">
                    <a href="#"><i class="uil uil-twitter"></i></a>
                    <a href="#"><i class="uil uil-facebook-f"></i></a>
                    <a href="#"><i class="uil uil-dribbble"></i></a>
                    <a href="#"><i class="uil uil-instagram"></i></a>
                    <a href="#"><i class="uil uil-youtube"></i></a>
                </nav>
            </div>
        </div>
    </div>
</header>
