<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Ejecutar migración
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        if (
            Schema::hasColumn('cart_items', 'price') &&
            ! Schema::hasColumn('cart_items', 'unit_price')
        ) {

            Schema::table('cart_items', function (Blueprint $table) {

                $table->renameColumn(
                    'price',
                    'unit_price'
                );

            });

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Revertir migración
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        if (
            Schema::hasColumn('cart_items', 'unit_price') &&
            ! Schema::hasColumn('cart_items', 'price')
        ) {

            Schema::table('cart_items', function (Blueprint $table) {

                $table->renameColumn(
                    'unit_price',
                    'price'
                );

            });

        }
    }
};