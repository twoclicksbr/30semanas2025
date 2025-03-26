@extends('layouts.app')

@section('title', 'Nova Senha')

@section('content')

<section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-light-100 text-white"
    data-image-src="https://30semanas.com.br/assets/img/photos/bg26.jpg"
    style="background-image: url('https://30semanas.com.br/assets/img/photos/bg26.jpg');">
    <div class="container pt-17 pb-20 pt-md-19 pb-md-21 text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                {{-- <h1 class="display-1 mb-3 text-white"></h1> --}}
            </div>
        </div>
    </div>
</section>

<section class="wrapper mb-10">
    <div class="container pb-14 pb-md-16">
        <div class="row">
            <div class="col-lg-7 col-xl-6 col-xxl-7 mx-auto mt-n20">
                <div class="card">
                    <div class="card-body p-11">

                        <h2 class="mb-3 text-start">Defina sua nova senha</h2>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                        
                            <div id="alert" class="mb-3"></div>
                        
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" name="password" id="password" required>
                                <label>Nova Senha: <span class="text-orange"><i class="uil uil-asterisk"></i></span></label>
                            </div>
                        
                            <button type="submit" class="btn btn-orange w-100">Atualizar Senha</button>
                        </form>

                        <script>
                            document.getElementById('reset-password-form').addEventListener('submit', function (e) {
                                const password = document.getElementById('password').value;
                                const alertBox = document.getElementById('alert');
                        
                                if (password.length < 6) {
                                    e.preventDefault();
                                    alertBox.innerHTML = `<div class="alert alert-danger">A senha deve ter no mínimo 6 caracteres.</div>`;
                                }
                            });
                        </script>

                        <p class="mt-5 text-center">
                            <a href="{{ route('login') }}" class="hover text-orange">
                                <i class="uil uil-signin"></i> Voltar ao Login
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
