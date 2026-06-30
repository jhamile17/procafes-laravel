<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('payment_method_id')
                ->constrained('payment_methods')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('estado_pago_id')
                ->constrained('estados_pago')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información del pago
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 10, 2);

            // ID devuelto por la pasarela de pago
            $table->string('transaction_id')->nullable()->unique();

            // Número de operación visible para el usuario (Yape, Plin, transferencia, etc.)
            $table->string('reference')->nullable();

            // Respuesta completa de la pasarela
            $table->json('transaction_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};