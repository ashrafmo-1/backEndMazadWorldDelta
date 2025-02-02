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
        Schema::create('_auction__detiales', function (Blueprint $table) {
            $table->id();
            $table->string('auction_name')->nullable();
            $table->string('auction_description')->nullable();
            $table->string('start_salary')->nullable();
            $table->string('current_salary')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('photo')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('isFav')->nullable();
            $table->boolean('isPublished')->nullable();
            $table->timestamps();
        });
    }

    /**
     *  'Cars', 'old car of 1996 made by frrai ', 'photo', '150,000$', '500,000$', '1', '01d:12h:03m', '00d:0h:00m'
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_auction__detiales');
    }
};