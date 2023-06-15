<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSellingPrice extends Model
{
    //
    protected $fillable = [
        'customer_code',
        'product_code',
        'unit_price',
        'updated_by'
    ];
}
