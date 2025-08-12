<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends CI_Controller
{
	public $menu = '이벤트';
	private $EventModel;
	private $BranchInfo;
	private $BasketInfo;
	private $ApiHostNamu;
	private $publicCi;
	private $branch;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Event_model');

		$this->EventModel = new Event_model();
		$this->BranchInfo = new BranchInfo();
		$this->BasketInfo = new BasketInfo();
		$this->ApiHostNamu = new ApiHostNamu();

		//사용자 조회
		$this->publicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	/**
	 * 이벤트 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//이벤트 리스트 조회
		$eventList = $this->ApiHostNamu->getEventList();
		//이벤트 리스트 추가 조회 API 경로
		$eventUrl = $this->ApiHostNamu->getHomeApiUrl() . '/event';

		$result = array(
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'branchInfo' => $branchInfo,
			'list' => $eventList,
			'homeApiUrl' => $eventUrl
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/event/event', $result, true);
	}

	/**
	 * 특정 이벤트 상세 보기 화면
	 * @return void
	 */
	public function info()
	{
		//로그인한 유저에게만 제공되는 정보 조회를 위해 로그인 정보 따로 조회
		$publicCi = $this->session->userdata('M_PUBLIC_CI');
		//국가 코드
		$lan = 'KR';
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//이벤트에서 제공되는 혜택 정보 조회할 API 정보
		$apiUrl = $this->ApiHostNamu->getNamuApiUrl() . '/v1/public/coupon';
		//조회할 이벤트 코드
		$tseCode = $this->input->get('tse_code');
		//이벤트 정보 조회
		$eventData = $this->ApiHostNamu->getEventDetail($publicCi, $tseCode);
		//이벤트 이미지 조회
		$imageData = $this->EventModel->getEventImage($this->branch, $lan, $tseCode);
		//이벤트 이미지 경로 설정
		$imagePath = isset($imageData) ? $imageData->image_path : '';

		$result = array(
			'mPublicCi' => $publicCi,
			'apiUrl' => $apiUrl,
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'branchInfo' => $branchInfo,
			'list' => $eventData,
			'imagePath' => $imagePath
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/event/detail', $result, true);
		//상품 정보 합산 뷰
		$this->load->view('/homepage/include/select_product_info');
	}

	/**
	 * 사용자 혜택 사용 여부 조회
	 * @return void
	 */
	public function benefitCheck()
	{
		//로그인한 유저에게만 제공되는 정보 조회를 위해 로그인 정보 따로 조회
		$publicCi = $this->session->userdata('M_PUBLIC_CI');
		$cpNo = $this->input->post('coupon_seq');

		$result = $this->ApiHostNamu->hasBenefitChecked($publicCi, $cpNo);

		echo json_encode($result);
	}

}
