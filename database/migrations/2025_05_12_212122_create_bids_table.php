<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            // معلومات المزايد
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('bid_value', 10, 2);
            $table->string('payment_method');
            
            // معلومات المنتج
            $table->string('product_title');
            $table->text('product_image_url');
            $table->decimal('product_price', 10, 2);
            
            // الحقول العامة
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids');
    }
};