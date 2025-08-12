<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyPageImg_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMyPageImgInfo($branch = null, $lan = null, $type)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_MYPAGE_IMG a
            where 
                1 = 1
            	and a.BRANCH = '%s'
            	and a.LAN = '%s'
            	and a.DEVICE_TYPE = '%s'
            order by 
            	a.SEQ asc
        ", $branch, $lan, $type);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}
	public function insertImg($data)
	{
		$inData = $data;

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MYPAGE_IMG', $inData);

		return $result;
	}

	public function deleteImg($branch, $type, $lan)
	{
		$this->db->where('BRANCH', $branch)->where('DEVICE_TYPE', $type)->where('LAN', $lan);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_MYPAGE_IMG');

		return $result;
	}



}
