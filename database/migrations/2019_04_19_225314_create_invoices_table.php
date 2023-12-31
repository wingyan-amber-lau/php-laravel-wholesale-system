<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_code');
            $table->dateTime('invoice_date');
            $table->date('delivery_date');
            $table->string('customer_code');
            $table->string('customer_name');
            $table->string('payment_method');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('fax');
            $table->string('address');
            $table->string('district_code');
            $table->decimal('total_amount', 8, 2);
            $table->string('remarks');
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
        Schema::dropIfExists('invoices');
    }
}
