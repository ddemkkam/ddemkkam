<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentContentModel extends Model
{
    protected $table = 'CRM2_TREATMENT_CONTENT as TC';
    public $timestamps = false;

    protected $connection = 'mysql';

}
