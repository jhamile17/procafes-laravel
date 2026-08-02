<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {

            if (! Schema::hasColumn('shipping_addresses', 'alias')) {
                $table->string('alias', 100)->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('shipping_addresses', 'departamento')) {
                $table->string('departamento', 100)->nullable()->after('direccion');
            }

            if (! Schema::hasColumn('shipping_addresses', 'provincia')) {
                $table->string('provincia', 100)->nullable()->after('departamento');
            }

            if (! Schema::hasColumn('shipping_addresses', 'distrito')) {
                $table->string('distrito', 100)->nullable()->after('provincia');
            }

            if (! Schema::hasColumn('shipping_addresses', 'referencia')) {
                $table->string('referencia', 255)->nullable()->after('distrito');
            }

            if (! Schema::hasColumn('shipping_addresses', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('referencia');
            }

            if (! Schema::hasColumn('shipping_addresses', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

        });

        if (
            Schema::hasColumn('shipping_addresses', 'state') &&
            Schema::hasColumn('shipping_addresses', 'city')
        ) {

            DB::statement("
                UPDATE shipping_addresses
                SET
                    departamento = state,
                    provincia = city,
                    distrito = city
            ");

        }

        Schema::table('shipping_addresses', function (Blueprint $table) {

            foreach (['city', 'state', 'zip_code', 'country'] as $column) {

                if (Schema::hasColumn('shipping_addresses', $column)) {
                    $table->dropColumn($column);
                }

            }

        });
    }

    public function down(): void
    {
        //
    }
};