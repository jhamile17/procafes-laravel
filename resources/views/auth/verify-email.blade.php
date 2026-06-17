<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar correo</title>

    <style>

        body{
            font-family:Arial,sans-serif;
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            background:white;
            padding:30px;
            border-radius:10px;
            width:400px;
            text-align:center;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        button{
            background:#6F4E37;
            color:white;
            border:none;
            padding:12px 20px;
            border-radius:5px;
            cursor:pointer;
        }

    </style>

</head>
<body>

<div class="card">

<h2>☕ PROCAFES</h2>

<h3>Verifica tu correo electrónico</h3>

<p>
Te enviamos un enlace de verificación a tu correo.
Debes confirmar que el correo ingresado es real antes de continuar.
</p>

@if(session('message'))
<p>
{{ session('message') }}
</p>
@endif

<form method="POST"
action="{{ route('verification.send') }}">

@csrf

<button type="submit">
Reenviar correo
</button>

</form>

</div>

</body>
</html>