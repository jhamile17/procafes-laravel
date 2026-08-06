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

            // Monto total pagado
            $table->decimal('amount', 10, 2);

            // Identificador devuelto por la pasarela
            $table->string('transaction_id')
                ->nullable()
                ->unique();

            // Referencia interna del sistema
            $table->string('reference')
                ->unique();

            // Respuesta completa de la pasarela
            $table->json('transaction_data')
                ->nullable();

            $table->timestamps();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Revertir migración
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};