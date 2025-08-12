<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BranchInfo
{
	public $branch;

	public function __construct()
	{
		$this->CI =& get_instance();

//		$this->CI->session->userdata('ss_id');

		$this->CI->load->model('/homepage/Branchinfo_model', 'Branchinfo_model');
		$this->CI->load->model('/home_admin/Branch_model', 'Branch_model');
		//branch check 로직 추가해야함.
		$resBranch = $this->getBranch();
		$this->branch = $resBranch;
	}

	public function checkBranch()
	{
		/*
		 * 임시로 branch 는 강남점(ppeum9)로 설정 2024.04.19
		 */
		if ( strpos($_SERVER['SERVER_NAME'], 'dev') !== false ) {
			$checkBranch = 'ppeum_dev';
		} else if ( strpos($_SERVER['SERVER_NAME'], 'local') !== false ) {
			$checkBranch = 'ppeum_dev';
		} else if ( strpos($_SERVER['SERVER_NAME'], 'gangnam') !== false ) {
			$checkBranch = 'ppeum09';
		} else if ( strpos($_SERVER['SERVER_NAME'], 'busan') !== false ) {
			$checkBranch = 'ppeum20';
		} else {
			$checkBranch = 'ppeum_dev';
		}

		return $checkBranch;
	}

	public function checkLan()
	{
		$lan = $this->CI->input->get('lan');

		if ( isset($lan) ) {
			$lan = $lan;
		} else {
			$lan = 'KR';
		}

		return $lan;
	}


	public function getBranchInfo()
	{
		$data['branch'] = $this->branch;
		$data['language'] = 'KR';

		$branchInfoData = $this->CI->Branchinfo_model->getBranchInfo($data);

		return $branchInfoData[0];
	}

	public function getBranchName()
	{
		$result = $this->CI->Branch_model->getBranchInfo($this->branch);

		return $result[0];
	}

	public function getBranch()
	{
		$domain = $_SERVER['SERVER_NAME'];

		$data = $this->CI->Branch_model->getBranch($domain);

		return isset($data) ? $data->BRANCH : getenv('TEST_BRANCH');
	}

	public function getUser()
	{
		return !$this->CI->session->userdata('M_PUBLIC_CI') ? $this->CI->input->cookie('basketCookie') : $this->CI->session->userdata('M_PUBLIC_CI');
	}

	public function getOnlyLoginUserInfo()
	{
		if (!$this->CI->session->userdata('M_PUBLIC_CI')) {
			echo "<script>location.replace('/login');</script>";
			exit();
		} else {
			$result = (object)array(
				'public_ci' => $this->CI->session->userdata('M_PUBLIC_CI'),
				'phone_number' => $this->CI->session->userdata('M_PHONE'),
				'user_name' => $this->CI->session->userdata('M_NAME')
			);

			return $result;
		}
	}

	public function getReservationReviewStatus()
	{
		$branchs = array('ppeum30');

		return in_array($this->branch, $branchs);
	}
}
