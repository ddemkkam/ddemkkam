<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;

class Homepage_Model extends Authenticatable
{
    protected $connection = 'mysql_home';
//    protected $table ='your_table';

    public function getHomeBasketDataList($publicCi = null, $branch = null)
    {
//        DB::enableQueryLog();
        $data =
            DB::connection('mysql_home')->table('P_BASKET')
                ->where("B_PUBLIC_CI", $publicCi)
                ->where("B_BRANCH", $branch)
                ->groupBy('B_PRODUCT_ID')
                ->orderBy('SEQ', 'DESC')
                ->get();
//        Log::error(DB::getQueryLog());

        return $data;
    }

}

?>
