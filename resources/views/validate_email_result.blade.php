<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de E-mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container {{ $status ? 'success' : 'error' }}">
        <h2>{{ $message }}</h2>
        <p>{{ $status ? 'Agora você pode acessar sua conta normalmente.' : 'Tente novamente mais tarde.' }}</p>
    </div>
</body>
</html>
