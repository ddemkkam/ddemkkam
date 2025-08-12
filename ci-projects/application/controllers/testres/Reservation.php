<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation extends CI_Controller
{
	public $menu = 'menu2';
	public $nav = array('navTitle' => '검색순위 설정', 'navLink1' => '/home_admin/rankset', 'navTitle2' => '', 'navLink2' => '');
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode'));

//		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();
	}

	public function index()
	{
//		$this->ApiHostNamu = new ApiHostNamu();
//		$api_url = $this->ApiHostNamu->apiUrlCheck();
//		echo $api_url;
		$categoryData = $this->getApiCategoryList( $this->api_url );

		$result['mainCategory'] = $categoryData['main'];
		$result['subCategory'] = $categoryData['sub'];

//		echo "<pre>"; print_r($result['subCategory']); echo "<pre>"; exit();

		$this->load->view('/test/testres', $result);
	}


	public function getApiCategoryList( $api_url = null )
	{
		$sendUrl = $api_url."/v1/public/treatmentShopCategory";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);
//		echo "<pre>"; print_r($jsonRes->data->category); echo "<pre>"; exit();

		if ( $jsonRes->code === 200 ) {
			$cData['main'] = $this->ApiHostNamu->categoryParseDate($jsonRes);

			$cData['sub'] = $this->ApiHostNamu->categorySubParseDate($jsonRes);
		} else {
			$this->namuErrorCode = new NamuErrorCode();
			$rData = $this->namuErrorCode->errorCode($jsonRes);
		}

//		echo "<pre>"; print_r($cData); echo "<pre>"; exit();
		return $cData;
	}

	public function getApiProductList( $api_url = null )
	{
		$data['device'] = 'web';
		$data['language'] = 'ko';
		$data['category1'] = $this->input->get('category1');
		$data['category2'] = $this->input->get('category2');

		$api_url = $this->api_url;
		$sendUrl = $api_url."/v1/public/treatmentShop";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, $data);
		$jsonRes = json_decode($curl_Result);

		if ( $jsonRes->code === 200 ) {
			$pData = $this->ApiHostNamu->productParseDate($jsonRes);
		}

		$result['pData'] = $pData;

//		echo "<pre>"; print_r($pData); echo "<pre>"; exit();
		$this->load->view('/test/testproduct', $result);
	}

	public function getTimeData()
	{
		$data['date'] = $this->input->get('date');

		$api_url = $this->api_url;
		$sendUrl = $api_url."/v1/public/reserveConfig/time";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, $data);
		$jsonRes = json_decode($curl_Result);

		if ( $jsonRes->code === 200 ) {
			$tData = $this->ApiHostNamu->timeParseDate($jsonRes);
		}

//		echo "<pre>"; print_r($tData); echo "<pre>"; exit();
//		$this->load->view('/test/testtime', $result);
	}


	public function getRoomTimeData()
	{
		$data['date'] = $this->input->get('date');
		$data['is_counsel'] = $this->input->get('is_counsel');
		$treatment_shop = $this->input->get('treatment_shop');
		$data['start_time'] = "10:00";
		$data['end_time'] = "20:00";
		$data['treatment_item'] = "";
		$data['public_ci'] = "";

		$treatment_shop_arr = array();
		foreach ( $treatment_shop as $index => $val ) {
			if ( strpos( $val, '_' ) !== false ) {
				$explodeData = explode( '_', $val );
				$treatment_shop_arr[$index] = $explodeData[0];
			} else {
				$treatment_shop_arr[$index] = $val;
			}
		}
		$data['treatment_shop'] = implode(",", $treatment_shop_arr);

//		echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$api_url = $this->api_url;
		$sendUrl = $api_url."/v1/public/reserveConfig/room";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, $data);
		$jsonRes = json_decode($curl_Result);
//		echo "<pre>"; print_r($jsonRes); echo "<pre>"; exit();

		if ( $jsonRes->code === 200 ) {
			$result['rtData'] = $this->ApiHostNamu->roomTimeParseDate($jsonRes);
		}

//		echo "<pre>"; print_r($result['rtData']); echo "<pre>"; exit();
		$this->load->view('/test/testroom', $result);
	}


	public function setReservation()
	{
//		$res_data = $this->input->post('res_data');

		$treament_shop = $this->input->post('treament_shop');
		foreach ( $treament_shop as $index => $val ) {
			$treament_shopArr[$index]['code'] = $val;
			$treament_shopArr[$index]['quantity'] = 1;
		}

		$data['public_ci'] = $this->input->post('public_ci');
		$data['reserve_type'] = $this->input->post('reserve_type');
		$data['reserve_date'] = $this->input->post('reserve_date');
		$data['reserve_time'] = $this->input->post('reserve_time');
		$data['room_seq'] = $this->input->post('room_seq');
		$data['is_counsel'] = $this->input->post('is_counsel');
//		$data['memo'] = $this->input->post('memo');
		$data['memo'] = '';
		$data['treament_shop'] = $treament_shopArr;

		$send_data = json_encode($data, JSON_UNESCAPED_UNICODE);
//		echo "<pre>"; print_r($send_data); echo "<pre>"; exit();

		$api_url = $this->api_url;
		$sendUrl = $api_url."/v1/public/reserve";
		$curl_Result = $this->ApiHostNamu->setReservation($sendUrl, $send_data);
		$jsonRes = json_decode($curl_Result);

//		$returnData = json_decode($jsonRes);

		if ( $jsonRes->code === 201 ) {
			echo "<script>alert('예약되었습니다.'); location.reload();</script>"; exit();
		}

	}


}
