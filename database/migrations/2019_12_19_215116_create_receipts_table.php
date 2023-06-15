<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('receipt_code');
            $table->dateTime('receipt_date');
            $table->date('delivery_date');
            $table->string('supplier_code');
            $table->string('supplier_name');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('phone_2');
            $table->string('fax');
            $table->string('address');
            $table->string('email');
            $table->string('remarks');
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
        Schema::dropIfExists('receipts');
    }
}
