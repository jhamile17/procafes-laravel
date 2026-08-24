<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_empresa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('configuracion_empresa_id')
                ->constrained('configuracion_empresa')
                ->cascadeOnDelete();

            $table->string('dia', 20);

            $table->time('hora_apertura')
                ->nullable();

            $table->time('hora_cierre')
                ->nullable();

            $table->boolean('activo')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'configuracion_empresa_id',
                'dia',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_empresa');
    }
};