<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class LoginCheck
{

	public function __construct()
	{
		$this->CI =& get_instance();

		if (!$this->CI->session->userdata('ss_id')) {
			header('location:/home_admin/login');
			exit();
		} else {
			//메인 섹션 사용 여부
			$this->sectionBranch();
		}
	}


	public function sectionBranch() {
		if ( $this->CI->session->userdata('master') ) {
			$where = "";
		} else {
			$where = " AND pb.branch = '".$this->CI->session->userdata('ss_id')."'";
		}

		$query = "select * from ".getenv('DATABASE_NAME').".ppeum_branch as pb where 1=1 ".$where." and pb.section_yn = 'Y' ";
		$queryResult = $this->CI->db->query($query);
		$resultArr = $queryResult->result_array();

		if ( count($resultArr) > 0 ) {
			$this->CI->load->vars(array('sectionBranch' => 'Y'));
		} else {
			$this->CI->load->vars(array('sectionBranch' => 'N'));
		}
	}

}
