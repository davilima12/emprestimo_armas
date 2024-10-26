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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_giver_id')->comment('ID do usuário que está emprestando'); //
            $table->unsignedBigInteger('user_receiver_id')->comment('ID do usuário que está recebendo'); //
            $table->timestamps();
            $table->softDeletes();

            // Chaves estrangeiras para os usuários
            $table->foreign('user_giver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
