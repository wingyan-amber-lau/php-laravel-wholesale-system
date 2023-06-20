<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('district_name')->after('district_code');
            // $table->integer('car_no')->after('district_name');
            // $table->integer('order_in_car')->after('car_no');
            $table->string('payment_status')->after('remarks')->default('NONE');
            $table->string('status')->after('payment_status')->default('NONE');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'car_no')) {
                $table->dropColumn(['car_no']);
            }
            if (Schema::hasColumn('invoices', 'order_in_car')) {
                $table->dropColumn(['order_in_car']);
            }
            if (Schema::hasColumn('invoices', 'payment_status')) {
                $table->dropColumn(['payment_status']);
            }
            if (Schema::hasColumn('invoices', 'status')) {
                $table->dropColumn(['status']);
            }
        });
    }
}
