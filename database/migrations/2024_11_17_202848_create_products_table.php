<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id')->primary(); // Use the API's product ID
            $table->string('title');
            $table->text('description');
            $table->string('price');
            $table->string('selling_price');
            $table->string('currency');
            $table->string('image_url')->nullable();
            $table->string('article_code')->nullable();
            $table->integer('stock');
            $table->json('properties')->nullable();
            $table->string('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
