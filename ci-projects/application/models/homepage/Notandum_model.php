<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notandum_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function getNotandumList($branch, $type)
	{
		$query = sprintf("
            SELECT
            	*
            	, (select BRANCH_NAME from P_BRANCH as c where B.N_BRANCH = c.BRANCH) as N_BRANCH_NAME
            	, (select MAIN_BRANCH from P_BRANCH as c where B.N_BRANCH = c.BRANCH) as N_MAIN_BRANCH
            FROM 
				P_BRANCH A
                INNER JOIN ".getenv('DATABASE_NAME').".P_NOTANDUM AS B ON A.BRANCH = B.N_BRANCH
        	WHERE
        	    B.N_DEL_YN = 'N'
        	    AND B.N_BRANCH = '%s'
        	    AND B.N_TYPE = '%s'
        	ORDER BY 
        	    N_MAIN_BRANCH DESC
        		, B.N_BRANCH
            	, B.SEQ
        ", $branch, $type);


//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}




}
