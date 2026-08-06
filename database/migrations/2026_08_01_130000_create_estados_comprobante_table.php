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
        Schema::create('estados_comprobante', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Información
            |--------------------------------------------------------------------------
            */

            $table->string('codigo', 30)
                ->unique();

            $table->string('nombre', 100);

            $table->string('descripcion')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Configuración
            |--------------------------------------------------------------------------
            */

            $table->boolean('estado')
                ->default(true);

            $table->timestamps();

        });
    }

    /**
     * Revertir migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_comprobante');
    }
};