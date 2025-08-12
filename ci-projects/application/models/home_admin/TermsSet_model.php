<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TermsSet_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getTermsList($branch, $type, $lan, $SEQ = null)
	{
		$where = "";
		if ( $SEQ ) {
			$where .= " and a.SEQ = '".$SEQ."'";
		}

		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_TERMS_DETAIL a
            where 
                1 = 1
            	and a.T_BRANCH = '%s'
				and a.T_TYPE = '%s'
				and a.T_LAN = '%s'
            	%s
        	order by 
        	    seq desc
        ", $branch, $type, $lan, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function insertTerms($data)
	{
		$insData['T_BRANCH'] = $data['T_BRANCH'];
		$insData['T_TYPE'] = $data['T_TYPE'];
		$insData['T_LAN'] = $data['T_LAN'];
		$insData['T_CONTEXT'] = $data['T_CONTEXT'];

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_TERMS_DETAIL', $insData);

		return $result;
	}

	public function deleteTerms($T_TYPE)
	{
		$this->db->where('T_TYPE', $T_TYPE);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_TERMS_DETAIL');

		return $result;
	}


}
