<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryValue extends Model
{
    protected $table = 'category_values';
    //
    protected $fillable = [
        'value_code',
        'value_name',
        'order_in_category',
        'category_id'
    ];
}
