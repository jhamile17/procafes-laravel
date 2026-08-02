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
    Schema::table('orders', function (Blueprint $table) {

        $table->dropColumn([
            'delivery_alias',
            'delivery_referencia',
            'delivery_type',
            'observaciones',
        ]);

    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {

        $table->string('delivery_alias')->nullable();

        $table->string('delivery_referencia')->nullable();

        $table->string('delivery_type');

        $table->text('observaciones')->nullable();

    });
}
};
