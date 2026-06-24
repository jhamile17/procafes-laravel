<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu correo | PROCAFES</title>
</head>
<body style="margin:0; padding:0; background:#f6f3ef; font-family:Arial, Helvetica, sans-serif; color:#2f241e;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f6f3ef; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%; max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td align="center" style="background:#4a2c1f; padding:28px 32px;">
                            <h1 style="margin:0; color:#ffffff; font-size:28px; letter-spacing:1px;">PROCAFES</h1>
                            <p style="margin:8px 0 0; color:#f4dfc6; font-size:14px;">Café que acompaña tus mejores momentos</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px;">
                            <h2 style="margin:0 0 18px; color:#4a2c1f; font-size:24px;">
                                ¡Hola{{ filled($pending->name) ? ', ' . $pending->name : '' }}!
                            </h2>

                            <p style="margin:0 0 16px; font-size:16px; line-height:1.6;">
                                Recibimos una solicitud para crear una cuenta en PROCAFES con:
                            </p>

                            <p style="margin:0 0 24px; padding:12px 16px; background:#f1ebe5; border-radius:8px; font-size:16px; font-weight:bold; word-break:break-word;">
                                {{ $pending->email }}
                            </p>

                            <p style="margin:0 0 24px; font-size:16px; line-height:1.6;">
                                Para confirmar que este correo te pertenece y crear tu cuenta, usa el siguiente botón:
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td style="background:#7b4b2a; border-radius:8px;">
                                        <a href="{{ $url }}" style="display:inline-block; padding:14px 24px; color:#ffffff; text-decoration:none; font-size:16px; font-weight:bold;">
                                            Confirmar y crear mi cuenta
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 12px; color:#665a53; font-size:14px; line-height:1.6;">
                                Este enlace vence en 60 minutos.
                            </p>

                            <p style="margin:0; color:#665a53; font-size:14px; line-height:1.6;">
                                Si no solicitaste una cuenta, puedes ignorar este mensaje. No se creará ninguna cuenta.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>