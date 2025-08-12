<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notandum extends CI_Controller
{
	public $menu = '시술전&후 주의사항';
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Notandum_model', 'Notandum_model');

		$this->BranchInfo = new BranchInfo();
		$this->branchData = $this->BranchInfo->getBranchInfo();

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();
		$this->BasketInfo = new BasketInfo();

		$this->publicCi = $this->BranchInfo->getUser();
		$this->branch = $this->BranchInfo->getBranch();
	}

	public function index()
	{


	}

	public function before()
	{
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		$result['cnt'] = $basketCnt ;
		$result['menu']	= '시술 전 유의사항';
		$result['branchInfo'] = $this->branchData;

		$notandumData = $this->Notandum_model->getNotandumList($result['branchInfo']['BRANCH'], 'B');

//		echo "<pre>"; print_r($noticeData); echo "</pre>";

		$result['notandumData'] = $notandumData;
		$result['type'] = 'B';

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/notandum/notandum', $result, true);
	}

	public function after()
	{
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		$result['cnt'] = $basketCnt ;
		$result['menu']	= '시술 후 주의사항';
		$result['branchInfo'] = $this->branchData;

		$notandumData = $this->Notandum_model->getNotandumList($result['branchInfo']['BRANCH'], 'A');

//		echo "<pre>"; print_r($noticeData); echo "</pre>";

		$result['notandumData'] = $notandumData;
		$result['type'] = 'A';

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/notandum/notandum', $result, true);
	}

}
