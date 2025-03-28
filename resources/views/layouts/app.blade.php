<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="An impressive and flawless site template that includes various UI elements and countless features, attractive ready-made blocks and rich pages, basically everything you need to create a unique and professional website.">
    <meta name="keywords"
        content="bootstrap 5, business, corporate, creative, gulp, marketing, minimal, modern, multipurpose, one page, responsive, saas, sass, seo, startup, html5 template, site template">
    <meta name="author" content="elemis">
    <title>30 Semanas | Igreja da Cidade - São José dos Campos - SP</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors/orange.css') }}">
    <link rel="preload" href="{{ asset('assets/css/fonts/urbanist.css') }}" as="style"
        onload="this.rel='stylesheet'">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>




    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <meta name="api-username" content="{{ config('api.username') }}"> --}}
    {{-- <meta name="api-token" content="{{ config('api.token') }}"> --}}




</head>

<body style="background-color: #f0ead9">

    {{-- <div class="page-frame "> --}}
    {{-- <div class="content-wrapper"> --}}

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    {{-- </div> --}}

    @include('partials.footer')

    {{-- </div> --}}

    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>



    <style>
        .mobile-text {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-text {
                display: none;
            }

            .mobile-text {
                display: inline;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00', {
                reverse: true
            });
        });

        $(document).ready(function() {
            $('#cep').mask('99.999-999', {
                reverse: true
            });
        });

        $(document).ready(function() {
            var options = {
                onKeyPress: function(celular, e, field, options) {
                    var masks = ['(00) 0000-00009', '(00) 00000-0000'];
                    var mask = (celular.length > 14) ? masks[1] : masks[0];
                    $('#whatsapp').mask(mask, options);
                }
            };
            $('#whatsapp').mask('(00) 00000-0000', options);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener("click", function(event) {
                    event.preventDefault();
                    const target = document.querySelector(this.getAttribute("href"));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 100, // Ajuste se o menu for fixo
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>



</body>

</html>
