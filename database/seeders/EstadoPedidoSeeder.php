<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoPedido;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoPedido::insert([

            [
                'codigo' => 'PENDIENTE',
                'nombre' => 'Pendiente',
                'descripcion' => 'Pedido registrado y pendiente de pago.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'PAGADO',
                'nombre' => 'Pagado',
                'descripcion' => 'Pago confirmado.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'PREPARACION',
                'nombre' => 'En preparación',
                'descripcion' => 'El pedido se encuentra en preparación.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'EN_CAMINO',
                'nombre' => 'En camino',
                'descripcion' => 'El pedido se encuentra en reparto.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'ENTREGADO',
                'nombre' => 'Entregado',
                'descripcion' => 'El pedido fue entregado al cliente.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'CANCELADO',
                'nombre' => 'Cancelado',
                'descripcion' => 'El pedido fue cancelado.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}