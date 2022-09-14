<?php

use App\Models\Product;
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
        if(!Schema::hasTable('products')){
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description', 1000);
                $table->integer('quantity')->unsigned();
                $table->string('status')->default(Product::PRODUCTO_NO_DISPONIBLE);
                $table->string('image');
                $table->bigInteger('seller_id')->unsigned(); // unsigned para evitar cualquier inconveniente al crear la clave foránea
                // $table->foreign('seller_id')->references('id')->on('users');
                $table->timestamps();
                $table->softDeletes();
            });


            Schema::table('products', function (Blueprint $table) {
                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');;
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
        Schema::dropIfExists('products');
    }
}
