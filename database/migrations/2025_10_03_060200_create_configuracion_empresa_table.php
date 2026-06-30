<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_empresa', function (Blueprint $table) {

            $table->id();

            $table->string('nombre_empresa',150);

            $table->string('ruc',20)->unique();

            $table->string('correo');

            $table->string('telefono',30);

            $table->text('direccion');

            $table->string('logo')->nullable();

            $table->string('facebook')->nullable();

            $table->string('instagram')->nullable();

            $table->string('tiktok')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_empresa');
    }
};