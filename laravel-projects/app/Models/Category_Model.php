<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;

class Category_Model extends Authenticatable
{
    protected $table = 'CRM2_CONFIG_BASE';
    public $timestamps = false;

    protected $connection = 'mysql';
    public function category1depthData()
    {
        $data =
            DB::table('CRM2_CONFIG_BASE')
                ->select('CB_SQ as uid', 'CB_NM as name')
                ->where("CB_GROUP1", "상품분류")
                ->where("CB_IS_USED", '1')
                ->where("CB_GROUP2", '')
                ->orderBy('CB_ORD', 'ASC')
                ->get();

        return $data;
    }

    public function category2depthData($c1seq)
    {
        $data =
            DB::select("
                    SELECT
                       aaa.event_code
                       , aaa.event_name
                       , aaa.product_code
                       , aaa.low_price
                       , bbb.TI_NM
                       , bbb.TI_PRICE
                       , bbb.TC_CONTENT
                       , bbb.TC_DESCRIPTION_JSON
                       , bbb.TC_HASHTAG

                    FROM (
                       SELECT
                        CTS.TS_CATEGORY2_SQ AS event_code
                            , CTS.TS_CATEGORY2_NM AS 'event_name'
                            , CTS.TS_CODE AS 'product_code'
                            , MIN(CTS.TS_PRICE) AS 'low_price'
                        FROM
                            CRM2_TREATMENT_SHOP AS CTS
                        WHERE
                            CTS.TS_CATEGORY1_SQ = $c1seq
                            AND CTS.TS_IS_ORI_DISPLAY = 1
                            AND CTS.TS_IS = 1
                        GROUP BY
                            CTS.TS_CATEGORY2_SQ
                    ) AS aaa
                    LEFT JOIN (
                       SELECT
                           CTC.TC_CONTENT
                           , CTC.TC_DESCRIPTION_JSON
                           , CTC.TC_HASHTAG
                           , CTI.TI_CONTENT_KO_SQ
                           , CTI.TI_NM
                           , CTI.TI_PRICE
                           , CTSD.TS_CODE
                           , CTSD.TSD_TI_SQ
                       FROM
                           CRM2_TREATMENT_ITEM AS CTI
                           LEFT JOIN CRM2_TREATMENT_SHOP_DETAIL AS CTSD ON CTI.TI_SQ = CTSD.TSD_TI_SQ
                           LEFT JOIN CRM2_TREATMENT_CONTENT AS CTC ON CTI.TI_CONTENT_KO_SQ = CTC.TC_SQ
                    ) AS bbb on aaa.product_code = bbb.TS_CODE
                ");
//                ->get();

        return $data;
    }

    public function categoryEventdepthData()
    {
        $data =
            DB::select("
                    SELECT
                        aaa.event_code
                        , aaa.event_name
                        , aaa.product_code
                        , aaa.low_price
                        , bbb.TI_NM
                        , bbb.TI_PRICE
                        , bbb.TC_CONTENT
                        , bbb.TC_DESCRIPTION_JSON
                        , bbb.TC_HASHTAG

                    FROM (
                        SELECT
                            CTSE.TSE_CODE AS 'event_code'
                            , CTSE.TSE_SUBJECT_KO AS 'event_name'
                            , CTSED.TS_CODE AS 'product_code'
                            , CTSE.TSE_SUBJECT_KO AS 'name'
                            , CTSED.LOW_PRICE AS 'low_price'
                        --	, CTC.TC_CONTENT AS 'TC_CONTENT'
                        FROM
                            CRM2_TREATMENT_SHOP_EVENT CTSE
                            LEFT JOIN (
                                SELECT
                                    CTSED.TSE_CODE
                                    -- , CTSED.TS_CODE
                                    , (SELECT S_CTSED.TS_CODE FROM CRM2_TREATMENT_SHOP_EVENT_DETAIL AS S_CTSED WHERE S_CTSED.TSE_CODE = CTSED.TSE_CODE ORDER BY S_CTSED.TSED_ORD ASC LIMIT 1)  AS TS_CODE
                                    , MIN(CTSED.TSED_PRICE) AS LOW_PRICE
                                FROM
                                    CRM2_TREATMENT_SHOP_EVENT_DETAIL as CTSED
                                    LEFT JOIN CRM2_TREATMENT_SHOP AS CTS ON CTSED.TS_CODE = CTS.TS_CODE
                                WHERE
                                    CTS.TS_IS = '1'
                                    AND CTS.TS_IS_ORI_DISPLAY = 1
                                GROUP BY
                                    CTSED.TSE_CODE
                                ORDER BY
                                    CTSED.TSED_PRICE ASC
                            ) AS CTSED ON CTSED.TSE_CODE = CTSE.TSE_CODE

                        WHERE
                            CTSE.TSE_IS = '1'
                            AND NOW() >= CTSE.TSE_START_DATETIME
                            AND NOW() < CTSE.TSE_END_DATETIME
                            AND CTSE.TSE_DELETE_DATETIME is null
                        ORDER BY
                            CTSE.TSE_ORD ASC
                            , CTSE.TSE_SQ ASC
                    ) AS aaa
                    LEFT JOIN (
                        SELECT
                            CTC.TC_CONTENT
                            , CTC.TC_DESCRIPTION_JSON
                            , CTC.TC_HASHTAG
                            , CTI.TI_CONTENT_KO_SQ
                            , CTI.TI_NM
                            , CTI.TI_PRICE
                            , CTSD.TS_CODE
                            , CTSD.TSD_TI_SQ
                        FROM
                            CRM2_TREATMENT_ITEM AS CTI
                            LEFT JOIN CRM2_TREATMENT_SHOP_DETAIL AS CTSD ON CTI.TI_SQ = CTSD.TSD_TI_SQ
                            LEFT JOIN CRM2_TREATMENT_CONTENT AS CTC ON CTI.TI_CONTENT_KO_SQ = CTC.TC_SQ
                    ) AS bbb on aaa.product_code = bbb.TS_CODE
                ");
//                ->get();

        return $data;
    }


    public function categoryEventdepthProductData($code = null)
    {
//        DB::enableQueryLog();
        $data =
            DB::table('CRM2_TREATMENT_SHOP_EVENT AS CTSE')
                ->select('CTSED.TSE_CODE', 'CTSED.TS_CODE', 'CTSED.TSED_DISCOUNT', 'CTSED.TSED_PRICE', 'CTS.TS_NM', 'CTS.TS_PRICE')
                ->leftJoin('CRM2_TREATMENT_SHOP_EVENT_DETAIL AS CTSED', 'CTSE.TSE_CODE', '=', 'CTSED.TSE_CODE')
                ->join('CRM2_TREATMENT_SHOP AS CTS', 'CTS.TS_CODE', '=', 'CTSED.TS_CODE')
                ->where("CTSE.TSE_IS", 1)
                ->where("CTS.TS_IS", 1)
                ->where("CTS.TS_IS_ORI_DISPLAY", 1)
                ->where("CTSE.TSE_CODE", $code)
                //->orderBy('CB_ORD', 'ASC')
                ->get();
//        Log::error(DB::getQueryLog());

        return $data;
    }

    public function categorySubdepthProductData($cate1, $cate2)
    {
        $data =
            DB::select("
                    SELECT AA.* FROM (
                        SELECT
                            '' AS TSE_CODE
                            , CTS.TS_CODE
                            , '' AS TSED_DISCOUNT
                            , '' AS TSED_PRICE
                            , CTS.TS_NM
                            , CTS.TS_PRICE
                        FROM
                            CRM2_TREATMENT_SHOP_CATEGORY AS CTSC
                            LEFT JOIN CRM2_TREATMENT_SHOP AS CTS ON CTS.TS_CODE = CTSC.TS_CODE
                        WHERE
                            CTSC.TSC_CATEGORY1_SQ = $cate1
                            AND CTSC.TSC_CATEGORY2_SQ = $cate2
                            AND CTS.TS_IS = 1
                            AND CTS.TS_IS_ORI_DISPLAY = 1
                    UNION ALL
                        SELECT
                            `CTSED`.`TSE_CODE`
                            , `CTSED`.`TS_CODE`
                            , `CTSED`.`TSED_DISCOUNT`
                            , `CTSED`.`TSED_PRICE`
                            , `CTS`.`TS_NM`
                            , `CTS`.`TS_PRICE`
                        FROM
                            `CRM2_TREATMENT_SHOP_EVENT` as `CTSE`
                            left join `CRM2_TREATMENT_SHOP_EVENT_DETAIL` as `CTSED` on `CTSE`.`TSE_CODE` = `CTSED`.`TSE_CODE`
                            inner join `CRM2_TREATMENT_SHOP` as `CTS` on `CTS`.`TS_CODE` = `CTSED`.`TS_CODE`
                        WHERE
                            `CTSE`.`TSE_IS` = 1
                            AND CTS.TS_IS = 1
                            AND CTS.TS_IS_ORI_DISPLAY = 1
                            AND CTSED.TS_CODE IN (
                                SELECT
                                    CTS.TS_CODE
                                FROM
                                    CRM2_TREATMENT_SHOP_CATEGORY AS CTSC
                                    LEFT JOIN CRM2_TREATMENT_SHOP AS CTS ON CTS.TS_CODE = CTSC.TS_CODE
                                WHERE
                                    CTSC.TSC_CATEGORY1_SQ = $cate1
                                    AND CTSC.TSC_CATEGORY2_SQ = $cate2
                                    AND CTS.TS_IS = 1
                                    AND CTS.TS_IS_ORI_DISPLAY = 1
                            )
                    ) AS AA
                    ORDER BY
	                    AA.TS_NM
                ");
//                ->get();

        return $data;
    }


}
?>
