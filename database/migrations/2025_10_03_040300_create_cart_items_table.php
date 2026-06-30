<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            $table->foreignId('cart_id')
                ->constrained('carts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity')->default(1);

            // Precio al momento de agregar al carrito
            $table->decimal('price', 10, 2);

            $table->decimal('sub_total', 10, 2);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Evita productos repetidos
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'cart_id',
                'product_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};