<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maimimg_model extends CI_Model
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
            	a.SEQ asc
        ", $branch, $lan);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}

	public function insertImg($data)
	{
		$inData = $data;

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MAIN_IMG', $inData);

		return $result;
	}

	public function deleteImg($branch, $lan)
	{
		$this->db->where('BRANCH', $branch)->where('LAN', $lan);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_MAIN_IMG');

		return $result;
	}



}
