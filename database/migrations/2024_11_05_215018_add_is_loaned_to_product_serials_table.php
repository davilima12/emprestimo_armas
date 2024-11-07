<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->boolean('is_loaned')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_serials', function (Blueprint $table) {
            $table->dropColumn('is_loaned');
        });
    }
};
