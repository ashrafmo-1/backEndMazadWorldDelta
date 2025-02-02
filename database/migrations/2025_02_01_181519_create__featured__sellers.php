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
        Schema::create('Featured_Sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status');
            $table->integer('startcount');
            $table->integer('countReviews');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Featured_Sellers');
    }
};
