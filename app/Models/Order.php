<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'order_no',
        'invoice_code',
        'category_name',
        'product_code',
        'product_name',
        'amount',
        'unit',
        'unit_price',
        'unit_cost',
        'discount',
        'discount_once',
        'total_price',
        'total_cost',
        'packing',
        'invoice_id',
        'updated_at'
    ];
}
