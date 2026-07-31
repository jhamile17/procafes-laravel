<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {

            // Nuevos campos
            $table->string('alias', 100)
                ->nullable()
                ->after('user_id');

            $table->string('departamento', 100)
                ->nullable()
                ->after('direccion');

            $table->string('provincia', 100)
                ->nullable()
                ->after('departamento');

            $table->string('distrito', 100)
                ->nullable()
                ->after('provincia');

            $table->string('referencia', 255)
                ->nullable()
                ->after('distrito');

            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('referencia');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');

        });

        /*
        |--------------------------------------------------------------------------
        | Migrar datos antiguos
        |--------------------------------------------------------------------------
        */

        DB::statement("
            UPDATE shipping_addresses
            SET
                departamento = state,
                provincia = city,
                distrito = city
        ");

        /*
        |--------------------------------------------------------------------------
        | Eliminar columnas antiguas
        |--------------------------------------------------------------------------
        */

        Schema::table('shipping_addresses', function (Blueprint $table) {

            $table->dropColumn([
                'city',
                'state',
                'zip_code',
                'country',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('zip_code')->nullable();

            $table->string('country')->nullable();

        });

        Schema::table('shipping_addresses', function (Blueprint $table) {

            $table->dropColumn([
                'alias',
                'departamento',
                'provincia',
                'distrito',
                'referencia',
                'latitude',
                'longitude',
            ]);

        });
    }
};