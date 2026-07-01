<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            // Rol
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Nombre completo (compatibilidad con Laravel)
            $table->string('name');

            // Datos personales
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);

            // Documento
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 20)->unique();

            // Correo
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // Contraseña
            $table->string('password')->nullable();

            // Login social
            $table->string('provider')->default('local');
            $table->string('provider_id')->nullable()->unique();

            // Contacto
            $table->string('celular', 20)->nullable();
            $table->text('direccion')->nullable();

            // Estado
            $table->boolean('estado')->default(false);

            // Último acceso
            $table->timestamp('ultimo_acceso')->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};