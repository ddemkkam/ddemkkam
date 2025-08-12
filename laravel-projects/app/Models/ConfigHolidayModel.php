<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigHolidayModel extends Model
{
    protected $table = 'CRM2_CONFIG_HOLIDAY as CH';
    public $timestamps = false;

    protected $connection = 'mysql';

}
