<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class AdminBranch
{
	public $branch;

	public function __construct()
	{
		$this->CI =& get_instance();

//		$this->CI->session->userdata('ss_id');

		$this->CI->load->model('/home_admin/Branch_model', 'Branch_model');

		//branch check 로직 추가해야함.
		$resBranch = $this->checkBranch();
//		$this->branch = $resBranch;
	}

	public function checkBranch()
	{
		$branchList = $this->CI->Branch_model->getBranchList();

		foreach ( $branchList as $index => $row ) {
			$branchList[$index]['LAN'] = $this->CI->Branch_model->getBranchListLan($row['BRANCH']);
		}
//		echo '<pre>'; print_r($branchList); echo '</pre>';
		if ( count($branchList) > 0 ) {

		} else {
			echo "<script>alert('설정된 지점이 없습니다. 관리자에게 문의하세요'); location.replace('/home_admin/main/')</script>";
			exit();
		}
//		echo '<pre>'; print_r($branchList); echo '</pre>'; exit();

		return $branchList;
	}

	public function checkBranchcheck($B_BRANCH, $B_LAN)
	{
		if ( $B_BRANCH == null || $B_LAN == null ) {
			$branchList = $this->checkBranch();
			$REQUEST_URI = explode('/', $_SERVER['REQUEST_URI']);
			echo "<script>location.replace('/".$REQUEST_URI[1]."/".$REQUEST_URI[2]."/".$REQUEST_URI[3]."/".$branchList[0]['BRANCH']."/KR')</script>";
			// layout 을 적용
//			$this->CI->layout->setLayout("/admin/layouts/layout");
//			$this->CI->layout->view('/admin/content/default/default', $result, true);
			exit();
		}

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




}
