<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends CI_Controller
{
	public $menu = '장바구니';
	private $BasketModel;
	private $BranchInfo;
	private $ApiHostNamu;
	private $BasketInfo;
	private $publicCi;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Basket_model');

		$this->BasketModel = new Basket_model();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BasketInfo = new BasketInfo();

		//사용자 조회
		$this->publicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	/*public function registBasket()
	{
		$this->load->helper('cookie');

		$this->BranchInfo = new BranchInfo();
		$data['branch'] = $this->BranchInfo->getBranch();

		$basketArr = $this->input->post('basketArr');
		$couponArr = $this->input->post('couponArr');
		$category1 = $this->input->post('category1');
		$category2 = $this->input->post('category2');
		$type = $this->input->post('type');
//		echo 'asdf - '.$type; exit();

		// 비회원이 장바구니를 이용한 경우
		if ( !$this->session->userdata('M_PUBLIC_CI') ) {
			//1. 비회원일 경우 쿠키 사용
			$randomStringID = 'B_'.random_string('basic', 12);
			$cookieBasketArr = $this->input->cookie('basketCookie');
//			$basketData = $this->BasketModel->getBasketData($M_PUBLIC_CI, $data['branch']);

			if ( !isset($cookieBasketArr) && $cookieBasketArr == '' ) {
				// 3-1. 기존의 저장된 쿠키가 없다면 new 쿠키 저장
				$basketCookie = array(
					'name'   => 'basketCookie',
					//'value'  => json_encode($basketArr),
					'value' => $randomStringID,
					'expire' => '86500',
				);

				$M_PUBLIC_CI = $randomStringID;
			} else {
				$basketCookie = array(
					'name'   => 'basketCookie',
					//'value'  => json_encode($basketArr),
					'value' => $cookieBasketArr,
					'expire' => '86500',
				);

				// 기존 쿠키 삭제후 새로 저장
				delete_cookie('basketCookie');

				$M_PUBLIC_CI = $cookieBasketArr;
			}

			$this->input->set_cookie($basketCookie);

		} else {
			// 회원일 경우
			$M_PUBLIC_CI = $this->session->userdata('M_PUBLIC_CI');
		}

		// 1. 회원의 장바구니 검색
		$basketData = $this->BasketModel->getBasketData($M_PUBLIC_CI, $data['branch']);

		//경로가 장바구니인 경우 해당 카테고리 삭제후 추가
		if ( $type == 'basket' ) {
			$this->BasketModel->deleteBasketData($M_PUBLIC_CI, $data['branch'], $category1, $category2);
		}

		// 5. 새로운 장바구니 상품 추가
		$insData['B_BRANCH'] = $data['branch'];
		$insData['B_PUBLIC_CI'] = $M_PUBLIC_CI;
		foreach ( $basketArr as $index => $val ) {
			$insData['B_PRODUCT_ID'] = $val;
			$insData['B_COUPON'] = $couponArr[$index];
			$insData['B_CATEGORY1'] = $category1;
			$insData['B_CATEGORY2'] = $category2;
//				echo "<pre>"; print_r($insData); echo "</pre>"; exit();

			$this->BasketModel->insertBasketData($insData);
		}

		if ( $type != 'basket' ) {
			echo "<script>alertViewShow('장바구니로 이동합니다.', 'locationBasket');</script>";
		} else {
			echo "<script>location.reload();</script>";
		}
	}


	public function getApiProductListView()
	{
		$curData['category1'] = $this->input->get('category1');
		$curData['category2'] = $this->input->get('category2');
		$curData['productArr'] = $this->input->get('productArr');
//		echo "<pre>"; print_r($curData); echo "</pre>";

		if ( $curData['category1'] === 'event' ) {
			$curl_Result = $this->ApiHostNamu->getCategoryEventProduct($curData['category2']);
			$resData = $this->ApiHostNamu->getCategoryEventProductParse($curl_Result);
		} else {
			$curl_Result = $this->ApiHostNamu->getCategorySubProduct($curData['category1'], $curData['category2']);
			$resData = $this->ApiHostNamu->getCategorySubProductParse($curl_Result);
		}

		if ( $this->session->userdata('M_PUBLIC_CI') ) {
			$resCData = $this->ApiHostNamu->getCategoryEventCouponData();
			$pCData = $this->ApiHostNamu->getCategoryEventCouponParse($resCData);
//			echo "<pre>"; print_r($resCData); echo "</pre>"; exit();
		} else {
			$pCData = array();
		}

		// 상품 쿠폰 매핑
		$aCdata = $this->ApiHostNamu->getCouponParseList($pCData, $resData, $curData['category1'], $curData['category2']);
//		echo "<pre>"; print_r($pCData); echo "</pre>"; exit();
		//상품에 쿠폰 매핑
		$resDataList = $this->ApiHostNamu->proCouponMapList($resData, $aCdata);

//		echo "<pre>"; print_r($curData['productArr']); echo "</pre>"; exit();
		foreach ( $resDataList as $index => $row ) {
			$resDataList[$index]['checked'] = '';
			if ( in_array($row['CODE'], $curData['productArr']) ) {
				$resDataList[$index]['checked'] = 'checked';
			}
		}

//		echo "<pre>"; print_r($resDataList); echo "<pre>"; exit();

//		$result['pData'] = $pData;
		$result['pData'] = $resDataList;
		$result['pCData'] = $pCData;
		$result['category1'] = $curData['category1'];
		$result['category2'] = $curData['category2'];
		$this->load->view('/homepage/content/basket/basketProductDetailListView', $result);

	}

	public function	deleteProduct()
	{
		$data['code'] = $this->input->post('code');
		$data['category1'] = $this->input->post('category1');
		$data['category2'] = $this->input->post('category2');
		$data['branch'] = $this->BranchInfo->getBranch();

		if ( !$this->session->userdata('M_PUBLIC_CI') ) {
			// 비로그인
			$this->load->helper('cookie');
			$cookieBasketArr = $this->input->cookie('basketCookie');
			$M_PUBLIC_CI = $cookieBasketArr;
		} else {
			// 1. 회원의 장바구니 검색
			$M_PUBLIC_CI = $this->session->userdata('M_PUBLIC_CI');
		}

		$this->BasketModel->deleteBasketDataOne($M_PUBLIC_CI, $data['branch'], $data['category1'], $data['category2'], $data['code']);

		echo "<script>location.reload();</script>";
	}

	public function	selectDeleteProduct()
	{
		$data['basketArray'] = $this->input->post('basketArray');
		$data['category1Array'] = $this->input->post('category1Array');
		$data['category2Array'] = $this->input->post('category2Array');
		$data['branch'] = $this->BranchInfo->getBranch();

		if ( !$this->session->userdata('M_PUBLIC_CI') ) {
			// 비로그인
			$this->load->helper('cookie');
			$cookieBasketArr = $this->input->cookie('basketCookie');
			$M_PUBLIC_CI = $cookieBasketArr;
		} else {
			// 1. 회원의 장바구니 검색
			$M_PUBLIC_CI = $this->session->userdata('M_PUBLIC_CI');
		}

		foreach ( $data['basketArray'] as $index => $val ) {
			$this->BasketModel->deleteBasketDataOne($M_PUBLIC_CI, $data['branch'], $data['category1Array'][$index], $data['category2Array'][$index], $val);
		}

		echo "<script>location.reload();</script>";
	}*/

	/**
	 * 장바구니 페이지 보기
	 * @return void
	 */
	public function index()
	{
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		//홈페이지 장바구니 조회
		$basket = $this->BasketModel->getBasketList($this->publicCi, $this->branch);
		//조회된 사용자 장바구니 상품 정보
		$basketProductData = array();

		if (!empty($basket->product) || !empty($basket->event) || !empty($basket->product_remain) || !empty($basket->event_remain)) {
			//홈페이지 장바구니 상품 정보를 CRM 상품 정보에서 조회
			$basketProductData = $this->ApiHostNamu->getBasket($this->publicCi, $basket);
		}
//		echo '<pre>'; print_r($basketProductData); echo '</pre>';

		$result = array(
			'branchInfo' => $branchInfo,
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'basketData' => $basketProductData
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/basket/basket_r', $result, true);
	}

	/**
	 * 혜택 팝업 보기
	 * @return void
	 */
	/*public function getBenefitView()
	{
		$tsCode = $this->input->post('tsCode');
		$cateCode = $this->input->post('cateCode');
		$array = $this->input->post('arr');

		$result['tsCode'] = $this->input->post('tsCode');
		$result['cateCode'] = $this->input->post('cateCode');
		$result['data'] = $this->ApiHostNamu->getBasketBenefit($this->publicCi, $this->branch, $tsCode, $cateCode, $array);

		$this->load->view('/homepage/content/basket/basket_b', $result);
	}*/

	/**
	 * 장바구니 상품에 혜택 등록
	 * @return void
	 */
	/*public function setBenefitInert()
	{
		$cateCode = $this->input->post('cateCode');
		$tsCode = $this->input->post('tsCode');
		$benefit = $this->input->post('coupon');

		$this->BasketModel->setBenefitInsert($this->publicCi, $this->branch, $cateCode, $tsCode, $benefit);
	}*/

	/**
	 * 장바구니 상품에 혜택 삭제
	 * @return void
	 */
	/*public function setBenefitDelete()
	{
		$cateCode = $this->input->post('cateCode');
		$tsCode = $this->input->post('tsCode');

		$this->BasketModel->setBenefitDelete($this->publicCi, $this->branch, $cateCode, $tsCode);
	}*/

	/**
	 * 장바구니 상품 삭제
	 * @return void
	 */
	public function setBasketDelete()
	{
		//상품 카테고리 코드 리스트
		$cateCode = $this->input->post('cateCode');
		//상품 코드 리스트
		$tsCode = $this->input->post('tsCode');
		//잔여 상품 코드 리스트
		$ctiCode = $this->input->post('ctiCode');

		if (!empty($ctiCode)) {
			$this->BasketModel->setBasketItemDelete($this->publicCi, $this->branch, $ctiCode);
		} else {
			$this->BasketModel->setBasketDelete($this->publicCi, $this->branch, $cateCode, $tsCode);
		}
	}

	/**
	 * 장바구니 상품 변경 페이지 보기
	 * @return void
	 */
	public function getBasketProduct()
	{
		//카테고리 코드
		$cateCode = $this->input->get('cateCode');
		//카테고리 코드에 포함된 상품중 장바구니에 있는 상품 조회
		$basket_list = $this->BasketModel->getBasketProduct($this->publicCi, $this->branch, $cateCode);
		//카테고리 코드에 상품 정보 조회
		$list = $this->ApiHostNamu->getBasketProduct($this->publicCi, $this->branch, $cateCode);

		$result = array(
			'cateCode' => $cateCode,
			'basketList' => $basket_list,
			'list' => $list
		);

		$this->load->view('/homepage/content/basket/basket_p', $result);
	}

	/**
	 * 장바구니 상품 변경 삭제 후 등록
	 * @return void
	 */
	public function setBasket()
	{
		$list = $this->input->post('list');
		$cateCode = $this->input->post('cate_code');

		$this->BasketModel->setBenefitCateDelete($this->publicCi, $this->branch, $cateCode);

		foreach ($list as $item) {
			$insert = Array(
				'B_BRANCH' => $this->branch
				,'B_PUBLIC_CI' => $this->publicCi
				,'B_CATEGORY1' => $item['fir_cate_code']
				,'B_CATEGORY2' => $item['sec_cate_code']
				,'B_PRODUCT_ID' => $item['ts_code']
			);

			$this->BasketModel->setBasket($insert);
		}
	}

	public function checkEventUsed()
	{
		$result = array(
			'eventUsed' => 'notUsed'
		);

		if ($this->session->userdata('M_PUBLIC_CI')) {
			$mPublicCi = $this->session->userdata('M_PUBLIC_CI');

			$checkList = array(
				'benefit' => array(''),
				'event' => $this->input->post('list')
			);

			//사용한 1회 이벤트
			if (count($checkList['event']) > 0) {
				$benefitCheck = $this->ApiHostNamu->usedBenefitCheck($mPublicCi, $checkList);
				$result['eventUsed'] = $benefitCheck['eventUsed'];
			}
		}

		echo json_encode($result);
	}

}
