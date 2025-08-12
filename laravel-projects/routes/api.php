<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EventController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//지점별 데이터베이스 설정
Route::prefix('{connection}')->group(function () {
    //검색
    Route::prefix('search')->group(function () {
        Route::get('/', [ProductController::class, 'getSearch']);
        Route::get('text', [ProductController::class, 'getTextSearch']);
    });

    Route::get('rank', [ProductController::class, 'getRank']);

    //사용자 정보
    Route::prefix('user')->group(function () {
        Route::get('info/{cPublicCi}', [UserController::class, 'getUserInfo']);

        Route::get('appointment/{cPublicCi}/{rNumber}', [UserController::class, 'getUserAppointment']);
        //사용자 혜택 정보 조회
        Route::get('benefit/{cPublicCi}', [UserController::class, 'getUserBenefit']);
        //사용자 예약 정보 조회
        Route::get('reservation/{cPublicCi}', [UserController::class, 'getUserReservation2']);
        Route::get('reservation/{cPublicCi}/{rNumber}', [UserController::class, 'getUserReservationRNumber']);
        //사용자 잔여시술 정보 조회
        Route::get('remain/{cPublicCi}', [UserController::class, 'getUserRemain']);
        //사용자 마이페이지 정보 조회
        Route::get('myPage/{cPublicCi}', [UserController::class, 'getUserMyPage']);
        //사용자 장바구니 정보 조회
        Route::get('basket/{cPublicCi}', [UserController::class, 'getUserBasket']);
        //사용자 미방문 정보 조회
        Route::get('nonReservation/{cPublicCi}/{rNumber}', [UserController::class, 'getUserNonReservationChecked']);
        //사용자 혜택 쿠폰 보유 여부 조회
        Route::get('hasBenefit/{cPublicCi}/{cpNo}', [UserController::class, 'getUserHasBenefitChecked']);

    });

    Route::prefix('product')->group(function () {
        Route::get('{cPublicCi}', [ProductController::class, 'getProductList']);
        Route::get('/{cate}/{cPublicCi}', [ProductController::class, 'getProduct']);
    });

    Route::prefix('event')->group(function () {
        Route::get('/', [EventController::class, 'getEventList']);
        Route::get('{tseCode}', [EventController::class, 'getEventDetail']);
    });

    //카테고리 정보
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'getCategory']);
        Route::get('/{group1}', [CategoryController::class, 'getFirstDepthCategory']);
        Route::get('/{group1}/{group2}', [CategoryController::class, 'getSecondDepthCategory']);
    });

    //병원 운영일 조회
    Route::get('holiday', [UserController::class, 'getHoliday']);
    //예약전 1회 최저가 이벤트, 사용가능쿠폰 여부 조회
    Route::get('usedCheck/{cPublicCi}', [UserController::class, 'getUsedCheck']);


    Route::get('/categoryEventCoupon/{publicCi}/{type}',[CouponController::class, 'getCouponList']);


    Route::get('check', [UserController::class, 'getCheckUser']);

});

Route::get('/category1','App\Http\Controllers\CategoryController@category1depth');//->name('test');

Route::get('/category2/{uid}','App\Http\Controllers\CategoryController@category2depth');

// 이벤트 카테고리
Route::get('/categoryEvent','App\Http\Controllers\CategoryController@categoryEvent');
// 이벤트 상품
Route::get('/categoryEventProduct/{code}','App\Http\Controllers\CategoryController@categoryEventProduct');
// 이벤트 쿠폰
Route::get('/categoryEventCoupon/{publicCi}','App\Http\Controllers\CouponController@eventCoupon');

// 일반 카테고리 상품
Route::get('/categorySubProduct/{cate1}/{cate2}','App\Http\Controllers\CategoryController@categorySubProduct');

// 장바구니 상품
Route::get('/basketProduct/{publicCi}/{branch}','App\Http\Controllers\ProductController@getBasketDataList');
