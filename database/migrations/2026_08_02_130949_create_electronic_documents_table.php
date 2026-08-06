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
        Schema::create('electronic_documents', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Comprobante
            |--------------------------------------------------------------------------
            */

            $table->foreignId('comprobante_id')
                ->constrained('comprobantes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Numeración SUNAT
            |--------------------------------------------------------------------------
            */

            $table->string('serie', 10);

            $table->string('numero', 20);

            /*
            |--------------------------------------------------------------------------
            | Estado SUNAT
            |--------------------------------------------------------------------------
            */

            $table->string('estado', 30)
                ->default('PENDIENTE');

            /*
            |--------------------------------------------------------------------------
            | Observaciones
            |--------------------------------------------------------------------------
            */

            $table->text('observacion')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Archivos
            |--------------------------------------------------------------------------
            */

            $table->string('pdf_url')
                ->nullable();

            $table->string('xml_url')
                ->nullable();

            $table->string('cdr_url')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Respuesta completa de NubeFact
            |--------------------------------------------------------------------------
            */

            $table->json('response')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Restricciones
            |--------------------------------------------------------------------------
            */

            $table->unique('comprobante_id');

        });
    }

    /**
     * Revertir migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('electronic_documents');
    }
};