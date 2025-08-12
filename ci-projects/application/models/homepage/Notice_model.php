<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getNoticeData($branch = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_NOTICE a
            where 
                1 = 1
                and a.N_BRANCH = '%s'
                and a.N_DEL_YN = 'N'
            order by 
            	a.SEQ DESC
        ", $branch);

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}
