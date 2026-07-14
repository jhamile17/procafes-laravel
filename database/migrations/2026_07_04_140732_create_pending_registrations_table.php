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
        Schema::create('pending_registrations', function (Blueprint $table) {

            $table->id();

            $table->string('nombres');

            $table->string('apellido_paterno');

            $table->string('apellido_materno');

            $table->string('email')->unique();

            $table->string('password');

            $table->string('telefono', 20)->nullable();

            $table->string('tipo_documento', 20);

            $table->string('numero_documento', 20);

            $table->string('token', 100)->unique();

            $table->timestamp('expires_at');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};