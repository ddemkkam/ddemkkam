<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class LoginReferer
{

	public function __construct()
	{
		$this->CI =& get_instance();

	}

	public function refererUri()
	{
		$this->CI->load->library('user_agent');
		$this->CI->load->helper('cookie');

		$refer = $this->CI->agent->referrer();
		$serverHost = 'http'.(!empty($_SERVER['HTTPS']) ? 's':null)."://".$_SERVER['HTTP_HOST'];

		$resRefererUri = str_replace($serverHost , "", $refer);

		if ($resRefererUri == '/login' || $resRefererUri == '') {
			$resRefererUri = '/mypage';
		}

		$cookie = array(
			'name'   => 'referer',
			'value'  => $resRefererUri,
			'expire' => '99999999',

		);

		$this->CI->input->set_cookie($cookie);
	}

	public function refererUriDelete()
	{
		$this->CI->load->helper('cookie');
		$this->CI->input->delete_cookie('referer');
	}

	public function userCookieDelete()
	{
		$this->CI->load->helper('cookie');
		$this->CI->input->delete_cookie('membercookie');
	}

}
