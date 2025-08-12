<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveTreatmentShopModel extends Model
{
    protected $table = 'CRM2_RESERVE_TREATMENT_SHOP AS RTS';
    public $timestamps = false;

    protected $connection = 'mysql';

}
