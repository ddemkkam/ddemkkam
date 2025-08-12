<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainimg_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getMainImgInfo($branch = null, $lan = null)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_MAIN_IMG a
            where 
                1 = 1
            	and a.BRANCH = '%s'
            	and a.LAN = '%s'
            order by 
            	a.SEQ desc
            limit 1
        ", $branch, $lan);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}

	function getMainImage($branch, $lan, $now)
	{
		$query = sprintf("
            select
                pmi.IMG_SRC as image_path
            	, pmi.LINK as link
            	, pmi.LINK_TARGET as link_target
            from
                ".getenv('DATABASE_NAME').".P_MAIN_IMG pmi
            where pmi.BRANCH = '%s'
			and pmi.LAN = '%s'
			and CONCAT(pmi.START_DATE, ' ', TIME_FORMAT(pmi.START_TIME, '%%H:%%i:%%s')) <= '%s'
        	and CONCAT(pmi.FINISH_DATE, ' ', TIME_FORMAT(pmi.FINISH_TIME, '%%H:%%i:%%s')) >= '%s'
            order by 
            	pmi.sort,
            	pmi.SEQ ASC
        ", $branch, $lan, $now, $now);

		$queryResult = $this->db->query($query);

		return $queryResult->result_array();
	}

	function getMainPopupImage($branch, $lan, $now)
	{
		$query = sprintf("
            select
                pps.P_TITLE as title,
                pps.P_IMG_PATH as image_path
            from ".getenv('DATABASE_NAME').".P_POPUP_SET pps
            where pps.P_BRANCH = '%s'
            and pps.P_LANGUAGE = '%s'
            and CONCAT(pps.P_START_DATE, ' ', TIME_FORMAT(pps.P_START_TIME, '%%H:%%i:%%s')) <= '%s'
        	and CONCAT(pps.P_FINISH_DATE, ' ', TIME_FORMAT(pps.P_FINISH_TIME, '%%H:%%i:%%s')) >= '%s'
            order by 
            	pps.P_SORT
        ", $branch, $lan, $now, $now);

		$queryResult = $this->db->query($query);

		return $queryResult->result_array();
	}

	public function insertImg($data)
	{
		$inData = $data;

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MAIN_IMG', $inData);

		return $result;
	}


}
