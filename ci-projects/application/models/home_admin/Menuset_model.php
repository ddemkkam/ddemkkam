<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menuset_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_L_Menu($data)
	{
		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] != 'all' ) {
			$where .= " and a.L_BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
                a.*
            	, (select BRANCH_NAME from ".getenv('DATABASE_NAME').".P_BRANCH as b where a.L_BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_L_PPEUM_MENU a
            where 
                1 = 1
                and a.L_DEL_YN = 'N'
            	%s
            order by
                L_BRANCH ASC, L_SORT ASC
        ", $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function insert_L_Menu($data)
	{
		$inData['L_BRANCH'] = $data['L_BRANCH'];
		$inData['L_TITLE'] = $data['L_TITLE'];
		if ( isset($data['L_URL']) && $data['L_URL'] !== '' ) {
			$inData['L_URL'] = $data['L_URL'];
		}
		$inData['L_SORT'] = $data['L_SORT'];

		$inData['REG_DATE'] = date('Y-m-d H:i:s');

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_L_PPEUM_MENU', $inData);

		return $result;
	}

	public function modify_L_Menu($data)
	{
		$upData['L_TITLE'] = $data['L_TITLE'];
		$upData['L_URL'] = $data['L_URL'];
		$upData['L_SORT'] = $data['L_SORT'];
		$upData['L_USE_YN'] = $data['L_USE_YN'];
		$upData['MOD_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('SEQ', $data['SEQ']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_L_PPEUM_MENU', $upData);

		return $result;
	}

	public function delete_L_Menu($data)
	{
		$upData['L_DEL_YN'] = 'Y';
		$upData['DEL_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('SEQ', $data['SEQ']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_L_PPEUM_MENU', $upData);

		return $result;
	}




}
?>
