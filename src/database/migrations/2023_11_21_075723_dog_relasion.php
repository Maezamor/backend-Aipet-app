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
        Schema::table('dogs', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('steril_id')->references('id')->on('sterlisations');
            $table->foreign('selter_id')->references('id')->on('selters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
