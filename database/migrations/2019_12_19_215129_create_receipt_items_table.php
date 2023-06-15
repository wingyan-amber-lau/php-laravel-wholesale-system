<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_no');
            $table->string('receipt_code');
            $table->string('category_name');
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('amount');
            $table->string('unit');
            $table->decimal('unit_cost',8,2);
            $table->string('packing');
            $table->integer('receipt_id');
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
        Schema::dropIfExists('receipt_items');
    }
}
