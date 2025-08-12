<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'masterCheck'));
	}

	public function index()
	{
		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";

		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/main/main', null, true);
	}



}
