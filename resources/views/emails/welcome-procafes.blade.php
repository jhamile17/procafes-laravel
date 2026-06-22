<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a PROCAFES</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f3ef; font-family:Arial, Helvetica, sans-serif; color:#2d241f;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f5f3ef; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%; max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background:#2d241f; padding:28px 32px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:28px; letter-spacing:1px;">
                                PROCAFES
                            </h1>
                            <p style="margin:8px 0 0; color:#e7d8c8; font-size:14px;">
                                Café peruano para tus mejores momentos
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 32px 24px;">
                            <h2 style="margin:0 0 18px; font-size:24px; color:#2d241f;">
                                ¡Bienvenido, {{ $user->name }}!
                            </h2>

                            <p style="margin:0 0 16px; font-size:16px; line-height:1.6; color:#5d5149;">
                                Tu correo electrónico fue verificado correctamente.
                            </p>

                            <p style="margin:0 0 16px; font-size:16px; line-height:1.6; color:#5d5149;">
                                Ya puedes comprar nuestros cafés, revisar tus pedidos y administrar tu cuenta en PROCAFES.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:28px 0;">
                                <tr>
                                    <td style="background:#2d241f; border-radius:8px;">
                                        <a href="{{ route('customer.dashboard') }}"
                                           style="display:inline-block; padding:14px 24px; color:#ffffff; text-decoration:none; font-size:16px; font-weight:bold;">
                                            Ir a mi cuenta
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0; font-size:16px; line-height:1.6; color:#5d5149;">
                                Gracias por ser parte de PROCAFES.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px; background:#f1ece6; text-align:center;">
                            <p style="margin:0; font-size:12px; line-height:1.5; color:#7b6d63;">
                                © {{ date('Y') }} PROCAFES. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>