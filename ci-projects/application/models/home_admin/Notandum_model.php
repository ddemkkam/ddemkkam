<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notandum_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function getNotandumList($data)
	{
		$where = "";

		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and A.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and A.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and A.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and A.BRANCH = '".$this->session->userdata('BRANCH')."'";
		}

		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and B.N_BRANCH = '".$data['searchBranch']."'";
		}

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
				%s
        	    %s
        	ORDER BY 
        	    N_MAIN_BRANCH DESC
        		, B.N_BRANCH
            	, B.SEQ
        ", $val, $where);


//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function delete_notandum($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_NOTANDUM', $updateData);

		return $result;
	}

	public function getNoticeInfo($SEQ = null)
	{
		$where = "";
		if ( isset($SEQ) ) {
			$where .= " and A.SEQ = '".$SEQ."'";
		}

		$query = sprintf("
            SELECT
            	*
            	, (select BRANCH_NAME from P_BRANCH as b where A.N_BRANCH = b.BRANCH) as N_BRANCH_NAME
            	, (select MAIN_BRANCH from P_BRANCH as b where A.N_BRANCH = b.BRANCH) as N_MAIN_BRANCH
            FROM 
                ".getenv('DATABASE_NAME').".P_NOTANDUM AS A
        	WHERE
        	    A.N_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    N_MAIN_BRANCH DESC
        		, A.N_BRANCH
            	, A.SEQ
        ", $where);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function update_notice($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_NOTANDUM', $updateData);

		return $result;
	}

	public function insert_notice($insertData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_NOTANDUM', $insertData);

		return $result;
	}

}
