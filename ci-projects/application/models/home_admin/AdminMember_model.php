<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMember_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getAdminMember($data = null) {

		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_ADMIN_MEMBER a
            where 
                1 = 1
                and a.DEL_YN = 'N'
            	and a.ADMIN_ID = '%s'
            	and a.PSDWD = '%s'
        ", $data['ADMIN_ID'], $data['PSDWD']);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	function getAdminMemberList($data = null) {

		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] != 'all' ) {
			$where .= " and a.BRANCH = '".$data['searchBranch']."'";
		}
		if ( isset($data['nameId']) && $data['nameId'] != '' ) {
			$where .= " and ( a.ADMIN_ID LIKE '%".$data['nameId']."%' OR a.ADMIN_NAME LIKE '%".$data['nameId']."%' )";
		}

		$query = sprintf("
            SELECT
                a.*
            	, (select BRANCH_NAME from P_BRANCH as b where a.BRANCH = b.BRANCH) as BRANCH_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_ADMIN_MEMBER a
            where 
                1 = 1
                and a.DEL_YN = 'N'
            	%s
        ", $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function modifyMember($data)
	{
		$upData['ADMIN_NAME'] = $data['ADMIN_NAME'];
		$upData['ADMIN_LEVEL'] = $data['ADMIN_LEVEL'];

		if ( isset($data['PSDWD']) && $data['PSDWD'] !== '' ) {
			$upData['PSDWD'] = md5($data['PSDWD']);
		}

		$upData['MOD_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('ADMIN_ID', $data['ADMIN_ID']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_ADMIN_MEMBER', $upData);

		return $result;
	}


	public function deleteMember($data)
	{
		$upData['DEL_YN'] = 'Y';

		$upData['DEL_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('ADMIN_ID', $data['ADMIN_ID']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_ADMIN_MEMBER', $upData);

		return $result;
	}

	public function insertMember($data)
	{
		$inData['BRANCH'] = $data['BRANCH'];
		$inData['ADMIN_NAME'] = $data['ADMIN_NAME'];
		$inData['ADMIN_ID'] = $data['ADMIN_ID'];
		$inData['PSDWD'] = md5($data['PSDWD']);

		$inData['REG_DATE'] = date('Y-m-d H:i:s');

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_ADMIN_MEMBER', $inData);

		return $result;
	}






}
?>
