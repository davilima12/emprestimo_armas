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
        Schema::table('loans', function (Blueprint $table) {
            $table->dateTime('receipt_date')->nullable();
            $table->unsignedBigInteger('user_receipt_id')->nullable()->comment('ID do usuário que está recebendo o emprestimo');
            $table->foreign('user_receipt_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('receipt_date');
            $table->dropColumn('user_receipt_id');
        });
    }
};
