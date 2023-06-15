<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'contact_person',
        'phone',
        'phone_2',
        'fax',
        'address',
        'email'
    ];
}
