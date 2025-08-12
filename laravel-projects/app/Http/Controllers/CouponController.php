<?php

namespace App\Http\Controllers;

use App\Models\CouponCustomerModel;
use App\Models\Coupon_Model;

use Illuminate\Http\Request;


class CouponController extends Controller
{
    public function __construct()
    {
        //parent::__construct();

        //$this->middleware('auth', ['except' => ['index', 'show']]);
    }


    public function eventCoupon($publicCi = null)
    {
//        Log::error("asdf - ".$publicCi);
        /*
         * 해당 API는 event 전용이라서
         * CPC_DISCOUNT_TYPE = 주문금액, '' 값만 체크
         */
        if ( isset($publicCi) ) {
            $dataResult = Coupon_Model::couponListDataEvent($publicCi);
        } else {
            $dataResult = array();
        }

        return response()->json(['result' => $dataResult]);
    }

    public function normalCoupon($publicCi = null)
    {
//        Log::error("asdf - ".$publicCi);
        /*
         * 일반 카테고리의 경우 이벤트 상품도 나오기때문에 모든 쿠폰 타입 조회
         */
        if ( isset($publicCi) ) {
            $dataResult = Coupon_Model::couponListDataNormal($publicCi);
        } else {
            $dataResult = array();
        }

        return response()->json(['result' => $dataResult]);
    }

    public function getCouponList($connection, $cPublicCi, $type)
    {
        $model = new CouponCustomerModel();
        $table = $this->changeConnection($connection, $model);

        $result = $table->leftJoin('CRM2_COUPON AS CP', 'CP.CP_SQ', 'CC.CP_SQ')
            ->join('CRM2_CUSTOMER AS CUS', function ($q) use ($cPublicCi) {
                $q->on('CUS.C_NUMBER', 'CC.C_NUMBER')
                    ->where('CUS.C_PUBLIC_CI', $cPublicCi);
            })
            ->where("CC.CPC_IS_USE", 0)
            ->when($type != '00', function ($q) use ($type) {
                $discountType = config('code.CP_DISCOUNT_TYPE')[$type] ?? config('code.CP_DISCOUNT_TYPE')['01'];
                $q->where("CP.CP_DISCOUNT_TYPE", $discountType);
            })
            ->get();

        return response()->json(['result' => $result]);
    }
}
