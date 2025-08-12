<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Defaultset extends CI_Controller {
	public $menu = 'menu1';
	public $nav = array('navTitle' => '기본정보 설정', 'navLink1' => '/home_admin/default', 'navTitle2' => '', 'navLink2' => '');

	public $branchList;

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model( '/home_admin/Branch_model', 'Branch_model' );
		$this->load->model( '/home_admin/DefaultSet_model', 'DefaultSet_model' );

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' && $data['searchBranch'] != '' ) {
			$result['branchListData'] = $this->Branch_model->getBranchCountryList($data);
		}
//		echo "<pre>"; print_r($result['branchListData']); echo "</pre>"; exit();

		//$result['lanListData'] = $this->Language_model->getLanguageList();
//		foreach ( $lanListData as $index => $row ) {
//			$lanListData[$index]['BRANCHLIST'] = $this->Language_model->getBranchLanguageList($row['COUNTRY']);
//		}

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/defaultset/index/', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/defaultset/defaultset', $result, true);
	}

	public function modifyset($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;
		//
		$info = $this->DefaultSet_model->getBranchPpeumInfo($B_BRANCH, $B_LAN);
		if ( isset($info) && count($info) > 0 ) {
			$result['mode'] = 'update';
			$result['info'] = $info[0];
		} else {
			$result['mode'] = 'insert';
			$result['info'] = array();
		}
//		$result['info'] = $info[0];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/defaultset/defaultset_modify', $result, true);
	}


	public function modify_default()
	{
		$MODE = $this->input->post('MODE');
		$BRANCH = $this->input->post('BRANCH');
		$LANGUAGE = $this->input->post('LANGUAGE');
		$imgFile = $this->input->post('imgFile');
		$data['H_FAVICON'] = $imgFile[0];
		$data['H_TITLE'] = $this->input->post('H_TITLE');
		$data['H_KEYWORDS'] = $this->input->post('H_KEYWORDS');
		$data['H_DESCRIPTION'] = $this->input->post('H_DESCRIPTION');
		$data['H_KAKAO_KEY'] = $this->input->post('H_KAKAO_KEY');
		$data['H_KAKAO_CLIENT'] = $this->input->post('H_KAKAO_CLIENT');
		$data['H_NAVER_CLIENT'] = $this->input->post('H_NAVER_CLIENT');
		$data['H_NAVER_SECRET'] = $this->input->post('H_NAVER_SECRET');
		$data['H_NAVER_SCRIPT'] = $this->input->post('H_NAVER_SCRIPT');
		$data['H_KAKAO_CHAT'] = $this->input->post('H_KAKAO_CHAT');
		$data['H_CONTACT1_2'] = $this->input->post('H_CONTACT1_2');
		$data['H_MESSAGE_TITLE'] = $this->input->post('H_MESSAGE_TITLE');
		$data['H_PVL_OFFICENUMBER'] = $this->input->post('H_PVL_OFFICENUMBER');
		$data['H_PVL_KAKAO_CHAT'] = $this->input->post('H_PVL_KAKAO_CHAT');

//		$data['H_LOCATION1'] = $this->input->post('H_LOCATION1');
//		$data['H_LOCATION2'] = $this->input->post('H_LOCATION2');
//		$data['H_LOCATION3'] = $this->input->post('H_LOCATION3');
//		$data['H_LOCATION4'] = $this->input->post('H_LOCATION4');
//		$data['H_LOCATION5'] = $this->input->post('H_LOCATION5');
//		$data['H_CONTACT1_1'] = $this->input->post('H_CONTACT1_1');
//		$data['H_CONTACT1_2'] = $this->input->post('H_CONTACT1_2');
//		$data['H_CONTACT2_1'] = $this->input->post('H_CONTACT2_1');
//		$data['H_CONTACT2_2'] = $this->input->post('H_CONTACT2_2');
//		$data['H_OFFICE_HOUR1_1'] = $this->input->post('H_OFFICE_HOUR1_1');
//		$data['H_OFFICE_HOUR1_2'] = $this->input->post('H_OFFICE_HOUR1_2');
//		$data['H_OFFICE_HOUR2_1'] = $this->input->post('H_OFFICE_HOUR2_1');
//		$data['H_OFFICE_HOUR2_2'] = $this->input->post('H_OFFICE_HOUR2_2');
//		$data['H_OFFICE_HOUR3_1'] = $this->input->post('H_OFFICE_HOUR3_1');
//		$data['H_OFFICE_HOUR3_2'] = $this->input->post('H_OFFICE_HOUR3_2');
//		$data['H_OFFICE_HOUR4_1'] = $this->input->post('H_OFFICE_HOUR4_1');
//		$data['H_OFFICE_HOUR4_2'] = $this->input->post('H_OFFICE_HOUR4_2');
//		$data['H_NAVER_MAP'] = $this->input->post('H_NAVER_MAP');
//		$data['H_KAKAO_MAP'] = $this->input->post('H_KAKAO_MAP');
//		$data['H_T_MAP'] = $this->input->post('H_T_MAP');
//		$data['H_KAKAO_MAP_KEY'] = str_replace('<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>', '', $this->input->post('H_KAKAO_MAP_KEY'));
//		$data['H_KAKAO_MAP_KEY'] = preg_replace('/<!--(.*?)-->/is', '', $data['H_KAKAO_MAP_KEY']);
//
//
//		// 주차장
//		$H_PARK_TITLE = $this->input->post('H_PARK_TITLE');
//		$H_PARK_ADDRESS = $this->input->post('H_PARK_ADDRESS');
//		$H_PARK_DESC1 = $this->input->post('H_PARK_DESC1');
//		$H_PARK_DESC2 = $this->input->post('H_PARK_DESC2');
//		$H_PARK_DESC3 = $this->input->post('H_PARK_DESC3');
//		$H_PARK_KAKAO = $this->input->post('H_PARK_KAKAO');
//		$H_PARK_NAVER = $this->input->post('H_PARK_NAVER');
//		$H_PARK_TMAP = $this->input->post('H_PARK_TMAP');
//		$H_PARK_KAKAO_MAP = $this->input->post('H_PARK_KAKAO_MAP');
//
//		foreach ( $H_PARK_TITLE as $index => $val ) {
//			if ( isset($val) && $val !== '' ) {
//				$H_PARKING[$index]['H_PARK_TITLE'] = $val;
//				$H_PARKING[$index]['H_PARK_ADDRESS'] = isset($H_PARK_ADDRESS[$index]) ? $H_PARK_ADDRESS[$index] : '';
//				$H_PARKING[$index]['H_PARK_DESC1'] = isset($H_PARK_DESC1[$index]) ? $H_PARK_DESC1[$index] : '';
//				$H_PARKING[$index]['H_PARK_DESC2'] = isset($H_PARK_DESC2[$index]) ? $H_PARK_DESC2[$index] : '';
//				$H_PARKING[$index]['H_PARK_DESC3'] = isset($H_PARK_DESC3[$index]) ? $H_PARK_DESC3[$index] : '';
//				$H_PARKING[$index]['H_PARK_KAKAO'] = isset($H_PARK_KAKAO[$index]) ? $H_PARK_KAKAO[$index] : '';
//				$H_PARKING[$index]['H_PARK_NAVER'] = isset($H_PARK_NAVER[$index]) ? $H_PARK_NAVER[$index] : '';
//				$H_PARKING[$index]['H_PARK_TMAP'] = isset($H_PARK_TMAP[$index]) ? $H_PARK_TMAP[$index] : '';
//				$H_PARKING[$index]['H_PARK_KAKAO_MAP'] = isset($H_PARK_KAKAO_MAP[$index]) ? $H_PARK_KAKAO_MAP[$index] : '';
//				$H_PARKING[$index]['H_PARK_KAKAO_MAP'] = str_replace('<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>', '', $H_PARK_KAKAO_MAP[$index]);
//				$H_PARKING[$index]['H_PARK_KAKAO_MAP'] = preg_replace('/<!--(.*?)-->/is', '', $H_PARKING[$index]['H_PARK_KAKAO_MAP']);
//				$H_PARKING[$index]['H_PARK_KAKAO_MAP'] = preg_replace('/\r\n|\r|\n/','',$H_PARKING[$index]['H_PARK_KAKAO_MAP']);
//			}
//		}
//		$data['H_PARKING'] = json_encode($H_PARKING, JSON_UNESCAPED_UNICODE);

//		echo "<pre>"; print_r($data); echo "</pre>"; exit();
		if ( $MODE === 'insert' ) {
			$data['BRANCH'] = $BRANCH;
			$data['LANGUAGE'] = $LANGUAGE;
			$res = $this->DefaultSet_model->insertBranchPpeumInfo($data);
			$text = "저장";
		} else {
			$res = $this->DefaultSet_model->updateBranchPpeumInfo($data, $BRANCH, $LANGUAGE);
			$text = "수정";
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			if ( $MODE === 'insert' ) {
				$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_PPEUM_INFO', json_encode($data, JSON_UNESCAPED_UNICODE));
			} else {
				$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_PPEUM_INFO', json_encode($data, JSON_UNESCAPED_UNICODE));
			}

			echo "<script>alert('".$text."하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('".$text."에 실패하였습니다.'); location.reload();</script>";
		}

	}


	public function addPark()
	{
		$data['id'] = $this->input->get('id');
		$this->load->view('admin/content/defaultset/add_park', $data);
	}



}
