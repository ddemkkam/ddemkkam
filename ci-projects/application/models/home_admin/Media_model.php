<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getMediaListTotal($data)
	{
		$where = "";
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.M_BRANCH = '".$data['searchBranch']."'";
		}

		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_MEDIA AS A
        	WHERE
        	    A.M_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    A.SEQ DESC
        ", $where);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}


	public function getMediaList($data)
	{
		$where = "";
		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' ) {
			$where .= " and A.M_BRANCH = '".$data['searchBranch']."'";
		}

		$limit = " LIMIT " . $data['start'] . ", " . $data['list_num'] . "";

		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_MEDIA AS A
        	WHERE
        	    A.M_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    A.SEQ DESC
            %s
        ", $where, $limit);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function delete_media($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_MEDIA', $updateData);

		return $result;
	}

	public function getMediaInfo($SEQ = null)
	{
		$where = "";
		if ( isset($SEQ) ) {
			$where .= " and A.SEQ = '".$SEQ."'";
		}

		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_MEDIA AS A
        	WHERE
        	    A.M_DEL_YN = 'N'
        	    %s
        	ORDER BY 
        	    A.SEQ DESC
        ", $where);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function update_media($updateData, $SEQ)
	{
		$this->db->where('SEQ', $SEQ);
		$result = $this->db->update(getenv('DATABASE_NAME').'.P_MEDIA', $updateData);

		return $result;
	}

	public function insert_media($insertData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_MEDIA', $insertData);

		return $result;
	}

}
