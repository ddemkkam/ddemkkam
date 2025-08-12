<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReserveModel extends Model
{
    protected $table = 'CRM2_RESERVE AS RES';
    public $timestamps = false;

    protected $connection = 'mysql';

}
