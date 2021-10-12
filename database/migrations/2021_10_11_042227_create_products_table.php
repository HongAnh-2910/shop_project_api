<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code' , 255)->nullable();
            $table->string('product_title' , 255)->nullable();
            $table->string('thumbnail_product' ,255)->nullable();
            $table->string('price' ,255)->nullable();
            $table->string('price_sale' ,255)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categorys')->onDelete('cascade');
            $table->string('content' ,255)->nullable();
            $table->string('excerpts' ,255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status_product'  ,['stocking' ,'out_of_stock']);
            $table->string('quantity_product')->nullable();
            $table->enum('featured_product' , ['true' ,'false']);
            $table->enum('selling_products' , ['true' ,'false']);
            $table->enum('status' , ['public' ,'pending' , 'cancel']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
