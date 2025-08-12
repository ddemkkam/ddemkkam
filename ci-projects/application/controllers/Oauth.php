<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oauth extends CI_Controller
{
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
//		$this->load->helper(array('form', 'url'));
//		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'branchInfo'));

//		$this->load->model('/home_admin/Branch_model', 'Branch_model');

//		$this->ApiHostNamu = new ApiHostNamu();
//		$this->api_url = $this->ApiHostNamu->apiUrlCheck();
	}

	public function index()
	{

	}
}
