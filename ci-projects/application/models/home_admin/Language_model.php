<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getLanguageList()
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_LANGUAGE a
            where 
                1 = 1
        	order by 
        	    seq asc
        ", );

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}

	public function getBranchLanguageList($lan)
	{
		$query = sprintf("
            SELECT
                a.*
            	, (SELECT BRANCH_NAME FROM P_BRANCH b where a.BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH_LAN a
            where 
                1 = 1
            	and a.LANGAUE = '%s'
        	order by 
        	    seq asc
        ", $lan);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}

	public function deleteBranchLanguage($lan)
	{
		$this->db->where('LANGAUE', $lan);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_BRANCH_LAN');

		return $result;
	}

	public function insertBranchLanguage($insData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_BRANCH_LAN', $insData);

		return $result;
	}


	public function getBranchLanguageData($branch)
	{
		$query = sprintf("
            SELECT
                a.*
            	, (SELECT BRANCH_NAME FROM P_BRANCH b where a.BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH_LAN a
            where 
                1 = 1
            	and a.BRANCH = '%s'
        	order by 
        	    seq asc
        ", $branch);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}


}
?>
