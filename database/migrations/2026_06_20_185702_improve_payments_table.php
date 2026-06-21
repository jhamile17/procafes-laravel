<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE payments
            MODIFY payment_method ENUM(
                'mercadopago',
                'paypal',
                'stripe',
                'bank_transfer',
                'cash'
            ) NOT NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY transaction_json JSON NULL
        ");

        Schema::table('payments', function ($table) {
            $table->unique('transaction_id', 'payments_transaction_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function ($table) {
            $table->dropUnique('payments_transaction_id_unique');
        });

        DB::statement("
            ALTER TABLE payments
            MODIFY transaction_json VARCHAR(255) NULL
        ");

        DB::statement("
            ALTER TABLE payments
            MODIFY payment_method ENUM(
                'paypal',
                'stripe',
                'bank_transfer'
            ) NOT NULL
        ");
    }
};