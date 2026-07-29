<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
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
            | Información del producto
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity')
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | Precio del producto al momento de agregarlo al carrito
            |--------------------------------------------------------------------------
            */

            $table->decimal('unit_price', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Evitar productos duplicados en el carrito
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'cart_id',
                'product_id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Índices
            |--------------------------------------------------------------------------
            */

            $table->index('product_id');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};