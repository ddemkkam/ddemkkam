<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branchinfo_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getBranchInfo($data = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_PPEUM_INFO a
            where 
                1 = 1
                and a.BRANCH = '%s'
                and a.LANGUAGE = '%s'
        ", $data['branch'], $data['language']);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}
