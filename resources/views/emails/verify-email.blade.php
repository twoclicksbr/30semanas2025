<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de E-mail</title>
</head>
<body style="font-family: Arial, sans-serif; text-align: center; background-color: #f8f9fa; padding: 20px;">

    <div style="background: #ffffff; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">

        <!-- Adicionando a imagem no topo -->
        <img src="https://30semanas.com.br/assets/img/photos/bg26.jpg" alt="{{ config('app.name') }}" style="width: 100%; max-width: 600px; margin-bottom: 20px;">

        <h2>Olá, {{ $userName }}!</h2>
        <p>Parabéns por fazer a sua inscrição para o <strong>Ciclo do 30 Semanas 2025</strong> . Mas antes de tudo, precisamos verificar seu e-mail. Utilize o código de verificação:</p>

        <h1 style="color: #2c3e50; font-size:30px; text-align-last: center ">{{ $verificationCode }}</h1>

        <p>Insira este código na página de verificação para concluir seu registro.</p>

        <p>Se você não solicitou este cadastro, ignore este e-mail.</p>
        
        <p style="margin-top: 20px;">Atenciosamente,<br><strong>{{ config('app.name') }}</strong></p>
    </div>


    <footer style="margin-top: 30px; font-size: 12px; color: #6c757d;">
        &copy; {{ date('Y') }} - Power by: <a href="https://twoclicks.com.br">Twoclicks</a>. <br> Todos os direitos reservados.
    </footer>

</body>
</html>



{{-- @component('mail::message') --}}
{{--  --}}
{{-- Removendo o título automático do Laravel Mail --}}
{{-- @slot('header') --}}
{{-- @endslot --}}
{{--  --}}
{{-- <!-- Adicionando a imagem no topo --> --}}
{{-- <img src="{{ asset('https://30semanas.com.br/assets/img/photos/bg26.jpg') }}" alt="{{ config('app.name') }}" style="width: 100%; margin-bottom: 20px;"> --}}
{{--  --}}
{{-- # Olá, {{ $userName }}! --}}
{{--  --}}
{{-- Parabéns por fazer a sua inscrição no 30Semanas. Mas antes de tudo, precisamos verificar seu e-mail, utilize o seguinte código de verificação: --}}
{{--  --}}
{{-- # **{{ $verificationCode }}** --}}
{{--  --}}
{{-- Insira este código na página de verificação para concluir seu registro. --}}
{{--  --}}
{{-- Se você não solicitou este cadastro, ignore este e-mail. --}}
{{--  --}}
{{-- Atenciosamente,<br> --}}
{{-- {{ config('app.name') }} --}}
{{-- @endcomponent --}}
