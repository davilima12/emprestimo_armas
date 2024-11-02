<!DOCTYPE html>
<html>
<head>
    <title>Recuperação de Senha</title>
</head>
<body style="background-color: #f4f4f4; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="background-color: white;margin-left:auto;margin-right:auto; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 600px; padding: 20px; font-family: Arial, sans-serif;max-height:400px">

        <div style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #3498db;">{{ config('app.name') }}</h2>
        </div>

        <div>
            <p>Olá, <strong>{{ $userName }}</strong>,</p>
            <p>Recebemos sua solicitação de recuperação de senha. Para redefinir sua senha, clique no botão abaixo:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.frontend_url') . '/reset_password/' . $token }}"
                   style="display: inline-block; padding: 15px 30px; color: white; background-color: #3498db; text-decoration: none; border-radius: 5px; font-size: 16px;">
                   Redefinir Senha
                </a>
            </div>

            <p>Se você não solicitou a recuperação de senha, por favor, ignore este e-mail.</p>
        </div>

        <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 20px; text-align: center; font-size: 12px; color: #777;">
            <p>Atenciosamente,<br>Equipe {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
