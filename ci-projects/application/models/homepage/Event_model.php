<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getEventImage($branch, $lan, $tseCode)
	{

		$query = sprintf("
            select
                E_IMG_PATH as image_path
            from
                " . getenv('DATABASE_NAME') . ".P_EVENT_INFO pei
            where pei.E_BRANCH = '%s'
            and pei.E_LANGUAGE = '%s'
			and pei.TSE_CODE = '%s'
            	
        ", $branch, $lan, $tseCode);

		$queryResult = $this->db->query($query);
		return $queryResult->row();
	}

}
