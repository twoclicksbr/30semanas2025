@extends('layouts.app')

@section('title', 'Verificar Código')

@section('content')

    @include('partials.painel')

    <section class="wrapper mb-10">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-7 mx-auto mt-n20">
                    <div class="card">
                        <div class="card-body p-11">

                            <h2 class="mb-3 text-start">
                                {{ request()->is('verify_email_code') ? 'Verificação de E-mail' : 'Informe o código enviado para seu e-mail' }}
                            </h2>

                            <p>Enviamos um código para seu e-mail. Insira-o abaixo para confirmar sua inscrição.</p>
                            
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            {{-- <form method="POST" action="{{ route('password.verify') }}">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" name="token" required>
                                <label>Código: <span class="text-orange"><i class="uil uil-asterisk"></i></span></label>
                            </div>

                            <button type="submit" class="btn btn-orange w-100">Verificar Código</button>
                        </form> --}}



                            <form id="verify-token-form" method="POST"
                                action="{{ request()->is('verify_email_code') ? route('verify.email.code') : route('password.verify') }}">

                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <div id="token-inputs" style="display: flex; gap: 10px; justify-content: center;">
                                    @for ($i = 0; $i < 6; $i++)
                                        <input type="text" maxlength="1" class="form-control text-center"
                                            style="width: 55px; height: 60px; font-size: 32px; line-height: 60px; padding: 0;"
                                            required>
                                    @endfor
                                </div>
                                <input type="hidden" name="token" id="token">
                                <button type="submit" class="btn btn-orange w-100 mt-4">Verificar Código</button>
                            </form>

                            <script>
                                const inputs = document.querySelectorAll('#token-inputs input');

                                inputs.forEach((input, i) => {
                                    input.addEventListener('input', () => {
                                        if (input.value.length === 1 && i < inputs.length - 1) {
                                            inputs[i + 1].focus();
                                        }
                                    });

                                    input.addEventListener('keydown', (e) => {
                                        if (e.key === 'Backspace' && input.value === '' && i > 0) {
                                            inputs[i - 1].focus();
                                        }
                                    });

                                    input.addEventListener('paste', (e) => {
                                        e.preventDefault();
                                        const paste = (e.clipboardData || window.clipboardData).getData('text');
                                        const digits = paste.replace(/\D/g, '').slice(0, 6);

                                        digits.split('').forEach((digit, index) => {
                                            if (inputs[index]) {
                                                inputs[index].value = digit;
                                            }
                                        });

                                        if (inputs[digits.length - 1]) {
                                            inputs[digits.length - 1].focus();
                                        }
                                    });
                                });

                                document.getElementById('verify-token-form').addEventListener('submit', function(e) {
                                    const token = Array.from(inputs).map(input => input.value).join('');
                                    document.getElementById('token').value = token;
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
