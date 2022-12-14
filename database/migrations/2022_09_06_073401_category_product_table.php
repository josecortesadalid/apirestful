<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('category_product')){
            Schema::create('category_product', function (Blueprint $table) {
                $table->bigInteger('category_id')->unsigned();
                $table->bigInteger('product_id')->unsigned();


            });

            
            Schema::table('category_product', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories');
                $table->foreign('product_id')->references('id')->on('products');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');
    }
}
