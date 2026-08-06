<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar migración.
     */
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {

            $table->string('codigo', 50)
                ->nullable()
                ->after('id');

        });
    }

    /**
     * Revertir migración.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {

            $table->dropColumn('codigo');

        });
    }
};