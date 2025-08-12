<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;

class Holiday_Model extends Authenticatable
{
    public function getHolidayData($branch = null)
    {
//        DB::enableQueryLog();
        $data =
            DB::table('CRM2_CONFIG_HOLIDAY')
                ->where("CH_IS", 1)
                ->where("CH_IS_RESERVATION", 0)
                ->get();
//        Log::error(DB::getQueryLog());

        return $data;
    }

}

?>
