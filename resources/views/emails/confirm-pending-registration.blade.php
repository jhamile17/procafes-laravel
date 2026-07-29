@php
    $primary = '#D62828';
    $coffee = '#3D2C2E';
    $background = '#F7F2EC';
    $surface = '#FFFFFF';
    $border = '#ECE7E2';
    $text = '#6B5E57';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Confirma tu correo | PROCÁFES</title>
</head>
<body
    style="
        margin:0;
        padding:0;
        background:{{ $background }};
        font-family:Arial,Helvetica,sans-serif;">
<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="padding:40px 16px;">
<tr>
<td align="center">
<table
    role="presentation"
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="
        max-width:520px;
        background:{{ $surface }};
        border-radius:16px;
        overflow:hidden;
        border:1px solid {{ $border }};">
    <!-- Header -->
    <tr>
        <td
            align="center"
            style="
                background:{{ $coffee }};
                padding:24px;">
            <img
                src="{{ $message->embed(public_path('images/logo.jpg')) }}"
                alt="PROCÁFES"
                width="58"
                style="
                display:block;
                width:64px;
                height:64px;
                margin:0 auto 12px;
                border-radius:50%;
                border:3px solid #FFFFFF;
                object-fit:cover;
                background:#FFFFFF;">
            <h1
                style="
                    margin:0;
                    color:#ffffff;
                    font-size:24px;
                    font-weight:bold;
                    letter-spacing:.5px;">
                PROCÁFES
            </h1>
        </td>
    </tr>
    <!-- Body -->
    <tr>
        <td
            align="center"
            style="
                padding:32px 32px 24px;">
            <h2
                style="
                    margin:0 0 18px;
                    color:{{ $coffee }};
                    font-size:28px;">

                Confirma tu correo
            </h2>
            <p
                style="
                    margin:0 0 12px;
                    color:{{ $text }};
                    font-size:16px;">

                Hola{{ filled($pending->name) ? ', '.$pending->name : '' }}.
            </p>
            <p
                style="
                    margin:0 0 28px;
                    color:{{ $text }};
                    font-size:15px;
                    line-height:1.6;">
                Confirma tu correo para activar tu cuenta en
                <strong>PROCÁFES</strong>.
            </p>
            <table
                role="presentation"
                cellpadding="0"
                cellspacing="0"
                border="0"
                align="center">
                <tr>
                    <td
                        bgcolor="{{ $primary }}"
                        style="border-radius:999px;">
                        <a
                            href="{{ $url }}"
                            style="
                                display:inline-block;
                                padding:14px 34px;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:15px;
                                font-weight:bold;">

                            Confirmar correo
                        </a>
                    </td>
                </tr>
            </table>
            <p
                style="
                    margin:24px 0 8px;
                    color:{{ $text }};
                    font-size:13px;">
                Válido durante <strong>60 minutos</strong>.
            </p>
            <p
                style="
                    margin:0;
                    color:#8A7D7D;
                    font-size:13px;">
                Si no realizaste este registro, puedes ignorar este correo.
            </p>
        </td>
    </tr>
    <!-- Footer -->
    <tr>
        <td
            align="center"
            style="
                border-top:1px solid {{ $border }};
                padding:18px;
                color:#9B8D85;
                font-size:12px;">
            © {{ date('Y') }} PROCÁFES
        </td>
    </tr>
</table>
</td>
</tr>
</table>
</body>
</html>