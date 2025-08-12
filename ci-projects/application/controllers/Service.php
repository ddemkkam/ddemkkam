<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller
{
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'branchInfo')); //, 'homepageLoginCheck'

		$this->load->model('/homepage/Member_model', 'Member_model');
		$this->load->model('/homepage/Service_model', 'Service_model');

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();

	}

	public function serviceInfo()
	{
		$this->BranchInfo = new BranchInfo();
		$data['branch'] = $this->BranchInfo->getBranch();
		$data['type'] = $this->input->get('type');

		/*
		 * 외국어 진행시 lan 체크 로직 추가해아함. 라이브러리로 체크.
		 */
		$data['lan'] = $this->BranchInfo->checkLan();

		$data['serviceData'] = $this->Service_model->getServiceData($data);

		$result = $data;
//		echo "<pre>"; print_r($data); echo "</pre>"; exit();
//		$this->layout->setLayout("/homepage/layouts/layout");
		$this->load->view('/homepage/content/service/service', $result);
	}





}
