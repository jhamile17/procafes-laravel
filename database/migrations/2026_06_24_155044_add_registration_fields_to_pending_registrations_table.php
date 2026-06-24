<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pending_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('pending_registrations', 'name')) {
                $table->string('name');
            }

            if (! Schema::hasColumn('pending_registrations', 'email')) {
                $table->string('email')->unique();
            }

            if (! Schema::hasColumn('pending_registrations', 'phone')) {
                $table->string('phone', 20)->nullable();
            }

            if (! Schema::hasColumn('pending_registrations', 'password')) {
                $table->string('password');
            }

            if (! Schema::hasColumn('pending_registrations', 'token')) {
                $table->string('token', 64)->unique();
            }

            if (! Schema::hasColumn('pending_registrations', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pending_registrations', function (Blueprint $table) {
            $columns = [
                'name',
                'email',
                'phone',
                'password',
                'token',
                'expires_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('pending_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};