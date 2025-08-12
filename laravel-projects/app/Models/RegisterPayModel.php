<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterPayModel extends Model
{
    protected $table = 'CRM2_REGISTER_PAY as RP';
    public $timestamps = false;

    protected $connection = 'mysql';

}
