@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')

    @include('partials.banner')

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
