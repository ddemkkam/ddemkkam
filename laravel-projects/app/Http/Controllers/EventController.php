<?php

namespace App\Http\Controllers;

use App\Models\CouponModel;
use App\Models\CustomerModel;
use App\Models\CustomerTreatmentItemModel;
use App\Models\TreatmentShopEventDetailModel;
use App\Models\TreatmentShopEventModel;
use App\Models\TreatmentShopModel;
use Illuminate\Support\Facades\Log;
use App\Models\Category_Model;
use App\Models\Coupon_Model;
use App\Models\Product_Model;
use App\Models\Homepage_Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function __construct()
    {

    }

    public function getEventList($connection, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $admin = $request->input('admin', '');

        $locale = $this->setLocale($request);
        $tseContent = $this->getTSELocaleColumn($locale);

        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);

        $y = $this->changeLocaleYoil($locale);

        $query = $eventTable->select(
            'TSE.TSE_CODE as tse_code',
            'TSE.' . $tseContent . ' as tse_name',
            'TSE.TSE_START_DATETIME',
            'TSE.TSE_END_DATETIME',
            'CCI.CCI_NM as cci_name',
            'CCI.CCI_HOST as cci_host',
            'CCI.CCI_FILEPATH as cci_path',
            'CCI.CCI_FILENAME as cci_file'
        )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_CODE', 'TSC.TS_CODE')
            ->leftJoin('CRM2_CONFIG_CDN_IMAGE as CCI', 'CCI.CCI_CODE', 'TSE.TSE_IMAGE_CODE_KO')
            ->where('TSE.TSE_IS', 1)
            ->where('TSE.TSE_DELETE_DATETIME', null);

        if ($admin !== 'y') {
            $query->where('TSE.TSE_START_DATETIME', '<=', now())
                ->where('TSE.TSE_END_DATETIME', '>=', now());
        }

        $result = $query
            ->where('TS.TS_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->where('TSE.TSE_CODE', '!=', 'EVT682EB3E313D22')
            ->groupBy('TSE_CODE')
            ->orderBy('TSE.TSE_ORD')
            ->paginate(10);

        foreach($result as $item) {
            $item['start_date'] = date('m.d', strtotime($item->TSE_START_DATETIME)) . '(' . $y[date('w', strtotime($item->TSE_START_DATETIME))] . ')';
            $item['end_date'] = date('m.d', strtotime($item->TSE_END_DATETIME)) . '(' . $y[date('w', strtotime($item->TSE_END_DATETIME))] . ')';
        }

        return response()->json($result);
    }

    public function getEventDetail($connection, $tseCode, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $cpContent = $this->getCPLocaleColumn($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);

        $y = $this->changeLocaleYoil($locale);

        $cPublicCi = $request->input('cPublicCi', '');
        $memberCheck = !empty($cPublicCi);

        $eventPay = null;
        $download = null;

        if ($memberCheck) {
            //1회 체험가 이벤트 상품 결제 여부 확인
            $eventPay = $cTable->select('RPTS.*')->leftjoin('CRM2_REGISTER_PAY as RP', 'RP.C_NUMBER', 'CUS.C_NUMBER')
                ->join('CRM2_REGISTER_PAY_TREATMENT_SHOP as RPTS', 'RP.RTP_NUMBER', 'RPTS.RTP_NUMBER')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RPTS.RTPTS_STATE', config('code.RTPTS_STATE')['02']);

            //쿠폰 발행 여부 확인
            $download = $cTable
                ->select(
                    'CC.CP_SQ'
                )
                ->leftjoin('CRM2_COUPON_CUSTOMER as CC', 'CC.C_NUMBER', 'CUS.C_NUMBER')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi);
        }

        $event = $eventTable
            ->select(
                'TSE.TSE_CODE'
                ,'TSE.' . $tseContent . ' as TSE_SUBJECT'
                ,'TSE.TSE_START_DATETIME'
                ,'TSE.TSE_END_DATETIME'
                ,'TSE.TSE_IS_ALWAY'
                ,DB::raw('MAX(ROUND(((TS.TS_DETAIL_TOTAL_PRICE - TSED.TSED_PRICE)/TS.TS_DETAIL_TOTAL_PRICE) * 100, 0)) as price')
            )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->where('TSE.TSE_CODE', $tseCode)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->groupBy('TSE_CODE')
            ->first();

        $tsData = $eventTable
            ->select(
                DB::raw('"' . $eventContent . '" as fir_cate_name')
                ,DB::raw('"event" as fir_cate_code')
                ,'TSE.TSE_CODE as sec_cate_code'
                ,'TSE.' . $tseContent . ' as sec_cate_name'
                ,'TS.TS_CODE as ts_code'
                ,'TS.' . $tsContent . ' as ts_name'
                ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                ,'TSED.TSED_PRICE as event_ts_price'
                ,'TS.TS_CONTENT as ts_content'
                ,'TC.TC_CONTENT as ts_comment'
//                ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
                ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
                ,DB::raw('GROUP_CONCAT(TC.TC_DESCRIPTION_JSON) as ts_desc')
                ,DB::raw('GROUP_CONCAT(TC.TC_CAUTION_JSON) as ts_caution')
            )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_CODE', 'TSC.TS_CODE')
            ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                $join->on('TC.TC_SQ', $tiContent)
                    ->where('TC.TC_LANGUAGE', $locale);
            })
            ->when(isset($eventPay), function($q) use ($eventPay) {
                $q->addSelect('EP.RTPTS_STATE as pay')->leftjoinSub($eventPay, 'EP', function($q2) {
                    $q2->on('EP.RTPTS_EVENT_CODE', 'TSE.TSE_CODE')
                        ->on('EP.TS_CODE', 'TS.TS_CODE')
                        ->where('TSE.TSE_OPTION', config('code.TSE_OPTION')['04']);
                });
            })
            ->where('TSE.TSE_CODE', $tseCode)
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSED.TS_CODE')
            ->get();


        $cou = $eventTable
            ->select(
                'COU.CP_SQ as cp_no'
                ,'COU.CP_TYPE as cp_type'
                ,'COU.' . $cpContent . ' as cp_name'
                ,'COU.CP_DISCOUNT_MEMO as cp_dc_memo'
                ,'COU.CP_MEMO as cp_memo'
                ,'COU.CP_END_DAY as cp_end_day'
            )
            ->leftJoin('CRM2_COUPON as COU', function ($q) {
                $q->on('COU.CP_DISCOUNT_EVENT_CODE', 'TSE.TSE_CODE')
                    ->where('COU.CP_DISCOUNT_TYPE', config('code.CP_DISCOUNT_TYPE')['05']);
            })
            ->when(isset($download), function($q) use ($download) {
                $q->addSelect('DOW.CP_SQ as cp_has')
                    ->leftjoinSub($download, 'DOW', 'DOW.CP_SQ', 'COU.CP_SQ');
            })
            ->where('TSE.TSE_CODE', $tseCode)
            ->where('COU.CP_IS', 1)
            /*->where('COU.CP_TYPE', '다운로드')*/
            ->get();

        $result = [
            'name' => $event->TSE_SUBJECT
            ,'is_always' => $event->TSE_IS_ALWAY
            ,'price' => $event->price
            ,'start_date' => date('m.d', strtotime($event->TSE_START_DATETIME)) . '(' . $y[date('w', strtotime($event->TSE_START_DATETIME))] . ')'
            ,'end_date' => date('m.d', strtotime($event->TSE_END_DATETIME)) . '(' . $y[date('w', strtotime($event->TSE_END_DATETIME))] . ')'
            ,'ts_list' => $tsData
            ,'benefit' => $cou
        ];


        return response()->json($result);
    }
}

?>
