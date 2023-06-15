<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    //
    protected $fillable = [
        'category_code',
        'product_code',
        'category_value_code',
        'category_id',
        'product_id',
        'category_value_id'
    ];
}
