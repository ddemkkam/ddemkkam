<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ApiHostNamu
{
	public $api_url;
	public $namu_api;
	public $home_api;

	public function __construct() {
		$this->CI =& get_instance();
		$protocol = 'https://';
		$this->CI->load->model( '/home_admin/Branch_model', 'Branch_model' );
		$branch = $this->getDomainBranch();

		$this->namu_api = $protocol . $branch . '.api.namucrm.com';
		$this->home_api = getenv('API_URL') . '/api/' . $branch;
	}

	public function getDomainBranch()
	{
		$domain = $_SERVER['SERVER_NAME'];
		$data = $this->CI->Branch_model->getBranch($domain);

		return isset($data) ? $data->BRANCH : getenv('TEST_BRANCH');
	}

	public function apiUrlCheck()
	{
		$this->CI =& get_instance();
		$url = '';

		if ( strpos( $_SERVER['SERVER_NAME'], 'local' ) !== false || strpos( $_SERVER['SERVER_NAME'], 'dev' ) !== false || strpos( $_SERVER['SERVER_NAME'], 'test' ) !== false ) {
//			$url = ' https://ppeum09.api.namucrm.com';
			$url = '203.245.30.242';
			$url = 'https://ppeumtest.api.namucrm.com';
		} else if (
			strpos( $_SERVER['SERVER_NAME'], 'ppeum9' ) !== false
			|| strpos( $_SERVER['SERVER_NAME'], 'ppeum09' ) !== false
			|| strpos( $_SERVER['SERVER_NAME'], 'gangnam' ) !== false ) {
			$url = 'https://ppeum09.api.namucrm.com';
		} else if (
			strpos( $_SERVER['SERVER_NAME'], 'ppeum20' ) !== false
			|| strpos( $_SERVER['SERVER_NAME'], 'busan' ) !== false ) {
			//$url = 'https://ppeum20.api.namucrm.com';
			$url = 'https://ppeumtest.api.namucrm.com';
		}

		$this->api_url = $url;

		return $url;
	}

	public function getCurl($url, $data = null)
	{
		if ( isset($data) && $data !== '' ) {
			$c_url = $url . "?" . http_build_query($data);
		} else {
			$c_url = $url;
		}
//		echo $c_url;

		$ch = curl_init();                                 //curl 초기화
		curl_setopt($ch, CURLOPT_URL, $c_url);               //URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);      //connection timeout 5초
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array("accept: application/json", "content-type: application/json"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//		curl_setopt($ch, CURLOPT_PORT , 3003);
//		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
//		print_r($ch);
//		echo "<pre>"; print_r($info); echo "</pre>";
		curl_close($ch);

		return $response;
	}

	public function putCurl($url, $jsonData)
	{
		$ch = curl_init();                                 //curl 초기화
		curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);      //connection timeout 5초
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array("accept: application/json", "content-type: application/json"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	// 내부 api start

	// 1depth category start
	public function getCategory1Depth()
	{
		$sendUrl = getenv('API_URL')."/api/category1";
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategory1DepthParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData['main'][$index]['uid'] = $row->uid;
			$cData['main'][$index]['name'] = $row->name;
		}

		return $cData;
	}
	// 1depth category end

	// event category start
	public function getCategoryEventDepth()
	{
		$sendUrl = getenv('API_URL')."/api/categoryEvent";
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategoryEventDepthParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		$ii = 0;
		foreach ( $jsonCdata as $index => $row ) {
			if ( isset($row->product_code) ) {
				$cData[$ii]['DEPTH1SQ'] = 'event';
				$cData[$ii]['DEPTH2SQ'] = $row->event_code;
				$cData[$ii]['UID'] = $row->event_code;
				$cData[$ii]['EVENT_NAME'] = $row->event_name;
				$cData[$ii]['P_CODE'] = $row->product_code;
				$cData[$ii]['EVENT_PRICE'] = $row->low_price;
				$cData[$ii]['OP_NM'] = $row->TI_NM;
				$cData[$ii]['PRICE'] = $row->TI_PRICE;
				$cData[$ii]['CONTENT'] = $row->TC_CONTENT;
				$cData[$ii]['DESCRIPTION'] = $row->TC_DESCRIPTION_JSON;
				$cData[$ii]['HASHTAG'] = explode('#', $row->TC_HASHTAG);
				$ii++;
			}
		}

		return $cData;
	}
	// event category end


	// event category start
	public function getCategorySubDepth($uid)
	{
		$sendUrl = getenv('API_URL')."/api/category2/".$uid;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategorySubDepthParse($jsonRes, $uid1)
	{
		$jsonCdata = $jsonRes->result;
		$ii = 0;
		foreach ( $jsonCdata as $index => $row ) {
			if ( isset($row->product_code) ) {
				$cData[$ii]['DEPTH1SQ'] = $uid1;
				$cData[$ii]['DEPTH2SQ'] = $row->event_code;
				$cData[$ii]['UID'] = $row->event_code;
				$cData[$ii]['EVENT_NAME'] = $row->event_name;
				$cData[$ii]['P_CODE'] = $row->product_code;
				$cData[$ii]['EVENT_PRICE'] = $row->low_price;
				$cData[$ii]['OP_NM'] = $row->TI_NM;
				$cData[$ii]['PRICE'] = $row->TI_PRICE;
				$cData[$ii]['CONTENT'] = $row->TC_CONTENT;
				$cData[$ii]['DESCRIPTION'] = $row->TC_DESCRIPTION_JSON;
				$cData[$ii]['HASHTAG'] = explode('#', $row->TC_HASHTAG);
				$ii++;
			}
		}

		return $cData;
	}
	// event category end

	// event product start
	public function getCategoryEventProduct($eventCode)
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/categoryEventProduct/".$eventCode;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategoryEventProductParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['TSE_CODE'] = $row->TSE_CODE;
			$cData[$index]['TS_CODE'] = $row->TS_CODE;
			$cData[$index]['CODE'] = $row->TSE_CODE.'_'.$row->TS_CODE;
			$cData[$index]['TSED_DISCOUNT'] = $row->TSED_DISCOUNT;
			$cData[$index]['TSED_PRICE'] = $row->TSED_PRICE;
			$cData[$index]['TS_NM'] = $row->TS_NM;
			$cData[$index]['TS_PRICE'] = $row->TS_PRICE;
		}

		return $cData;
	}

	public function getCategoryEventCouponData()
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/categoryEventCoupon/".$this->CI->session->userdata('M_PUBLIC_CI');

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategoryEventCouponParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['CPC_SQ'] = $row->CPC_SQ;
			$cData[$index]['CPC_NAME'] = $row->CPC_NAME;
			$cData[$index]['CPC_DISCOUNT_TYPE'] = $row->CP_DISCOUNT_TYPE;
			$cData[$index]['CPC_IS_OVERLAP'] = $row->CPC_IS_OVERLAP;
			$cData[$index]['CPC_DISCOUNT_PRICE_TYPE'] = $row->CPC_DISCOUNT_PRICE_TYPE;
			$cData[$index]['CPC_DISCOUNT_PER'] = $row->CPC_DISCOUNT_PER;
			$cData[$index]['CPC_DISCOUNT_PRICE'] = $row->CPC_DISCOUNT_PRICE;
			$cData[$index]['CPC_DISCOUNT_MAX_PRICE'] = $row->CPC_DISCOUNT_MAX_PRICE;
			$cData[$index]['CPC_DISCOUNT_MIN_PRICE'] = $row->CPC_DISCOUNT_MIN_PRICE;
			$cData[$index]['CPC_DISCOUNT_CATEGORY1_SQ'] = $row->CPC_DISCOUNT_CATEGORY1_SQ;
			$cData[$index]['CPC_DISCOUNT_CATEGORY2_SQ'] = $row->CPC_DISCOUNT_CATEGORY2_SQ;
			$cData[$index]['CPC_DISCOUNT_SHOP_CODE'] = $row->CPC_DISCOUNT_SHOP_CODE;
			$cData[$index]['CPC_DISCOUNT_EVENT_CODE'] = $row->CP_DISCOUNT_EVENT_CODE;
		}

		return $cData;
	}
	// event product end

	// sub product start
	public function getCategorySubProduct($cate1, $cate2)
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/categorySubProduct/".$cate1."/".$cate2;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategorySubProductParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['TSE_CODE'] = $row->TSE_CODE;
			$cData[$index]['TS_CODE'] = $row->TS_CODE;
			$cData[$index]['CODE'] = isset($row->TSE_CODE) && $row->TSE_CODE !== '' ? $row->TSE_CODE.'_'.$row->TS_CODE : $row->TS_CODE;
			$cData[$index]['TSED_DISCOUNT'] = $row->TSED_DISCOUNT;
			$cData[$index]['TSED_PRICE'] = $row->TSED_PRICE;
			$cData[$index]['TS_NM'] = $row->TS_NM;
			$cData[$index]['TS_PRICE'] = $row->TS_PRICE;
		}

		return $cData;
	}

	public function getCategoryNormalCouponData()
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/categoryNormalCoupon/".$this->CI->session->userdata('M_PUBLIC_CI');

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getCategoryNormalCouponParse()
	{

	}
	// sub product end

	public function getCouponParseList($couponData, $productData, $category1_uid = null, $category2_uid = null)
	{
		/*
		 * 사용가능한 쿠폰 파싱
		 * 1. discount_type 주문금액 - 모든 카테고리 사용
		 * 2. discount_type 단품할인 - 해당 상품만 사용
		 * 3. discount_type 카테고리할인 - 해당 카테고리의 모든 상품 사용
		 * 4. discount_type 이벤트할인 - 이벤트에 속한 상품 사용
		 */
//		echo "<pre>"; print_r($productData); echo "</pre>";
		$i = 0;
		foreach ( $couponData as $index => $row ) {
			if ( $row['CPC_DISCOUNT_TYPE'] === '주문금액' ) {
				// 1. discount_type 주문금액 - 모든 카테고리 사용
				$cData[$i] = $row;
				$i++;
			} else if ( $row['CPC_DISCOUNT_TYPE'] === '단품할인' ) {
				// 2. discount_type 단품할인 - 해당 상품만 사용
				foreach ( $productData as $index2 => $row2 ) {
//					echo '------------------------------------';
//					echo '<pre>'; print_r($row2); echo '</pre>';
//					echo '------------------------------------';
					if ( $row['CPC_DISCOUNT_SHOP_CODE'] == $row2['CODE'] ) {
						$cData[$i] = $row;
						$i++;
						break;
					}

				}
			} else if ( $row['CPC_DISCOUNT_TYPE'] === '카테고리할인' ) {
				/*
				 * 3. discount_type 카테고리할인 - 해당 상품만 사용
				 * 3-1. 카테고리1, 카테고리2 체크
				 */
//				foreach ( $productData as $index2 => $row2 ) {
					if ( $row['CPC_DISCOUNT_CATEGORY1_SQ'] == $category1_uid && $row['CPC_DISCOUNT_CATEGORY2_SQ'] == $category2_uid ) {

						$cData[$i] = $row;
						$i++;
//						break;
					}
//				}
			} else if ( $row['CPC_DISCOUNT_TYPE'] == '이벤트할인' && $category1_uid == 'event' ) {
				// 4. discount_type 이벤트할인 - 이벤트에 속한 상품 사용
//				echo $row['CPC_DISCOUNT_CATEGORY1_SQ'].' - '.$row['CPC_DISCOUNT_EVENT_CODE'];
				if ( $row['CPC_DISCOUNT_EVENT_CODE'] == $category2_uid ) {
					$cData[$i] = $row;
					$i++;
				}
			} else if ( $row['CPC_DISCOUNT_TYPE'] === '무료이용권' ) {

			} else {

			}

		}

		return $cData;

	}

	public function proCouponMapList($pData, $cData)
	{
		foreach ( $pData as $index => $row ) {
			$pData[$index]['couponList'] = array();

			foreach ( $cData as $index2 => $row2 ) {
				if ( $row2['CPC_DISCOUNT_TYPE'] === '주문금액' ) {
					array_push( $pData[$index]['couponList'], $row2 );
				} else if ( $row2['CPC_DISCOUNT_TYPE'] === '카테고리할인' ) {
					array_push( $pData[$index]['couponList'], $row2 );
				} else if ( $row2['CPC_DISCOUNT_TYPE'] === '단품할인' ) {
					if ( $row['CODE'] == $row2['CPC_DISCOUNT_SHOP_CODE'] ) {
						array_push($pData[$index]['couponList'], $row2);
					}
				} else if ( $row2['CPC_DISCOUNT_TYPE'] === '이벤트할인' ) {
					array_push( $pData[$index]['couponList'], $row2 );
				}
				// 무료이용권, 이벤트 할인 제외 (추후 작업 필요)
			}
		}

		return $pData;
	}

	public function getBasketData($B_BRANCH = null, $M_PUBLIC_CI = null)
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/basketProduct/".$M_PUBLIC_CI.'/'.$B_BRANCH;
//		echo $sendUrl; exit();

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	//BASEKET DATA PARSE
	public function basketDataParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['TSE_CODE'] = $row->TSE_CODE;
			$cData[$index]['TS_CODE'] = $row->TS_CODE;
			$cData[$index]['CODE'] = $row->CODE;
			$cData[$index]['TSE_SUBJECT'] = $row->TSE_SUBJECT;
			$cData[$index]['TSED_DISCOUNT'] = $row->TSED_DISCOUNT;
			$cData[$index]['TSED_PRICE'] = $row->TSED_PRICE;
			$cData[$index]['TS_NM'] = $row->TS_NM;
			$cData[$index]['TS_PRICE'] = $row->TS_PRICE;
			$cData[$index]['TSC_CATEGORY1_SQ'] = isset($row->TSE_CODE) && $row->TSE_CODE != '' ? 'event' : $row->TSC_CATEGORY1_SQ;
			$cData[$index]['TSC_CATEGORY2_SQ'] = isset($row->TSE_CODE) && $row->TSE_CODE != '' ? $row->TSE_CODE : $row->TSC_CATEGORY2_SQ;
			$cData[$index]['TSC_CATEGORY1_NM'] = $row->TSC_CATEGORY1_NM;
			$cData[$index]['TSC_CATEGORY2_NM'] = $row->TSC_CATEGORY2_NM;
			$cData[$index]['TSE_START_DATETIME'] = $row->TSE_START_DATETIME;
			$cData[$index]['TSE_END_DATETIME'] = $row->TSE_END_DATETIME;
		}

		return $cData;
	}

	public function getHolidayData($B_BRANCH = null)
	{
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/getHoliday/".$B_BRANCH;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getHolidayDataParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['CH_SQ'] = $row->CH_SQ;
			$cData[$index]['CH_DATE_LUNAR'] = $row->CH_DATE_LUNAR;
			$cData[$index]['CH_DATE'] = $row->CH_DATE;
			$cData[$index]['CH_NM'] = $row->CH_NM;
			$cData[$index]['CH_IS'] = $row->CH_IS;
			$cData[$index]['CH_IS_RESERVATION'] = $row->CH_IS_RESERVATION;
			$cData[$index]['CH_MEMO'] = $row->CH_MEMO;
		}

		return $cData;
	}

	public function getFreeCouponData($B_BRANCH = null, $M_PUBLIC_CI = null)
	{
		if ( getenv('TYPE') == 'dev' ) {
			$B_BRANCH = getenv('TEST_BRANCH');
		}
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/".$B_BRANCH.'/categoryEventCoupon/'.$M_PUBLIC_CI.'/04';

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getFreeCouponDataParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['CPC_SQ'] = $row->CPC_SQ;
			$cData[$index]['CP_SQ'] = $row->CP_SQ;
			$cData[$index]['C_NUMBER'] = $row->C_NUMBER;
			$cData[$index]['CPC_IS_USE'] = $row->CPC_IS_USE;
			$cData[$index]['CPC_IS_OVERLAP'] = $row->CPC_IS_OVERLAP;
			$cData[$index]['CPC_NAME'] = $row->CPC_NAME;
			$cData[$index]['CPC_DISCOUNT_TYPE'] = $row->CPC_DISCOUNT_TYPE;
			$cData[$index]['CPC_START_DATE'] = $row->CPC_START_DATE;
			$cData[$index]['CPC_END_DATE'] = $row->CPC_END_DATE;
		}

		return $cData;
	}

	//마일리지
	public function getMileageData($B_BRANCH = null, $M_PUBLIC_CI = null)
	{
		if ( getenv('TYPE') == 'dev' ) {
			$B_BRANCH = getenv('TEST_BRANCH');
		}
		$this->CI =& get_instance();
		$sendUrl = getenv('API_URL')."/api/".$B_BRANCH.'/user/info/'.$M_PUBLIC_CI;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getMileageDataParse($jsonRes)
	{
		$jsonCdata = $jsonRes->result;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['CPC_SQ'] = $row->CPC_SQ;
			$cData[$index]['CP_SQ'] = $row->CP_SQ;
			$cData[$index]['C_NUMBER'] = $row->C_NUMBER;
			$cData[$index]['CPC_IS_USE'] = $row->CPC_IS_USE;
			$cData[$index]['CPC_IS_OVERLAP'] = $row->CPC_IS_OVERLAP;
			$cData[$index]['CPC_NAME'] = $row->CPC_NAME;
			$cData[$index]['CPC_DISCOUNT_TYPE'] = $row->CPC_DISCOUNT_TYPE;
			$cData[$index]['CPC_START_DATE'] = $row->CPC_START_DATE;
			$cData[$index]['CPC_END_DATE'] = $row->CPC_END_DATE;
		}

		return $cData;
	}























	// 내부 api end


	public function categoryParseDate($jsonRes)
	{
		$jsonCdata = $jsonRes->data->category;
		foreach ( $jsonCdata as $index => $row ) {
			$cData[$index]['uid'] = $row->uid;
			$cData[$index]['name'] = $row->name;
		}

		return $cData;
	}

	public function categorySubParseDate($jsonRes)
	{
		//event_first_date, event_last_date 가 없는 경우 상시로 인식. 날짜를 -1day, +1day 로 강제 파싱
		$jsonCdata = $jsonRes->data->category;
		foreach ( $jsonCdata as $index => $row ) {
			$subCategory = $row->sub_category;
			foreach ( $subCategory as $index2 => $row2 ) {
				$cData[$row->uid][$index2]['uid'] = $row2->uid;
				$cData[$row->uid][$index2]['name'] = $row2->name;
				$cData[$row->uid][$index2]['type'] = $row2->type;
				$cData[$row->uid][$index2]['event_first_date'] = $row2->event_first_date !== '' ? $row2->event_first_date : date('Y-m-d H:i:s',strtotime("-1 day"));
				$cData[$row->uid][$index2]['event_last_date'] = $row2->event_last_date !== '' ? $row2->event_last_date : date('Y-m-d H:i:s',strtotime("+1 day"));
			}
		}

		return $cData;
	}

	public function productParseDate($jsonRes)
	{
		$jsonPdata = $jsonRes->data->list;

		foreach ( $jsonPdata as $index => $row ) {
			$pdata[$index]['code'] = $row->code;
			$pdata[$index]['type'] = $row->type;
			$pdata[$index]['category1_uid'] = $row->category1_uid;
			$pdata[$index]['category1_name'] = $row->category1_name;
			$pdata[$index]['category2_uid'] = $row->category2_uid;
			$pdata[$index]['category2_name'] = $row->category2_name;
			$pdata[$index]['name'] = $row->name;
			$pdata[$index]['is_hipass'] = $row->is_hipass;
			$pdata[$index]['is_sleep'] = $row->is_sleep;
			$pdata[$index]['is_tax'] = $row->is_tax;
			$pdata[$index]['price'] = $row->price;
			$pdata[$index]['tax_price'] = $row->tax_price;
			$pdata[$index]['total_price'] = $row->total_price;
			$pdata[$index]['detail_total_price'] = $row->detail_total_price;
			$pdata[$index]['discount_price'] = $row->discount_price;
			$pdata[$index]['discount_per'] = $row->discount_per;
//			$pdata[$index]['image_url'] = $row->image_url;
//			$pdata[$index]['image_w200_url'] = $row->image_w200_url;
//			$pdata[$index]['image_w400_url'] = $row->image_w400_url;
//			$pdata[$index]['image_w600_url'] = $row->image_w600_url;

//			foreach ( $row->treatment_items as $index2 => $row2 ) {
//				$pdata[$index]['treatment_items'][$index2]['name'] = $row2->name;
//				$pdata[$index]['treatment_items'][$index2]['quantity'] = $row2->quantity;
//				$pdata[$index]['treatment_items'][$index2]['option'] = $row2->option;
//				$pdata[$index]['treatment_items'][$index2]['price'] = $row2->price;
//				$pdata[$index]['treatment_items'][$index2]['hashtag'] = $row2->hashtag;
//			}
		}

		return $pdata;

	}

	public function productDetailParseDate($jsonRes)
	{
		$jsonPdata = $jsonRes->data->detail;

		foreach ( $jsonPdata->treatment_items as $index2 => $row2 ) {
			$pddata['code'] = $jsonPdata->code;
			$pddata['name'] = $row2->name;
			$pddata['category1_uid'] = $jsonPdata->category1_uid;
			$pddata['category1_name'] = $jsonPdata->category1_name;
			$pddata['category2_uid'] = $jsonPdata->category2_uid;
			$pddata['category2_name'] = $jsonPdata->category2_name;
			$pddata['is_hipass'] = $jsonPdata->is_hipass;
			$pddata['is_sleep'] = $jsonPdata->is_sleep;
			$pddata['is_tax'] = $jsonPdata->is_tax;
			$pddata['option'] = $row2->option;
			$pddata['content'] = $row2->content;
			$pddata['hashtag'] = explode('#', $row2->hashtag);
			foreach ( $row2->description as $index3 => $row3 ) {
				$pddata['description'][$index3]['name'] = $row3->name;
				$pddata['description'][$index3]['content'] = $row3->content;
			}
		}

		return $pddata;

	}


	public function timeParseDate($jsonRes)
	{
		$jsonTdata = $jsonRes->data->config->time;

		foreach ( $jsonTdata as $index => $row ) {
			$tdata[$index]['time'] = $row->time;
			$tdata[$index]['max_count'] = $row->max_count;
			$tdata[$index]['count'] = $row->count;
			$tdata[$index]['is_reserve'] = $row->is_reserve;
		}

		return $tdata;
	}


	public function roomTimeParseDate($jsonRes, $is_counsel = null)
	{
		$jsonTdata = $jsonRes->data->config->room;

		foreach ( $jsonTdata as $index => $row ) {
			$today = date('Ymd');
//			echo $row->date.' - '.$today.'<br />';
			if ( $row->date == $today ) {
				$dbDate = strtotime($row->date.' '.$row->time.':00');
				$todayDate = strtotime("Now");
//				echo $dbDate.'<br />';
				if ( $dbDate >  $todayDate) {
					$rtdata[$index]['seq'] = $row->seq;
					$rtdata[$index]['name'] = $row->name;
					$rtdata[$index]['max_count'] = $row->max_count;
					$rtdata[$index]['date'] = $row->date;
					$rtdata[$index]['time'] = $row->time;
					$rtdata[$index]['now_count'] = $row->now_count;
					$rtdata[$index]['is_reserve'] = $row->max_count <= $row->now_count ? 0 : $row->is_reserve;
					$rtdata[$index]['treatment_minute'] = $row->treatment_minute;
				}
			} else {
				$rtdata[$index]['seq'] = $row->seq;
				$rtdata[$index]['name'] = $row->name;
				$rtdata[$index]['max_count'] = $row->max_count;
				$rtdata[$index]['date'] = $row->date;
				$rtdata[$index]['time'] = $row->time;
				$rtdata[$index]['now_count'] = $row->now_count;
				$rtdata[$index]['is_reserve'] = $row->max_count <= $row->now_count ? 0 : $row->is_reserve;
				$rtdata[$index]['treatment_minute'] = $row->treatment_minute;
			}
		}

		if ( $is_counsel == 'Y' ) {
			$ii = 0;
			foreach ($rtdata as $index => $row) {
				if ( $row['name'] == '상담실' ) {
					$returnData[$ii]['seq'] = $row['seq'];
					$returnData[$ii]['name'] = $row['name'];
					$returnData[$ii]['max_count'] = $row['max_count'];
					$returnData[$ii]['date'] = $row['date'];
					$returnData[$ii]['time'] = $row['time'];
					$returnData[$ii]['now_count'] = $row['now_count'];
					$returnData[$ii]['is_reserve'] = $row['is_reserve'];
					$returnData[$ii]['treatment_minute'] = $row['treatment_minute'];
					$ii++;
				}

			}
		} else {
			$returnData = $rtdata;
		}


		return $returnData;
	}


	public function setReservation($url, $jsonData)
	{
		$headers = array(
			"content-type: application/json",
			"accept-encoding: gzip"
		);

		//CURL함수 사용
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		//header값 셋팅(없을시 삭제해도 무방함)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//POST방식
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, true);
		//POST방식으로 넘길 데이터(JSON데이터)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);

		$response = curl_exec($ch);

//		echo "<pre>"; print_r($response); echo "<pre>"; exit();

		return $response;

	}

	public function getCouponList()
	{
		$this->CI =& get_instance();

		if ( !$this->CI->session->userdata('M_PUBLIC_CI') ) {
			return false;
		} else {
			$apiUrl = $this->getNamuApiUrl();
			$sendUrl = $apiUrl . "/v1/public/customerCoupon/" . $this->CI->session->userdata('M_PUBLIC_CI');
			$curl_Result = $this->getCurl($sendUrl);
			$jsonRes = json_decode($curl_Result);

			$couponData = $jsonRes->data->list;
			$resCoupon['totalCnt'] = count($couponData);

			$ii = 0;
			foreach ( $couponData as $index => $row ) {
				$date_possible = $row->end_date . ' 23:59:59' > date('Y-m-d H:is:s') ? 'Y' : 'N';
				if ( $row->expire === '미사용' && $date_possible === 'Y' ) {
					$cData[$ii]['seq'] = $row->seq;
					$cData[$ii]['customer_name'] = $row->customer_name;
					$cData[$ii]['is_use'] = $row->is_use ? 'Y' : 'N';
					$cData[$ii]['is_overlap'] = $row->is_overlap ? 'Y' : 'N';
					$cData[$ii]['name'] = $row->name;
					$cData[$ii]['discount_type'] = $row->discount_type;
					$cData[$ii]['discount_price_type'] = $row->discount_price_type;
					$cData[$ii]['discount_category1_seq'] = $row->discount_category1_seq;
					$cData[$ii]['discount_category1_name'] = $row->discount_category1_name;
					$cData[$ii]['discount_category2_seq'] = $row->discount_category2_seq;
					$cData[$ii]['discount_category2_name'] = $row->discount_category2_name;
					$cData[$ii]['discount_shop_code'] = $row->discount_shop_code;
					$cData[$ii]['discount_shop_name'] = $row->discount_shop_name;
					$cData[$ii]['discount_per'] = $row->discount_per;
					$cData[$ii]['discount_price'] = $row->discount_price;
					$cData[$ii]['discount_max_price'] = $row->discount_max_price;
					$cData[$ii]['discount_min_price'] = $row->discount_min_price;
					$cData[$ii]['discount_memo'] = $row->discount_memo;
					$cData[$ii]['start_date'] = $row->start_date;
					$cData[$ii]['end_date'] = $row->end_date;
					$cData[$ii]['expire'] = $row->expire;

					$ii++;
				}
			}

			$resCoupon['list'] = $cData;
//			echo "<pre>"; print_r($cData); echo "</pre>";

		}

		return $resCoupon;
	}



	public function proCouponCheckedList($resData, $couponArr)
	{
		foreach ( $resData[0]['couponList'] as $index => $row ) {
			$explodeCoupon = explode(',', $couponArr);
			foreach ( $explodeCoupon as $index2 => $val ) {
				if ( $row['seq'] === $val ) {
					$resData[0]['couponList'][$index]['checked'] = 'checked';
				}
			}
		}

		return $resData;
	}

	public function getUserBenefit($mPublicCi)
	{
		$sendUrl = $this->home_api . '/user/benefit/' . $mPublicCi;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserReservation()
	{
		$sendUrl = $this->home_api . '/user/reservation/' . $this->CI->session->userdata('M_PUBLIC_CI');
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserRemain()
	{
		$sendUrl = $this->home_api . '/user/remain/' . $this->CI->session->userdata('M_PUBLIC_CI');

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserInfo()
	{
		$sendUrl = $this->home_api . '/user/myPage/' . $this->CI->session->userdata('M_PUBLIC_CI');

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getBasket($mPublicCi, $ps)
	{
		$sendUrl = $this->home_api . '/user/basket/'  . $mPublicCi . '?' . http_build_query($ps);

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}
	public function getBasketBenefit($mPublicCi, $branch, $tsCode, $cateCode, $exception)
	{
		$sendUrl = $this->home_api . '/user/benefit/'  . $mPublicCi . '?ts_code=' . $tsCode . '&cate_code=' . $cateCode . '&exception=' . implode(',', $exception);

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getBasketProduct($mPublicCi, $branch, $cateCode)
	{
		$sendUrl = $this->home_api . '/product/' . $cateCode . '/' . $mPublicCi;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getCategory($mPublicCi)
	{
		$sendUrl = $this->home_api . '/category?cPublicCi=' . $mPublicCi;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getCategoryBranch($branch, $area)
	{
		$sendUrl = getenv('API_URL') . '/api/' . $branch . '/category?area=' . $area;

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getResHoliday()
	{
		$sendUrl = $this->home_api . '/holiday';

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getResProduct($mPublicCi, $ps)
	{
		$sendUrl = $this->home_api . '/product/' . $mPublicCi . '?' . http_build_query($ps);

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserReservationRNumber($mPublicCi, $rNumber)
	{
		$sendUrl = $this->home_api . '/user/reservation/' . $mPublicCi . '/' . $rNumber;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getHaveMileageData($mPublicCi)
	{
		$sendUrl = $this->home_api . '/user/info/' . $mPublicCi;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function getEventList()
	{
		$sendUrl = $this->home_api . '/event';
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getAdminEventList($branch, $area)
	{
		$sendUrl = getenv('API_URL') . '/api/' . $branch . '/event?admin=y&area=' . $area;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}
	public function getEventDetail($cPublicCi, $tseCode)
	{
		$sendUrl = $this->home_api . '/event/' . $tseCode . '?cPublicCi=' . $cPublicCi;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getSearch()
	{
		$sendUrl = $this->home_api . '/search';
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getTextSearch($text)
	{
		$sendUrl = $this->home_api . '/search/text?text=' . urlencode($text);

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getRankList($ts)
	{
		$sendUrl = $this->home_api . '/rank?' . http_build_query($ts);
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserCheck($parameters)
	{
		$sendUrl = $this->home_api . '/check?' . http_build_query($parameters);

		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	public function getUserLoginInfo($mPublicCi)
	{
		$sendUrl = $this->home_api . '/user/info/' . $mPublicCi;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function setCPublicCiUpdate($mId, $mPublicCi)
	{
		$data = array('public_ci' => $mPublicCi);
		$apiUrl = $this->getNamuApiUrl();
		$sendUrl = $apiUrl . "/check" . $mId;
		$curl_Result = $this->putCurl($sendUrl, json_encode($data));
		$jsonRes = json_decode($curl_Result);

		return $jsonRes;
	}

	public function usedBenefitCheck($cPublicCi, $list)
	{
		$sendUrl = $this->home_api . '/usedCheck/' . $cPublicCi . '?' . http_build_query($list);
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	/**
	 * 미방문 여부 조회
	 * @return void
	 */
	public function nonReservationChecked($cPublicCi, $rNumber)
	{
		$sendUrl = $this->home_api . '/user/nonReservation/' . $cPublicCi . '/' . $rNumber;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}

	/**
	 * 혜택 쿠폰 발급 여부 조회
	 * @return void
	 */
	public function hasBenefitChecked($cPublicCi, $cpNo)
	{
		$sendUrl = $this->home_api . '/user/hasBenefit/' . $cPublicCi . '/' . $cpNo;
		$curl_Result = $this->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result, true);

		return $jsonRes;
	}


	public function setNamuApiPost($url, $data)
	{
		$headers = array(
			"content-type: application/json",
			"accept-encoding: gzip"
		);

		$namuUrl = $this->namu_api . $url;
		$jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);

		//CURL함수 사용
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $namuUrl);
		//header값 셋팅(없을시 삭제해도 무방함)
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//POST방식
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, true);
		//POST방식으로 넘길 데이터(JSON데이터)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);

		return curl_exec($ch);
	}

	public function getNamuApi($url, $data = null)
	{
		$c_url = $this->namu_api . $url;

		if (isset($data) && $data !== '') {
			$c_url .= '?' . http_build_query($data);
		}

		$ch = curl_init();                                 //curl 초기화
		curl_setopt($ch, CURLOPT_URL, $c_url);               //URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);      //connection timeout 5초
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array("accept: application/json", "content-type: application/json"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	/**
	 * 나무 API 경로 조회
	 * @return string
	 */
	public function getNamuApiUrl()
	{
		return $this->namu_api;
	}

	/**
	 * 홈 API 경로 조회
	 * @return string
	 */
	public function getHomeApiUrl()
	{
		return $this->home_api;
	}
}
?>
