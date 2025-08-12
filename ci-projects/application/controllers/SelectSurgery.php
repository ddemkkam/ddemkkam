<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SelectSurgery extends CI_Controller
{
	public $menu = '시술예약';
	private $BasketModel;
	private $ApiHostNamu;
	private $BranchInfo;
	private $BasketInfo;
	private $mPublicCi;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));
		$this->load->model('/homepage/Basket_model');

		$this->BasketModel = new Basket_model();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BranchInfo = new BranchInfo();
		$this->BasketInfo = new BasketInfo();

		//사용자 조회
		$this->mPublicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	public function getSubCategoryProductList()
	{
		$type = $this->input->get('type');
		$uid = $this->input->get('uid');

		if ( $type === 'event' ) {
			$curl_Result = $this->ApiHostNamu->getCategoryEventDepth();
//			echo "<pre>"; print_r($curl_Result); echo "</pre>"; exit();
			$parseResult = $this->ApiHostNamu->getCategoryEventDepthParse($curl_Result);
		} else {
			$curl_Result = $this->ApiHostNamu->getCategorySubDepth($uid);
			$parseResult = $this->ApiHostNamu->getCategorySubDepthParse($curl_Result, $uid);
		}

		$result['subCategory'] = $parseResult;
		$result['type'] = $type;

		$this->load->view('/homepage/content/selectSurgery/productList', $result);
	}

	public function getApiProductListView()
	{
		$curData['category1'] = $this->input->get('category1');
		$curData['category2'] = $this->input->get('category2');
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
		} else {
			$pCData = array();
		}
//		echo "<pre>"; print_r($resData); echo "</pre>"; exit();
		// 상품 쿠폰 매핑
		$aCdata = $this->ApiHostNamu->getCouponParseList($pCData, $resData, $curData['category1'], $curData['category2']);
//		echo "<pre>"; print_r($pCData); echo "</pre>"; exit();
		//상품에 쿠폰 매핑
		$resDataList = $this->ApiHostNamu->proCouponMapList($resData, $aCdata);


//		echo "<pre>"; print_r($resDataList); echo "<pre>"; exit();

//		$result['pData'] = $pData;
		$result['pData'] = $resDataList;
		$result['pCData'] = $pCData;
		$result['category1'] = $curData['category1'];
		$result['category2'] = $curData['category2'];
		$this->load->view('/homepage/content/selectSurgery/productDetailListView', $result);

	}

	public function getApiProductCouponView()
	{
		$code = $this->input->post('code');
		$couponArr = $this->input->post('couponArr');
		$productArr = $this->input->post('productArr');
		$category1_uid = $this->input->post('category1_uid');
		$category2_uid = $this->input->post('category2_uid');

		$productArrA[0] = $productArr;
		if ( $this->session->userdata('M_PUBLIC_CI') ) {
			// 상품 쿠폰 매핑(쿠폰, 상품, 카테고리1, 카테고리2)
			$aCdata = $this->ApiHostNamu->getCouponParseList($productArr['couponList'], $productArrA, $category1_uid, $category2_uid);

			//상품에 쿠폰 매핑
			$resDataList = $this->ApiHostNamu->proCouponMapList($productArrA, $aCdata);
		} else {
			$resDataList = array();
		}

//		echo "<pre>"; print_r($resDataList); exit();

		$result['pData'] = $resDataList[0]['couponList'];
		$result['code'] = $code;
		$this->load->view('/homepage/content/selectSurgery/productCouponListView', $result);
	}


	/**
	 * 시술예약 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//전체 노출 상품리스트 조회
		$productList = $this->ApiHostNamu->getCategory($this->mPublicCi);
		/*echo '<pre>'; print_r($productList); echo '</pre>';exit;*/
		//보유 혜택 조회
		$hasBenefit = $this->ApiHostNamu->getUserBenefit($this->mPublicCi);
		//보유 혜택 개수 조회
		$hasBenefitCount = count($hasBenefit['data']);
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->mPublicCi, $this->branch);

		$result = array(
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'branchInfo' => $branchInfo,
			'list' => $productList,
			'hasBenefitCount' => $hasBenefitCount
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/selectSurgery/selectSurgery_r', $result, true);
		//상품 정보 합산 뷰
		$this->load->view('/homepage/include/select_product_info');
	}

	public function setBasketProduct()
	{
		$this->load->helper('string');
		$this->load->helper('cookie');

		if (!$this->session->userdata('M_PUBLIC_CI')) {
			/*
			 * 1. 비회원일 경우 쿠키 사용
			 */
			$randomStringID = 'B_'.random_string('basic', 12);

			$cookieBasketArr = $this->input->cookie('basketCookie');

			if ( !isset($cookieBasketArr) && $cookieBasketArr == '' ) {
				// 3-1. 기존의 저장된 쿠키가 없다면 new 쿠키 저장
				$basketCookie = array(
					'name'   => 'basketCookie',
					//'value'  => json_encode($basketArr),
					'value' => $randomStringID,
					'expire' => '86500',
				);

				$mPublicCi = $randomStringID;
			} else {
				$basketCookie = array(
					'name'   => 'basketCookie',
					//'value'  => json_encode($basketArr),
					'value' => $cookieBasketArr,
					'expire' => '86500',
				);

				// 기존 쿠키 삭제후 새로 저장
				delete_cookie('basketCookie');

				$mPublicCi = $cookieBasketArr;
			}

			$this->input->set_cookie($basketCookie);

		} else {
			$mPublicCi = $this->session->userdata('M_PUBLIC_CI');
		}

		$branch = $this->BranchInfo->getBranch();
		$list = $this->input->post('list');

		$dupCheck = '';

		foreach ($list as $item) {
			$check = $this->BasketModel->getBasketCheck($mPublicCi, $branch, $item['ts_code'], $item['fir_cate_code'], $item['sec_cate_code'], $item['remain_code']);

			if (count($check) == 0) {
				$insert = Array(
					'B_BRANCH' => $branch
					,'B_PUBLIC_CI' => $mPublicCi
					,'B_CATEGORY1' => $item['fir_cate_code']
					,'B_CATEGORY2' => $item['sec_cate_code']
					,'B_PRODUCT_ID' => $item['ts_code']
					,'B_END_DATE' => $item['tse_end_datetime']
					,'B_REMAINS_PRO' => isset($item['remain_code']) ? $item['remain_code'] : ''
				);

				$this->BasketModel->setBasket($insert);

				$dupCheck = false;
			} else {
				if (empty($dupCheck)) $dupCheck = true;
			}
		}

		$result = array(
			'success' => true
			,'dup_check' => $dupCheck
			,'basket_cnt' => $this->BasketInfo->getBasketCount($mPublicCi, $this->BranchInfo->getBranch())
		);

		echo json_encode($result);
	}



}
