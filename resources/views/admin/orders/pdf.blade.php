<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>
        Orden {{ $order->numero_pedido }}
    </title>

    <style>

        @page {
            size: 80mm auto;
            margin: 4mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            width: 72mm;
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 10px;
        }

        .header {
            text-align: center;
            padding-bottom: 7px;
            border-bottom: 1px dashed #555;
        }

        .logo {
            font-size: 19px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .order-number {
            margin-top: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .info {
            padding: 7px 0;
            border-bottom: 1px dashed #555;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .label {
            font-weight: bold;
        }

        .delivery {
            margin: 7px 0;
            padding: 5px;
            text-align: center;
            border: 1px solid #333;
            font-weight: bold;
            text-transform: uppercase;
        }

        .products-title {
            margin-top: 7px;
            margin-bottom: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .products {
            width: 100%;
            border-collapse: collapse;
        }

        .products td {
            padding: 5px 0;
            border-bottom: 1px dashed #aaa;
            vertical-align: top;
        }

        .quantity {
            width: 35px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }

        .product {
            font-size: 11px;
            font-weight: bold;
            padding-left: 5px !important;
        }

        .footer {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px dashed #555;
            text-align: center;
            font-size: 8px;
        }

    </style>

</head>

<body>

    {{-- ENCABEZADO --}}

    <div class="header">

        <div class="logo">
            PROCÁFES
        </div>

        <div class="order-number">
            {{ $order->numero_pedido }}
        </div>

    </div>


    {{-- CLIENTE --}}

    <div class="info">

        <div class="info-row">

            <span class="label">
                Cliente:
            </span>

            {{ $order->user?->name ?? 'No registrado' }}

        </div>

        <div class="info-row">

            <span class="label">
                Teléfono:
            </span>

            {{ $order->user?->celular ?? 'No registrado' }}

        </div>

    </div>


    {{-- TIPO DE ENTREGA --}}

    <div class="delivery">

        {{ $order->delivery_label }}

    </div>


    {{-- PRODUCTOS --}}

    <div class="products-title">
        Productos
    </div>

    <table class="products">

        <tbody>

            @forelse($items as $item)

                <tr>

                    <td class="quantity">
                        {{ $item->quantity }}x
                    </td>

                    <td class="product">
                        {{ $item->product?->name ?? 'Producto eliminado' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="2">
                        No hay productos.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">

        PROCÁFES

    </div>

</body>

</html>