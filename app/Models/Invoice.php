<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = [
        'delviery_date',
        'status',
        'payment_status',
        'remarks',
        'total_amount',
        'updated_at'
    ];
}
