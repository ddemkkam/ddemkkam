<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getServiceData($data = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_TERMS_DETAIL a
            where 
                1 = 1
                and a.T_TYPE = '%s'
            	and a.T_BRANCH = '%s'
            	and a.T_LAN = '%s'
            order by 
            	a.SEQ DESC
            limit 1
        ", $data['type'], $data['branch'], $data['lan']);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}
