<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de E-mail</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <table align="center" width="100%" style="max-width: 600px; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <tr>
            <td align="center">
                <h2 style="color: #333;">Confirme seu E-mail</h2>
                <p style="color: #555;">Oi, <strong>{{ $user->person->name }}</strong>,</p>
                <p style="color: #555;">Vimos que você cadastrou seu e-mail <strong>{{ $user->email }}</strong> em nosso sistema.</p>
                <p style="color: #555;">Para ativar sua conta, clique no botão abaixo:</p>
                <a href="{{ $verificationLink }}" 
                   style="display: inline-block; padding: 10px 20px; color: #fff; background: #28a745; text-decoration: none; border-radius: 5px; font-weight: bold;">
                   Confirmar E-mail
                </a>
                <p style="color: #999; font-size: 12px;">Se não foi você, ignore este e-mail.</p>
            </td>
        </tr>
    </table>
</body>
</html>
