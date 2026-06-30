<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::create('alertas_stock', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('nivel_alerta_id')
                ->constrained('niveles_alerta_stock')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información de la alerta
            |--------------------------------------------------------------------------
            */

            $table->integer('stock_detectado');

            $table->text('mensaje');

            /*
            |--------------------------------------------------------------------------
            | Estado del envío
            |--------------------------------------------------------------------------
            */

            $table->boolean('enviado_correo')->default(false);

            $table->boolean('enviado_app')->default(false);

            $table->timestamp('fecha_envio')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_stock');
    }
};