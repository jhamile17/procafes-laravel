<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('electronic_documents', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('billing_profile_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Documento
            |--------------------------------------------------------------------------
            */

            $table->enum('tipo', [
                'BOLETA',
                'FACTURA',
            ]);

            $table->string('serie')
                ->nullable();

            $table->string('numero')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Estado SUNAT
            |--------------------------------------------------------------------------
            */

            $table->enum('estado', [

                'PENDIENTE',

                'ENVIADO',

                'ACEPTADO',

                'RECHAZADO',

                'ANULADO',

            ])->default('PENDIENTE');

            /*
            |--------------------------------------------------------------------------
            | Archivos
            |--------------------------------------------------------------------------
            */

            $table->string('codigo_hash')
                ->nullable();

            $table->string('xml_url')
                ->nullable();

            $table->string('pdf_url')
                ->nullable();

            $table->string('cdr_url')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Respuesta SUNAT
            |--------------------------------------------------------------------------
            */

            $table->text('sunat_response')
                ->nullable();

            $table->text('observaciones')
                ->nullable();

            $table->timestamp('fecha_emision')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electronic_documents');
    }
};