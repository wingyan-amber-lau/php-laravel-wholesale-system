<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_flows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->dateTime('flow_date');
            $table->string('in_out');
            $table->string('company_code');
            $table->string('receipt_code');
            $table->integer('amount');
            $table->decimal('unit_cost',8,2);
            $table->decimal('unit_price',8,2);
            $table->decimal('discount',8,2);
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
        Schema::dropIfExists('product_flows');
    }
}
