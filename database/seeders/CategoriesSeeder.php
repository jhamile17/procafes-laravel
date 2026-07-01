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
                'description' => 'Bebidas calientes preparadas con café.',
            ],

            [
                'name' => 'Cafés Fríos',
                'description' => 'Bebidas frías preparadas con café.',
            ],

            [
                'name' => 'Frappés',
                'description' => 'Frappés de diferentes sabores.',
            ],

            [
                'name' => 'Jugos',
                'description' => 'Jugos naturales.',
            ],

            [
                'name' => 'Cremoladas',
                'description' => 'Cremoladas artesanales.',
            ],

            [
                'name' => 'Chocolates',
                'description' => 'Chocolates de la marca PROCAFES.',
            ],

            [
                'name' => 'Licores',
                'description' => 'Licores de café.',
            ],

            [
                'name' => 'Café en Bolsa',
                'description' => 'Café tostado y molido.',
            ],

            [
                'name' => 'Accesorios',
                'description' => 'Productos y accesorios.',
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
                    'updated_at' => now(),
                    'created_at' => now(),
                ]

            );
        }
    }
}