<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PopupSet_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function deletePopup($BRANCH, $LANGUAGE)
	{
		$this->db->where('P_BRANCH', $BRANCH);
		$this->db->where('P_LANGUAGE', $LANGUAGE);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_POPUP_SET');

		return $result;
	}

	public function insertPopup($insertData)
	{
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_POPUP_SET', $insertData);

		return $result;
	}

	public function getPopupData($BRANCH, $LANGAUE)
	{
		$query = sprintf("
            SELECT
            	*
            FROM 
                ".getenv('DATABASE_NAME').".P_POPUP_SET AS A
        	WHERE
        	    A.P_BRANCH = '%s'
            	and A.P_LANGUAGE = '%s' 
        ", $BRANCH, $LANGAUE);

		//echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

}

?>
