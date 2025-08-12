<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class HistoryData
{

	public function __construct() {

	}

	public function test()
	{
		echo "asdf";
	}


	public function insertHistoryData($mode, $table, $context = null)
	{
		$this->CI =& get_instance();

		$historyData['ADMIN_ID'] = $this->CI->session->userdata('ADMIN_ID');
		$historyData['MODE'] = $mode;
		$historyData['TABLE'] = $table;
		$historyData['CONTEXT'] = $context;
		$historyData['DATE_TIME'] = date("Y-m-d H:i:s");
		$this->CI->db->insert(getenv('DATABASE_NAME').'.P_HISTORY', $historyData);
	}

}
?>
