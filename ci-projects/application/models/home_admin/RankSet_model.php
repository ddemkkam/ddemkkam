<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankSet_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function getRankData($BRANCH, $LANGUAGE)
	{
		$query = sprintf("
            SELECT
                a.*
            FROM
                ".getenv('DATABASE_NAME').".P_RANKSET a
            where 
                1 = 1
            	and a.BRANCH = '%s'
            	and a.LANGUAGE = '%s'
            order by 
            	a.SORT
        ", $BRANCH, $LANGUAGE);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function deleteRank($BRANCH, $LANGUAGE)
	{
		$this->db->where('BRANCH', $BRANCH);
		$this->db->where('LANGUAGE', $LANGUAGE);
		$this->db->delete(getenv('DATABASE_NAME') . '.P_RANKSET');

		$affected = $this->db->affected_rows();

		if ($affected > 0) {
			$historyData = new HistoryData();
			$deleteLogData = [
				'BRANCH' => $BRANCH,
				'LANGUAGE' => $LANGUAGE,
				'MESSAGE' => 'P_RANKSET 데이터 삭제',
			];
			$historyData->insertHistoryData('DELETE', 'ppeum_homepage.P_RANKSET', json_encode($deleteLogData, JSON_UNESCAPED_UNICODE));
		}

		return $affected > 0;
	}


	public function insertRank($insertData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_RANKSET', $insertData);

		return $result;
	}

}
