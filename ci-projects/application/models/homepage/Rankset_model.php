<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rankset_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getRankList($branch)
	{

		$query = sprintf("
            SELECT
               group_concat(a.ts_code ORDER BY a.SORT) as product
				,group_concat(IF (a.fir_cate_code = 'event', IFNULL(a.sec_cate_code, ''), '') ORDER BY a.SORT) AS event_category
            FROM (
                select * from  " . getenv('DATABASE_NAME') . ".P_RANKSET
				where 
					1 = 1
					and BRANCH = '%s'
					AND '%s' BETWEEN CONCAT(sDate, ' ', sTime) AND CONCAT(eDate, ' ', eTime)
				order by SORT
				limit 10
            ) as a
               
        ", $branch, date('Y-m-d H:i:s'));

		$queryResult = $this->db->query($query);
		return $queryResult->row();

		return $result;
	}
}
