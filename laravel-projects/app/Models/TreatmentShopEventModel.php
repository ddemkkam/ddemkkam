<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentShopEventModel extends Model
{
    protected $table = 'CRM2_TREATMENT_SHOP_EVENT as TSE';
    public $timestamps = false;

    protected $connection = 'mysql';

}
