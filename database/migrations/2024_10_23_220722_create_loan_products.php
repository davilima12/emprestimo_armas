<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('product_serial_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->integer('magazines')->default(0);
            $table->integer('ammunition')->default(0);
            $table->boolean('returned')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('product_serial_id')->references('id')->on('product_serials')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
