<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegistMember extends CI_Controller
{
	public $menu = '이름/연락처 로그인';
	private $MemberModel;
	private $ApiHostNamu;
	private $BranchInfo;
	private $apiUrl;
	private $branchData;
	private $branch;
	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'cookie'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'branchInfo', 'homepageSession')); //, 'homepageLoginCheck'

		$this->load->model('/homepage/Member_model');

		$this->MemberModel = new Member_model();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BranchInfo = new BranchInfo();

		$this->apiUrl = $this->ApiHostNamu->getNamuApiUrl();
		$this->branchData = $this->BranchInfo->getBranchInfo();
		$this->branch = $this->BranchInfo->getBranch();
	}
	public function index()
	{
		$branchName = $this->BranchInfo->getBranchName();

		$result = array(
			'menu' => $this->menu,
			'type' => $this->input->get('type'),
			'id' => $this->input->get('id'),
			'name' => $this->input->get('name'),
			'number' => $this->input->get('number')
		);

		//카카오 로그인시
		if ($result['type'] == 'kakao') {
			$result['menu'] = '회원정보 입력';
			//CRM DB 회원 여부 확인 (이름/연락처)
			$crmData = $this->ApiHostNamu->getUserCheck($result);
			//CRM DB 회원 계정 수 확인 (단일 = 로그인, 복수 = 오류 페이지)
			if ($crmData['cnt'] == 1) {
				//로그인 키값
				$loginKey = $crmData['cPublicCi'];
				//CRM DB 기준 홈페이지 DB 회원 연동 여부 확인
				if (!empty($crmData['cPublicCi']) && $crmData['cPublicCi'] != 'good_vibe') {
					//CRM DB 회원 정보 조회
					$info = $this->ApiHostNamu->getUserLoginInfo($crmData['cPublicCi']);
					//홈페이지 DB 회원 정보 조회
					$homeData = $this->MemberModel->getMemberInfo($crmData['cPublicCi'], $this->branch);
					//홈페이지 DB 기준 회원 정보 연동 확인
					if (!isset($homeData)) {
						$insert = array(
							'M_BRANCH' => $this->branch
							,'M_ID' => $info->result->C_NUMBER
							,'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
							,'M_TYPE' => $result['type']
							,'M_USE' => $info->result->C_IS_AGREEMENT
							,'M_PUB' => $info->result->C_IS_AGREEMENT
							,'M_MARKET' => $info->result->C_IS_MARKTING
						);
						//홈페이지 DB 기준 회원 정보 없을때, 홈페이지 DB 회원 등록 처리
						$this->MemberModel->setMemberData($insert);
					}
					//로그인후 이전 페이지로 이동
					$this->setLogin($loginKey);
				} else {
					//홈페이지 DB 회원 여부 확인
					$homeData = $this->MemberModel->getMemberInfo($result['id'], $this->branch);
					//CRM DB 에는 정보가 없고, 홈페이지 DB 에는 정보가 있을때
					if (isset($homeData)) {
						//CRM DB의 연동 키값 홈페이지 DB의 연동 키값 으로 업데이트
						$this->ApiHostNamu->setCPublicCiUpdate($homeData->M_ID, $homeData->M_PUBLIC_CI);
						//로그인 키값 업데이트
						$loginKey = $homeData->M_PUBLIC_CI;
						//로그인후 이전 페이지로 이동
						$this->setLogin($loginKey);
					}
				}
			} else if ($crmData['cnt'] > 1) {
				//회원 중복 오류 페이지로 이동
				$this->signUpError();
			}
		}
		//카카오 알림톡 메세지
		$result['sendCodeTitle'] = '[' . $this->branchData['H_MESSAGE_TITLE'] . ']\r\n휴대폰 인증코드가 발송되었습니다.\r\n';
		$result['branch'] = $this->branch;
		$result['template'] = $this->branchData['H_KAKAO_TEMPLATE'];
		//회원가입 페이지로 이동
		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/regist_member/signup', $result, true);
	}


	public function registMember()
	{
		$this->BranchInfo = new BranchInfo();
		$data['branch'] = $this->branch;

		$data['M_ID'] = $this->input->post('id');
		$data['M_TYPE'] = $this->input->post('type');
		$data['M_NAME'] = $this->input->post('userName');
		//$data['M_BIRTHDAY'] = $this->input->post('birth');
		$data['M_PHONE'] = $this->input->post('phone');
		$data['M_USE'] = $this->input->post('agree1');
		$data['M_PUB'] = $this->input->post('agree2');
		$data['M_MARKET'] = $this->input->post('agree3');

//		echo "<pre>"; print_r($data); echo "</pre>"; exit();

		/*
		 * 1. 다른 경로를 통해서 회원가입이 있는 체크
		 * 2. 없다면 회원가입
		 *    - crm 회원 등록
		 * 3. 있다면 data insert 후 group 값 동기화(최초 등록된 public ci 값으로 통일)
		 */

		// 1. 다른 경로를 통해서 회원가입이 있는 체크
		$dupResult = $this->MemberModel->getMemberDupCheck($data);
		$memberCheck = false;
		if ( isset($dupResult) && count($dupResult) > 0 ) {
			$memberCheck = true;
		}

		// 2. 없다면 회원가입
		if ( !$memberCheck ) {
			$this->load->helper('string');

			//중복 키값 확인
			$resGroupKey = $this->groupKeyCheck();

			$insertData['M_BRANCH'] = $data['branch'];
			$insertData['M_ID'] = $data['M_ID'];
			$insertData['M_PUBLIC_CI'] = $data['M_ID'];
			$insertData['M_GROUP'] = $resGroupKey;
			$insertData['M_TYPE'] = $data['M_TYPE'];
			$insertData['M_NAME'] = $data['M_NAME'];
			//$insertData['M_BIRTHDAY'] = $data['M_BIRTHDAY'];
			$insertData['M_PHONE'] = $data['M_PHONE'];
			$insertData['M_USE'] = $data['M_USE'];
			$insertData['M_PUB'] = $data['M_PUB'];
			$insertData['M_MARKET'] = $data['M_MARKET'];

			$insResult = $this->MemberModel->setMemberData($insertData);
//			echo "<pre>"; print_r($insResult); echo "</pre>"; exit();

			if ( $insResult ) {
				//CRM 회원 등록
				$sData['type'] = '내국인';
				$sData['name'] = $insertData['M_NAME'];
				//$sData['birth'] = $insertData['M_BIRTHDAY'];
				$sData['hp'] = $insertData['M_PHONE'];
				$sData['public_ci'] = $insertData['M_PUBLIC_CI'];
				$sData['is_agreement'] = $insertData['M_PUB'];
				$sData['is_marketing'] = $insertData['M_MARKET'];

				$send_data = json_encode($sData);
				$sendUrl = $this->apiUrl . "/v1/public/customer";
				$curl_Result = $this->ApiHostNamu->setReservation($sendUrl, $send_data);
				$jsonRes = json_decode($curl_Result);
//				echo "<pre>"; print_r($jsonRes); echo "</pre>"; exit();

				if ( $jsonRes->code === 201 ) {
					$this->MemberModel->modMemberData($insertData['M_ID']);

					//세션 저장
					$this->HomepageSession = new HomepageSession();
					$this->HomepageSession->setSession($insertData);

					$this->load->helper('cookie');
					$referer = $this->input->cookie('referer');

					delete_cookie('referer');
					delete_cookie('membercookie');

//					echo "<script>location.replace('".$referer."');</script>";
					echo "<script>location.replace('/registMember/signUpComplete');</script>";
				}

			}

		}

		// 3. 있다면 data insert 후 group 값 동기화(최초 등록된 public ci 값으로 통일)


	}


	public function groupKeyCheck()
	{
		$groupKey = random_string('basic', 12);

		$dupResult = $this->MemberModel->getGroupKeyCheck($groupKey);

		if ( isset($dupResult) && count($dupResult) > 0 ) {
			$this->groupKeyCheck();
		}

		return $groupKey;
	}


	public function signUpComplete()
	{
		$result['menu']	= '회원가입 완료';
		$result['branchInfo'] = $this->branchData;

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/signup/complete', $result, true);
	}

	public function signUpError()
	{
		$result['menu']	= '중복회원 안내';
		$result['branchInfo'] = $this->branchData;
		$result['name'] = $this->input->get('name');
		$result['number'] = $this->input->get('number');

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/signup/error', $result, true);
	}

	public function setRegistCode()
	{
		$parameters = Array(
			'type' => $this->input->post('type'),
			'id' => $this->input->post('id'),
			'name' => $this->input->post('name'),
			'number' => $this->input->post('number')
		);

		$result = $this->ApiHostNamu->getUserCheck($parameters);

		echo json_encode($result);
	}

	public function setRegistMember()
	{
		//계정 보유 여부
		$hasCount = $this->input->post('hasCount');

		$send = array(
			'type' => '내국인'
			,'name' => $this->input->post('name')
			,'hp' => $this->input->post('hp')
			,'public_ci' => $this->input->post('id') ?: 'good_vibe'
			,'is_agreement' => $this->input->post('m_pub')
			,'is_marketing' => $this->input->post('m_market')
		);

		$insert = array(
			'M_BRANCH' => $this->branch
			,'M_ID' => ''
			,'M_PUBLIC_CI' => $this->input->post('id')
			,'M_TYPE' => $this->input->post('type')
			,'M_USE' => $this->input->post('m_use')
			,'M_PUB' => $this->input->post('m_pub')
			,'M_MARKET' => $this->input->post('m_market')
		);

		if ($hasCount == 0) {
			$sendUrl = $this->apiUrl . "/v1/public/customer";

			$curlResult = $this->ApiHostNamu->setReservation($sendUrl, json_encode($send));
			$jsonResult = json_decode($curlResult);

			if ($jsonResult->code === 201 ) {
				if ($this->input->post('type') == 'normal') {
					$send['public_ci'] = 'CRM_'.$jsonResult->data->number;
					$this->ApiHostNamu->setReservation($sendUrl, json_encode($send));

					$insert['M_PUBLIC_CI'] = 'CRM_'.$jsonResult->data->number;
				}

				$insert['M_ID'] = $jsonResult->data->number;

				$this->MemberModel->setMemberData($insert);

				$info = $this->ApiHostNamu->getUserLoginInfo($insert['M_PUBLIC_CI']);

				$login = array(
					'M_ID' => $info->result->C_NUMBER
					,'M_NAME' => $info->result->C_NAME
					,'M_PHONE' => $info->result->C_HP
					,'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
				);

				$this->HomepageSession = new HomepageSession();
				$this->HomepageSession->setSession($login);
			}
		} else if ($hasCount == 1) {
			if ($this->input->post('type') == 'normal') {
				$send['public_ci'] = 'CRM_' . $this->input->post('cNumber');
				$insert['M_PUBLIC_CI'] = 'CRM_' . $this->input->post('cNumber');
			} else {
				$send['public_ci'] = $this->input->post('id');
				$insert['M_PUBLIC_CI'] = $this->input->post('id');
			}
			$insert['M_ID'] = $this->input->post('cNumber');

			$sendUrl = $this->apiUrl . "/v1/public/customer";
			$this->ApiHostNamu->setReservation($sendUrl, json_encode($send));

			$this->MemberModel->setMemberData($insert);

			$info = $this->ApiHostNamu->getUserLoginInfo($insert['M_PUBLIC_CI']);

			$login = array(
				'M_ID' => $info->result->C_NUMBER
				,'M_NAME' => $info->result->C_NAME
				,'M_PHONE' => $info->result->C_HP
				,'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
			);

			$this->HomepageSession = new HomepageSession();
			$this->HomepageSession->setSession($login);

		}

		$result = array(
			'success' => true
		);

		echo json_encode($result);
	}

	public function setRegistMemberLogin()
	{
		$cPublicCi = $this->input->post('cPublicCi');

		$this->load->helper('cookie');
		$referer = $this->input->cookie('referer') ?? '/mypage';

		//CRM DB 회원 정보 조회
		$info = $this->ApiHostNamu->getUserLoginInfo($cPublicCi);

		$login = array(
			'M_ID' => $info->result->C_NUMBER
			,'M_NAME' => $info->result->C_NAME
			,'M_PHONE' => $info->result->C_HP
			,'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
		);

		$this->HomepageSession = new HomepageSession();
		$this->HomepageSession->setSession($login);

		echo json_encode(array(
			'success' => true
			,'referer' => $referer
		));
	}

	private function setLogin($mPublicCi, $return = true)
	{
		//CRM DB 회원 정보 조회
		$info = $this->ApiHostNamu->getUserLoginInfo($mPublicCi);

		//로그인 정보 세팅
		$login = array(
			'M_ID' => $info->result->C_NUMBER
			,'M_NAME' => $info->result->C_NAME
			,'M_PHONE' => $info->result->C_HP
			,'M_PUBLIC_CI' => $info->result->C_PUBLIC_CI
		);

		$this->HomepageSession = new HomepageSession();
		$this->HomepageSession->setSession($login);

		if ($return) {
			$this->load->helper('cookie');
			$referer = $this->input->cookie('referer');
			delete_cookie('referer');

			echo "<script>location.replace('".$referer."');</script>";
		}
	}



}
