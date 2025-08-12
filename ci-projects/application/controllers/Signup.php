<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller
{
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'branchInfo')); //, 'homepageLoginCheck'

		$this->load->model('/homepage/Member_model', 'Member_model');

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();

	}

	public function index()
	{
//		$data['type'] = $this->input->post('type');
//		$data['id'] = $this->input->post('id');
//		$data['name'] = $this->input->post('name');
//		$data['phone_number'] = str_replace( "-", "", $this->input->post('phone_number') );
//		$data['email'] = $this->input->post('email');
//		$data['birthday'] = $this->input->post('birthday');
//		echo "<pre>"; print_r($data); echo "</pre>";

		$this->load->helper('cookie');
		$memberData = json_decode($this->input->cookie('membercookie'));

		$data['id'] = $memberData->id;
		$data['type'] = $memberData->type;
		$data['name'] = $memberData->name;
		$data['email'] = $memberData->email;
		$data['phone_number'] = str_replace( "-", "", $memberData->phone_number);
		$data['birthday'] = $memberData->birthday;

		$result = $data;
//		echo "<pre>"; print_r($memberData); echo "</pre>";
		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/signup/signup', $result, true);
	}





}
