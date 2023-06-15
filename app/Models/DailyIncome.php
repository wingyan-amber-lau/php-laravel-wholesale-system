<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyIncome extends Model
{
    //
    protected $fillable = [
        'item_descr',
        'pending_amount',
        'received_amount',
        'updated_at'
    ];
}
