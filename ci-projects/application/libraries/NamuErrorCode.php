<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class NamuErrorCode
{

	public function __construct()
	{
		$this->CI =& get_instance();

	}

	public function errorCode($code)
	{
		$errorCode[401] = '토큰 오류';
		$errorCode[600] = 'PUT, DELETE - 매칭 정보 없음';
		$errorCode[601] = 'GET, POST, PUT, DELETE - 필수 입력 오류 ( data.error_field 참고 )';
		$errorCode[602] = 'GET, POST, PUT - 입력 양식 오류 ( data.error_field 참고 )';
		$errorCode[610] = 'GET, POST, PUT - 조회 불가 정보';
		$errorCode[611] = 'GET, POST, PUT - 매칭 정보 오류 (중요 정보가 다름)';
		$errorCode[612] = 'POST, PUT - 등록할 정보가 없음';
		$errorCode[630] = 'FILE CDN TP Upload Error';
		$errorCode[631] = 'File Upload 파일 양식(MimeType)이 맞지 않을 경우';
		$errorCode[632] = 'File Upload시 Error가 있을경우';
		$errorCode[691] = 'SERVER - 오류';
		$errorCode[692] = 'DB - 오류';

		return $errorCode[$code];
	}

}
