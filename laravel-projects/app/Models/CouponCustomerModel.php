<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCustomerModel extends Model
{
    protected $table = 'CRM2_COUPON_CUSTOMER as CC';
    public $timestamps = false;

    protected $connection = 'mysql';

}
