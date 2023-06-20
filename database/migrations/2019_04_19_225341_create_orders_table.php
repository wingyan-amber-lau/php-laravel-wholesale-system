<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_no');
            $table->string('invoice_code');
            $table->string('category_name');
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('amount');
            $table->string('unit');
            $table->decimal('unit_cost',8,2)->nullable();
            $table->decimal('unit_price',8,2);
            $table->decimal('discount',8,2);
            $table->tinyInteger('discount_once');
            $table->decimal('total_cost',8,2);
            $table->decimal('total_price',8,2);
            $table->string('packing');
            $table->integer('invoice_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
