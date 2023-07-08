<?php
/*
* Author: Amber Wing Yan Lau
* Description: Web Wholesale System
* Developed in 2019-2020
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //Table Name
    protected $table = 'customers';
    //Primary Key
    public $primaryKey = 'id';
    //timestamps
    public $timestamps = true;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'contact_person',
        'phone',
        'phone_2',
        'phone_3',
        'fax',
        'address',
        'district_id',
        'delivery_order',
        'payment_method',
        'untrade',
        'remarks'
    ];
}
