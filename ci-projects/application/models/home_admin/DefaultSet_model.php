<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DefaultSet_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getBranchPpeumInfo($branch = null, $lan = null)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_PPEUM_INFO a
            where 
                1 = 1
            	and a.BRANCH = '%s'
            	and a.LANGUAGE = '%s'
        ", $branch, $lan);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}

	public function insertBranchPpeumInfo($data)
	{
		$inData = $data;
		//$upData['DEL_YN'] = $data['DEL_YN'];
		$inData['REG_DATE'] = date('Y-m-d H:i:s');

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_PPEUM_INFO', $inData);

		return $result;
	}

	public function updateBranchPpeumInfo($data, $BRANCH, $LANGUAGE)
	{
		$upData = $data;
		//$upData['DEL_YN'] = $data['DEL_YN'];
		$upData['MOD_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('BRANCH', $BRANCH)->where('LANGUAGE', $LANGUAGE);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_PPEUM_INFO', $upData);

		return $result;
	}

}
