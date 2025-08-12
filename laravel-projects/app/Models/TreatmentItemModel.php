<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentItemModel extends Model
{
    protected $table = 'CRM2_TREATMENT_ITEM as TI';
    public $timestamps = false;

    protected $connection = 'mysql';

}
