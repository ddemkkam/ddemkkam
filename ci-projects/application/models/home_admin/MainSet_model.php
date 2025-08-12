<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainSet_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function insert_mainset($insertData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MAIN_SET', $insertData);

		return $result;
	}

	public function modify_mainset($modifyData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_MAIN_SET', $modifyData);

		return $result;
	}

	public function delete_mainset($deleteData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_MAIN_SET', $deleteData);

		return $result;
	}

	public function getMainSetData($data)
	{
		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.M_BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
            	*
            	, (select BRANCH_NAME from P_BRANCH as b where A.M_BRANCH = b.BRANCH) as M_BRANCH_NAME
            	, (select MAIN_BRANCH from P_BRANCH as b where A.M_BRANCH = b.BRANCH) as M_MAIN_BRANCH
            FROM 
                ".getenv('DATABASE_NAME').".P_MAIN_SET AS A
        	WHERE
        	    A.M_DEL_YN = 'N'
        	    %s
        	ORDER BY A.SEQ DESC
        ", $where);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}
