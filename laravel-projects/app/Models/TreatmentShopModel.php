<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentShopModel extends Model
{
    protected $table = 'CRM2_TREATMENT_SHOP as TS';
    public $timestamps = false;

    protected $connection = 'mysql';

}
