<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Confirma tu cuenta | PROCAFES</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:640px; margin:0 auto; padding:24px;">
        <div style="background:#ffffff; border-radius:10px; padding:28px; box-shadow:0 1px 3px rgba(0,0,0,.08);">
            <div style="text-align:center; margin-bottom:20px;">
                <img
                    src="{{ url('images/logo.png') }}"
                    alt="PROCAFES"
                    height="42"
                    style="display:inline-block; max-width:180px;"
                >
            </div>

            <h1 style="margin:0 0 12px; color:#111827; font-size:22px; text-align:center;">
                ¡Bienvenido a PROCAFES, {{ $user->name }}!
            </h1>

            <p style="margin:0 0 20px; color:#4b5563; font-size:15px; line-height:1.6; text-align:center;">
                Gracias por registrarte. Para activar tu cuenta y confirmar que este correo te pertenece,
                haz clic en el siguiente botón.
            </p>

            <div style="text-align:center; margin:24px 0;">
                <a
                    href="{{ $url }}"
                    target="_blank"
                    rel="noopener"
                    style="display:inline-block; background:#3E350E; color:#ffffff; text-decoration:none; padding:12px 20px; border-radius:8px; font-weight:bold;"
                >
                    Confirmar mi cuenta
                </a>
            </div>

            <p style="margin:0 0 10px; color:#6b7280; font-size:13px; line-height:1.6; text-align:center;">
                Este enlace vence en 60 minutos.
            </p>

            <p style="margin:0; color:#6b7280; font-size:13px; line-height:1.6; text-align:center;">
                Si no creaste esta cuenta, puedes ignorar este correo.
            </p>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin:24px 0;">

            <p style="margin:0; color:#9ca3af; font-size:12px; text-align:center;">
                © {{ date('Y') }} PROCAFES. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>