<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getMemberCheck($data = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_MEMBER a
            where 
                1 = 1
                and a.M_TYPE = '%s'
            	and a.M_PUBLIC_CI = '%s'
            	and a.M_BRANCH = '%s'
            order by 
            	a.SEQ ASC
        ", $data['M_TYPE'], $data['M_ID'], $data['branch']);

//		echo '<pre>'; print_r($query); echo '</pre>'; exit();

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getMemberDupCheck($data = null, $type = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_MEMBER a
            where 
                1 = 1
            	and a.M_NAME = '%s'
            	and a.M_PHONE = '%s'
            	and a.M_BIRTHDAY = '%s'
            	and a.M_TYPE = '%s'
            	and a.M_BRANCH = '%s'
        ",$data['M_NAME'], $data['M_PHONE'], $data['M_BIRTHDAY'], $data['M_TYPE'], $data['branch']);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function setMemberData($insertData = null)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MEMBER', $insertData);

		return $result;
	}

	public function modMemberData($M_ID)
	{
		$upData['M_CRM_MAP'] = 'Y';

		$this->db->where('M_ID', $M_ID);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_MEMBER', $upData);

		return 	$result;
	}

	public function getGroupKeyCheck($groupKey)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_MEMBER a
            where 
                1 = 1
            	and a.M_GROUP = '%s'
        ",$groupKey);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getMemberInfo($cPublicCi, $branch)
	{
		$query = sprintf("
            SELECT
                a.M_ID,
                a.M_NAME,
                a.M_PHONE,
            	a.M_PUBLIC_CI
            FROM
                " . getenv('DATABASE_NAME') . ".P_MEMBER a
            where 
                1 = 1
            	and a.M_PUBLIC_CI = '%s'
            	and a.M_BRANCH = '%s'
        ",$cPublicCi, $branch);

		$queryResult = $this->db->query($query);

		$result = $queryResult->row();
		return $result;
	}


	public function setKakaoLog($insertData = null)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.ci_kakao_logs', $insertData);

		return $result;
	}



}
