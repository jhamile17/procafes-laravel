<?php

namespace App\Services\Cliente;

use App\Models\EstadoPedido;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PedidoService
{
    /**
     * Obtener los pedidos del cliente.
     */
    public function getOrders(int $userId): LengthAwarePaginator
        {
            return Order::query()
                ->with([
                    'estadoPedido',
                    'items.product',
                    'payment',
                    'comprobante.electronicDocument',
                ])
                ->where('user_id', $userId)
                ->latest()
                ->paginate(8);
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