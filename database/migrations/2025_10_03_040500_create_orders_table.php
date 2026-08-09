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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('shipping_address_id')
                ->nullable()
                ->constrained('shipping_addresses')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('estado_pedido_id')
                ->constrained('estados_pedido')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información del pedido
            |--------------------------------------------------------------------------
            */

            $table->string('numero_pedido', 30)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Tipo de entrega
            |--------------------------------------------------------------------------
            */

            $table->enum('delivery_type', [
                'pickup',
                'delivery',
            ])->default('pickup');

            /*
            |--------------------------------------------------------------------------
            | Snapshot de la dirección
            |--------------------------------------------------------------------------
            | Solo se llena cuando el pedido es DELIVERY.
            | Para RECOJO todos estos campos permanecen en NULL.
            |--------------------------------------------------------------------------
            */

            $table->string('delivery_alias', 100)
                ->nullable();

            $table->string('delivery_direccion')
                ->nullable();

            $table->string('delivery_departamento', 100)
                ->nullable();

            $table->string('delivery_provincia', 100)
                ->nullable();

            $table->string('delivery_distrito', 100)
                ->nullable();

            $table->string('delivery_referencia', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Totales
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_price', 10, 2);
            /*
            |--------------------------------------------------------------------------
            | Observaciones
            |--------------------------------------------------------------------------
            */

            $table->text('observaciones')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};