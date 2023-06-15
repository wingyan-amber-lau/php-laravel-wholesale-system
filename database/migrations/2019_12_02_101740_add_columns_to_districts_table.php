<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->integer('car_no_mon')->after('mon');
            $table->integer('order_in_car_mon')->after('car_no_mon');
            $table->integer('car_no_tue')->after('tue');
            $table->integer('order_in_car_tue')->after('car_no_tue');
            $table->integer('car_no_wed')->after('wed');
            $table->integer('order_in_car_wed')->after('car_no_wed');
            $table->integer('car_no_thu')->after('thu');
            $table->integer('order_in_car_thu')->after('car_no_thu');
            $table->integer('car_no_fri')->after('fri');
            $table->integer('order_in_car_fri')->after('car_no_fri');
            $table->integer('car_no_sat')->after('sat');
            $table->integer('order_in_car_sat')->after('car_no_sat');
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
        Schema::table('districts', function (Blueprint $table) {
            if (Schema::hasColumn('districts', 'car_no_mon')) {
                $table->dropColumn(['car_no_mon']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_mon')) {
                $table->dropColumn(['order_in_car_mon']);
                //
            }
            if (Schema::hasColumn('districts', 'car_no_tue')) {
                $table->dropColumn(['car_no_tue']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_tue')) {
                $table->dropColumn(['order_in_car_tue']);
                //
            }
            if (Schema::hasColumn('districts', 'car_no_wed')) {
                $table->dropColumn(['car_no_wed']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_wed')) {
                $table->dropColumn(['order_in_car_wed']);
                //
            }
            if (Schema::hasColumn('districts', 'car_no_thu')) {
                $table->dropColumn(['car_no_thu']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_thu')) {
                $table->dropColumn(['order_in_car_thu']);
                //
            }
            if (Schema::hasColumn('districts', 'car_no_fri')) {
                $table->dropColumn(['car_no_fri']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_fri')) {
                $table->dropColumn(['order_in_car_fri']);
                //
            }
            if (Schema::hasColumn('districts', 'car_no_sat')) {
                $table->dropColumn(['car_no_sat']);
                //
            }
            if (Schema::hasColumn('districts', 'order_in_car_sat')) {
                $table->dropColumn(['order_in_car_sat']);
                //
            }
            //
        });
    }
}
