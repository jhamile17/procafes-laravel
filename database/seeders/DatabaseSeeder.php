<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([

            /*
            |--------------------------------------------------------------------------
            | Seguridad
            |--------------------------------------------------------------------------
            */

            RolesSeeder::class,
            UserSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Catálogo
            |--------------------------------------------------------------------------
            */

            CategoriesSeeder::class,
            BrandsSeeder::class,
            TiposConsumoSeeder::class,
            ProductsSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Inventario
            |--------------------------------------------------------------------------
            */

            TiposMovimientoInventarioSeeder::class,
            NivelesAlertaStockSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Pedidos
            |--------------------------------------------------------------------------
            */

            EstadosPedidoSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Pagos
            |--------------------------------------------------------------------------
            */

            PaymentMethodsSeeder::class,
            EstadosPagoSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Empresa
            |--------------------------------------------------------------------------
            */

            ConfiguracionEmpresaSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Datos de prueba
            |--------------------------------------------------------------------------
            */

            ShippingAddressesSeeder::class,
            OrdersSeeder::class,
            OrderItemsSeeder::class,

        ]);
    }
}