<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
</head>
<body style="font-family: Arial, sans-serif; text-align: center; background-color: #f8f9fa; padding: 20px;">

    <div style="background: #ffffff; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">

        <img src="https://30semanas.com.br/assets/img/photos/bg26.jpg" alt="{{ config('app.name') }}" style="width: 100%; max-width: 600px; margin-bottom: 20px;">

        <h2>Olá, {{ $userName }}!</h2>
        <p>Você solicitou a recuperação de senha no <strong>{{ config('app.name') }}</strong>.</p>

        <p>Use o código abaixo para redefinir sua senha:</p>

        <h1 style="color: #2c3e50; font-size: 30px;">{{ $token }}</h1>

        <p>Se você não solicitou isso, pode ignorar este e-mail.</p>

        <p style="margin-top: 20px;">Atenciosamente,<br><strong>{{ config('app.name') }}</strong></p>
    </div>

    <footer style="margin-top: 30px; font-size: 12px; color: #6c757d;">
        &copy; {{ date('Y') }} - Power by: <a href="https://twoclicks.com.br">Twoclicks</a>.<br>Todos os direitos reservados.
    </footer>

</body>
</html>
