<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KakaoChat extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo'));

		$this->load->model('/homepage/About_model', 'About_model');

		$this->BranchInfo = new BranchInfo();
		$this->branchData = $this->BranchInfo->getBranchInfo();

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();
	}

	public function index()
	{

		$result['menu']	= $this->menu;
		$result['branchInfo'] = $this->branchData;
//		print_r($result['branchInfo']['BRANCH']);

		$this->load->view('/homepage/content/kakaoChat/kakaoChat', $result);
	}

	public function pvl()
	{

		$result['menu']	= $this->menu;
		$result['branchInfo'] = $this->branchData;
//		print_r($result['branchInfo']['BRANCH']);

		$this->load->view('/homepage/content/kakaoChat/pvlKakaoChat', $result);
	}

}
