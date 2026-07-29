<?php

namespace App\Services\Cliente;

use App\Models\EstadoPedido;
use App\Models\Order;
use Illuminate\Support\Collection;

class PedidoService
{
    /**
     * Obtener los pedidos del cliente.
     */
    public function getOrders(int $userId): Collection
        {
            return Order::query()
                ->with([
                    'estadoPedido',
                    'items.product',
                    'payment',
                ])
                ->where('user_id', $userId)
                ->latest()
                ->get();
        }

    /**
     * Transformar un pedido para la vista.
     */
    protected function transform(Order $order): array
    {
        return [
            'id' => $order->id,

            'numero' => $order->numero_pedido,

            'fecha' => $order->created_at->format('d/m/Y'),

            'estado' => $order->estadoPedido->nombre,

            'estado_class' => $this->estadoClass(
                $order->estadoPedido->codigo
            ),

            'productos' => $order->totalItems(),

            'total' => number_format($order->total_price, 2),

            'delivery_type' => $order->delivery_type,
        ];
    }

    /**
     * Obtener la clase CSS del estado.
     */
    protected function estadoClass(string $codigo): string
    {
        return match ($codigo) {

            EstadoPedido::PENDIENTE => 'pending',

            EstadoPedido::CONFIRMADO => 'processing',

            EstadoPedido::ENTREGADO => 'completed',

            EstadoPedido::CANCELADO => 'cancelled',

            default => 'default',
        };
    }
}