<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'cookie'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'branchInfo', 'loginReferer', 'homepageSession'));

		$this->load->model('/homepage/Member_model', 'Member_model');

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();
		$this->BranchInfo = new BranchInfo();

		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();

	}

	public function test()
	{
		$sendData['M_ID'] = '240823-10004';
		$sendData['M_NAME'] = '김병준';
		$sendData['M_PHONE'] = '01026260691';
		$sendData['M_PUBLIC_CI'] = 'KKO_240823-10004';
		$this->HomepageSession = new HomepageSession();
		$this->HomepageSession->setSession($sendData);
	}

	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		$this->LoginReferer = new LoginReferer();
		$this->LoginReferer->refererUri();
		$test = isset($_GET['test']) && $_GET['test'] === '1';
		sleep(1);
//		$data['H_NAVER_CLIENT'] = $branchInfoData[0]['H_NAVER_CLIENT'];
//		$data['H_NAVER_SECRET'] = $branchInfoData[0]['H_NAVER_SECRET'];

		$result['kakaoConfig'] = $this->kakaoConfig();
		$result['branch'] = $this->branch;
		$result['isTest'] = $test;
//		echo "<pre>"; print_r($result); echo "</pre>";

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/login/login', $result, true);
	}

	public function logout()
	{
		$this->session->sess_destroy();

		sleep(1);

		header('location:/');
	}


	public function checkMemberData($data, $type)
	{
		$this->BranchInfo = new BranchInfo();
		$dbData['branch'] = $this->BranchInfo->getBranch();
		if ($type == 'kakao') {
			$dbData['M_ID'] = 'KKO_' . $data['id'];
		} else {
			$dbData['M_ID'] = $data['id'];
		}

		$dbData['M_TYPE'] = $data['type'];

		$memberCheck = false;
		$memberCheckData = $this->Member_model->getMemberCheck($dbData, $type);

		if ( isset($memberCheckData) && count($memberCheckData) > 0 ) {
			$memberCheck = $memberCheckData;
		}
		return $memberCheck;
	}



	public function curl_kakao($callUrl, $method, $headers = array(), $data = array(), $returnType="jsonObject"){

		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $callUrl);
			if ($method == "POST") {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			} else {
				curl_setopt($ch, CURLOPT_POST, false);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HTTP200ALIASES, array(400));
			$response = curl_exec($ch);
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
//			echo "<pre>".$status_code.":".$response."</pre>";

			if ($returnType=="jsonObject") {
				return json_decode($response);
			}
			else return $response;
		} catch (Exception $e) {
			echo $e;
		}

//		return $data;
	}

	//카카오 로그인 결과
	public function kakaoOauth()
	{
		$code = $this->input->get('code');

		$kakaoConfig = $this->kakaoConfig($code);

		//카카오 토큰 요청
		$kakaoToken = $this->kakaoTokenData($kakaoConfig);

		//카카오 프로필 요청
		$kakaoProfile = $this->kakaoProfileData($kakaoConfig, $kakaoToken);

		//카카오 데이터 파싱
		$parseKakaoData = $this->parseKakaoData($kakaoProfile);

		////카카오 데이터 log 추가 
		$this->BranchInfo = new BranchInfo();
		$kakaoData = [
			'branch' => $this->BranchInfo->getBranch() ,
			'level' => '000',
			'message' => json_encode($parseKakaoData, true)
		];

		// 테스트
		// if($parseKakaoData['id'] === '3905997044'){
		// 	$kakaoData['level'] = '999';
		// 	$this->Member_model->setKakaoLog($kakaoData );
		// 	$this->kakaoError();
		// 	exit;
		// }

		if(empty($parseKakaoData['id'])){
			$kakaoData['level'] = '999';
			$this->Member_model->setKakaoLog($kakaoData );
			$this->kakaoError();
		}else{
			$this->Member_model->setKakaoLog($kakaoData );
			//회원 가입 여부 체크
			$memberCheck = $this->checkMemberData($parseKakaoData, 'kakao');

			//echo "<pre>"; print_r($memberCheck); echo '</pre>'; exit();

			if ( !$memberCheck ) {
				//회원 가입으로 이동
				/*$this->load->helper('cookie');

				$regJsonData = json_encode($parseKakaoData);
				$serverHost = 'http'.(!empty($_SERVER['HTTPS']) ? 's':null)."://".$_SERVER['HTTP_HOST'];
				echo $serverHost;

				$memberCookie = array(
					'name'   => 'membercookie',
					'value'  => $regJsonData,
					'expire' => '6000',
				);
				$this->input->set_cookie($memberCookie);*/

				$type = 'kakao';
				$id =  $parseKakaoData['id'] === '' ? '' : 'KKO_' . urlencode($parseKakaoData['id']);
				$name = urlencode($parseKakaoData['name']);
				$number = urlencode(str_replace('-', '', $parseKakaoData['phone_number']));

				echo "<script>location.href = '/registMember?type=" . $type ."&id=" . $id . "&name=" . $name ."&number=" . $number . "';</script>";

			} else {
				//CRM DB 회원 정보 조회
				$info = $this->ApiHostNamu->getUserLoginInfo($memberCheck[0]['M_PUBLIC_CI']);

			//			$sendData['M_ID'] = $memberCheck[0]['M_ID'];
			//			$sendData['M_NAME'] = $memberCheck[0]['M_NAME'];
			//			$sendData['M_PHONE'] = $memberCheck[0]['M_PHONE'];
			//			$sendData['M_PUBLIC_CI'] = $memberCheck[0]['M_PUBLIC_CI'];
			//			$this->HomepageSession = new HomepageSession();
			//			$this->HomepageSession->setSession($sendData);

				if (is_null($info->result)) {
					$type = 'kakao';
					$id =  $parseKakaoData['id'] === '' ? '' : 'KKO_' . urlencode($parseKakaoData['id']);
					$name = urlencode($parseKakaoData['name']);
					$number = urlencode(str_replace('-', '', $parseKakaoData['phone_number']));

					echo "<script>location.href = '/registMember?type=" . $type ."&id=" . $id . "&name=" . $name ."&number=" . $number . "';</script>";
				} else {
					$login = array(
						'M_ID' => $info->result->C_NUMBER,
						'M_NAME' => $info->result->C_NAME,
						'M_PHONE' => $info->result->C_HP,
						'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
					);

					$this->HomepageSession = new HomepageSession();
					$this->HomepageSession->setSession($login);

					$this->load->helper('cookie');
					$referer = $this->input->cookie('referer');

					delete_cookie('referer');
					delete_cookie('membercookie');

					echo "<script>location.replace('".$referer."');</script>";
				}
			}
			
		}


	}

	public function parseKakaoData($kakaoProfile)
	{
//		echo "<pre>"; print_r($kakaoProfile); echo "<pre>";

		$returnData['type'] = 'kakao';
		$returnData['id'] = $kakaoProfile->id;
		$returnData['name'] = $kakaoProfile->kakao_account->name;
		$returnData['email'] = $kakaoProfile->kakao_account->email;
		$returnData['phone_number'] = str_replace("+82 ", "0", $kakaoProfile->kakao_account->phone_number);
		$returnData['birthyear'] = $kakaoProfile->kakao_account->birthyear;
		$returnData['birthdayday'] = $kakaoProfile->kakao_account->birthday;
		$returnData['birthday'] = $kakaoProfile->kakao_account->birthyear.$kakaoProfile->kakao_account->birthday;

		return $returnData;
	}

	public function kakaoConfig($code = null)
	{
		$this->BranchInfo = new BranchInfo();
		$branchInfoData = $this->BranchInfo->getBranchInfo();

		$data['H_KAKAO_KEY'] = $branchInfoData['H_KAKAO_KEY'];
		$data['H_KAKAO_CLIENT'] = $branchInfoData['H_KAKAO_CLIENT'];

		// ????
		$kakaoConfig['state'] = md5(mt_rand(111111111,999999999));

		$kakaoConfig['code'] = $code;

		// 설정파일 조정 필요
		$kakaoConfig['client_id'] = $data['H_KAKAO_KEY']; // ![수정필요] 카카오 REST API 키값 , 카카오 개발자 사이트 > 내 애플리케이션 > 요약정보에서 REST API 키값
		// ![수정필요] 카카오 개발자 사이트 > 내 애플리케이션 > 카카오로그인 > 보안 에서 생성가능
		$kakaoConfig['client_secret'] = $data['H_KAKAO_CLIENT'];
		// ![수정필요] 로그인 인증 후 Callback url 설정 - 변경시 URL 수정 필요, 카카오 개발자 사이트 > 내 애플리케이션 > 카카오로그인 > Redirect URI 에서 등록가능
		$kakaoConfig['redirect_uri'] = 'http'.(!empty($_SERVER['HTTPS']) ? 's':null).'://'.$_SERVER['HTTP_HOST'].getenv('KAKAO_AOUTH');
		$kakaoConfig['redirect_uri'] = urlencode($kakaoConfig['redirect_uri']);

		// 로그인 인증 URL
		$kakaoConfig['login_auth_url']  = 'https://kauth.kakao.com/oauth/authorize?';
		$kakaoConfig['login_auth_url'] .= 'prompt=login&response_type=code';
		$kakaoConfig['login_auth_url'] .= '&client_id='.$kakaoConfig['client_id'];
		$kakaoConfig['login_auth_url'] .= '&redirect_uri='.$kakaoConfig['redirect_uri'];
//		$kakaoConfig['login_auth_url'] .= '&state='.$kakaoConfig['state'];
		// 로그인 인증토큰 요청 URL
		$kakaoConfig['login_token_url']  = 'https://kauth.kakao.com/oauth/token?';
		$kakaoConfig['login_token_url'] .= 'grant_type=authorization_code';
		$kakaoConfig['login_token_url'] .= '&client_id='.$kakaoConfig['client_id'];
		$kakaoConfig['login_token_url'] .= '&redirect_uri='.$kakaoConfig['redirect_uri'];
		$kakaoConfig['login_token_url'] .= '&client_secret='.$data['H_KAKAO_CLIENT'];
		$kakaoConfig['login_token_url'] .= '&code='.$kakaoConfig['code'];
//											https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=".$REST_API_KEY."&redirect_uri=".$REDIRECT_URI."&code=".$code."&client_secret=".$CLIENT_SECRET;
		// 프로필정보 호출 URL
		$kakaoConfig['profile_url'] = 'https://kapi.kakao.com/v2/user/me';

//		echo "<pre>"; print_r($kakaoConfig); echo "</pre>"; exit();

		return $kakaoConfig;
	}

	public function kakaoTokenData($kakaoConfig)
	{

		$login_token_url = $kakaoConfig['login_token_url'];

		$getTokenData = $this->curl_kakao($login_token_url, 'POST');
//		$token_data = json_decode($getTokenData);
//		echo "<pre>"; print_r($getTokenData); echo "</pre>"; exit();

		return $getTokenData;
	}

	public function kakaoProfileData($kakaoConfig, $kakaoToken)
	{
		$header = array("Authorization: Bearer ".$kakaoToken->access_token);
		$profile_url = $kakaoConfig['profile_url'];
		$getPdata = $this->curl_kakao($profile_url, 'POST', $header);
//		$profile_data = json_decode($getPdata);

//		echo "<pre>"; print_r($getPdata); echo "</pre>";

		return $getPdata;
	}

	//카카오 에러 페이지
	public function kakaoError()
	{
		$result['menu']	= '네트워크 오류 안내';
		$result['branchInfo'] = $this->branchData;

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/signup/registError', $result, true);
	}

}
