<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller
{
	public $menu = '병원 소개';
	private $AboutModel;
	private $BranchInfo;
	private $ApiHostNamu;
	private $branch;
	private $publicCi;
	private $BasketInfo;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/About_model');

		$this->AboutModel = new About_model();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BasketInfo = new BasketInfo();

		$this->publicCi = $this->BranchInfo->getUser();
		$this->branch = $this->BranchInfo->getBranch();
	}

	/**
	 * 병원 소개 화면
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
		//병원 소개 초기 노출 화면 설정 타입 (1: 둘러보기, 2: 의료진 소개, 3: 오시는 길)
		$idx = $this->input->get('type');
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//병원 둘러보기 정보 조회
		$hospitalList = $this->AboutModel->selectHospitalData($this->branch);
		//대표 의료진 정보 조회
		$doctorData = $this->AboutModel->selectDoctorData($this->branch);

		$result = array(
			'cnt' => $basketCnt,
			'menu' => $this->menu,
			'branchInfo' => $branchInfo,
			'idx' => $idx,
			'hospitalList' => $hospitalList,
			'doctorData' => $doctorData
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/about/about', $result, true);
	}
}
