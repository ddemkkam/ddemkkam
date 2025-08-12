<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class HomepageSession
{

	public function __construct()
	{
		$this->CI =& get_instance();

	}

	public function setSession($data)
	{
		$this->CI->load->library('session');

		$this->CI->session->set_userdata(
			array(
				'M_ID' 			=> isset($data['M_ID']) ? $data['M_ID'] : ''
				, 'M_NAME' 		=> isset($data['M_NAME']) ? $data['M_NAME'] : ''
				, 'M_PHONE' 	=> isset($data['M_PHONE']) ? $data['M_PHONE'] : ''
				, 'M_PUBLIC_CI' 			=> isset($data['M_PUBLIC_CI']) ? $data['M_PUBLIC_CI'] : ''
			)
		);

//		$this->CI->input->set_cookie($cookie);
	}

}
