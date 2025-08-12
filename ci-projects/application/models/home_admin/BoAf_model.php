<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BoAf_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getBFListTotal($data)
	{
		$where = "";
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.BA_BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_BEFORE_AFTER AS A
        	WHERE
        	    A.BA_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    A.SEQ DESC
        ", $where);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getBFList($data)
	{
		$where = "";
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.BA_BRANCH = '".$data['searchBranch']."'";
		}

		$limit = " LIMIT " . $data['start'] . ", " . $data['list_num'] . "";

		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_BEFORE_AFTER AS A
        	WHERE
        	    A.BA_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    A.SEQ DESC
			%s
        ", $where, $limit);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}
