<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->bigInteger('nm_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // откат к обычному int
            $table->integer('nm_id')->change();
        });
    }
};
