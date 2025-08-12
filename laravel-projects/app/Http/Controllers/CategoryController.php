<?php

namespace App\Http\Controllers;

use App\Models\ConfigBaseModel;
use App\Models\CustomerModel;
use App\Models\TreatmentItemModel;
use App\Models\TreatmentShopEventModel;
use App\Models\TreatmentShopModel;
use Illuminate\Support\Facades\Log;
use App\Models\Category_Model;
use App\Models\Coupon_Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function getCategory($connection, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $model = new ConfigBaseModel();
        $table = $this->changeConnection($connection, $model);
        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);

        $cPublicCi = $request->input('cPublicCi', '');
        $memberCheck = !empty($cPublicCi);

        $result = [];
        $eventPay = null;

        if ($memberCheck) {
            //1회 체험가 이벤트 상품 결제 여부 확인
            $eventPay = $cTable->select('RPTS.*')->leftjoin('CRM2_REGISTER_PAY as RP', 'RP.C_NUMBER', 'CUS.C_NUMBER')
                ->join('CRM2_REGISTER_PAY_TREATMENT_SHOP as RPTS', 'RP.RTP_NUMBER', 'RPTS.RTP_NUMBER')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RPTS.RTPTS_STATE', config('code.RTPTS_STATE')['02']);
        }

        //이벤트 상품 정보 조회
        $event = $eventTable->select(
            DB::raw('"' . $eventContent . '" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.' . $tseContent . ' as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_NM as mg_name'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'TS.TS_CONTENT as ts_content'
            ,'TC.TC_CONTENT as ts_comment'
//            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('GROUP_CONCAT(TC.TC_DESCRIPTION_JSON) as ts_desc')
            ,DB::raw('GROUP_CONCAT(TC.TC_CAUTION_JSON) as ts_caution')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
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
            ->where('TSE.TSE_IS', 1)
            ->where('TSE.TSE_DELETE_DATETIME', null)
            ->where('TSE.TSE_START_DATETIME', '<=', Now())
            ->where('TSE.TSE_END_DATETIME', '>', Now())
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->where('TSE.TSE_CODE', '!=', 'EVT682EB3E313D22')
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE')
            ->get();

        foreach ($event->groupBy('fir_cate_name') as $key => $item) {
            $result[$key] = $item->groupBy('sec_cate_name');
        }

        //일반 카테고리에 사용되는 이벤트 상품 조회
        $product_event = $eventTable->select(
            DB::raw('"' . $eventContent . '" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.' . $tseContent . ' as sec_cate_name'
//            ,DB::raw('(select SCB.CB_NM from CRM2_CONFIG_BASE as SCB where SCB.CB_SQ = TSC.TSC_CATEGORY1_SQ) as fir_cate_name_group')
//            ,DB::raw('(select SCB2.CB_NM from CRM2_CONFIG_BASE as SCB2 where SCB2.CB_SQ = TSC.TSC_CATEGORY2_SQ) as sec_cate_name_group')
//            ,'TS.TS_CATEGORY1_NM as fir_cate_name_group'
//            ,'TS.TS_CATEGORY2_NM as sec_cate_name_group'
            ,'CB.' . $cateContent . ' as fir_cate_name_group'
            ,'CB2.' . $cateContent . ' as sec_cate_name_group'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_NM as mg_name'
            ,'TS.TS_TYPE as ts_type'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,'TS.TS_CONTENT as ts_content'
            ,'TC.TC_CONTENT as ts_comment'
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('GROUP_CONCAT(TC.TC_DESCRIPTION_JSON) as ts_desc')
            ,DB::raw('GROUP_CONCAT(TC.TC_CAUTION_JSON) as ts_caution')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
        )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TSED.TS_CODE', 'TSC.TS_CODE')
            ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TSC.TSC_CATEGORY1_SQ')
            ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TSC.TSC_CATEGORY2_SQ')
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
            ->when(!isset($eventPay), function ($q) {
                $q->addSelect(DB::raw('null as pay'));
            })
            ->where('TSE.TSE_IS', 1)
            ->where('TSE.TSE_DELETE_DATETIME', null)
            ->where('TSE.TSE_START_DATETIME', '<=', Now())
            ->where('TSE.TSE_END_DATETIME', '>', Now())
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSC.TSC_CATEGORY1_SQ')
            ->groupBy('TSC.TSC_CATEGORY2_SQ')
            ->groupBy('TSC.TS_CODE');

        //일반 상품 정보 조회
        $product = $table->select(
            'CB.' . $cateContent . ' as fir_cate_name'
            ,'CB2.CB_GROUP2 as fir_cate_code'
            ,'CB2.CB_SQ as sec_cate_code'
            ,'CB2.' . $cateContent . ' as sec_cate_name'
            //,'TSC.TSC_CATEGORY1_NM as fir_cate_name_group'
            //,'TSC.TSC_CATEGORY2_NM as sec_cate_name_group'
            //,DB::raw('(select SCB.CB_NM from CRM2_CONFIG_BASE as SCB where SCB.CB_SQ = TSC.TSC_CATEGORY1_SQ) as fir_cate_name_group')
            //,DB::raw('(select SCB2.CB_NM from CRM2_CONFIG_BASE as SCB2 where SCB2.CB_SQ = TSC.TSC_CATEGORY2_SQ) as sec_cate_name_group')
            ,'CB.' . $cateContent . ' as fir_cate_name_group'
            ,'CB2.' . $cateContent . ' as sec_cate_name_group'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_NM as mg_name'
            ,'TS.TS_TYPE as ts_type'
            ,'TS.TS_PRICE as ts_price'
            //,'TS.TS_PRICE as event_ts_price'
            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,'TS.TS_CONTENT as ts_content'
            ,'TC.TC_CONTENT as ts_comment'
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('GROUP_CONCAT(TC.TC_DESCRIPTION_JSON) as ts_desc')
            ,DB::raw('GROUP_CONCAT(TC.TC_CAUTION_JSON) as ts_caution')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
            ,DB::raw('null as pay')
        )
            ->join('CRM2_CONFIG_BASE as CB2', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'CB2.CB_SQ', 'TSC.TSC_CATEGORY2_SQ')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSC.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                $join->on('TC.TC_SQ', $tiContent)
                    ->where('TC.TC_LANGUAGE', $locale);
            })
            ->where('CB.CB_GROUP1', config('code.CB_GROUP1')['24'])
            ->where('CB.CB_GROUP2', '')
            ->where('CB.CB_IS_USED', 1)
            ->where('CB2.CB_GROUP2', '!=', '')
            ->where('CB2.CB_IS_USED', 1)
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->orderBy('TSC.TSC_CATEGORY1_SQ')
            ->orderBy('TSC.TSC_CATEGORY2_SQ')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSC.TSC_CATEGORY1_SQ')
            ->groupBy('TSC.TSC_CATEGORY2_SQ')
            ->groupBy('TSC.TS_CODE');

        //멤버십 상품 정보 조회
        $memberProduct = $table->select(
            'CB.' . $cateContent . ' as fir_cate_name'
            ,'CB2.CB_GROUP2 as fir_cate_code'
            ,'CB2.CB_SQ as sec_cate_code'
            ,'CB2.' . $cateContent . ' as sec_cate_name'
            ,'CB.' . $cateContent . ' as fir_cate_name_group'
            ,'CB2.' . $cateContent . ' as sec_cate_name_group'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_NM as mg_name'
            ,'TS.TS_TYPE as ts_type'
            ,'TS.TS_PRICE as ts_price'
            //,'TS.TS_PRICE as event_ts_price'
            ,'TS.TS_TOTAL_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,'TS.TS_CONTENT as ts_content'
//            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('null as ts_comment')
//            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as ts_hash')
            ,DB::raw('null as ts_desc')
            ,DB::raw('null as ts_caution')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
            ,DB::raw('null as pay')
        )
            ->join('CRM2_CONFIG_BASE as CB2', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'CB2.CB_SQ', 'TSC.TSC_CATEGORY2_SQ')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSC.TS_CODE')
//            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
//            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
//            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
            ->where('CB.CB_GROUP1', config('code.CB_GROUP1')['24'])
            ->where('CB.CB_GROUP2', '')
            ->where('CB.CB_IS_USED', 1)
            ->where('CB2.CB_GROUP2', '!=', '')
            ->where('CB2.CB_IS_USED', 1)
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
//            ->where('TI.TI_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->where('TS.TS_TYPE', '멤버쉽')
            ->groupBy('TSC.TSC_CATEGORY1_SQ')
            ->groupBy('TSC.TSC_CATEGORY2_SQ')
            ->groupBy('TSC.TS_CODE');

        $data = $product->unionAll($product_event)->unionAll($memberProduct)
            ->get();

        foreach ($data->sortBy('fir_ord')->groupBy('fir_cate_name_group') as $key => $item) {
            foreach ($item->sortBy('sec_ord')->groupBy('sec_cate_name_group') as $key2 => $item2) {
                $result[$key][$key2] = array_values($item2->sortBy('thi_ord')->toArray());
            }
        }

        return response()->json($result);
    }
    public function getFirstDepthCategory($connection, $group1, Request $request)
    {
        $locale = $this->setLocale($request);
        $model = new ConfigBaseModel();

        $table = $this->changeConnection($connection, $model);

        $data = $table->select(
                'CB_SQ as uid'
                ,'CB_NM as name'
            )
            ->where('CB_GROUP1', config('code.CB_GROUP1')[$group1])
            ->where('CB_GROUP2', '')
            ->where('CB_IS_USED', 1)
            ->orderBy('CB_ORD')
            ->get();

        $result = [
            0 => [
                'uid' => 'event'
                ,'name' => $this->changeEventName($locale)
            ]
        ];

        foreach ($data as $key => $value) {
            $key += 1;
            $result[$key]['uid'] = $value->uid;
            $result[$key]['name'] = $value->name;
        }

        return response()->json(['result' => $result]);
    }

    public function getSecondDepthCategory($connection, $group1, $group2, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        //이벤트 상품 조회 테이블이 다르다.
        if ($group2 == 'event') {
            $model = new TreatmentShopEventModel();
            $table = $this->changeConnection($connection, $model);

            $result = $table->select(
                'TSE.TSE_CODE'
                ,'TSE.TSE_SUBJECT_KO as TSE_SUBJECT'
                ,'TS.TS_CODE'
                ,DB::raw('MIN(TSED.TSED_PRICE) as MIN_TSED_PRICE')
                ,'TI.TI_NM'
                ,'TI.TI_PRICE'
                ,'TC.TC_CONTENT'
                ,'TC.TC_DESCRIPTION_JSON'
                ,'TC.TC_HASHTAG'
            )
                ->leftjoin('CRM2_TREATMENT_SHOP_EVENT_DETAIL AS TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'TSED.TS_CODE')
                ->leftjoin('CRM2_TREATMENT_SHOP_DETAIL AS TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
                ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'TSD.TSD_TI_SQ')
                ->leftjoin('CRM2_TREATMENT_CONTENT AS TC', 'TC.TC_SQ', $tiContent)
                ->where('TS.TS_IS', 1)
                ->where('TS.TS_IS_ORI_DISPLAY', 1)
                ->where('TSE.TSE_IS', 1)
                ->where('TSE.TSE_DELETE_DATETIME', null)
                //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                ->whereRaw('NOW() >= TSE.TSE_START_DATETIME')
                ->whereRaw('NOW() < TSE.TSE_END_DATETIME')
                ->groupBy('TSE.TSE_CODE')
                ->get();
        } else {
            $model = new ConfigBaseModel();
            $table = $this->changeConnection($connection, $model);

            $result = $table->select(
                'CB.CB_SQ'
                ,'CB.CB_NM'
                ,'TS.TS_CODE'
                ,DB::raw('MIN(TS.TS_PRICE) as MIN_TS_PRICE')
                ,'TS.TS_NM'
                ,'TSD.TSD_TI_SQ'
                ,'TI.TI_CONTENT_KO_SQ'
                ,'TI.TI_NM'
                ,'TI.TI_PRICE'
                ,'TC.TC_CONTENT'
                ,'TC.TC_DESCRIPTION_JSON'
                ,'TC.TC_HASHTAG'
            )
                ->leftjoin('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CATEGORY2_SQ', 'CB.CB_SQ')
                ->leftjoin('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
                ->leftjoin('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
                ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
                ->where('CB.CB_GROUP1', config('code.CB_GROUP1')[$group1])
                ->where('CB.CB_SQ', $group2)
                ->where('CB_IS_USED', 1)
                ->where('TS.TS_IS_ORI_DISPLAY', 1)
                ->where('TS.TS_IS', 1)
                //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                ->groupBy('TS.TS_NM')
                ->orderBy('CB.CB_ORD')
                ->get();
        }

        return response()->json(['result' => $result]);
    }
    public function category1depth()
    {
//        Log::error("asdf");
        $qureyResult = Category_Model::category1depthData();

        $dataResult = array();
        $dataResult[0]['uid'] = 'event';
        $dataResult[0]['name'] = '이벤트';
        foreach ($qureyResult as $index => $row) {
            $dataResult[$index+1]['uid'] = $row->uid;
            $dataResult[$index+1]['name'] = $row->name;
        }

        return response()->json(['result' => $dataResult]);
    }

    public function category2depth($uid = null)
    {
//        Log::error("asdf");
        $dataResult = Category_Model::category2depthData($uid);

        return response()->json(['result' => $dataResult]);
    }

    public function categoryEvent()
    {
        $dataResult = Category_Model::categoryEventdepthData();

        return response()->json(['result' => $dataResult]);
    }

    public function categoryEventProduct($code = null, $publicCi = null)
    {
//        Log::error("asdf - ".$publicCi);
        $dataResult = Category_Model::categoryEventdepthProductData($code);

        return response()->json(['result' => $dataResult]);
    }

    public function categorySubProduct($cate1 = null, $cate2 = null)
    {
        $dataResult = Category_Model::categorySubdepthProductData($cate1, $cate2);

        return response()->json(['result' => $dataResult]);
    }



}
