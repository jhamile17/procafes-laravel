<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\TipoConsumo;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::where('name', 'PROCAFES')->first();

        $productos = [

            [
                'categoria' => 'Cafés Calientes',
                'tipo' => 'Caliente',

                'name' => 'Café Americano',

                'cost_price' => 4.50,

                'sale_price' => 8.50,

                'stock' => 50,

                'stock_minimo' => 10,
            ],

            [
                'categoria' => 'Frappés',

                'tipo' => 'Frío',

                'name' => 'Frappé Oreo',

                'cost_price' => 8.00,

                'sale_price' => 16.00,

                'stock' => 30,

                'stock_minimo' => 5,
            ],

            [
                'categoria' => 'Chocolates',

                'tipo' => 'Empacado',

                'name' => 'Chocolate Bitter',

                'cost_price' => 5.00,

                'sale_price' => 10.00,

                'stock' => 40,

                'stock_minimo' => 8,
            ],

            [
                'categoria' => 'Licores',

                'tipo' => 'Embotellado',

                'name' => 'Licor de Café',

                'cost_price' => 25.00,

                'sale_price' => 45.00,

                'stock' => 20,

                'stock_minimo' => 5,
            ],

        ];

        foreach ($productos as $producto) {

            $categoria = Category::where('name', $producto['categoria'])->first();

            $tipo = TipoConsumo::where('nombre', $producto['tipo'])->first();

            Product::updateOrCreate(

                [

                    'name' => $producto['name']

                ],

                [

                    'categories_id' => $categoria->id,

                    'brand_id' => $brand->id,

                    'tipo_consumo_id' => $tipo->id,

                    'sku' => strtoupper(substr($producto['name'],0,3)).'-'.str_pad(rand(1,9999),4,'0',STR_PAD_LEFT),

                    'barcode' => null,

                    'description' => $producto['name'],

                    'cost_price' => $producto['cost_price'],

                    'sale_price' => $producto['sale_price'],

                    'stock' => $producto['stock'],

                    'stock_minimo' => $producto['stock_minimo'],

                    'status' => true,

                ]

            );

        }
    }
}