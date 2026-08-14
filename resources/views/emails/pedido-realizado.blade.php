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

    <title>Pedido realizado | PROCÁFES</title>

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
                width="64"
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
                    color:#FFFFFF;
                    font-size:24px;">

                PROCÁFES

            </h1>

        </td>

    </tr>

    <!-- Body -->

    <tr>

        <td
            style="
                padding:32px;">

            <h2
                style="
                    margin:0 0 18px;
                    color:{{ $coffee }};
                    text-align:center;">

                ¡Pago confirmado!

            </h2>

            <p
                style="
                    margin:0 0 12px;
                    color:{{ $text }};
                    font-size:16px;">

                Hola{{ filled($user->name) ? ', '.$user->name : '' }}.

            </p>

            <p
                style="
                    margin:0 0 24px;
                    color:{{ $text }};
                    line-height:1.7;">

                Tu pedido ya fue registrado y se encuentra en proceso.

            </p>

            <table
                role="presentation"
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    border:1px solid {{ $border }};
                    border-radius:12px;
                    background:#FAF8F5;">

                <tr>
                    <td style="padding:18px;">

                        <table width="100%">

                            <tr>
                                <td style="color:{{ $text }};">
                                    Número de pedido
                                </td>

                                <td align="right">
                                    <strong style="color:{{ $coffee }};">
                                        {{ $order->numero_pedido }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" height="12"></td>
                            </tr>

                            <tr>
                                <td style="color:{{ $text }};">
                                    Total pagado
                                </td>

                                <td align="right">
                                    <strong style="color:{{ $primary }};">
                                        S/ {{ number_format($order->total_price,2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2" height="12"></td>
                            </tr>

                            <tr>
                                <td style="color:{{ $text }};">
                                    Entrega
                                </td>

                                <td align="right">

                                    {{ $order->delivery_type == 'pickup'
                                        ? 'Recojo en tienda'
                                        : 'Delivery' }}

                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

            </table>

            <p
                style="
                    margin:24px 0;
                    color:{{ $text }};
                    line-height:1.7;">

                También podrás consultar el estado de tu pedido desde tu cuenta en PROCÁFES.

            </p>

            <table
                align="center"
                cellpadding="0"
                cellspacing="0">

                <tr>

                    <td
                        bgcolor="{{ $primary }}"
                        style="border-radius:999px;">

                        <a
                            href="{{ route('customer.orders') }}"
                            style="
                                display:inline-block;
                                padding:14px 34px;
                                color:#FFFFFF;
                                text-decoration:none;
                                font-weight:bold;">

                            Ver mis pedidos

                        </a>

                    </td>

                </tr>

            </table>

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