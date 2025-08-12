<?php

namespace App\Http\Controllers;

use App\Models\ConfigBaseModel;
use App\Models\CouponModel;
use App\Models\CustomerModel;
use App\Models\CustomerTreatmentItemModel;
use App\Models\TreatmentContentModel;
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


class ProductController extends Controller
{
    public function __construct()
    {
        //parent::__construct();

        //$this->middleware('auth', ['except' => ['index', 'show']]);
    }


    public function getBasketDataList($publicCi = null, $branch = null)
    {
//        Log::error("asdf - ".$publicCi);

        /*
         * 1. 홈페이지 DB에서 장바구니 데이터 GET
         * 2. 나무 DB에서 상품 정보 검색
         */

        if (isset($publicCi)) {
            $basketData = Homepage_Model::getHomeBasketDataList($publicCi, $branch);
            //상품 키값 파싱
            foreach ($basketData as $index => $row) {
                $parseData[$index] = $row->B_PRODUCT_ID;
            }
            $sendDataBasket = implode("','", $parseData);
            $sendDataBasket = "'".$sendDataBasket."'";
//            echo $sendDataBasket; exit();

            //나무 DB에서 상품 조회
            $dataResult = Product_Model::getBasketProductDataList($sendDataBasket);
//            dd($dataResult);
        } else {
            $dataResult = array();
        }

        return response()->json(['result' => $dataResult]);
    }
    public function getProduct($connection, $cate, $cPublicCi, Request $request)
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

        $memberCheck = $cTable->where('C_PUBLIC_CI', $cPublicCi)->exists();

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
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'TS.TS_CONTENT as ts_content'
            ,'TC.TC_CONTENT as ts_comment'
            //,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
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
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->where('TSE.TSE_CODE', '!=', 'EVT682EB3E313D22')
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            ->where('TSED.TSE_CODE', $cate)
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE')
            ->get();

        //일반 카테고리에 사용되는 이벤트 상품 조회
        $product_event = $eventTable->select(
            DB::raw('"' . $eventContent . '" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.' . $tseContent . ' as sec_cate_name'
            ,'CB.' . $cateContent . ' as fir_cate_name_group'
            ,'CB2.' . $cateContent . ' as sec_cate_name_group'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
        )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_CODE', 'TSC.TS_CODE')
            ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
            ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
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
            ->where('TS.TS_CATEGORY2_SQ', $cate)
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE');

        //일반 상품 정보 조회
        $product = $table->select(
            'CB.' . $cateContent . ' as fir_cate_name'
            ,'CB2.CB_GROUP2 as fir_cate_code'
            ,'CB2.CB_SQ as sec_cate_code'
            ,'CB2.' . $cateContent . ' as sec_cate_name'
            ,'CB.' . $cateContent . ' as fir_cate_name_group'
            ,'CB2.' . $cateContent . ' as sec_cate_name_group'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_PRICE as ts_price'
            ,'TS.TS_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
        )
            ->join('CRM2_CONFIG_BASE as CB2', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CATEGORY2_SQ', 'CB2.CB_SQ')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_CODE', 'TSC.TS_CODE')
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
            ->where('TS.TS_CATEGORY2_SQ', $cate)
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');

        $data = $product
            ->orderBy('fir_ord')
            ->orderBy('sec_ord')
            ->orderBy('ts_code')
            ->get();

        $result = $data->mergeRecursive($event);

        return response()->json($result);
    }

    public function getProductList($connection, $cPublicCi, Request $request)
    {
        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $cpContent = $this->getCPLocaleColumn($locale);
        $remainContent = $this->changeLocaleRemain($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $model = new TreatmentShopModel();
        $table = $this->changeConnection($connection, $model);
        $eventModel = new TreatmentShopEventDetailModel();
        $eventTable = $this->changeConnection($connection, $eventModel);
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);
        $ctiModel = new CustomerTreatmentItemModel();
        $ctiTable = $this->changeConnection($connection, $ctiModel);

        //일반 상품 정보
        $product = explode(',', $request->input('product'));
        //일반 상품 혜택 정보
        $productBenefit = explode(',', $request->input('product_benefit'));
        //일반 상품 잔여 시술 정보
        $productRemain = explode(',', $request->input('product_remain'));
        //이벤트 상품 정보
        $event = !is_null($request->input('event')) ? explode(',', $request->input('event')) : [];
        //이벤트 카테고리 정보
        $eventCategory = !is_null($request->input('event_category')) ? explode(',', $request->input('event_category')) : [];
        //이벤트 상품 혜택 정보
        $eventBenefit = explode(',', $request->input('event_benefit'));
        //이벤트 상품 잔여 시술 정보
        $eventRemain = explode(',', $request->input('event_remain'));

        $dcType = config('code.CP_DISCOUNT_TYPE');
        unset($dcType['04']);

        $result = [
            'list' => []
            ,'total_count' => 0
            ,'total_price' => 0
        ];


        //회원 여부 확인
        $memberCheck = $cPublicCi != 'null';

        if ($memberCheck) {
            //오늘 날짜 기준
            $now = date('Y-m-d');
            //사용 가능한 쿠폰 목록 조회
            $benefitData = $cTable->select(
                'COU.CP_SQ as cp_code'
                ,'COU.' . $cpContent . ' as cp_name'
                ,DB::raw('IF(COU.CP_IS_OVERLAP = 1, "Y", "N") as cp_overlap_yn')
                ,'COU.CP_DISCOUNT_PER as cp_dc_per'
                ,'COU.CP_DISCOUNT_PRICE as cp_dc_price'
                ,'COU.CP_DISCOUNT_PRICE_TYPE as cp_dc_price_type'
                ,'COU.CP_DISCOUNT_MAX_PRICE as cp_dc_max_price'
                ,'COU.CP_DISCOUNT_MIN_PRICE as cp_dc_min_price'
                ,'COU.CP_DISCOUNT_TYPE as cp_dc_type'
                ,'COU.CP_DISCOUNT_SHOP_CODE as cp_dc_ts_code'
                ,'COU.CP_DISCOUNT_CATEGORY2_SQ as sec_cate_code'
                ,'COU.CP_DISCOUNT_EVENT_CODE as event_code'
                ,'CC.CPC_SQ as cpc_code'
            )
                ->join('CRM2_COUPON_CUSTOMER AS CC', 'CUS.C_NUMBER', 'CC.C_NUMBER')
                ->join('CRM2_COUPON AS COU', 'COU.CP_SQ', 'CC.CP_SQ')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('CC.CPC_IS_USE', 0)
                ->where('CC.CPC_START_DATE', '<=', $now)
                ->where('CC.CPC_END_DATE', '>=', $now)
                ->whereIn('COU.CP_DISCOUNT_TYPE', $dcType)
                ->get();
        } else {
            $benefitData = null;
        }

        //이벤트 상품 정보
        foreach ($event as $key => $value) {

            //이벤트 카테고리 확인
            if (!empty($eventCategory[$key])) {
                if ($memberCheck) {
                    //1회 체험가 이벤트 상품 결제 여부 확인
                    $eventPay = $cTable->select('RPTS.*')->leftjoin('CRM2_REGISTER_PAY as RP', 'RP.C_NUMBER', 'CUS.C_NUMBER')
                        ->join('CRM2_REGISTER_PAY_TREATMENT_SHOP as RPTS', 'RP.RTP_NUMBER', 'RPTS.RTP_NUMBER')
                        ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                        ->where('RPTS.RTPTS_STATE', config('code.RTPTS_STATE')['02'])
                        ->where('RPTS.RTPTS_EVENT_CODE', $eventCategory[$key])
                        ->where('RPTS.TS_CODE', $value);
                } else {
                    $eventPay = null;
                }

                $eventResult = $eventTable->select(
                    DB::raw('null as cti_no')
                    ,'TS.TS_CODE as ts_code'
                    ,'TS.' . $tsContent . ' as ts_name'
                    ,'TSED.TSED_PRICE as ts_price'
                    ,DB::raw('"event" as fir_cate_code')
                    ,DB::raw('"' . $eventContent . '" as fir_cate_name')
                    ,'TSE.TSE_CODE as sec_cate_code'
                    ,'TSE.' . $tseContent . ' as sec_cate_name'
                    ,'TS.TS_IS_ORI_DISPLAY as show_cate'
                    ,DB::raw('"N" as ts_has_yn')
                    ,'TSE.TSE_START_DATETIME as start_date_time'
                    ,'TSE.TSE_END_DATETIME as end_date_time'
                )
                    ->join('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'TSED.TS_CODE')
                    ->join('CRM2_TREATMENT_SHOP_EVENT AS TSE', 'TSE.TSE_CODE', 'TSED.TSE_CODE')
                    ->when(isset($eventPay), function ($q) use ($eventPay) {
                        $q->addSelect('EP.RTPTS_STATE as pay')->leftjoinSub($eventPay, 'EP', function($q2) {
                            $q2->on('EP.RTPTS_EVENT_CODE', 'TSE.TSE_CODE')
                                ->on('EP.TS_CODE', 'TS.TS_CODE')
                                ->where('TSE.TSE_OPTION', config('code.TSE_OPTION')['04']);
                        });
                    })
                    ->where('TSED.TSE_CODE', $eventCategory[$key])
                    ->where('TSED.TS_CODE', $value)
                    ->first();

                //이벤트 상품 있는지 확인
                if (!is_null($eventResult)) {
                    //사용가능한 쿠폰이 있는지 확인
                    if (isset($benefitData)) {
                        $eventBenefitList = $benefitData->where('cp_dc_type', $dcType['05'])->where('event_code', $eventResult->sec_cate_code);
                        //카테고리에도 노출하는 상품일 때, 체크
                        if ($eventResult->TS_IS_ORI_DISPLAY) {
                            $eventBenefitList = $eventBenefitList->mergeRecursive($benefitData->where('cp_dc_type', $dcType['02'])->where('sec_cate_code', $eventResult->ts_sec_cate_code));
                        }

                        //사용가능한 쿠폰 정보
                        $eventResult->benefit_list = $eventBenefitList;

                        if (!empty($eventBenefit[$key])) {
                            //사용한 쿠폰 정보
                            $eventBenefitUseList = explode('|', $eventBenefit[$key]);
                            $eventResult->benefit_use_list = $benefitData->whereIn('cpc_code', $eventBenefitUseList);
                        }
                    }

                    //잔여 시술 여부
                    $eventResult->remain_yn = 'N';

                    //상품 정보 설정
                    $result['list'][$eventResult->sec_cate_code][] = $eventResult;
                    $result['total_count'] += 1;
                    $result['total_price'] += $eventResult->ts_price;
                }
            }
        }

        //일반 상품 정보
        foreach ($product as $key => $value) {
            //잔여 시술 여부 확인
            $remainCheck = !empty($productRemain[$key]);

            //상품 정보 조회
            if ($remainCheck) {
                $productResult = $ctiTable->select(
                    'CTI.CTI_SQ as cti_no'
                    ,DB::raw('null as ts_code')
                    ,DB::raw('IFNULL(TC.TC_NM, CTI.CTI_NM) as ts_name')
                    ,DB::raw('0 as ts_price')
                    ,DB::raw('null as fir_cate_code')
                    ,DB::raw('null as fir_cate_name')
                    ,DB::raw('null as sec_cate_code')
                    ,DB::raw('"' . $remainContent . '" as sec_cate_name')
                    ,DB::raw('null as show_cate')
                    ,DB::raw('"Y" as ts_has_yn')
                    ,DB::raw('null as start_date_time')
                    ,DB::raw('null as end_date_time')
                )
                    ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'CTI.TI_SQ')
                    ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                        $join->on('TC.TC_SQ', $tiContent)
                            ->where('TC.TC_LANGUAGE', $locale);
                    })
                    ->where('CTI.CTI_SQ', $productRemain[$key])
                    ->first();
            } else {
                $productResult = $table->select(
                    DB::raw('null as cti_no')
                    ,'TS.TS_CODE as ts_code'
                    ,'TS.' . $tsContent . ' as ts_name'
                    //,'TS.TS_PRICE as ts_price'
                    ,DB::raw('if(TS.TS_TYPE = "멤버쉽", TS.TS_TOTAL_PRICE, TS.TS_PRICE) as ts_price')
                    ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
                    ,'CB.' . $cateContent . ' as fir_cate_name'
                    ,'TS.TS_CATEGORY2_SQ as sec_cate_code'
                    ,'CB2.' . $cateContent . ' as sec_cate_name'
                    ,'TS.TS_IS_ORI_DISPLAY as show_cate'
                    ,DB::raw('"N" as ts_has_yn')
                    ,DB::raw('null as start_date_time')
                    ,DB::raw('null as end_date_time')
                )
                    ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                    ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                    ->where('TS.TS_CODE', $value)
                    ->first();
            }

            if (!is_null($productResult)) {
                //사용 가능한 쿠폰이 있는지 확인
                if (isset($benefitData) && !$remainCheck) {
                    $benefitList = $benefitData->where('cp_dc_type', $dcType['03'])->where('cp_dc_ts_code', $productResult->ts_code);
                    //주문금액 쿠폰 미노출
                    $benefitList = $benefitList->mergeRecursive($benefitData->where('cp_dc_type', $dcType['01']));
                    $benefitList = $benefitList->mergeRecursive($benefitData->where('cp_dc_type', $dcType['02'])->where('sec_cate_code', $productResult->sec_cate_code));
                    //사용 가능한 쿠폰 정보
                    $productResult->benefit_list = $benefitList;

                    if (!empty($productBenefit[$key])) {
                        //사용한 쿠폰 정보
                        $productBenefitUseList = explode('|', $productBenefit[$key]);
                        $productResult->benefit_use_list = $benefitData->whereIn('cpc_code', $productBenefitUseList);
                    }
                } else {
                    $productResult->benefit_list = [];
                }

                //잔여 시술 여부
                $productResult->remain_yn = $remainCheck ? 'Y' : 'N';

                //상품 정보 설정
                $result['list'][$productResult->sec_cate_code][] = $productResult;
                $result['total_count'] += 1;
                $result['total_price'] += $productResult->ts_price;
            }
        }

        foreach ($benefitData as $key => $value) {
            if ($value['cp_dc_price_type'] == '정액' && $value['cp_dc_min_price'] > $result['total_price']) {
                unset($benefitData[$key]);
                continue;
            }
            if ($value['cp_dc_type'] == '이벤트할인' && !isset($result['list'][$value['event_code']])) {
                unset($benefitData[$key]);
                continue;
            }
            if ($value['cp_dc_type'] == '카테고리할인' && !isset($result['list'][$value['sec_cate_code']])) {
                unset($benefitData[$key]);
            }
        }

        $bList = [];
        if (isset($productResult->benefit_list)) {
            foreach ($productResult->benefit_list as $item) {
                if ($item['cp_dc_type'] == '주문금액') {
                    if ($item['cp_dc_min_price'] <= $result['total_price']) $bList[$item->cp_code] = $item;
                } else {
                    $bList[$item->cp_code] = $item;
                }

            }
        }
        if (isset($eventResult)) {
            foreach ($eventResult->benefit_list as $item) {
                $bList[$item->cp_code] = $item;
            }
        }
        $result['benefitList'] = $bList;

        return response()->json($result);
    }

    public function getSearch($connection, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);

        $model = new TreatmentShopModel();
        $table = $this->changeConnection($connection, $model);
        $eModel = new TreatmentShopEventModel();
        $eTable = $this->changeConnection($connection, $eModel);

        $data = $table->select(
            'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'CB2.' . $cateContent . ' as sec_cate_name'
        )
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->get();


        $eData = $eTable->select(
            'TSE.' . $tseContent . ' as tse_name'
        )
            ->where('TSE.TSE_IS', 1)
            ->where('TSE.TSE_DELETE_DATETIME', null)
            ->where('TSE.TSE_START_DATETIME', '<=', Now())
            ->where('TSE.TSE_END_DATETIME', '>', Now())
            ->where('TSE.TSE_CODE', '!=', 'EVT682EB3E313D22')
            ->get();

        $product = [];
        $category = [];
        $eventCategory = [];
        foreach ($data as $item) {
            $product[] = $item->ts_name;
            if (!in_array($item->sec_cate_name, $category)) {
                $category[] = $item->sec_cate_name;
            }
        }

        foreach ($eData as $item) {
            $eventCategory[] = $item->tse_name;
        }

        $result['search'] = array_merge($product, $category, $eventCategory);

        $map = [
            'ppeum_dev'  => 'mysql',
            'ppeum09'    => 'mysql_ppeum_09',
            'ppeum20'    => 'mysql_ppeum_20',
            'ppeum920'   => 'mysql_ppeum_920',
            'ppeum30'    => 'mysql_ppeum_30',
            'ppeum01'    => 'mysql_ppeum_01',
            'ppeum916'   => 'mysql_ppeum_916',
            'ppeum27'    => 'mysql_ppeum_27',
            'ppeum37'    => 'mysql_ppeum_37',
            'ppeum931'   => 'mysql_ppeum_931',
            'ppeumtest'  => 'mysql_ppeumtest',
        ];

        $realConn = $map[$connection] ?? 'mysql';

        $hashTable = DB::connection($realConn)
            ->table('CRM2_TREATMENT_CONTENT as a');

        $hashTable = $hashTable->crossJoin(
            DB::raw("(
                select 1 as n union all
                select 2 union all
                select 3 union all
                select 4 union all
                select 5
            ) as numbers")
        );

        $result['hash_tag'] = $hashTable
            ->selectRaw("
                substring_index(
                    SUBSTRING_INDEX(a.TC_HASHTAG, '#', numbers.n),
                    '#', -1
                ) as tc_hash_tag
            ")
            ->selectRaw("COUNT(*) as cnt")
            ->where('a.TC_LANGUAGE', $locale)
            ->whereRaw("
                CHAR_LENGTH(a.TC_HASHTAG)
                - CHAR_LENGTH(REPLACE(a.TC_HASHTAG, '#', ''))
                >= numbers.n - 1
            ")

            ->whereRaw("
                substring_index(
                    SUBSTRING_INDEX(a.TC_HASHTAG, '#', numbers.n),
                    '#', -1
                ) != ''
            ")
            ->groupBy('tc_hash_tag')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get();
        /*$result['hash_tag'] = DB::select('SELECT
            aa.TC_HASHTAG as tc_hash_tag
            , COUNT(aa.TC_HASHTAG) AS cnt
            FROM
            (
                SELECT
                    substring_index(SUBSTRING_INDEX(a.TC_HASHTAG, "#", numbers.n), "#" , -1) as TC_HASHTAG
                from (
                    select 1 as n union all
                    select 2 union all
                    select 3 union all
                    select 4 union all
                    select 5
                ) as numbers
                    inner join CRM2_TREATMENT_CONTENT AS a on char_length(a.TC_HASHTAG) - char_length(replace(a.TC_HASHTAG, "#", "")) >= numbers.n - 1
                    where a.TC_LANGUAGE = "'. $locale . '"
            ) AS aa' .
            '
            WHERE
                aa.TC_HASHTAG != ""
            GROUP BY
                aa.TC_HASHTAG
            ORDER BY
                cnt desc
            LIMIT 10');*/


        return response()->json($result);
    }

    public function getTextSearch($connection, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $model = new ConfigBaseModel();
        $table = $this->changeConnection($connection, $model);
        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);

        $text = $request->input('text', '');
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
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
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
            ->where(function($q) use ($tsContent, $tseContent, $text) {
                return $q->where('TS.' . $tsContent, 'like', '%' . $text . '%')
                    ->orWhere('TC.TC_HASHTAG', 'like', '%#' . $text . '%')
                    ->orWhere('TSE.' . $tseContent, 'like', '%' . $text . '%');
            })
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->where('TSE.TSE_CODE', '!=', 'EVT682EB3E313D22')
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE');

        //일반 상품 정보 조회
        $product = $table->select(
            'CB.' . $cateContent . ' as fir_cate_name'
            ,'CB2.CB_GROUP2 as fir_cate_code'
            ,'CB2.CB_SQ as sec_cate_code'
            ,'CB2.' . $cateContent . ' as sec_cate_name'
            ,DB::raw('(select SCB.' . $cateContent . ' from CRM2_CONFIG_BASE as SCB where SCB.CB_SQ = TSC.TSC_CATEGORY1_SQ) as fir_cate_name_group')
            ,DB::raw('(select SCB2.' . $cateContent . ' from CRM2_CONFIG_BASE as SCB2 where SCB2.CB_SQ = TSC.TSC_CATEGORY2_SQ) as sec_cate_name_group')
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_TYPE as ts_type'
            ,'TS.TS_PRICE as ts_price'
            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'CB.CB_ORD as fir_ord'
            ,'CB2.CB_ORD as sec_ord'
            ,'TSC.TSC_ORD as thi_ord'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('GROUP_CONCAT(TC.TC_DESCRIPTION_JSON) as ts_desc')
            ,DB::raw('GROUP_CONCAT(TC.TC_CAUTION_JSON) as ts_caution')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
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
            ->where(function($q) use ($tsContent, $cateContent, $text) {
                return $q->where('TS.' . $tsContent, 'like', '%' . $text . '%')
                    ->orWhere('TC.TC_HASHTAG', 'like', '%#' . $text . '%')
                    ->orWhere('CB2.' . $cateContent, 'like', '%' . $text . '%');
            })
            //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
            ->orderBy('TSC.TSC_CATEGORY1_SQ')
            ->orderBy('TSC.TSC_CATEGORY2_SQ')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSC.TSC_CATEGORY1_SQ')
            ->groupBy('TSC.TSC_CATEGORY2_SQ')
            ->groupBy('TSC.TS_CODE');

        $result = [];
        foreach ($event->get() as $key => $item) {
            $result[$item->sec_cate_name][] = $item;
        }
        foreach ($product->get() as $key => $item) {
            $result[$item->sec_cate_name][] = $item;
        }

        return response()->json($result);

        /*$locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);

        $tsModel = new TreatmentShopModel();
        $tsTable = $this->changeConnection($connection, $tsModel);
        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);
        $contentModel = new TreatmentContentModel();
        $contentTable = $this->changeConnection($connection, $contentModel);

        $text = $request->input('text', '');
        $cPublicCi = $request->input('cPublicCi', '');
        $memberCheck = !empty($cPublicCi);
        $eventPay = null;

        if ($memberCheck) {
            //1회 체험가 이벤트 상품 결제 여부 확인
            $eventPay = $cTable->select('RPTS.*')->leftjoin('CRM2_REGISTER_PAY as RP', 'RP.C_NUMBER', 'CUS.C_NUMBER')
                ->join('CRM2_REGISTER_PAY_TREATMENT_SHOP as RPTS', 'RP.RTP_NUMBER', 'RPTS.RTP_NUMBER')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RPTS.RTPTS_STATE', config('code.RTPTS_STATE')['02']);
        }

        //이벤트 상품
        $product_event = $eventTable->select(
            DB::raw('"이벤트" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.TSE_SUBJECT as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
        )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
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
            ->where('TS.' . $tsContent, 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE');

        //이벤트 해쉬태그
        $product_event_hash = $contentTable->select(
            DB::raw('"이벤트" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.TSE_SUBJECT as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC2.TC_HASHTAG) as ts_hash')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
        )
            ->join('CRM2_TREATMENT_ITEM as TI', $tiContent, 'TC.TC_SQ')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSD.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_EVENT as TSE', 'TSE.TSE_CODE', 'TSED.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD2', 'TSD2.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI2', 'TSD2.TSD_TI_SQ', 'TI2.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC2', 'TC2.TC_SQ', 'TI2.TI_CONTENT_KO_SQ')
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
            ->where('TC.TC_HASHTAG', 'like', '%#' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE');



        //이벤트 카테고리
        $product_event_category = $eventTable->select(
            DB::raw('"이벤트" as fir_cate_name')
            ,DB::raw('"event" as fir_cate_code')
            ,'TSE.TSE_CODE as sec_cate_code'
            ,'TSE.TSE_SUBJECT as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
            ,'TSED.TSED_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,'TSE.TSE_START_DATETIME as tse_start_datetime'
            ,'TSE.TSE_END_DATETIME as tse_end_datetime'
        )
            ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
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
            ->where('TSE.TSE_SUBJECT', 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->orderBy('TSE.TSE_ORD')
            ->orderBy('TSED.TSED_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TSE.TSE_CODE')
            ->groupBy('TS.TS_CODE');

        //일반 상품 정보 조회
        $product = $tsTable->select(
            'TS.TS_CATEGORY1_NM as fir_cate_name'
            ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
            ,'TS.TS_CATEGORY1_SQ as sec_cate_code'
            ,'TS.TS_CATEGORY2_NM as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_PRICE as ts_price'
//            ,'TS.TS_PRICE as event_ts_price'
            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
        )
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->join('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            ->where('TS.' . $tsContent, 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->where('TS.TS_TYPE', '!=', '멤버쉽')
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');

        //멤버십 상품 정보 조회
        $member_product = $tsTable->select(
            'TS.TS_CATEGORY1_NM as fir_cate_name'
            ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
            ,'TS.TS_CATEGORY2_SQ as sec_cate_code'
//            ,'TS.TS_CATEGORY2_NM as sec_cate_name'
            ,DB::raw("(select CB.CB_NM from CRM2_CONFIG_BASE as CB where CB.CB_SQ = TS.TS_CATEGORY2_SQ) as sec_cate_name")
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_PRICE as ts_price'
//            ,'TS.TS_PRICE as event_ts_price'
//            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'TS.TS_TOTAL_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
//            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
//            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as ts_comment')
            ,DB::raw('null as ts_hash')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
        )
//            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
//            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
//            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->join('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
//            ->where('TI.TI_IS', 1)
            ->where('TS.' . $tsContent, 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->where('TS.TS_TYPE', '멤버쉽')
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');


        //일반상품 해쉬태그
        $product_hash = $contentTable->select(
            'TS.TS_CATEGORY1_NM as fir_cate_name'
            ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
            ,'TS.TS_CATEGORY1_SQ as sec_cate_code'
            ,'TS.TS_CATEGORY2_NM as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_PRICE as ts_price'
//            ,'TS.TS_PRICE as event_ts_price'
            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC2.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
        )
            ->join('CRM2_TREATMENT_ITEM as TI', $tiContent, 'TC.TC_SQ')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSD.TS_CODE')
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->join('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD2', 'TSD2.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI2', 'TSD2.TSD_TI_SQ', 'TI2.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC2', 'TC2.TC_SQ', 'TI2.TI_CONTENT_KO_SQ')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            ->where('TC.TC_HASHTAG', 'like', '%#' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');

        //일반상품 카테고리
        $product_category = $tsTable->select(
            'TS.TS_CATEGORY1_NM as fir_cate_name'
            ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
            ,'TS.TS_CATEGORY1_SQ as sec_cate_code'
            ,'TS.TS_CATEGORY2_NM as sec_cate_name'
            ,'TS.TS_CODE as ts_code'
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_PRICE as ts_price'
//            ,'TS.TS_PRICE as event_ts_price'
            ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
            ,'TS.TS_TYPE as ts_type'
            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            ,DB::raw('null as pay')
            ,DB::raw('null as tse_start_datetime')
            ,DB::raw('null as tse_end_datetime')
        )
            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->join('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
            ->where('TI.TI_IS', 1)
            ->where('TS.TS_CATEGORY2_NM', 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');

        //멤버십 상품 카테고리
        $member_product_category = $tsTable->select(
            'TS.TS_CATEGORY1_NM as fir_cate_name'
            , 'TS.TS_CATEGORY1_SQ as fir_cate_code'
            , 'TS.TS_CATEGORY1_SQ as sec_cate_code'
//            ,'TS.TS_CATEGORY2_NM as sec_cate_name'
            , DB::raw("(select CB.CB_NM from CRM2_CONFIG_BASE as CB where CB.CB_SQ = TS.TS_CATEGORY2_SQ) as sec_cate_name")
            , 'TS.TS_CODE as ts_code'
            , 'TS.' . $tsContent . ' as ts_name'
            , 'TS.TS_PRICE as ts_price'
//            ,'TS.TS_PRICE as event_ts_price'
            , 'TS.TS_TOTAL_PRICE as event_ts_price'
            , 'TS.TS_TYPE as ts_type'
//            ,DB::raw('IF(TS_TYPE = "' . config('code.TS_TYPE')['01'] . '", TC.TC_CONTENT, TS.TS_CONTENT) as ts_comment')
            , DB::raw('null as ts_comment')
//            ,DB::raw('GROUP_CONCAT(TC.TC_HASHTAG) as ts_hash')
            , DB::raw('null as ts_hash')
            , DB::raw('null as pay')
            , DB::raw('null as tse_start_datetime')
            , DB::raw('null as tse_end_datetime')
        )
//            ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
//            ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
//            ->leftjoin('CRM2_TREATMENT_CONTENT as TC', 'TC.TC_SQ', $tiContent)
            ->join('CRM2_TREATMENT_SHOP_CATEGORY as TSC', 'TS.TS_SQ', 'TSC.TS_SQ')
            ->join('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
            ->join('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'CB2.CB_GROUP2')
            ->where('TS.TS_IS_ORI_DISPLAY', 1)
            ->where('TS.TS_IS', 1)
//            ->where('TI.TI_IS', 1)
            ->where('TS.TS_CATEGORY1_NM', 'like', '%' . $text . '%')
            ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
            ->where('TS.TS_TYPE', '멤버쉽')
            ->orderBy('CB.CB_ORD')
            ->orderBy('CB2.CB_ORD')
            ->orderBy('TSC.TSC_ORD')
            ->groupBy('TS.TS_CODE');

        $result = [];
        foreach ($product_event->union($product_event_hash)->union($product_event_category)->get() as $key => $item) {
            $result[$item->sec_cate_name][] = $item;
        }
        foreach ($product->union($product_hash)->union($product_category)->union($member_product)->union($member_product_category)->get() as $key => $item) {
            $result[$item->sec_cate_name][] = $item;
        }

        return response()->json($result);*/
    }

    public function getRank($connection, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

        $locale = $this->setLocale($request);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);

        $model = new TreatmentShopModel();
        $table = $this->changeConnection($connection, $model);
        $eventModel = new TreatmentShopEventModel();
        $eventTable = $this->changeConnection($connection, $eventModel);

        //일반 상품 정보
        $product = explode(',', $request->input('product'));
        //이벤트 카테고리 정보
        $eventCategory = explode(',', $request->input('event_category'));

        $result = [];
        foreach ($product as $key => $value) {
            $eCate = $eventCategory[$key];

            if (empty($eCate)) {
                $result[] = $table->select(
                    'TS.' . $tsContent . ' as ts_name'
                    ,'TS.TS_PRICE as ts_price'
                    //,'TS.TS_PRICE as event_ts_price'
                    ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
                    ,'CB2.' . $cateContent . ' as sec_cate_name'
                    ,DB::raw('"normal" as product_type')
                )
                    ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
                    ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
                    ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                    ->where('TS.TS_CODE', $value)
                    ->where('TS.TS_IS_ORI_DISPLAY', 1)
                    ->where('TS.TS_IS', 1)
                    ->where('TI.TI_IS', 1)
                    //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                    ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                    ->first();
            } else {
                $result[] = $eventTable->select(
                    'TS.' . $tsContent . ' as ts_name'
                    ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                    ,'TSED.TSED_PRICE as event_ts_price'
                    ,'TSE.' . $tseContent . ' as sec_cate_name'
                    ,DB::raw('"event" as product_type')
                )
                    ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', 'TSED.TSE_CODE', 'TSE.TSE_CODE')
                    ->join('CRM2_TREATMENT_SHOP as TS', 'TS.TS_CODE', 'TSED.TS_CODE')
                    ->join('CRM2_TREATMENT_SHOP_DETAIL as TSD', 'TSD.TS_CODE', 'TS.TS_CODE')
                    ->join('CRM2_TREATMENT_ITEM as TI', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
                    ->where('TS.TS_CODE', $value)
                    ->where('TSE.TSE_IS', 1)
                    ->where('TSE.TSE_DELETE_DATETIME', null)
                    ->where('TSE.TSE_START_DATETIME', '<=', Now())
                    ->where('TSE.TSE_END_DATETIME', '>', Now())
                    ->where('TS.TS_IS', 1)
                    ->where('TI.TI_IS', 1)
                    //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                    ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                    ->first();
            }
        }

        return response()->json($result);
    }
}

?>
