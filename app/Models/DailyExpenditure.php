<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyExpenditure extends Model
{
    //
    protected $fillable = [
        'item_descr',
        'cost',
        'paid_amount',
        'updated_at'
    ];

}
