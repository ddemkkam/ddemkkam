<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function selectHospitalData($BRANCH) {
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_HOSPITAL_INFO a
            where 
                1 = 1
            	and HI_BRANCH = '%s'
            order by 
            	a.HI_SORT
        ", $BRANCH);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	function selectDoctorData($BRANCH) {
		$query = sprintf("
            SELECT
                a.*
            	, (select BRANCH_NAME from P_BRANCH b where a.D_BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_DOCTOR_INFO a
            where 
                1 = 1
                and a.D_MAIN_YN = 'Y'
            	and a.D_BRANCH = '%s'
            	and a.SD_BRANCH = '%s'
            order by 
            	a.SEQ asc
            limit 1
        ", $BRANCH, $BRANCH);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->row();

		return $result;
	}

	public function selectDoctorGroupData($BRANCH)
	{
		$query = sprintf("
            SELECT
                a.D_BRANCH
            	, (select BRANCH_NAME from P_BRANCH b where a.D_BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_DOCTOR_INFO a
                inner join P_BRANCH b on b.BRANCH = a.D_BRANCH
            where 
                1 = 1
                and a.D_USE_YN = 'Y'
                and a.D_DEL_YN = 'N'
                and b.DEL_YN = 'N'
                and a.SD_BRANCH = '%s'
            group by 
            	D_BRANCH
            order by 
            	FIELD(a.D_BRANCH, '%s') DESC
            	, b.SORT
            	, a.D_BRANCH 
            	, a.D_MAIN_YN ASC
            	, a.SEQ
        ", $BRANCH, $BRANCH);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function selectDoctorListData($BRANCH)
	{
		$query = sprintf("
            SELECT
                a.*
            	, (select BRANCH_NAME from P_BRANCH b where a.D_BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_DOCTOR_INFO a
                inner join P_BRANCH b on b.BRANCH = a.D_BRANCH
            where 
                1 = 1
                and a.D_USE_YN = 'Y'
                and b.DEL_YN = 'N'
                and a.D_DEL_YN = 'N'
                and a.SD_BRANCH = '%s'
            order by 
            	FIELD(a.D_BRANCH, '%s') DESC
            	, b.SORT
            	, a.D_BRANCH 
            	, a.D_MAIN_YN ASC
            	, a.SEQ
        ", $BRANCH, $BRANCH);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}





}
?>
