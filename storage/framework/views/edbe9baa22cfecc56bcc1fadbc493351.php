<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablece tu contraseña - PROCAFES</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f5f5f5;">
        <tr>
            <td align="center" style="padding:30px 15px;">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                       style="max-width:600px; width:100%; background:#ffffff; border-radius:10px; overflow:hidden;">

                    <tr>
                        <td style="background:#212529; color:#ffffff; padding:24px; text-align:center;">
                            <h1 style="margin:0; font-size:24px;">PROCAFES</h1>
                            <p style="margin:8px 0 0; font-size:13px; color:#e9ecef;">
                                Café peruano para cada momento
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px; color:#333333; font-size:16px; line-height:1.6;">
                            <h2 style="margin:0 0 18px; color:#212529;">
                                Restablece tu contraseña
                            </h2>

                            <p>Hola <?php echo e($user->name); ?>,</p>

                            <p>
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta de PROCAFES.
                            </p>

                            <p style="text-align:center; margin:30px 0;">
                                <a href="<?php echo e($url); ?>"
                                   style="background:#212529; color:#ffffff; padding:13px 22px;
                                          text-decoration:none; border-radius:6px; display:inline-block;
                                          font-weight:bold;">
                                    Restablecer contraseña
                                </a>
                            </p>

                            <p>
                                Este enlace vence en 60 minutos.
                            </p>

                            <p>
                                Si no solicitaste este cambio, puedes ignorar este correo.
                                Tu contraseña seguirá siendo la misma.
                            </p>

                            <p style="margin-bottom:0;">
                                Saludos,<br>
                                <strong>Equipo PROCAFES</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f1f1f1; color:#6c757d; padding:16px; text-align:center; font-size:12px;">
                            © <?php echo e(date('Y')); ?> PROCAFES. Todos los derechos reservados.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html><?php /**PATH E:\Pagina-web-\resources\views/emails/reset-password-procafes.blade.php ENDPATH**/ ?>