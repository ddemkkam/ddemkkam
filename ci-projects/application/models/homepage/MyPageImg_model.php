<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyPageImg_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMyPageImgInfo($branch = null, $lan = null, $type,$nowDate ,$nowTime)
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
            	and ((START_DATE < '$nowDate' or (START_DATE = '$nowDate' and START_TIME <= '$nowTime')) and (FINISH_DATE > '$nowDate' or (FINISH_DATE = '$nowDate' and FINISH_TIME >= '$nowTime')))
            order by 
            	a.SEQ asc
        ", $branch, $lan, $type);

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}
}
