<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table = 'CRM2_CUSTOMER as CUS';
    public $timestamps = false;

    protected $connection = 'mysql';

}
