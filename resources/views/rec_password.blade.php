@extends('layouts.app')

@section('title', 'Recuperar Senha')

@section('content')

    @include('partials.painel')

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

                            {{-- <form method="POST" action="{{ route('password.send') }}">
                            @csrf

                            <div class="form-floating mb-4">
                                <input type="email" class="form-control" name="email" required>
                                <label>Email: <span class="text-orange"><i class="uil uil-asterisk"></i></span></label>
                            </div>

                            <button type="submit" class="btn btn-orange w-100">Enviar Código</button>
                        </form> --}}

                            <form id="rec-password-form">
                                @csrf

                                <div id="alert" class="mb-3"></div>

                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control" name="email" id="email" required>
                                    <label for="email">Email: <span class="text-orange"><i
                                                class="uil uil-asterisk"></i></span></label>
                                </div>

                                <button type="submit" class="btn btn-orange w-100">Enviar Código</button>
                            </form>

                            <script>
                                document.getElementById('rec-password-form').addEventListener('submit', async function(e) {
                                    e.preventDefault();

                                    const email = document.getElementById('email').value;
                                    const alertBox = document.getElementById('alert');
                                    const API_BASE_URL = "{{ config('api.base_url') }}";

                                    try {
                                        const response = await fetch(`${API_BASE_URL}/api/v1/rec_password`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'username': "{{ config('api.username') }}",
                                                'token': "{{ config('api.token') }}"
                                            },
                                            body: JSON.stringify({
                                                email
                                            })
                                        });

                                        const data = await response.json();
                                        console.log("Resposta da API:", data);

                                        if (response.ok) {
                                            window.location.href = `/verify_token?email=${encodeURIComponent(email)}`;
                                        } else {
                                            let msg = data.error || 'Erro desconhecido.';

                                            if (msg === 'Email not found.') {
                                                msg = 'E-mail não encontrado.';
                                            } else if (msg === 'Invalid token or email.') {
                                                msg = 'Token ou e-mail inválido.';
                                            }

                                            alertBox.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
                                        }
                                    } catch (error) {
                                        alertBox.innerHTML = `<div class="alert alert-danger">Erro ao conectar com a API.</div>`;
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
