<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EventInfo_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getEventInfo($branch = null, $tse_code = null) {

		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_EVENT_INFO a
            where 
                1 = 1
            	and a.E_BRANCH = '%s'
            	and a.TSE_CODE = '%s'
            order by 
            	a.E_SORT
        ", $branch, $tse_code);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function deleteEventInfo($HI_BRANCH = null, $HI_LAN = null) {
		$this->db->where('E_BRANCH', $HI_BRANCH);
		$this->db->where('E_LANGUAGE', $HI_LAN);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_EVENT_INFO');

		return $result;
	}

	public function insertEventInfo($insertData = null) {
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_EVENT_INFO', $insertData);

		return $result;
	}





}
?>
