@php
    $primary = '#D62828';
    $coffee = '#3D2C2E';
    $background = '#F7F2EC';
    $surface = '#FFFFFF';
    $border = '#ECE7E2';
    $text = '#6B5E57';

    /*
    |--------------------------------------------------------------------------
    | Estado del pedido
    |--------------------------------------------------------------------------
    */

    $codigoEstado = strtoupper(
        $order->estadoPedido?->codigo ?? 'PENDIENTE'
    );

    $estadoNombre = $order->estadoPedido?->nombre ?? 'Pendiente';

    /*
    |--------------------------------------------------------------------------
    | Color y mensaje según estado
    |--------------------------------------------------------------------------
    */

    $estadoColor = match ($codigoEstado) {

        'PENDIENTE'
            => '#F59E0B',
        'CONFIRMADO'
            => '#16A34A',
        'PREPARACION'
            => '#D97706',
        'LISTO_RECOJO'
            => '#7C3AED',
        'EN_CAMINO'
            => '#2563EB',
        'ENTREGADO'
            => '#16A34A',
        'CANCELADO'
            => '#DC2626',

        default
            => $primary,
    };

    $estadoMensaje = match ($codigoEstado) {

        'PENDIENTE'
            => 'Tu pedido ha sido registrado y está pendiente de confirmación.',

        'CONFIRMADO'
            => 'Tu pedido ha sido confirmado y pronto comenzaremos a prepararlo.',

        'PREPARACION'
            => 'Tu pedido está siendo preparado. ¡Ya falta poco!',
        'LISTO_RECOJO'
            => 'Tu pedido está listo para recoger en nuestra tienda. Puedes acercarte a recogerlo cuando estés listo.',
        'EN_CAMINO'
            => 'Tu pedido está en camino. Pronto llegará a su destino.',

        'ENTREGADO'
            => 'Tu pedido ha sido entregado. ¡Gracias por comprar en PROCÁFES!',

        'CANCELADO'
            => 'Tu pedido ha sido cancelado. Si tienes alguna consulta, puedes comunicarte con nosotros.',

        default
            => 'El estado de tu pedido ha sido actualizado.',
    };

    $tituloEstado = match ($codigoEstado) {

        'PENDIENTE'
            => 'Pedido pendiente',

        'CONFIRMADO'
            => '¡Pedido confirmado!',

        'PREPARACION'
            => '¡Tu pedido está en preparación!',
        'LISTO_RECOJO'
            => '¡Tu pedido está listo para recoger!',
        'EN_CAMINO'
            => '¡Tu pedido está en camino!',
        'ENTREGADO'
            => '¡Pedido entregado!',
        'CANCELADO'
            => 'Pedido cancelado',

        default
            => 'Estado de pedido actualizado',
    };
@endphp

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        {{ $tituloEstado }} | PROCÁFES
    </title>

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

                {{ $tituloEstado }}

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

                {{ $estadoMensaje }}

            </p>


            <!-- Estado -->

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

                    <td
                        align="center"
                        style="padding:24px 18px;">

                        <div
                            style="
                                display:inline-block;
                                padding:10px 24px;
                                border-radius:999px;
                                background:{{ $estadoColor }};
                                color:#FFFFFF;
                                font-size:14px;
                                font-weight:bold;">

                            {{ $estadoNombre }}

                        </div>

                    </td>

                </tr>

            </table>


            <!-- Información del pedido -->

            <table
                role="presentation"
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    margin-top:16px;
                    border:1px solid {{ $border }};
                    border-radius:12px;
                    background:#FAF8F5;">

                <tr>

                    <td style="padding:18px;">

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0">

                            <tr>

                                <td style="color:{{ $text }};">

                                    Número de pedido

                                </td>

                                <td align="right">

                                    <strong
                                        style="
                                            color:{{ $coffee }};">

                                        {{ $order->numero_pedido }}

                                    </strong>

                                </td>

                            </tr>

                            <tr>

                                <td
                                    colspan="2"
                                    height="12">
                                </td>

                            </tr>

                            <tr>

                                <td style="color:{{ $text }};">

                                    Total

                                </td>

                                <td align="right">

                                    <strong
                                        style="
                                            color:{{ $primary }};">

                                        S/
                                        {{ number_format(
                                            $order->total_price,
                                            2
                                        ) }}

                                    </strong>

                                </td>

                            </tr>

                            <tr>

                                <td
                                    colspan="2"
                                    height="12">
                                </td>

                            </tr>

                            <tr>

                                <td style="color:{{ $text }};">

                                    Entrega

                                </td>

                                <td align="right">

                                    {{ $order->delivery_type === 'pickup'
                                        ? 'Recojo en tienda'
                                        : 'Delivery' }}

                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

            </table>


            <!-- Mensaje adicional -->

            <p
                style="
                    margin:24px 0;
                    color:{{ $text }};
                    line-height:1.7;">

                También puedes consultar el estado de tu pedido
                desde tu cuenta en PROCÁFES.

            </p>


            <!-- Botón -->

            <table
                align="center"
                cellpadding="0"
                cellspacing="0">

                <tr>

                    <td
                        bgcolor="{{ $primary }}"
                        style="
                            border-radius:999px;">

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