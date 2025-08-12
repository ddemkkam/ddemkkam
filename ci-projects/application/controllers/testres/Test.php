<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo'));

	}

	public function index()
	{
		$ch = curl_init();                                 //curl 초기화
		curl_setopt($ch, CURLOPT_URL, getenv('API_URL')."/category1");               //URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);      //connection timeout 5초
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

		$response = curl_exec($ch);
		curl_close($ch);
//		print_r($response);
	}

}
