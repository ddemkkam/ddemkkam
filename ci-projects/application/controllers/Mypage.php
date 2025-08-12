<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypage extends CI_Controller
{
	public $menu = '마이페이지';
	private $ReservationModel;
	private $BranchInfo;
	private $ApiHostNamu;
	private $BasketInfo;
	private $userInfo;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Reservation_model');
		$this->load->model('/homepage/MyPageImg_model');

		$this->ReservationModel = new Reservation_model();
		$this->MyPageImgModel = new MyPageImg_model();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BasketInfo = new BasketInfo();

		//로그인된 사용자 C_PUBLIC_CI 값 조회 (비로그인시 로그인 페이지로 이동 처리)
		$this->userInfo = $this->BranchInfo->getOnlyLoginUserInfo();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
		//국가 코드
		$this->lan = 'KR';
	}

	/**
	 * 마이페이지 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//사용자명
		$userName = $this->userInfo->user_name;
		//사용자 연락처
		$pattern = '/(^02.{0}|^01.{1}|^15.{2}|^16.{2}|^18.{2}|[0-9]{3})([0-9]+)([0-9]{4})/';
		$phoneNumber = preg_replace($pattern, '$1-$2-$3', $this->userInfo->phone_number);
		//사용자 마일리지,혜택,예약,잔여시술 정보 조회
		$myInfo = $this->ApiHostNamu->getUserInfo();
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->userInfo->public_ci, $this->branch);

		$nowDate = Date('Y-m-d');
		$nowTime = Date('H:i:s');

		$pcImage = $this->MyPageImgModel->getMyPageImgInfo($this->branch, $this->lan, '01', $nowDate, $nowTime);
		$mobileImage = $this->MyPageImgModel->getMyPageImgInfo($this->branch, $this->lan, '02', $nowDate, $nowTime);

		$result = array(
			'menu' => $this->menu,
			'branchInfo' => $branchInfo,
			'cnt' => $basketCnt,
			'data' => $myInfo,
			'name' => $userName,
			'phone' => $phoneNumber,
			'pc' => $pcImage,
			'mobile' => $mobileImage,
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/mypage/mypage', $result, true);
	}

	/**
	 * 사용자 보유 혜택 조회
	 * @return void
	 */
	public function coupon()
	{
		$result = $this->ApiHostNamu->getUserBenefit($this->userInfo->public_ci);

		$this->load->view('/homepage/content/mypage/coupon', $result);
	}

	/**
	 * 사용자 예약 현황 조회
	 * @return void
	 */
	public function reservationInfo()
	{
		$result['data'] = $this->ApiHostNamu->getUserReservation();
		$result['api_url'] = $this->ApiHostNamu->getNamuApiUrl() . '/v1/public/reserve/' . $this->userInfo->public_ci . '/';
		$result['branch_check'] = $this->BranchInfo->getReservationReviewStatus();

		foreach ($result['data'] as $key => $item) {
			$benefit = $this->ReservationModel->getBenefit($this->userInfo->public_ci, $this->branch, $item['r_number']);
			$cpc = 0;
			$mileage = '0';

			foreach ($benefit as $benefitItem) {
				if ($benefitItem['R_RESERVE_TYPE'] != '') {
					$cpc += (int)json_decode($benefitItem['R_COUPON'])->dc_price;
				} else {
					$mileage = $benefitItem['R_MILEAGE'];
				}
			}

			$result['data'][$key]['cpc'] = $cpc;
			$result['data'][$key]['mileage'] = $mileage;
		}

		$this->load->view('/homepage/content/mypage/reservationInfo', $result);
	}

	/**
	 * 예약 미방문 확인
	 * @return void
	 */
	public function nonReservationChecked()
	{
		$rNumber = $this->input->post('rNumber');

		$result = $this->ApiHostNamu->nonReservationChecked($this->userInfo->public_ci, $rNumber);

		echo json_encode($result);
	}

	/**
	 * 사용자 보유 시술 조회
	 * @return void
	 */
	public function remainsItem()
	{
		$result = array(
			'remain' => $this->ApiHostNamu->getUserRemain(),
			'branch' => $this->BranchInfo->getBranchInfo()
		);

		$this->load->view('/homepage/content/mypage/remainsItem', $result);
	}

}
