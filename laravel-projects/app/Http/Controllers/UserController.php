<?php

namespace App\Http\Controllers;

use App\Models\ConfigHolidayModel;
use App\Models\CouponCustomerModel;
use App\Models\CouponModel;
use App\Models\CustomerModel;
use App\Models\CustomerTreatmentItemModel;
use App\Models\RegisterPayModel;
use App\Models\ReserveModel;
use App\Models\ReserveTreatmentShopModel;
use App\Models\TreatmentShopEventDetailModel;
use App\Models\TreatmentShopEventModel;
use App\Models\TreatmentShopModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUserInfo($connection, $cPublicCi)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $result = $table->where('C_PUBLIC_CI', $cPublicCi)->where('C_DELETE_DATETIME', null)->first();

        return response()->json(['result' => $result]);
    }

    public function getUserAppointment($connection, $cPublicCi, $rNumber, Request $request)
    {
        $locale = $this->setLocale($request);
        $tsColumn = $this->getTSLocaleColumn($locale);

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $appointment = $table->select(
                'C_NAME'
                ,'R_DATE'
                ,'R_TIME'
            )
            ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            //->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('RES.R_NUMBER', $rNumber)
            ->first();

        if (!is_null($appointment)) {
            $rSTime = strtotime($appointment->R_DATE);
            $rDate = $this->changeLocaleYoil($locale);
            $rTime = $this->setTime($locale, $appointment->R_TIME);

            $date = date('Y.m.d', $rSTime) . '(' . $rDate[date('w', $rSTime)] . ') ' . $rTime . ' ' . substr($appointment->R_TIME, 0, 5);

            $result = [
                'name' => $appointment->C_NAME
                ,'date' => $date
                ,'list' => [
                    'new' => []
                    ,'old' => []
                ]
                ,'totalPrice' => 0
                ,'mPrice' => 0
                ,'mPrice2' => 0
                ,'mPrice3' => 0
            ];

            $model = new ReserveModel();
            $reserveTable = $this->changeConnection($connection, $model);

            //신규상품 예약 정보
            $new = $reserveTable
                ->join('CRM2_RESERVE_TREATMENT_SHOP AS RTS', 'RES.R_NUMBER', 'RTS.R_NUMBER')
                ->leftjoin('CRM2_TREATMENT_SHOP_DETAIL AS TSD', 'TSD.TS_CODE','RTS.TS_CODE')
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TSD.TS_CODE', 'TS.TS_CODE')
                ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'TSD.TSD_TI_SQ')
                ->where('RES.R_NUMBER', $rNumber)
                ->get();

            //잔여시술 예약 정보
            $old = $reserveTable->join('CRM2_RESERVE_TREATMENT_ITEM AS RTI', 'RTI.R_NUMBER', 'RES.R_NUMBER')
                ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'RTI.TI_SQ')
                ->leftjoin('CRM2_TREATMENT_SHOP_DETAIL AS TSD', 'TSD.TSD_TI_SQ', 'TI.TI_SQ')
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TSD.TS_CODE', 'TS.TS_CODE')
                ->where('RES.R_NUMBER', $rNumber)
                ->get();

            foreach ($new as $item) {
                //총 시술금액
                $result['totalPrice'] += $item->TS_PRICE;
                //혜택 금액
                $result['price1'] += 0;
                //마일리지사용
                $result['price2'] += 0;
                //멤버십 할인
                $result['price3'] += 0;

                if (array_key_exists($item->TS_CATEGORY2_SQ, $result['list']['new'])) {
                    $result['list']['new'][$item->TS_CATEGORY2_SQ]['totalPrice'] += $item->TS_PRICE;
                    $result['list']['new'][$item->TS_CATEGORY2_SQ]['list'][$item->RTS_SQ] = [
                        'name' => $item->{$tsColumn}
                        ,'price' => $item->TS_PRICE
                    ];
                } else {
                    $result['list']['new'][$item->TS_CATEGORY2_SQ] = [
                        'name' => $item->TS_CATEGORY2_NM
                        ,'totalPrice' => $item->TS_PRICE
                        ,'list' => [
                            $item->RTS_SQ => [
                                'name' => $item->{$tsColumn}
                                ,'price' => $item->TS_PRICE
                            ]
                        ]
                    ];
                }
            }

            foreach ($old as $item) {
                $result['list']['old'][$item->TS_CATEGORY2_SQ] = [
                    'name' => $item->TS_CATEGORY2_NM
                    ,'totalPrice' => 0
                    ,'list' => [
                        $item->RTI_SQ => [
                            'name' => $item->{$tsColumn}
                            ,'price' => 0
                        ]
                    ]
                ];
            }

            dd($result);

        }
    }

    public function getUserBenefit($connection, $cPublicCi, Request $request)
    {
        $locale = $this->setLocale($request);
        $cpContent = $this->getCPLocaleColumn($locale);

        $now = date('Y-m-d');
        $tsCode = $request->input('ts_code', '');
        $cateCode = $request->input('cate_code', '');
        $excetion = explode(',', $request->input('exception', ''));

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $result = $table->select(
                'CC.CPC_SQ as cpc_code'
                ,'COU.' . $cpContent . ' as cpc_name'
                ,'CC.CPC_DISCOUNT_SHOP_NAME as cpc_dis_shop_name'
                ,'CC.CPC_DISCOUNT_MEMO as cpc_dis_memo'
                ,'CC.CPC_START_DATE as cpc_start_date'
                ,'CC.CPC_END_DATE as cpc_end_date'
                ,'CC.CPC_IS_OVERLAP as cpc_is_overlap'
                ,'COU.CP_DISCOUNT_TYPE as cp_dis_type'
                ,'COU.CP_DISCOUNT_SHOP_CODE as cp_dis_shop_code'
                ,'COU.CP_DISCOUNT_CATEGORY2_SQ as cp_dis_cate_code'
                ,'COU.CP_DISCOUNT_EVENT_CODE as cp_dis_event_code'
            )
            ->join('CRM2_COUPON_CUSTOMER AS CC', 'CC.C_NUMBER', 'CUS.C_NUMBER')
            ->join('CRM2_COUPON AS COU', 'COU.CP_SQ', 'CC.CP_SQ')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CC.CPC_START_DATE', '<=', $now)
            ->where('CC.CPC_END_DATE', '>=', $now)
            ->whereNotIn('CC.CPC_SQ', $excetion)
            ->where('CC.CPC_IS_USE', 0)
            ->get();

        if (!empty($tsCode) && !empty($cateCode)) {
            $data = [];
            foreach ($result->where('CP_DISCOUNT_TYPE', config('code.CP_DISCOUNT_TYPE')['03'])->where('CP_DISCOUNT_SHOP_CODE', $tsCode) as $item) {
                array_push($data, $item->toArray());
            }
            foreach ($result->where('CP_DISCOUNT_TYPE', config('code.CP_DISCOUNT_TYPE')['01']) as $item) {
                array_push($data, $item->toArray());
            }
            foreach ($result->where('CP_DISCOUNT_TYPE', config('code.CP_DISCOUNT_TYPE')['02'])->where('CP_DISCOUNT_CATEGORY2_SQ', $cateCode) as $item) {
                array_push($data, $item->toArray());
            }

            foreach ($result->where('CP_DISCOUNT_TYPE', config('code.CP_DISCOUNT_TYPE')['05'])->where('CP_DISCOUNT_EVENT_CODE', $cateCode) as $item) {
                array_push($data, $item->toArray());
            }
            $result = $data;
        } else {
            $result = $result->toArray();
        }

        return response()->json(['data' => $result]);
    }

    public function getUserReservation2($connection, $cPublicCi, Request $request)
    {
        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $remainContent = $this->changeLocaleRemain($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $cDate = $this->changeLocaleYoil($locale);
        $result = [];
        $date = Date('Y-m-d');
        $time = Date('H:i:s');

        $default = $table
            ->select(
                'R_STATE'
                ,'R_TYPE'
                ,'R_DATE'
                ,'R_TIME'
                ,'R_NUMBER'
                ,'R_IS_COUNSEL'
                ,'R_CONFIRM'
                ,'CUS.C_MILEAGE'
            )
            ->join('CRM2_RESERVE as RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->whereIn('RES.R_STATE', ['예약', '취소', '접수', '미방문'])
            ->where('RES.R_DATE', '>', $date)
            ->orWhere(function ($q) use ($cPublicCi, $date, $time) {
                $q->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->whereIn('RES.R_STATE', ['예약', '취소', '접수', '미방문'])
                    ->where('RES.R_DATE', '=', $date)
                    ->where('RES.R_TIME', '>', $time);
            })
            ->orderBy('R_DATE', 'DESC')
            ->get();

        if (!is_null($default)) {
            foreach ($default as $key => $item) {
                $rDate = strtotime($item->R_DATE);
                $time = $item->R_TIME;
                $rTime = $this->setTime($locale, $time);
                $rNumber = $item->R_NUMBER;
                $date = date('Y.m.d', $rDate) . '(' . $cDate[date('w', $rDate)] . ') ' . $rTime . ' ' . substr($time, 0, 5);

                //이벤트 정보
                $event = $table
                    ->select(
                        'TS.TS_CODE as ts_code'
                        ,'TS.' . $tsContent . ' as ts_name'
                        ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                        ,DB::raw('"' . $eventContent . '" as fir_cate_name')
                        ,DB::raw('"event" as fir_cate_code')
                        ,'TSE.' . $tseContent . ' as sec_cate_name'
                        ,'TSE.TSE_CODE as sec_cate_code'
                        ,'TSED.TSED_PRICE as event_ts_price'
                        ,DB::raw('"N" as remain_yn')
                        ,DB::raw('null as cti_code')
                    )
                    ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                    ->leftjoin('CRM2_RESERVE_TREATMENT_SHOP AS RTS', 'RTS.R_NUMBER', 'RES.R_NUMBER')
                    ->join('CRM2_TREATMENT_SHOP_EVENT AS TSE', 'TSE.TSE_CODE', 'RTS.TSE_CODE')
                    ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', function ($q) {
                        $q->on('TSED.TSE_CODE', 'TSE.TSE_CODE')
                            ->on('TSED.TS_CODE', 'RTS.TS_CODE');
                    })
                    ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RTS.TS_CODE')
                    ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->where('RES.R_NUMBER', $rNumber)
                    ->orderBy('TSE.TSE_ORD')
                    ->orderBy('TSED.TSED_ORD');

                //일반 상품 정보
                $product = $table
                    ->select(
                        'TS.TS_CODE as ts_code'
                        ,'TS.' . $tsContent . ' as ts_name'
                        ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                        ,'CB.' . $cateContent . ' as fir_cate_name'
                        ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
                        ,'CB2.' . $cateContent . ' as sec_cate_name'
                        ,'TS.TS_CATEGORY2_SQ as sec_cate_code'
                        ,'TS.TS_PRICE as event_ts_price'
                        ,DB::raw('"N" as remain_yn')
                        ,DB::raw('null as cti_code')
                    )
                    ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                    ->join('CRM2_RESERVE_TREATMENT_SHOP AS RTS', function ($q) {
                        $q->on('RTS.R_NUMBER', 'RES.R_NUMBER')
                            ->where(function ($q2) {
                                $q2->where('RTS.TSE_CODE', NULL)
                                    ->orWhere('RTS.TSE_CODE', '');
                            });
                    })
                    ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RTS.TS_CODE')
                    ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                    ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                    ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->where('RES.R_NUMBER', $rNumber)
                    ->orderBy('CB.CB_ORD')
                    ->orderBy('CB2.CB_ORD');

                //잔여 시술 정보
                $itemProduct = $table
                    ->select(
                        'TS.TS_CODE as ts_code'
                        ,'TC.TC_NM as ts_name'
                        ,DB::raw('0 as ts_price')
                        ,'CB.' . $cateContent .' as fir_cate_name'
                        ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
                        ,DB::raw('"' . $remainContent . '" as sec_cate_name')
                        ,'CTI.CTI_CATEGORY_SQ as sec_cate_code'
                        ,DB::raw('0 as event_ts_price')
                        ,DB::raw('"Y" as remain_yn')
                        ,'CTI.CTI_SQ as cti_code'
                    )
                    ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                    ->join('CRM2_RESERVE_TREATMENT_ITEM AS RTI', 'RTI.R_NUMBER', 'RES.R_NUMBER')
                    ->join('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.CTI_SQ', 'RTI.CTI_SQ')
                    ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'CTI.TI_SQ')
                    ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                        $join->on('TC.TC_SQ', $tiContent)
                            ->where('TC.TC_LANGUAGE', $locale);
                    })
                    ->leftjoin('CRM2_REGISTER_PAY_TREATMENT_SHOP AS RPTS', function ($q) {
                        $q->on('RPTS.RTPTS_SQ', 'CTI.RTPTS_SQ')
                            ->where('RPTS.RTPTS_EVENT_CODE', '')
                            ->where('RPTS.RTPTS_EVENT_CODE', null);
                    })
                    ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RPTS.TS_CODE')
                    ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                    ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                    ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->where('RES.R_NUMBER', $rNumber)
                    ->orderBy('CB.CB_ORD')
                    ->orderBy('CB2.CB_ORD');

                $result[$key] = [
                    'list' => $event->unionAll($product)->unionAll($itemProduct)->get()->groupBy('sec_cate_name')
                    ,'date' => $date
                    ,'state' => $item->R_STATE
                    ,'confirm' => $item->R_CONFIRM
                    ,'r_number' => $rNumber
                ];
            }
        }

        return response()->json($result);
    }
    public function getUserReservation($connection, $cPublicCi, Request $request)
    {
        $locale = $this->setLocale($request);

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $date = Date('Y-m-d');
        $time = Date('H:i:s');

        $result = $table->select(
            'RES.R_NUMBER'
            ,'RES.R_STATE'
            ,'RES.R_DATE'
            ,'RES.R_TIME'
            ,'TS1.TS_NM AS TS1_TS_NM'
            ,'TS2.TS_NM AS TS2_TS_NM'
            ,'TS1.TS_PRICE AS TS1_TS_PRICE'
            ,'TS2.TS_PRICE AS TS2_TS_PRICE'
            ,DB::raw('IFNULL(IFNULL(IFNULL(TSE1.TSE_SUBJECT_KO, TS1.TS_CATEGORY2_NM), TSE2.TSE_SUBJECT_KO), TS2.TS_CATEGORY2_NM) AS CATE')
        )
            ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            ->leftjoin('CRM2_RESERVE_TREATMENT_SHOP AS RTS', 'RTS.R_NUMBER', 'RES.R_NUMBER')
            ->leftjoin('CRM2_TREATMENT_SHOP AS TS1', 'TS1.TS_CODE', 'RTS.TS_CODE')
            ->leftjoin('CRM2_TREATMENT_SHOP_EVENT AS TSE1', 'TSE1.TSE_CODE', 'RTS.TSE_CODE')
            ->leftjoin('CRM2_RESERVE_TREATMENT_ITEM AS RTI', 'RTI.R_NUMBER', 'RES.R_NUMBER')
            ->leftjoin('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.CTI_SQ', 'RTI.CTI_SQ')
            ->leftjoin('CRM2_REGISTER_PAY_TREATMENT_SHOP AS RPTS', 'RPTS.RTPTS_SQ', 'CTI.RTPTS_SQ')
            ->leftjoin('CRM2_TREATMENT_SHOP AS TS2', 'TS2.TS_CODE', 'RPTS.TS_CODE')
            ->leftjoin('CRM2_TREATMENT_SHOP_EVENT AS TSE2', 'TSE2.TSE_CODE', 'RPTS.RTPTS_EVENT_CODE')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->whereIn('RES.R_STATE', ['예약', '취소'])
            ->where('RES.R_DATE', '>', $date)
            ->orWhere(function ($q) use ($cPublicCi, $date, $time) {
                $q->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->whereIn('RES.R_STATE', ['예약', '취소'])
                    ->where('RES.R_DATE', '=', $date)
                    ->where('RES.R_TIME', '>', $time);
            })
            ->orderBy('RES.R_NUMBER', 'DESC')
            ->get();

        $data = $result->groupBy('R_NUMBER')->map(function ($i) use ($locale) {
            $rDate = strtotime($i->first()->R_DATE);
            $cDate = $this->changeLocaleYoil($locale);
            $time = $i->first()->R_TIME;
            $rTime = $this->setTime($locale, $time);

            $date = date('Y.m.d', $rDate) . '(' . $cDate[date('w', $rDate)] . ') ' . $rTime . ' ' . substr($time, 0, 5);
            $state = $i->first()->R_STATE;

            $d = $i->groupBy('CATE')->map(function ($v) {
                $result = $v->map(function ($q) {
                    $r['name'] = $q['TS1_TS_NM'] ?? $q['TS2_TS_NM'];
                    $r['price'] = $q['TS1_TS_PRICE'] ?? 0;
                    return $r;
                });

                return [
                    'data' => $result
                    ,'total_price' => $result->sum('price')
                ];
            });

            return [
                'date' => $date
                ,'state' => $state
                ,'total_price' => $d->sum('total_price')
                ,'data' => $d
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getUserReservationRNumber($connection, $cPublicCi, $rNumber, Request $request)
    {
        $locale = $this->setLocale($request);
        $cateContent = $this->getCateLocaleColumn($locale);
        $tseContent = $this->getTSELocaleColumn($locale);
        $remainContent = $this->changeLocaleRemain($locale);
        $eventContent = $this->changeLocaleEvent($locale);

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $tiContent = $this->changeLocaleColumn($locale);
        $tsContent = $this->getTSLocaleColumn($locale);
        $cDate = $this->changeLocaleYoil($locale);
        $result = [];

        $default = $table
            ->select(
                'R_STATE'
                ,'R_TYPE'
                ,'R_DATE'
                ,'R_TIME'
                ,'R_IS_COUNSEL'
                ,'CUS.C_MILEAGE'
            )
            ->join('CRM2_RESERVE as RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('RES.R_NUMBER', $rNumber)
            ->first();
        if (!is_null($default)) {
            $rDate = strtotime($default->R_DATE);
            $time = $default->R_TIME;
            $rTime = $this->setTime($locale, $time);
            $date = date('Y.m.d', $rDate) . '(' . $cDate[date('w', $rDate)] . ') ' . $rTime . ' ' . substr($time, 0, 5);

            //이벤트 정보
            $event = $table
                ->select(
                    'TS.TS_CODE as ts_code'
                    ,'TS.TS_TYPE as ts_type'
                    ,'TS.' . $tsContent . ' as ts_name'
                    ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                    ,DB::raw('"' . $eventContent . '" as fir_cate_name')
                    ,DB::raw('"event" as fir_cate_code')
                    ,'TSE.' . $tseContent . ' as sec_cate_name'
                    ,'TSE.TSE_CODE as sec_cate_code'
                    ,'TSED.TSED_PRICE as event_ts_price'
                    ,DB::raw('"N" as remain_yn')
                    ,DB::raw('null as cti_code')
                )
                ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                ->leftjoin('CRM2_RESERVE_TREATMENT_SHOP AS RTS', 'RTS.R_NUMBER', 'RES.R_NUMBER')
                ->join('CRM2_TREATMENT_SHOP_EVENT AS TSE', 'TSE.TSE_CODE', 'RTS.TSE_CODE')
                ->join('CRM2_TREATMENT_SHOP_EVENT_DETAIL as TSED', function ($q) {
                    $q->on('TSED.TSE_CODE', 'TSE.TSE_CODE')
                        ->on('TSED.TS_CODE', 'RTS.TS_CODE');
                })
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RTS.TS_CODE')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RES.R_NUMBER', $rNumber)
                ->orderBy('TSE.TSE_ORD')
                ->orderBy('TSED.TSED_ORD');

            //일반 상품 정보
            $product = $table
                ->select(
                    'TS.TS_CODE as ts_code'
                    ,'TS.TS_TYPE as ts_type'
                    ,'TS.' . $tsContent . ' as ts_name'
//                    ,'TS.TS_PRICE as ts_price'
                    ,DB::raw('if(TS.TS_TYPE = "멤버쉽", TS.TS_TOTAL_PRICE, TS.TS_PRICE) as ts_price')
                    ,'CB.' . $cateContent . ' as fir_cate_name'
                    ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
//                    ,'TS.TS_CATEGORY2_NM as sec_cate_name'
                    ,DB::raw('if(TS.TS_TYPE = "멤버쉽", "멤버십", CB2.' . $cateContent . ') as sec_cate_name')
                    ,'TS.TS_CATEGORY2_SQ as sec_cate_code'
//                    ,'TS.TS_PRICE as event_ts_price'
                    ,DB::raw('if(TS.TS_TYPE = "멤버쉽", TS.TS_TOTAL_PRICE, TS.TS_PRICE) as event_ts_price')
                    ,DB::raw('"N" as remain_yn')
                    ,DB::raw('null as cti_code')
                )
                ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                ->join('CRM2_RESERVE_TREATMENT_SHOP AS RTS', function ($q) {
                    $q->on('RTS.R_NUMBER', 'RES.R_NUMBER')
                        ->where(function ($q2) {
                            $q2->where('RTS.TSE_CODE', NULL)
                                ->orWhere('RTS.TSE_CODE', '');
                        });
                })
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RTS.TS_CODE')
                ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RES.R_NUMBER', $rNumber)
                ->orderBy('CB.CB_ORD')
                ->orderBy('CB2.CB_ORD');

            //잔여 시술 정보
            $itemProduct = $table
                ->select(
                    'TS.TS_CODE as ts_code'
                    ,'TS.TS_TYPE as ts_type'
                    ,'TC.TC_NM as ts_name'
                    ,DB::raw('0 as ts_price')
                    ,DB::raw('null as fir_cate_name')
                    ,DB::raw('null as fir_cate_code')
                    ,DB::raw('"' . $remainContent . '" as sec_cate_name')
                    ,DB::raw('null as sec_cate_code')
                    ,DB::raw('0 as event_ts_price')
                    ,DB::raw('"Y" as remain_yn')
                    ,'CTI.CTI_SQ as cti_code'
                )
                ->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
                ->join('CRM2_RESERVE_TREATMENT_ITEM AS RTI', 'RTI.R_NUMBER', 'RES.R_NUMBER')
                ->join('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.CTI_SQ', 'RTI.CTI_SQ')
                ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'CTI.TI_SQ')
                ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                    $join->on('TC.TC_SQ', $tiContent)
                        ->where('TC.TC_LANGUAGE', $locale);
                })
                ->leftjoin('CRM2_REGISTER_PAY_TREATMENT_SHOP AS RPTS', function ($q) {
                    $q->on('RPTS.RTPTS_SQ', 'CTI.RTPTS_SQ')
                        ->where('RPTS.RTPTS_EVENT_CODE', '')
                        ->where('RPTS.RTPTS_EVENT_CODE', null);
                })
                ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RPTS.TS_CODE')
                ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                ->where('CUS.C_PUBLIC_CI', $cPublicCi)
                ->where('RES.R_NUMBER', $rNumber)
                ->orderBy('CB.CB_ORD')
                ->orderBy('CB2.CB_ORD');

            $result['list'] = $event->unionAll($product)->unionAll($itemProduct)->get()->groupBy('sec_cate_name');
            $result['date'] = $date;
            $result['mileage'] = $default->C_MILEAGE;
            $result['r_date'] = $default->R_DATE;
            $result['r_time'] = $default->R_TIME;
            $result['r_is_counsel'] = $default->R_IS_COUNSEL;
            $result['r_state'] = $default->R_STATE;
        }

        return response()->json($result);
    }

    public function getUserRemain($connection, $cPublicCi, Request $request)
    {
        $locale = $this->setLocale($request);
        $tiContent = $this->changeLocaleColumn($locale);

        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $tsContent = $this->getTSLocaleColumn($locale);

        $payQuery = $table->select(
            'CTI.CTI_SQ as cti_code'
            ,'CTI.TI_SQ as ti_code'
            ,DB::raw('IFNULL(TC.TC_NM, CTI.CTI_NM) as cti_name')
            ,'TS.' . $tsContent . ' as ts_name'
            ,'TS.TS_CODE as ts_code'
            ,'CTI.CTI_QUANTITY AS count'
            ,'CTI.CTI_QUANTITY_USE AS use_count'
            ,'CTI.CTI_DATETIME as datetime'
            ,DB::raw('IF(CTI.CTI_QUANTITY > CTI.CTI_QUANTITY_USE, 1, 0) as checked')
        )
            ->join('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.C_NUMBER', 'CUS.C_NUMBER')
            ->leftjoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'CTI.TI_SQ')
            ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                $join->on('TC.TC_SQ', $tiContent)
                    ->where('TC.TC_LANGUAGE', $locale);
            })
            ->leftjoin('CRM2_REGISTER_PAY_TREATMENT_SHOP AS RPTS', 'RPTS.RTPTS_SQ', 'CTI.RTPTS_SQ')
            ->leftjoin('CRM2_TREATMENT_SHOP AS TS', 'TS.TS_CODE', 'RPTS.TS_CODE')
            ->leftjoin('CRM2_TREATMENT_SHOP_EVENT AS TSE', 'TSE.TSE_CODE', 'RPTS.RTPTS_EVENT_CODE')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CTI_STATE', '사용')
            ->where('CTI_STATE_PAY', '결제');

        $manualQuery = $table->select(
            'CTI.CTI_SQ as cti_code'
            ,'CTI.TI_SQ as ti_code'
            ,DB::raw('IFNULL(TC.TC_NM, CTI.CTI_NM) as cti_name')
            ,DB::raw('null as ts_name')
            #,'TSD.TS_CODE as ts_code'
            ,DB::raw('null as ts_code')
            ,'CTI.CTI_QUANTITY AS count'
            ,'CTI.CTI_QUANTITY_USE AS use_count'
            ,'CTI.CTI_DATETIME as datetime'
            ,DB::raw('IF(CTI.CTI_QUANTITY > CTI.CTI_QUANTITY_USE, 1, 0) as checked')
        )
            ->join('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.C_NUMBER', 'CUS.C_NUMBER')
            ->leftJoin('CRM2_TREATMENT_ITEM AS TI', 'TI.TI_SQ', 'CTI.TI_SQ')
            ->leftJoin('CRM2_TREATMENT_CONTENT as TC', function($join) use ($tiContent, $locale) {
                $join->on('TC.TC_SQ', $tiContent)
                    ->where('TC.TC_LANGUAGE', $locale);
            })
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CTI_STATE', '사용')
            ->where('CTI_STATE_PAY', '수동등록');

        $result = [];
        foreach($payQuery->union($manualQuery)->get() as $item) {
            if ($item['checked'] == 1) {
                $result[] = $item;
            }
        }

        return response()->json(['data' => $result]);
    }

    public function getUserMyPage($connection, $cPublicCi)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $date = Date('Y-m-d');
        $time = Date('H:i:s');

        $info = $table->where('CUS.C_PUBLIC_CI', $cPublicCi)->where('C_DELETE_DATETIME', null)->first();

        $cnt1 = $table->join('CRM2_COUPON_CUSTOMER AS CC', 'CC.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CC.CPC_END_DATE', '>=', DB::raw("DATE_FORMAT(NOW(), '%Y-%m-%d')"))
            ->where('CC.CPC_IS_USE', 0)
            ->count();

        $cnt2 = $table->join('CRM2_RESERVE AS RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->whereIn('RES.R_STATE', ['예약', '취소', '접수', '미방문'])
            ->where('RES.R_DATE', '>', $date)
            ->orWhere(function ($q) use ($cPublicCi, $date, $time) {
                $q->where('CUS.C_PUBLIC_CI', $cPublicCi)
                    ->whereIn('RES.R_STATE', ['예약', '취소', '접수', '미방문'])
                    ->where('RES.R_DATE', '=', $date)
                    ->where('RES.R_TIME', '>', $time);
            })
            ->count();

        $cnt3Query = $table->select(
            DB::raw('IF(CTI.CTI_QUANTITY > CTI.CTI_QUANTITY_USE, 1, 0) as checked')
        )
            ->join('CRM2_CUSTOMER_TREATMENT_ITEM AS CTI', 'CTI.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CTI_STATE', '사용')
            ->whereIn('CTI_STATE_PAY', ['결제', '수동등록'])
            ->get();

        $cnt3 = 0;
        foreach($cnt3Query as $item) {
            if ($item['checked'] == 1) {
                $cnt3++;
            }
        }

        $result = [
            'mileage' => $info->C_MILEAGE
            ,'benefitCount' => $cnt1
            ,'reservationCount' => $cnt2
            ,'remainCount' => $cnt3
        ];

        return response()->json($result);
    }


    public function getUserBasket($connection, $cPublicCi, Request $request)
    {
        $area_type = $request->input('area', 'domestic');
        $displayType = ($area_type === 'domestic') ? 'WEB' : 'WEB_GLOBAL';

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
        /*$benefitModel = new CouponModel();
        $benefitTable = $this->changeConnection($connection, $benefitModel);*/
        $cModel = new CustomerModel();
        $cTable = $this->changeConnection($connection, $cModel);
        $ctiModel = new CustomerTreatmentItemModel();
        $ctiTable = $this->changeConnection($connection, $ctiModel);
        /*$rpModel = new RegisterPayModel();
        $rpTable = $this->changeConnection($connection, $rpModel);*/

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

        $result = [
            'list' => []
            ,'total_count' => 0
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
                ->get();
        } else {
            $benefitData = null;
        }

        //일반 상품 정보
        foreach ($product as $key => $value) {
            //잔여 시술 여부 확인
            $remainCheck = !empty($productRemain[$key]);

            //상품 정보 조회
            if ($remainCheck) {
                $productResult = $ctiTable->select(
                    'CTI.CTI_SQ as cti_code'
                    ,DB::raw('null as ts_code')
                    ,DB::raw('IFNULL(TC.TC_NM, CTI.CTI_NM) as ts_name')
                    ,DB::raw('0 as ts_price')
                    ,DB::raw('null as ts_type')
                    ,DB::raw('null as fir_cate_code')
                    ,DB::raw('null as fir_cate_name')
                    ,DB::raw('null as sec_cate_code')
                    ,DB::raw('"' . $remainContent . '" as sec_cate_name')
                    ,DB::raw('null as show_cate')
                    ,DB::raw('"Y" as ts_has_yn')
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
                    'TS.TS_CODE as ts_code'
                    ,'TS.' . $tsContent . ' as ts_name'
                    ,'TS.TS_PRICE as ts_price'
                    ,'TS.TS_TYPE as ts_type'
//                    ,'TS.TS_DETAIL_TOTAL_PRICE as event_ts_price'
                    ,DB::raw("if(TS.TS_TYPE = '멤버쉽', TS_TOTAL_PRICE, TS.TS_DETAIL_TOTAL_PRICE) as event_ts_price")
                    ,'TS.TS_CATEGORY1_SQ as fir_cate_code'
                    ,'CB.' . $cateContent . ' as fir_cate_name'
                    ,'TS.TS_CATEGORY2_SQ as sec_cate_code'
                    ,'CB2.' . $cateContent . ' as sec_cate_name'
                    ,'TS.TS_IS_ORI_DISPLAY as show_cate'
                    ,DB::raw('null as cti_code')
                )
                    ->leftjoin('CRM2_CONFIG_BASE as CB', 'CB.CB_SQ', 'TS.TS_CATEGORY1_SQ')
                    ->leftjoin('CRM2_CONFIG_BASE as CB2', 'CB2.CB_SQ', 'TS.TS_CATEGORY2_SQ')
                    ->where('TS.TS_CODE', $value)
                    //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                    ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                    ->first();
            }

            if (!is_null($productResult)) {
                //사용 가능한 쿠폰이 있는지 확인
                if (isset($benefitData)) {
                    $benefitList = $benefitData->where('cp_dc_type', config('code.CP_DISCOUNT_TYPE')['03'])->where('cp_dc_ts_code', $productResult->ts_code);
                    //주문금액 쿠폰 미노출
                    //$benefitList = $benefitList->mergeRecursive($benefitData->where('cp_dc_type', '주문금액'));
                    $benefitList = $benefitList->mergeRecursive($benefitData->where('cp_dc_type', config('code.CP_DISCOUNT_TYPE')['02'])->where('sec_cate_code', $productResult->sec_cate_code));
                    //사용 가능한 쿠폰 정보
                    $productResult->benefit_list = $benefitList;

                    if (!empty($productBenefit[$key])) {
                        //사용한 쿠폰 정보
                        $productBenefitUseList = explode('|', $productBenefit[$key]);
                        $productResult->benefit_use_list = $benefitData->whereIn('cpc_code', $productBenefitUseList);
                    }
                }

                //잔여 시술 여부
                $productResult->remain_yn = $remainCheck ? 'Y' : 'N';

                //상품 정보 설정
                $result['list'][$productResult->sec_cate_code][] = $productResult;
                $result['total_count'] += 1;
            }
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
                    'TS.TS_CODE as ts_code'
                    ,'TS.' . $tsContent . ' as ts_name'
                    ,'TS.TS_DETAIL_TOTAL_PRICE as ts_price'
                    ,'TS.TS_TYPE as ts_type'
                    ,'TSED.TSED_PRICE as event_ts_price'
                    ,DB::raw('"event" as fir_cate_code')
                    ,DB::raw('"' . $eventContent . '" as fir_cate_name')
                    ,'TSE.TSE_CODE as sec_cate_code'
                    ,'TSE.' . $tseContent . ' as sec_cate_name'
                    ,'TS.TS_IS_ORI_DISPLAY as show_cate'
                    ,DB::raw('null as cti_code')
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
                    //->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)WEB(,|$)'")
                    ->whereRaw("TS.TS_DISPLAY REGEXP '(^|,)" . $displayType . "(,|$)'")
                    ->where('TSE.TSE_START_DATETIME', '<=', now())
                    ->where('TSE.TSE_END_DATETIME', '>=', now())
                    ->where('TSED.TSE_CODE', $eventCategory[$key])
                    ->where('TSED.TS_CODE', $value)
                    ->first();

                //이벤트 상품 있는지 확인
                if (!is_null($eventResult)) {
                    //사용가능한 쿠폰이 있는지 확인
                    if (isset($benefitData)) {
                        $eventBenefitList = $benefitData->where('cp_dc_type', config('code.CP_DISCOUNT_TYPE')['03'])->where('cp_dc_ts_code', $eventResult->TS_CODE);
                        //주문금액 쿠폰 미노출
                        //$eventBenefitList = $eventBenefitList->mergeRecursive($benefitData->where('cp_dc_type', '주문금액'));
                        $eventBenefitList = $eventBenefitList->mergeRecursive($benefitData->where('cp_dc_type', config('code.CP_DISCOUNT_TYPE')['05'])->where('event_code', $eventResult->TSE_CODE));
                        //카테고리에도 노출하는 상품일 때, 체크
                        if ($eventResult->TS_IS_ORI_DISPLAY) {
                            $eventBenefitList = $eventBenefitList->mergeRecursive($benefitData->where('cp_dc_type', config('code.CP_DISCOUNT_TYPE')['02'])->where('sec_cate_code', $eventResult->TS_CATEGORY2_SQ));
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
                }
            }
        }

        return response()->json($result);
    }

    /**
     * 미방문 여부 조회
     * @return void
     */
    public function getUserNonReservationChecked($connection, $cPublicCi, $rNumber)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $data = $table->join('CRM2_RESERVE as RES', 'RES.C_NUMBER', 'CUS.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('RES.R_NUMBER', $rNumber)
            ->where('RES.R_STATE', '미방문')
            ->count();

        $result = [
            'nonReservationChecked' => $data > 0 ? 'true' : 'false'
        ];

        return response()->json($result);
    }

    /**
     * 사용자 혜택 쿠폰 발급 여부 조회
     * @return void
     */
    public function getUserHasBenefitChecked($connection, $cPublicCi, $cpNo)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $data = $table->join('CRM2_COUPON_CUSTOMER AS CC', 'CUS.C_NUMBER', 'CC.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('CC.CP_SQ', $cpNo)
            ->exists();

        $result = [
            'has' => $data
        ];

        return response()->json($result);
    }

    public function getHoliday($connection, Request $request)
    {
        $locale = $this->setLocale($request);

        $model = new ConfigHolidayModel();
        $table = $this->changeConnection($connection, $model);

        $toDay = date('Y-m-d');
        $endDay = date('Y-m-d', strtotime('+ 90 days'));

        $y = $this->changeLocaleYoil($locale);

        $result = $table->select(
            'CH_DATE as ch_date'
            ,'CH_NM as ch_name'
            ,'CH_IS as ch_is'
            ,'CH_IS_RESERVATION as ch_is_reservation'
        )
            ->where('CH_DATE', '>=', $toDay)
            ->where('CH_DATE', '<', $endDay)
            ->get();

        foreach ($result as $item) {
            $item->ch_yoil = $y[date('w', strtotime($item->ch_date))];
            $item->ch_day = date('d', strtotime($item->ch_date));
        }

        return response()->json($result);
    }

    public function getCheckUser($connection, Request $request)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $count = $table->where('C_NAME', $request->name)
            ->where('C_HP', $request->number)
            ->where('C_DELETE_DATETIME', null)
            ->count();

        $cNumber = '';
        $cPublicCi = '';

        if ($count == 1) {
            $data = $table->where('C_NAME', $request->name)
                ->where('C_HP', $request->number)
                ->where('C_DELETE_DATETIME', null)
                ->first();

            $cNumber = $data->C_NUMBER;
            $cPublicCi = $data->C_PUBLIC_CI;
        }

        $result = [
            'cNumber' => $cNumber
            ,'cnt' => $count
            ,'cPublicCi' => $cPublicCi
        ];

        return response()->json($result);
    }

    public function getUsedCheck($connection, $cPublicCi, Request $request)
    {
        $model = new CustomerModel();
        $table = $this->changeConnection($connection, $model);

        $benefit = $request->input('benefit', []);
        $tsCode = $request->input('event', []);

        $data = $table->select(
            'CC.CPC_IS_USE'
        )
            ->join('CRM2_COUPON_CUSTOMER AS CC', 'CUS.C_NUMBER', 'CC.C_NUMBER')
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->whereIn('CC.CPC_SQ', $benefit)
            ->where('CC.CPC_IS_USE', 1)
            ->count();

        $eventPay = $table->select('RPTS.*')
            ->leftjoin('CRM2_REGISTER_PAY as RP', 'RP.C_NUMBER', 'CUS.C_NUMBER')
            ->join('CRM2_REGISTER_PAY_TREATMENT_SHOP as RPTS', 'RP.RTP_NUMBER', 'RPTS.RTP_NUMBER')
            ->join('CRM2_TREATMENT_SHOP_EVENT as TSE', 'RPTS.RTPTS_EVENT_CODE', 'TSE.TSE_CODE')
            ->where('TSE.TSE_OPTION', config('code.TSE_OPTION')['04'])
            ->where('CUS.C_PUBLIC_CI', $cPublicCi)
            ->where('RPTS.RTPTS_STATE', config('code.RTPTS_STATE')['02'])
            ->whereIn('RPTS.TS_CODE', $tsCode)
            ->count();

        $result = [
            'benefit' => isset($data) && $data == 0 ? 'notUsed' : 'used',
            'eventUsed' => isset($eventPay) && $eventPay == 0 ? 'notUsed' : 'used'
        ];

        return response()->json($result);
    }

}
