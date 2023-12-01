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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('picture')->nullable();
            $table->string('name')->nullable(false);
            $table->string('sosial_media_1')->nullable();
            $table->string('sosial_media_2')->nullable();
            $table->string('sosial_media_3')->nullable();
            $table->string('description')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->double('lon')->nullable(false);
            $table->double('let')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
