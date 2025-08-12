<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session'));

		$this->load->model( '/home_admin/AdminMember_model', 'AdminMember_model' );

	}

	public function index()
	{
		if( $this->session->userdata('ADMIN_ID') != '' ) {
			header('location:/home_admin/main');
			exit();
		} else {

		}

		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/login");
		$this->layout->view('/admin/content/login/login', null, true);

		//view
		//$this->load->view('login/login');
	}

	public function loginProc(){

		$data['ADMIN_ID'] = $this->input->post('ADMIN_ID');
		$data['PASSWORD'] = md5($this->input->post('PASSWORD')); // md5 암호화
		$data['PSDWD'] = md5($this->input->post('PASSWORD'));

		//DB 검색
		$userData = $this->AdminMember_model->getAdminMember($data);
		//echo "<pre>"; print_r($userData); echo "</pre>"; exit();
		if ( count($userData) > 0 ) {
			$master = true;
		} else {
				echo "<script>alert('아이디와 패스워드를 확인해 주세요.'); location.replace('/home_admin/login');</script>";
				exit();
		}

		$uData = $userData[0];
		$this->session->set_userdata(
			array(
				'ADMIN_ID' 			=> isset($uData['ADMIN_ID']) ? $uData['ADMIN_ID'] : ''
				, 'ADMIN_NAME' 		=> isset($uData['ADMIN_ID']) ? $uData['ADMIN_NAME'] : ''
				, 'ADMIN_LEVEL' 	=> isset($uData['ADMIN_LEVEL']) ? $uData['ADMIN_LEVEL'] : ''
				, 'BRANCH' 			=> isset($uData['BRANCH']) ? $uData['BRANCH'] : ''
				, 'DIVISION' 		=> isset($uData['DIVISION']) && $uData['DIVISION'] != '' ? $uData['DIVISION'] : $uData['BRANCH']
			)
		);

		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";

		header('location:/home_admin/main/');

	}


	public function logout()
	{
		$this->session->set_userdata(
			array(
				'ADMIN_ID' 			=> ''
				, 'ADMIN_NAME' 		=> ''
				, 'ADMIN_LEVEL' 	=> ''
				, 'BRANCH' 			=> ''
				, 'DIVISION' 		=> ''
			)
		);

		session_destroy();
		$this->session->sess_destroy();

		setcookie('ck_mb_id', "", 0, "/");
		setcookie('ck_auto', "", 0, "/");
		sleep(1);

		header('location:/home_admin/login');
	}



}
