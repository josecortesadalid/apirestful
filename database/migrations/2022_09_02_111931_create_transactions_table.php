<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('transactions')){
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->integer('quantity')->unsigned();
                $table->bigInteger('buyer_id')->unsigned();
                $table->bigInteger('product_id')->unsigned();
                $table->timestamps();
                $table->softDeletes();
            });

            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
