@if (session()->has('auth_id_person'))
    @php
        header('Location: ' . route('home'));
        exit();
    @endphp
@endif

@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')

    @include('partials.painel')

    <section class="wrapper mb-10">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-7 mx-auto mt-n20">
                    <div class="card">
                        <div class="card-body p-11 ">

                            <h2 class="mb-3 text-start">Bem Vindo de Volta</h2>
                            <p class="lead mb-6 text-start">Preencha seu e-mail e senha para entrar.</p>

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif


                            <form class="text-start mb-3" method="POST" action="{{ route('login.process') }}">
                                @csrf
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control" placeholder="Email" id="email"
                                        name="email" required>
                                    <label for="email">Email: <span class="text-orange"><i
                                                class="uil uil-asterisk"></i></span></label>
                                </div>
                                <div class="form-floating password-field mb-4">
                                    <input type="password" class="form-control" placeholder="Password" id="password"
                                        name="password" required>
                                    <span class="password-toggle"><i class="uil uil-eye"></i></span>
                                    <label for="password">Senha: <span class="text-orange"><i
                                                class="uil uil-asterisk"></i></span></label>
                                </div>

                                <button type="submit"
                                    class="btn btn-orange btn-icon btn-icon-start rounded btn-login w-100 mb-2">
                                    <i class="uil uil-signin"></i>
                                    Entrar e Partilhar
                                </button>
                            </form>


                            <p class="mb-2 text-center">
                                Não lembra sua senha?
                                <a href="/rec_password" class="hover text-orange">
                                    Perdi minha senha
                                </a>
                            </p>

                            <p class="mb-1 mt-5 text-center">
                                <a href="/" class="hover text-orange">
                                    <i class="uil uil-corner-up-left-alt"></i>
                                    Voltar para Página Principal
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
