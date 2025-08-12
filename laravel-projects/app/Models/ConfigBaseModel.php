<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigBaseModel extends Model
{
    protected $table = 'CRM2_CONFIG_BASE as CB';
    public $timestamps = false;

    protected $connection = 'mysql';

}
