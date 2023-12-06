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
        Schema::create('onboardings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('group')->nullable();
            $table->string('age')->nullable();
            $table->string('sterlisasi')->nullable();
            $table->string('rescue_story_1')->nullable();
            $table->string('rescue_story_2')->nullable();
            $table->string('rescue_story_3')->nullable();
            $table->string('request_story')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboardings');
    }
};
