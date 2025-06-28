<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo mensaje de contacto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Nuevo mensaje de contacto</h1>
        <p><strong>Nombre:</strong> {{ $name }}</p>
        <p><strong>Correo electrónico:</strong> {{ $email }}</p>
        <p><strong>Mensaje:</strong></p>
        <p>{{ $messageContent }}</p>
        <div class="footer">
            Este mensaje fue enviado desde el formulario de contacto de tu sitio web.
        </div>
    </div>
</body>

</html>