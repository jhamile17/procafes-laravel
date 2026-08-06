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
        Schema::create('comprobantes', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Pedido
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Estado
            |--------------------------------------------------------------------------
            */

            $table->foreignId('estado_comprobante_id')
                ->constrained('estados_comprobante')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Tipo de comprobante
            |--------------------------------------------------------------------------
            */

            $table->string('tipo_comprobante', 20);
            // BOLETA | FACTURA

            /*
            |--------------------------------------------------------------------------
            | Documento del receptor
            |--------------------------------------------------------------------------
            */

            $table->string('tipo_documento', 20);
            // DNI | RUC

            $table->string('numero_documento', 20);

            /*
            |--------------------------------------------------------------------------
            | Datos del receptor
            |--------------------------------------------------------------------------
            */

            // Solo para boleta
            $table->string('nombre')
                ->nullable();

            // Solo para factura
            $table->string('razon_social')
                ->nullable();

            $table->string('direccion_fiscal');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Índices
            |--------------------------------------------------------------------------
            */

            $table->unique('order_id');

            $table->index('tipo_comprobante');

            $table->index('numero_documento');

        });
    }

    /**
     * Revertir migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};