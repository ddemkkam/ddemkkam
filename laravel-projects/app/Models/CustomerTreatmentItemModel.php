<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTreatmentItemModel extends Model
{
    protected $table = 'CRM2_CUSTOMER_TREATMENT_ITEM as CTI';
    public $timestamps = false;

    protected $connection = 'mysql';

}
