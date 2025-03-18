@component('mail::message')
# Olá, {{ $userName }}!

Obrigado por se cadastrar. Para verificar seu e-mail, utilize o seguinte código de verificação:

# **{{ $verificationCode }}**

Insira este código na página de verificação para concluir seu registro.

Se você não solicitou este cadastro, ignore este e-mail.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
