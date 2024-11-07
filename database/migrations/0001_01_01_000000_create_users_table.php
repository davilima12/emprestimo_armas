<?php

declare(strict_types=1);

use App\Features\Auth\Models\Role;
use App\Features\User\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('user_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->ulid('token')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_type');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->foreignIdFor(Role::class, 'role_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignIdFor(User::class);
        //     $table->string('email');
        //     $table->ulid('token');
        //     $table->timestamps();
        // });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_type');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
