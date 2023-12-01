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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->nullable(false)->unique("users_username_unique");
            $table->string('name', 100)->nullable(false);
            $table->string('address', 200)->nullable(false);
            $table->string('phone', 15)->nullable(false);
            $table->string('email', 50)->nullable(false)->unique('users_email_unique');
            $table->string('password', 100)->nullable(false);
            $table->string('token', 100)->nullable()->unique('users_token_unique');
            $table->unsignedBigInteger('role_id')->nullable(false);
            $table->boolean('email_verivication_status')->nullable();
            $table->date('email_verivicarition_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
