@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')

    <section id="decisoes" class="">
        <div class="wrapper image-wrapper bg-cover bg-image bg-overlay bg-overlay-500"
            data-image-src="https://30semanas.com.br/assets/img/photos/bg26.jpg"
            style="background-image: url('https://30semanas.com.br/assets/img/photos/bg26.jpg');">
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
                            <mark style="background-color: #8b5a3c80;">24 de Abril - Quinta-Feira.</mark>
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

    @if ($featuredVideo)
        <div class="container pt-10 pt-md-10 pb-15 pb-md-10">

            <div class="row gx-lg-8 gx-xl-12 gy-10 mb-10 mb-md-5 align-items-center">
                <div class="col-lg-7">

                    <div class="player text-orange" data-plyr-provider="youtube"
                        data-plyr-embed-id="{{ $featuredVideo['link_youtube'] }}">
                    </div>

                </div>
                <!--/column -->
                <div class="col-lg-5">
                    <h3 class="fs-16 text-uppercase text-muted mt-xxl-8 mb-3">Culto Online!</h3>
                    <h3 class="display-4 mb-6">QUINTA-FEIRA!</h3>
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <h4>Toda a quinta-feira temos a nossa celebração <br> on-line do <b>30 Semanas</b> a partir das
                                20h.
                            </h4>


                        </div>
                    </div>
                </div>





            </div>
        </div>
    @endif

    <div class="container">
        <div class="row mb-16">
            @forelse ($videos as $video)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden; background-color: #fffdf5;">
                        
                        <div class="player text-orange rounded-top" data-plyr-provider="youtube"
                            data-plyr-embed-id="{{ getYouTubeID($video['link_youtube']) }}">
                        </div>
    
                        <div class="p-3">
                            <h6 class="mb-1 " style="font-weight: bold;">{{ $video['name'] }}</h6>
                            <p class="text-muted small mb-2">{{ \Carbon\Carbon::parse($video['dt_celebration'])->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Nenhum vídeo encontrado.</p>
            @endforelse
        </div>
    </div>
    

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
