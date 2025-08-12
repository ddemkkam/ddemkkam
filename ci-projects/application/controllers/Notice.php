<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller
{
	public $menu = '공지사항';
	private $NoticeModel;
	private $BranchInfo;
	private $BasketInfo;
	private $ApiHostNamu;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'BranchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Notice_model');
		$this->load->model('/homepage/Basket_model');

		$this->BasketModel = new Basket_model();
		$this->NoticeModel = new Notice_model();
		$this->BasketInfo = new BasketInfo();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();

		//사용자 조회
		$this->publicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	public function index()
	{
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//공지사항 정보 조회
		$noticeData = $this->NoticeModel->getNoticeData($this->branch);
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);

		$result = array(
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'branchInfo' => $branchInfo,
			'noticeData' => $noticeData
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/notice/notice', $result, true);
	}

}
