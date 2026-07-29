<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_empresa', function (Blueprint $table) {

            $table->string('whatsapp', 30)
                  ->nullable()
                  ->after('tiktok');

        });
    }

    public function down(): void
    {
        Schema::table('configuracion_empresa', function (Blueprint $table) {

            $table->dropColumn('whatsapp');

        });
    }
};