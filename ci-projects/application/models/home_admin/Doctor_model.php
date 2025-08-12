<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function selectDoctorInfo($branch = null, $data = null) {

		$where = '';
		if ($data['searchBranch'] != null && $data['searchBranch'] != 'all') {
			$where .= " and a.D_BRANCH = '".$data['searchBranch']."'";
		}
		if ($data['searchLan'] != null && $data['searchLan'] != 'all') {
			$where .= " and a.D_LAN = '".$data['searchLan']."'";
		}


		if ($data['SEQ'] != null) {
			$where .= " and a.SEQ = '".$data['SEQ']."'";
		}

		$query = sprintf("
            SELECT
                a.*
            	, b.BRANCH_NAME as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_DOCTOR_INFO a
                inner join P_BRANCH b on b.BRANCH = a.D_BRANCH
            where 
                1 = 1
                and a.D_DEL_YN = 'N'
                and b.DEL_YN = 'N'
                and a.SD_BRANCH = '%s'
            	%s
            order by 
            	a.SEQ DESC
        ", $branch, $where);

//		echo '<pre>'; print_r($query); echo '</pre>';

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function insertDoctorInfo($insertData = null) {
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_DOCTOR_INFO', $insertData);

		return $result;
	}


	public function updateDoctorInfo($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_DOCTOR_INFO', $updateData);

		return 	$result;
	}


	public function deleteDoctorInfo($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_DOCTOR_INFO', $updateData);

		return 	$result;
	}








}
?>
