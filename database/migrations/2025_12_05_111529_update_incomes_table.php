<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('id');
            $table->unique(['account_id', 'date'], 'orders_account_date_unique');
            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropUnique('orders_account_date_unique');
            $table->dropColumn('account_id');
        });
    }
};
