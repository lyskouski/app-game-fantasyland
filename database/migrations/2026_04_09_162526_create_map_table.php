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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id')->index();
            $table->integer('place_id')->index();
            $table->integer('z')->index();
            $table->integer('x');
            $table->integer('y');
            $table->integer('type');
            $table->timestamps();
            $table->longText('loc');
            $table->longText('info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map');
    }
};
