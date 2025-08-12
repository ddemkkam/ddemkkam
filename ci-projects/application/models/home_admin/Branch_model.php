<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getBranchList_BAK($data = null) {

		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and a.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and a.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and a.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and a.BRANCH = '".$this->session->userdata('DIVISION')."'";
		}

		$where = '';
		if ( isset($data['branch']) && $data['branch'] !== '' ) {
			$where .= " and a.BRANCH_NAME LIKE '%".$data['branch']."%'";
		}
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and a.BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
                a.*
            	, (SELECT COUNT(*) FROM P_A_PPEUM_MENU AS b WHERE a.BRANCH = b.A_BRANCH) AS SET_YN
            	, (SELECT PLACE_NAME FROM P_PLACE AS c WHERE a.PLACE_ID = c.SEQ) AS PLACE_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH a
            where 
                1 = 1
                and a.DEL_YN = 'N'
            	%s
            	%s
        	order by 
        	    a.MAIN_BRANCH asc, seq asc
        ", $val, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	function getBranchList($data = null) {

		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and PB.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and PB.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and PB.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and PB.BRANCH = '".$this->session->userdata('DIVISION')."'";
		}

		$where = '';
		if ( isset($data['branch']) && $data['branch'] !== '' ) {
			$where .= " and PB.BRANCH_NAME LIKE '%".$data['branch']."%'";
		}
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and PB.BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
                PB.BRANCH
				, PB.BRANCH_NAME
            	, PB.SORT
            	, PB.MAIN_BRANCH
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH AS PB
            where 
                1 = 1
                and PB.DEL_YN = 'N'
            	%s
            	%s
        	order by 
        	    PB.SORT, PB.MAIN_BRANCH asc, PB.seq asc
        ", $val, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getBranchListLan($branch)
	{
		$query = sprintf("
            SELECT
                PBL.BRANCH
				, PBL.LANGAUE
            	, PL.COUNTRY_NAME
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH_LAN AS PBL
            left join ".getenv('DATABASE_NAME').".P_LANGUAGE AS PL on PBL.LANGAUE = PL.COUNTRY
            where 
                1 = 1
            	and PBL.BRANCH = '%s'
        	order by 
        	    PBL.SEQ asc
        ", $branch);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getPlaceList()
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_PLACE a
            where 
                1 = 1
                
        	order by 
        	    SEQ asc
        ");

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function getBranchCountryList($data)
	{
		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and A.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and A.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and A.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and A.BRANCH = '".$this->session->userdata('BRANCH')."'";
		}

		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.BRANCH = '".$data['searchBranch']."'";
		}
		if ( isset($data['searchLan']) && $data['searchLan'] !== 'all' ) {
			$where .= " and B.LANGAUE = '".$data['searchLan']."'";
		}

		$query = sprintf("
            SELECT
				A.SEQ
				, A.BRANCH
				, A.BRANCH_NAME
				, A.EVENT_MAP
				, A.MAIN_BRANCH
				, B.LANGAUE
			FROM 
				P_BRANCH A
				INNER JOIN P_BRANCH_LAN B ON A.BRANCH = B.BRANCH
			WHERE
				A.DEL_YN = 'N'
				%s
				%s
			ORDER BY 
				A.MAIN_BRANCH ASC, A.seq ASC
        ", $val, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}


	public function getBranchCountryList_BAK($data)
	{
		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and A.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and A.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and A.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and A.BRANCH = '".$this->session->userdata('BRANCH')."'";
		}

		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
				A.SEQ
				, A.BRANCH
				, A.BRANCH_NAME
				, A.EVENT_MAP
				, A.MAIN_BRANCH
				, B.LANGAUE
			FROM 
				P_BRANCH A
				INNER JOIN P_BRANCH_LAN B ON A.BRANCH = B.BRANCH
			WHERE
				A.DEL_YN = 'N'
				%s
				%s
			ORDER BY 
				A.MAIN_BRANCH ASC, A.seq ASC
        ", $val, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}


	public function getBranchCountryListGroup($data)
	{
		if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'master' ) {
			$val = " and A.MAIN_BRANCH IN ('Y', 'N')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'main' ) {
			$val = " and A.MAIN_BRANCH IN ('Y')";
		} else if ( $this->session->userdata('DIVISION') && $this->session->userdata('DIVISION') == 'etc' ) {
			$val = " and A.MAIN_BRANCH IN ('N')";
		} else {
			$val = " and A.BRANCH = '".$this->session->userdata('BRANCH')."'";
		}

		$where = '';
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
				A.BRANCH
				, A.BRANCH_NAME
				, A.EVENT_MAP
				, A.MAIN_BRANCH
				, B.LANGAUE
			FROM 
				P_BRANCH A
				INNER JOIN P_BRANCH_LAN B ON A.BRANCH = B.BRANCH
			WHERE
				A.DEL_YN = 'N'
				%s
				%s
			GROUP BY
			    B.LANGAUE
			ORDER BY 
				A.MAIN_BRANCH ASC, A.seq ASC
        ", $val, $where);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;

	}


	public function getBranchInfo($branch)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_BRANCH a
            where 
                1 = 1
                and a.DEL_YN = 'N'
            	and a.BRANCH = '%s'
        ", $branch);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function addBranch($data)
	{
		$inData['BRANCH'] = $data['BRANCH'];
		$inData['BRANCH_NAME'] = $data['BRANCH_NAME'];
		$inData['EVENT_MAP'] = $data['EVENT_MAP'];
		$inData['MAIN_BRANCH'] = $data['MAIN_BRANCH'];
		//$upData['DEL_YN'] = $data['DEL_YN'];
		$inData['REG_DATE'] = date('Y-m-d H:i:s');

		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_BRANCH', $inData);

		return $result;
	}


	public function modifyBranch($data)
	{
		$upData['BRANCH_NAME'] = $data['BRANCH_NAME'];
		$upData['EVENT_MAP'] = $data['EVENT_MAP'];
		$upData['MAIN_BRANCH'] = $data['MAIN_BRANCH'];
		//$upData['DEL_YN'] = $data['DEL_YN'];
		$upData['SORT'] = $data['SORT'];
		$upData['MOD_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('BRANCH', $data['BRANCH']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_BRANCH', $upData);

		return $result;
	}


	public function deleteBranch($data)
	{
		$upData['DEL_YN'] = 'Y';
		$upData['DEL_DATE'] = date('Y-m-d H:i:s');

		$this->db->where('BRANCH', $data['BRANCH']);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_BRANCH', $upData);

		return $result;
	}



	public function getBranch($domain)
	{
		$query = sprintf("
			SELECT 
				* 
			FROM " . getenv('DATABASE_NAME') . ".P_BRANCH
			WHERE `DOMAIN` = '%s'
		", $domain);

		$queryResult = $this->db->query($query);
		return $queryResult->row();
	}


}
?>
