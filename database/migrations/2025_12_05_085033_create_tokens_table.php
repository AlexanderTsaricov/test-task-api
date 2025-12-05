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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('api_id');
            $table->unsignedBigInteger('token_type_id');
            $table->string('token');
            $table->timestamp('expires_at')->nullable();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');
                
            $table->foreign('api_id')
                ->references('id')->on('apis')
                ->onDelete('cascade');

            $table->foreign('token_type_id')
                ->references('id')->on('token_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
