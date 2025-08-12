<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function setBenefit($insert)
	{
		$this->db->insert(getenv('DATABASE_NAME').'.P_RESERVATION', $insert);
	}

	public function getBenefit($mPublicCi, $branch, $rNumber)
	{
		$query = sprintf("
			SELECT 
				*
			FROM
				 " . getenv('DATABASE_NAME') . ".P_RESERVATION
			WHERE R_PUBLIC_CI = '%s'
			AND R_BRANCH = '%s' 
			AND R_RES_NUM = '%s'
		", $mPublicCi, $branch, $rNumber);

		$queryResult = $this->db->query($query);
		return $queryResult->result_array();
	}

}
