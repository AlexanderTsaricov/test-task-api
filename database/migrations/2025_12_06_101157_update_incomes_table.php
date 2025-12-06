<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // 1. убрать FK
            $table->dropForeign('incomes_account_id_foreign');
            // 2. убрать старый уникальный индекс
            $table->dropUnique('incomes_account_date_unique');
        });

        Schema::table('incomes', function (Blueprint $table) {
            // 3. создать обычный индекс для FK
            $table->index('account_id', 'incomes_account_id_index');

            // 4. новый уникальный индекс
            $table->unique(
                ['account_id', 'income_id', 'nm_id', 'barcode'],
                'incomes_account_income_nm_barcode_unique'
            );

            // 5. вернуть FK
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign('incomes_account_id_foreign');
            $table->dropUnique('incomes_account_income_nm_barcode_unique');
            $table->dropIndex('incomes_account_id_index');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->unique(['account_id', 'date'], 'incomes_account_date_unique');
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
        });
    }
};
