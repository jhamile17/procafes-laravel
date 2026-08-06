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
                ->constrained('shipping_addresses')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

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
            | Snapshot de la dirección de entrega
            |--------------------------------------------------------------------------
            | Se guarda una copia de la dirección utilizada en el momento de la
            | compra para conservar el historial, aunque el cliente modifique
            | posteriormente sus direcciones guardadas.
            |--------------------------------------------------------------------------
            */

            $table->string('delivery_alias', 100)
                ->nullable();

            $table->string('delivery_direccion');

            $table->string('delivery_departamento', 100);

            $table->string('delivery_provincia', 100);

            $table->string('delivery_distrito', 100);

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
            | Tipo de entrega
            |--------------------------------------------------------------------------
            */

            $table->string('delivery_type', 30);
            $table->string('tipo_comprobante', 20);

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