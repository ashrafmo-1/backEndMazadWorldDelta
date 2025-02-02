<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->json('best_auctions')->nullable();
            $table->json('categories')->nullable();
            $table->json('navbar_links')->nullable();
            $table->json('contact_numbers')->nullable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }    
};
