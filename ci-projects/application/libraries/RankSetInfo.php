<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class RankSetInfo
{
	public $branch;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('/homepage/Rankset_model', 'Rankset_model');


	}

}
