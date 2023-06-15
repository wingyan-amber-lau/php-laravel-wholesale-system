<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'district_code',
        'district_name',
        'mon',
        'car_no_mon',
        'order_in_car_mon',
        'tue',
        'car_no_tue',
        'order_in_car_tue',
        'wed',
        'car_no_wed',
        'order_in_car_wed',
        'thu',
        'car_no_thu',
        'order_in_car_thu',
        'fri',
        'car_no_fri',
        'order_in_car_fri',
        'sat',
        'car_no_sat',
        'order_in_car_sat'
    ];
}
