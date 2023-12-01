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
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('age')->nullable();
            $table->text('rescue_story')->nullable(false);
            $table->string('character')->nullable();
            $table->string('picture')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->boolean('reads')->nullable()->default(false);
            $table->unsignedBigInteger("type_id")->nullable(false);
            $table->unsignedBigInteger("steril_id")->nullable(false);
            $table->unsignedBigInteger("selter_id")->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dogs');
    }
};
