<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponModel extends Model
{
    protected $table = 'CRM2_COUPON as COU';
    public $timestamps = false;

    protected $connection = 'mysql';

}
