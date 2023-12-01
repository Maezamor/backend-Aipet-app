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
        Schema::create('selters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('picture')->nullable(false);
            $table->string('address')->nullable(false);
            $table->text('description')->nullable(false);
            $table->string('sosial_media_1')->nullable();
            $table->string('sosial_media_2')->nullable();
            $table->string('sosial_media_3')->nullable();
            $table->string('phone')->nullable(false);
            $table->string('city')->nullable(false);
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
        Schema::dropIfExists('selters');
    }
};
