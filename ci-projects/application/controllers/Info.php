<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller
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

	public function index()
	{
		if ($this->branch !== 'ppeum920') {
			show_404();                // 404로 막기
			return;
		}
		$branchInfo = $this->BranchInfo->getBranchInfo();

		$result = array(
			'branchInfo' => $branchInfo,
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/busan/custom', $result, true);
	}
}
