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
        Schema::create('shipping_addresses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Información de la dirección
            |--------------------------------------------------------------------------
            */

            $table->string('alias', 100)
                ->nullable()
                ->comment('Casa, Trabajo, Oficina, etc.');

            $table->string('direccion');

            $table->string('departamento', 100);

            $table->string('provincia', 100);

            $table->string('distrito', 100);

            $table->string('referencia', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Coordenadas (LocationIQ)
            |--------------------------------------------------------------------------
            */

            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->comment('Latitud obtenida desde LocationIQ');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->comment('Longitud obtenida desde LocationIQ');

            /*
            |--------------------------------------------------------------------------
            | Configuración
            |--------------------------------------------------------------------------
            */

            $table->boolean('es_principal')
                ->default(false);

            $table->timestamps();

        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};