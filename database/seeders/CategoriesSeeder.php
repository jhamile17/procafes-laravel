<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Cafés Calientes',
                'description' => 'Bebidas calientes preparadas a base de café.',
            ],

            [
                'name' => 'Cafés Fríos',
                'description' => 'Bebidas frías preparadas a base de café.',
            ],

            [
                'name' => 'Frappés',
                'description' => 'Frappés de diferentes sabores elaborados con ingredientes seleccionados.',
            ],

            [
                'name' => 'Jugos',
                'description' => 'Jugos naturales preparados con frutas frescas.',
            ],

            [
                'name' => 'Cremoladas',
                'description' => 'Cremoladas artesanales y bebidas frozen.',
            ],

            [
                'name' => 'Cold Brew',
                'description' => 'Bebidas preparadas mediante extracción en frío del café.',
            ],

            [
                'name' => 'Refrescos',
                'description' => 'Refrescos naturales preparados con frutas y café.',
            ],

            [
                'name' => 'Piqueos Artesanales',
                'description' => 'Piqueos y acompañamientos preparados al momento.',
            ],

            [
                'name' => 'Sándwiches',
                'description' => 'Sándwiches, hamburguesas y tostadas artesanales.',
            ],

            [
                'name' => 'Café Bar',
                'description' => 'Bebidas y cócteles especiales preparados con café.',
            ],

            [
                'name' => 'Chocolates',
                'description' => 'Chocolates y productos derivados del cacao.',
            ],

            [
                'name' => 'Licores',
                'description' => 'Licores y bebidas alcohólicas especiales.',
            ],

            [
                'name' => 'Café en Bolsa',
                'description' => 'Café tostado y molido en distintas presentaciones.',
            ],

            [
                'name' => 'Accesorios',
                'description' => 'Accesorios y artículos relacionados con el café.',
            ],

        ];

        foreach ($categories as $category) {

            DB::table('categories')->updateOrInsert(

                [
                    'slug' => Str::slug($category['name']),
                ],

                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]

            );
        }
    }
}