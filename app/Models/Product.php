<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'product_name',
        'inner_packing',
        'unit',
        'unit_price',
        'unit_cost',
        'packing',
        'count_inventory',
        'remarks',
        'category_value_id'
    ];
}
