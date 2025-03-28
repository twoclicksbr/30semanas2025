<section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-light-100 text-white"
    data-image-src="{{ $background ?? 'https://30semanas.com.br/assets/img/photos/bg26.jpg' }}">
    <div class="container pt-17 pb-20 pt-md-19 pb-md-21 text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
