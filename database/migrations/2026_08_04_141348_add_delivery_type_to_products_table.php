<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->enum(
                'delivery_type',
                [
                    'AMBOS',
                    'RECOJO',
                ]
            )
            ->default('AMBOS')
            ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn('delivery_type');

        });
    }
};