<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_account_id_foreign');
            $table->dropUnique('sales_account_date_unique');
            $table->unique(['account_id', 'sale_id'], 'sales_account_sale_id_unique');
            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_account_id_foreign');
            $table->dropUnique('sales_account_sale_id_unique');
            $table->unique(['account_id', 'date'], 'sales_account_date_unique');
            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('cascade');
        });
    }
};
