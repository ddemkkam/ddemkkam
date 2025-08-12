<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class MasterCheck
{

	public function __construct() {
		$this->CI =& get_instance();

		if( !$this->CI->session->userdata('ADMIN_ID') ) {
			header('location:/home_admin/login');
			exit();
		} else {
			//$this->checkBranch();
			//$this->sectionBranch();
		}
	}

	public function checkBranch() {
		$query = "select * from ".getenv('DATABASE_NAME').".event_member as em where em.id = '".$this->CI->session->userdata('id')."'";
		$queryResult = $this->CI->db->query($query);
		$resultArr = $queryResult->result_array();
		$result = $resultArr[0];

		if ( $result['master_level'] >= 10 && $result['id'] != 'admin' ) {
			$query = "select * from ".getenv('DATABASE_NAME').".event_member as em where em.manage_id = '".$this->CI->session->userdata('id')."'";
			$queryResult2 = $this->CI->db->query($query);
			$resultArr2 = $queryResult2->result_array();

			foreach ($resultArr2 as $index2 => $row) {
				$branchArr[$index2] = $row['branch'];
			}
		} else {
			if ( $result['id'] == 'admin' ) {
				$query = "select * from ".getenv('DATABASE_NAME').".event_member as em";
				$queryResult3 = $this->CI->db->query($query);
				$resultArr3 = $queryResult3->result_array();

				foreach ($resultArr3 as $index3 => $row) {
					$branchArr[$index3] = $row['branch'];
				}
			} else {
				$branchArr[0] = $this->CI->session->userdata('id');
			}
		}
		//echo "<pre>"; print_r($branchArr); echo "</pre>";
		$this->CI->load->vars(array('arrBranch' => $branchArr));
		$this->CI->load->vars(array('arrBranchJson' => str_replace(array('[',']'), '', json_encode($branchArr))));
	}


	public function sectionBranch() {
		if ( $this->CI->session->userdata('master') ) {
			$where = "";
		} else {
			$where = " AND pb.branch in (".$this->CI->load->get_vars('arrBranchJson')['arrBranchJson'].")";
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
?>
