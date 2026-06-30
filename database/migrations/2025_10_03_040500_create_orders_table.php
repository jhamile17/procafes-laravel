<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            $table->string('numero_pedido',30)->unique();

            $table->decimal('total_price',10,2);

            $table->string('delivery_type',30);

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};