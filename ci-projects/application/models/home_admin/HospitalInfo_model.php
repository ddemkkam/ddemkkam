<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HospitalInfo_model extends CI_Model  {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getHospitalInfo($branch = null, $lan = null) {

		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_HOSPITAL_INFO a
            where 
                1 = 1
                and a.HI_USE_YN = 'Y'
            	and a.HI_BRANCH = '%s'
            	and a.HI_LAN = '%s'
            order by 
            	a.HI_SORT
        ", $branch, $lan);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function deleteHospitalInfo($HI_BRANCH = null, $HI_LAN = null, $HI_TITLE = [], $HI_IMG_PATH = [])
	{
		// HI_TITLE, HI_IMG_PATH는 삭제 쿼리에 사용되지 않으며, 로그 기록용 데이터입니다.
		$this->db->where('HI_BRANCH', $HI_BRANCH);
		$this->db->where('HI_LAN', $HI_LAN);
		$this->db->delete(getenv('DATABASE_NAME').'.P_HOSPITAL_INFO');

		$affected = $this->db->affected_rows();

		if ($affected > 0) {
			// 로그 기록
			$historyData = new HistoryData();
			$deleteLogData = [
				'HI_BRANCH' => $HI_BRANCH,
				'HI_LAN' => $HI_LAN,
				'HI_TITLE' => $HI_TITLE,
				'HI_IMG_PATH' => $HI_IMG_PATH
			];
			$historyData->insertHistoryData('DELETE', 'ppeum_homepage.P_HOSPITAL_INFO', json_encode($deleteLogData, JSON_UNESCAPED_UNICODE));
		}

		return $affected > 0;
	}


	public function insertHospitalInfo($insertData = null) {
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_HOSPITAL_INFO', $insertData);

		return $result;
	}





}
?>
