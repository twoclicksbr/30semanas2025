@extends('layouts.app')

@section('title', 'Recuperar Senha')

@section('content')

<section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-light-100 text-white"
    data-image-src="https://30semanas.com.br/assets/img/photos/bg26.jpg"
    style="background-image: url('https://30semanas.com.br/assets/img/photos/bg26.jpg');">
    <div class="container pt-17 pb-20 pt-md-19 pb-md-21 text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-1 mb-3 text-white">Recuperar Senha</h1>
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

                        <h2 class="mb-3 text-start">Esqueceu sua senha?</h2>
                        <p class="lead mb-6 text-start">Informe seu e-mail para receber um código de recuperação.</p>

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.send') }}">
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control" name="email" required>
                                <label>Email: <span class="text-orange"><i class="uil uil-asterisk"></i></span></label>
                            </div>

                            <button type="submit" class="btn btn-orange w-100">Enviar Código</button>
                        </form>

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
