<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_code');
            $table->string('category_name');
            $table->char('for_product',1);
            $table->timestamps();
        });

        DB::table('categories')->insert(
            array(
                array(
                    'category_code' => 'PRE',
                    'category_name' => 'Preparation',
                    'for_product' => '1',
                ),
                array(
                    'category_code' => 'INV',
                    'category_name' => 'Invoice Order',
                    'for_product' => '1',
                ),
                array(
                    'category_code' => 'CAT',
                    'category_name' => 'Category',
                    'for_product' => '0',
                ),
                array(
                    'category_code' => 'PYM',
                    'category_name' => 'Payment Method',
                    'for_product' => '0',
                )
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
