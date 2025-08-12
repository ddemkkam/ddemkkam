<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;

class Product_Model extends Authenticatable
{
    public function getBasketProductDataList($sendDataBasket = null)
    {
//        DB::enableQueryLog();
        $data =
            DB::select("
                    SELECT AA.* FROM (
                        SELECT
                           '' AS TSE_CODE
                           , CTS.TS_CODE
                           , CTS.TS_CODE AS CODE
                           , '' AS TSED_DISCOUNT
                           , '' AS TSED_PRICE
                           , '' AS TSE_SUBJECT
                           , CTS.TS_NM
                           , CTS.TS_PRICE
                           , CTSC.TSC_CATEGORY1_SQ
                           , CTSC.TSC_CATEGORY2_SQ
                           , CTSC.TSC_CATEGORY1_NM
                           , CTSC.TSC_CATEGORY2_NM
                           , '' as TSE_START_DATETIME
                           , '' as TSE_END_DATETIME
                        FROM
                            CRM2_TREATMENT_SHOP_CATEGORY AS CTSC
                            LEFT JOIN CRM2_TREATMENT_SHOP AS CTS ON CTS.TS_CODE = CTSC.TS_CODE
                        WHERE
                            CTS.TS_CODE IN ($sendDataBasket)
                           AND CTS.TS_IS = 1
                           AND CTS.TS_IS_ORI_DISPLAY = 1

                        UNION ALL

                        SELECT
                            CTSED.TSE_CODE
                            , CTSED.TS_CODE
                            , CONCAT(CTSED.TSE_CODE, '_' , CTSED.TS_CODE) AS CODE
                            , CTSED.TSED_DISCOUNT
                            , CTSED.TSED_PRICE
                            , CTSE.TSE_SUBJECT_KO as TSE_SUBJECT
                            , CTS.TS_NM
                            , CTS.TS_PRICE
                            , CTSC.TSC_CATEGORY1_SQ
                            , CTSC.TSC_CATEGORY2_SQ
                            , CTSC.TSC_CATEGORY1_NM
                            , CTSC.TSC_CATEGORY2_NM
                            , CTSE.TSE_START_DATETIME
                            , CTSE.TSE_END_DATETIME
                        FROM
                            CRM2_TREATMENT_SHOP_EVENT_DETAIL AS CTSED
                            LEFT JOIN CRM2_TREATMENT_SHOP_EVENT AS CTSE ON CTSE.TSE_CODE = CTSED.TSE_CODE
                            LEFT JOIN CRM2_TREATMENT_SHOP AS CTS ON CTS.TS_CODE = CTSED.TS_CODE
                            LEFT JOIN CRM2_TREATMENT_SHOP_CATEGORY AS CTSC ON CTSC.TS_CODE = CTSED.TS_CODE AND CTSC.TS_CODE = CTS.TS_CODE
                        WHERE
                            CONCAT(CTSED.TSE_CODE,'_',CTSED.TS_CODE) IN ($sendDataBasket)
                            AND CTSE.TSE_IS = 1
                            AND CTS.TS_IS = 1
                            AND CTS.TS_IS_ORI_DISPLAY = 1
                    ) AS AA
                    GROUP BY
	                    AA.CODE
                    ;
                ");
//                ->get();
//        Log::error(DB::getQueryLog());
        return $data;
    }

}

?>
