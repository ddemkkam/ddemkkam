<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Footer extends CI_Controller {
	public $menu = 'menu1';
	public $nav = array('navTitle' => '푸터 설정', 'navLink1' => '/home_admin/default', 'navTitle2' => '', 'navLink2' => '');

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

	public function modifyfooter($B_BRANCH = null, $B_LAN = null)
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
		$this->layout->view('/admin/content/defaultset/defaultfooter_modify', $result, true);
	}


	public function modify_default()
	{
		$MODE = $this->input->post('MODE');
		$BRANCH = $this->input->post('BRANCH');
		$LANGUAGE = $this->input->post('LANGUAGE');

		$data['H_ADDRESS'] = $this->input->post('H_ADDRESS');
		$data['H_NAME'] = $this->input->post('H_NAME');
		$data['H_CONTACT_M'] = $this->input->post('H_CONTACT_M');
		$data['H_CEO'] = $this->input->post('H_CEO');
		$data['H_OFFICENUMBER'] = $this->input->post('H_OFFICENUMBER');

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
