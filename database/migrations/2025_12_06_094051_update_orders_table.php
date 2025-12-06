<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('account_id', 'orders_account_id_index');
        });

        DB::statement('ALTER TABLE orders DROP INDEX orders_account_date_unique');

        Schema::table('orders', function (Blueprint $table) {
            $table->unique(['account_id', 'g_number'], 'orders_account_g_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_account_g_number_unique');
        });

        DB::statement('ALTER TABLE orders ADD UNIQUE KEY orders_account_date_unique (account_id, date)');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_account_id_index');
        });
    }
};
