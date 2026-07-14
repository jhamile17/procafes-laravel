@php
    $primary = '#D62828';
    $coffee = '#3D2C2E';
    $gold = '#F2C94C';
    $background = '#F7F2EC';
    $surface = '#FFFFFF';
    $border = '#ECE7E2';
    $text = '#6B5E57';
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu correo | PROCÁFES</title>
</head>

<body style="margin:0;padding:0;background:{{ $background }};font-family:Arial,Helvetica,sans-serif;">

<table
    role="presentation"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    style="background:{{ $background }};padding:40px 16px;">

<tr>

<td align="center">

<table
    role="presentation"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    style="max-width:600px;background:{{ $surface }};border-radius:14px;overflow:hidden;">

    <!-- Cabecera -->

    <tr>

        <td
            align="center"
            style="background:{{ $coffee }};padding:26px 32px;">

            <img
                src="{{ $message->embed(public_path('images/logo.jpg')) }}"
                alt="PROCÁFES"
                width="56"
                style="display:block;margin:0 auto 10px;">

            <h1
                style="margin:0;color:#fff;font-size:24px;font-weight:bold;letter-spacing:.5px;">

                PROCÁFES

            </h1>

        </td>

    </tr>

    <!-- Contenido -->

    <tr>

        <td
            style="padding:40px 36px;color:{{ $text }};font-size:16px;line-height:1.7;">

            <h2
                style="margin:0 0 18px;color:{{ $coffee }};font-size:28px;">

                Confirma tu correo

            </h2>

            <p style="margin:0 0 18px;">

                Hola{{ filled($pending->name) ? ', '.$pending->name : '' }}.

            </p>

            <p style="margin:0 0 24px;">

                Solo falta un paso para crear tu cuenta en <strong>PROCÁFES</strong>.
                Confirma que este correo electrónico te pertenece.

            </p>

            <!-- Email -->

            <table
                role="presentation"
                width="100%"
                cellspacing="0"
                cellpadding="0"
                border="0"
                style="margin:0 0 32px;">

                <tr>

                    <td
                        style="background:{{ $background }};
                               border:1px solid {{ $border }};
                               border-left:4px solid {{ $gold }};
                               padding:14px 18px;
                               border-radius:8px;
                               font-weight:bold;
                               color:{{ $coffee }};
                               word-break:break-word;">

                        {{ $pending->email }}

                    </td>

                </tr>

            </table>

            <!-- Botón -->

            <table
                role="presentation"
                cellspacing="0"
                cellpadding="0"
                border="0"
                align="center"
                style="margin:0 auto 32px;">

                <tr>

                    <td
                        bgcolor="{{ $primary }}"
                        style="border-radius:999px;">

                        <a
                            href="{{ $url }}"
                            style="
                                display:inline-block;
                                padding:15px 30px;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:16px;
                                font-weight:bold;">

                            Confirmar mi cuenta

                        </a>

                    </td>

                </tr>

            </table>

            <!-- Información -->

            <p
                style="margin:0 0 12px;font-size:14px;color:{{ $text }};">

                Este enlace estará disponible durante <strong>60 minutos</strong>.

            </p>

            <p
                style="margin:0;font-size:14px;color:{{ $text }};">

                Si no realizaste este registro, puedes ignorar este mensaje.
                No se creará ninguna cuenta.

            </p>

        </td>

    </tr>

    <!-- Footer -->

    <tr>

        <td
            align="center"
            style="padding:24px 16px;
                   border-top:1px solid {{ $border }};
                   color:#8A7D7D;
                   font-size:12px;">

            Equipo PROCÁFES

            <br><br>

            © {{ date('Y') }} PROCÁFES.
            Todos los derechos reservados.

        </td>

    </tr>

</table>

</td>

</tr>

</table>

</body>

</html>