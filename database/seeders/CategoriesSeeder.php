<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Cafés Calientes',
                'description' => 'Bebidas calientes preparadas con café.'
            ],

            [
                'name' => 'Cafés Fríos',
                'description' => 'Bebidas frías preparadas con café.'
            ],

            [
                'name' => 'Frappés',
                'description' => 'Frappés de diferentes sabores.'
            ],

            [
                'name' => 'Jugos',
                'description' => 'Jugos naturales.'
            ],

            [
                'name' => 'Cremoladas',
                'description' => 'Cremoladas artesanales.'
            ],

            [
                'name' => 'Chocolates',
                'description' => 'Chocolates de la marca PROCafés.'
            ],

            [
                'name' => 'Licores',
                'description' => 'Licores de café.'
            ],

            [
                'name' => 'Café en Bolsa',
                'description' => 'Café tostado y molido.'
            ],

            [
                'name' => 'Accesorios',
                'description' => 'Productos y accesorios.'
            ],

        ];

        foreach ($categories as $category) {

            DB::table('categories')->updateOrInsert(

                ['name' => $category['name']],

                [

                    'description' => $category['description'],

                    'created_at' => now(),

                    'updated_at' => now()

                ]

            );

        }
    }
}