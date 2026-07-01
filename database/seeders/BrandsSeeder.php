<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            [
                'name' => 'PROCAFES',
                'description' => 'Marca oficial de PROCAFES.',
            ],

        ];

        foreach ($brands as $brand) {

            DB::table('brands')->updateOrInsert(

                [
                    'slug' => Str::slug($brand['name']),
                ],

                [
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'description' => $brand['description'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]

            );

        }
    }
}