<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;

class Coupon_Model extends Authenticatable
{
    public function couponListDataEvent($publicCi = null)
    {
        DB::enableQueryLog();
        $data =
            DB::table('CRM2_COUPON_CUSTOMER AS CCC')
                ->leftJoin('CRM2_COUPON AS CP', 'CP.CP_SQ', '=', 'CCC.CP_SQ')
                ->join('CRM2_CUSTOMER AS CC', 'CC.C_NUMBER', '=', 'CCC.C_NUMBER')
                ->where("CCC.CPC_IS_USE", 0)
                ->where("CC.C_PUBLIC_CI", $publicCi)
                //->whereIn("CP.CP_DISCOUNT_TYPE", ['주문금액', '이벤트할인'])
                //->orderBy('CB_ORD', 'ASC')
                ->get();
        Log::error(DB::getQueryLog());

        return $data;
    }

    public function couponListDataAll($publicCi = null)
    {
        $data =
            DB::table('CRM2_COUPON_CUSTOMER AS CCC')
                ->join('CRM2_CUSTOMER AS CC', 'CC.C_NUMBER', '=', 'CCC.C_NUMBER')
                ->where("CCC.CPC_IS_USE", 0)
                ->where("CC.C_PUBLIC_CI", $publicCi)
                //->whereIn("CCC.CPC_DISCOUNT_TYPE", ['주문금액', ''])
                //->orderBy('CB_ORD', 'ASC')
                ->get();
//        Log::error(DB::getQueryLog());

        return $data;
    }

    public function couponListDataNormal($publicCi = null)
    {
//        DB::enableQueryLog();
        $data =
            DB::table('CRM2_COUPON_CUSTOMER AS CCC')
                ->join('CRM2_CUSTOMER AS CC', 'CC.C_NUMBER', '=', 'CCC.C_NUMBER')
                ->where("CCC.CPC_IS_USE", 0)
                ->where("CC.C_PUBLIC_CI", $publicCi)
                ->whereIn("CCC.CPC_DISCOUNT_TYPE", ['주문금액', ''])
                //->orderBy('CB_ORD', 'ASC')
                ->get();
//        Log::error(DB::getQueryLog());

        return $data;
    }






}
?>
