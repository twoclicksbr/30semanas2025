@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')

    @php
        // dd(session()->all());
    @endphp

    <section id="" class="">
        <div class="wrapper image-wrapper bg-cover bg-image bg-overlay bg-overlay-500"
            data-image-src="https://30semanas.com.br/assets/img/photos/bg26.jpg"
            style="background-image: url(&quot;https://30semanas.com.br/assets/img/photos/bg26.jpg&quot;);">
            <div class="container pt-18 pt-lg-21 pb-17 pb-lg-19 ">
                <div class="row">
                    <!--<div class="col-md-9 col-lg-8 col-xl-7 col-xxl-6 " data-cues="zoomIn" data-group="page-title" data-interval="-200" data-delay="200">-->
                    <!--<h1 class="display-2 fs-54 text-white mb-7">NÃO PERCA TEMPO PARA VIVER A SUA NOVA <mark style="background-color: #8b5a3c80;">ESTAÇÃO DA VIDA!</mark></h1>-->
                    <!--<h2 class="h6 text-uppercase ls-xl text-white mb-5">O 30 Semanas é uma ferramenta, e como tal, não tem o poder de mudar a vida de ninguém. Quem tem o poder para mudar, restaurar, transformar e curar é Jesus!</h2>-->
                    <!--<a href="./assets/media/movie.mp4" class="btn btn-circle btn-white btn-play ripple mx-auto" data-glightbox><i class="icn-caret-right"></i></a> -->
                    <!--</div>-->

                </div>

                <div class="row">
                    <div class="col-md-9 col-lg-8 col-xl-7 col-xxl-6 " data-cues="zoomIn" data-group="page-title"
                        data-interval="-200" data-delay="200" data-disabled="true">
                        <h1 class="display-2 fs-54 text-white mb-7" data-cue="zoomIn" data-interval="-200"
                            data-group="page-title" data-delay="200" data-show="true"
                            style="animation-name: zoomIn; animation-duration: 700ms; animation-timing-function: ease; animation-delay: 200ms; animation-direction: normal; animation-fill-mode: both;">
                            NÃO PERCA TEMPO <br> PARA O <mark style="background-color: #8b5a3c80;">CICLO 2025!</mark></h1>
                        <h1 class="display-2 fs-50 text-white mb-7" data-cue="zoomIn" data-interval="-200"
                            data-group="page-title" data-delay="200" data-show="true"
                            style="animation-name: zoomIn; animation-duration: 700ms; animation-timing-function: ease; animation-delay: 700ms; animation-direction: normal; animation-fill-mode: both;">
                            <mark style="background-color: #8b5a3c80;">25 de Abriu - Sexta-Feira.</mark>
                        </h1>
                        <h2 class="h6 text-uppercase ls-xl text-white mb-5" data-cue="zoomIn" data-interval="-200"
                            data-group="page-title" data-delay="200" data-show="true"
                            style="animation-name: zoomIn; animation-duration: 700ms; animation-timing-function: ease; animation-delay: 1200ms; animation-direction: normal; animation-fill-mode: both;">
                            O 30 Semanas é uma ferramenta, mas vale lembrar que ele não tem o poder de mudar a vida de
                            ninguém. Quem tem o poder para mudar, restaurar, transformar e curar é Jesus!
                        </h2>
                        <!-- <a href="./assets/media/movie.mp4" class="btn btn-circle btn-white btn-play ripple mx-auto" data-glightbox><i class="icn-caret-right"></i></a> -->
                    </div>
                </div>
            </div>

            <div class="overflow-hidden">
                <div class="divider text-white mx-n2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 60">
                        <path fill="currentColor" d="M0,0V60H1440V0A5771,5771,0,0,1,0,0Z" style="color: #f0ead9;"></path>
                    </svg>
                </div>
            </div>

        </div>
    </section>


    <section id="decision" class="section-frame overflow-hidden mt-10 mb-10">
        <div class="wrapper " style="border-radius: 1rem;">
            <div class="container ">
                <div class="row text-center">
                    <div class="col-xl-11 col-xxl-10 mx-auto">
                        <h2 class="fs-16 text-uppercase text-muted mb-3"></h2>

                        <h3 class="display-4 mb-10 px-lg-20 px-xl-20" style="color: #8b5a3c;"><b>AS DECISÕES DA SUA
                                VIDA!</b></h3>

                        <div class="row gy-6 text-center">

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/01-adminitir.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">1° Decisão</p>
                                <h1>Admitir</h1>
                                <p class="mb-0">Eu tenho problemas.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/02-confiar.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">2° Decisão</p>
                                <h1>Confiar</h1>
                                <p class="mb-0">Eu preciso confiar.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/03-entregar.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">3° Decisão</p>
                                <h1>Entregar</h1>
                                <p class="mb-0">Eu entrego tudo.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/04-depender.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">4° Decisão</p>
                                <h1>Depender</h1>
                                <p class="mb-0">Eu preciso depender.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/05-obedecer.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">5° Decisão</p>
                                <h1>Obedecer</h1>
                                <p class="mb-0">Eu vou obedecer.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/06-confessar.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">6° Decisão</p>
                                <h1>Confessar</h1>
                                <p class="mb-0">Eu vou falar.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/07-reparar.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">7° Decisão</p>
                                <h1>Reparar</h1>
                                <p class="mb-0">Eu me disponho a reparar.</p>

                            </div>

                            <div class="col-md-6 col-lg-3">

                                <img class="mb-4" src="https://30semanas.com.br/assets/img/decisoes/08-compartilhar.png"
                                    width="50%">

                                <!-- <div class="progressbar semi-circle purple" data-value="75"></div> -->

                                <p class="mb-0">8° Decisão</p>
                                <h1>Compartilhar</h1>
                                <p class="mb-0">Eu vou repartir.</p>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="share" class="wrapper" style="background-color: #8b5a3c">
        <div class="container py-md-5">

            <div class="row gx-lg-0 gy-10 mb-5 mb-md-10 align-items-center">
                <div class="col-lg-6 text-center">

                    <video width="70%" controls=""
                        style="border-radius: 84px 0px 0px 0px; margin-top: 60px; margin-bottom: 60px;">
                        <source src="{{ asset('https://30semanas.com.br/assets/img/null923.mp4') }}" type="video/mp4">
                        <source src="{{ asset('movie.ogg') }}" type="video/ogg">
                    </video>

                </div>
                <div class="col-lg-5 ms-auto">

                    <h2 class="fs-40 mb-3 text-white">Grupos de Partilhas</h2>
                    <h3 class="fs-28 mb-3 text-white">Regras de Primeiro Acesso aos Grupos de Partilhas On-line</h3>
                    <p class="text-white">
                        1° Acessar o site <b>https://30semanas.com.br</b>
                        <br>
                        2° No menu superior clique no botão Entrar.
                        <br>
                        3° Clicar no botão "Primeiro Acesso".
                        <br>
                        4° Informar o número Eklesia e clicar no botão "Confirmar".
                        <br>
                        5º Neste momento aparecerá o e-mail que você cadastrou no ato da sua inscrição.
                        <br>
                        6° Entrar nesta conta, abra o e-mail que enviamos e clique no link, "ALTERAR SENHA".
                        <br>
                        7° Altere a Senha e clique no botão "Alterar Senha".
                        <br>
                        8° Clicar no botão "Confirmar os dados".
                        <br>
                        9º Aparecerá uma mensagem "Seus dados foram alterados com sucesso".
                        <br>
                        9° No menu superior clique no botão "Partilhas".
                        <br>
                        10° Após às 20h, aparecerá as salas de partilhas, escolha uma das salas e você será direcionado para
                        a sala do Google Meet.

                    </p>

                    <h3 class="text-white">IMPORTANTE: NA HORA DA PARTILHA, USAR FONE DE OUVIDO E DEIXAR A CÂMERA ABERTA.
                    </h3>

                    {{-- <a href="/login" class="btn btn-soft-primary w-100 mt-2 mb-0">
                        <i class="uil uil-signin"></i> Entrar e Partilhar
                    </a> --}}
                </div>
            </div>
        </div>
    </section>

    <section id="celebration" class="wrapper ">
        <div class="container pt-14 pt-md-16 pb-15 pb-md-17">

            <div class="row gx-lg-8 gx-xl-12 gy-10 mb-15 mb-md-10 align-items-center">

                <div class="col-lg-7">

                    @if ($featuredVideo)
                        <div class="player text-orange" data-plyr-provider="youtube"
                            data-plyr-embed-id="{{ $featuredVideo['link_youtube'] }}">
                        </div>
                    @else
                        <p class="text-muted text-center">Nenhum vídeo disponível no momento.</p>
                    @endif

                </div>

                <div class="col-lg-5">
                    <h3 class="fs-16 text-uppercase text-muted mt-xxl-8 mb-3">Culto Online!</h3>
                    <h3 class="display-4 mb-6">QUINTA-FEIRA!</h3>
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <h4>Toda a quinta-feira temos a nossa celebração <br> on-line do <b>30 Semanas</b> a partir das
                                20h.</h4>

                            <a href="/video" class="btn btn-orange btn-icon btn-icon-start rounded ">
                                <i class="uil uil-youtube"></i> Ver todas as Celebrações.
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @php
        function getYouTubeID($url)
        {
            preg_match(
                '/(?:youtube\.com\/(?:[^\/]+\/[^\/]+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/',
                $url,
                $match,
            );
            return $match[1] ?? null;
        }
    @endphp

@endsection
