<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HospitalInfo extends CI_Controller
{
	public $menu = 'menu2';
	public $nav = array('navTitle' => '둘러보기', 'navLink1' => '/home_admin/hospitalinfo', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/HospitalInfo_model', 'HospitalInfo_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		if ( isset($data['searchBranch']) && $data['searchBranch'] !== 'all' && $data['searchBranch'] != '' ) {
			$result['branchListData'] = $this->Branch_model->getBranchList($data);
		} else {
			$result['branchListData'] = array();
		}
//		echo "<pre>"; print_r($result['branchList']); echo "</pre>"; exit();

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/branch', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//return search value
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/hospitalInfo/hospitalInfo', $result, true);

	}

	public function Hospitalmodify($B_BRANCH = null, $B_LAN = null)
	{

		$result['BRANCH'] = $B_BRANCH;
		$result['LAN'] = $B_LAN;
		$getBranchInfo = $this->Branch_model->getBranchInfo($B_BRANCH);
		$result['BRANCH_NAME'] = $getBranchInfo[0]['BRANCH_NAME'];

		$infoData = $this->HospitalInfo_model->getHospitalInfo($B_BRANCH, $B_LAN);
//		echo "<pre>"; print_r($infoData); echo "</pre>"; exit();

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$result['info'] = $infoData;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/hospitalInfo/hospital_modify', $result, true);
	}

	public function save()
	{
		$HI_BRANCH = $this->input->post('HI_BRANCH');
		$HI_LAN = $this->input->post('HI_LAN');
		$HI_TITLE = $this->input->post('HI_TITLE');
		$HI_IMG_PATH = $this->input->post('HI_IMG_PATH');

		//데이터 초기화
		$this->HospitalInfo_model->deleteHospitalInfo($HI_BRANCH, $HI_LAN, $HI_TITLE, $HI_IMG_PATH);

		$result = false; // 기본값 설정

		$this->HistoryData = new HistoryData();

		/*
		 * 데이터 저장
		 * 이미지 설정이 되어 있지 않은 데이터는 저장 X
		 */
		foreach ( $HI_IMG_PATH as $index => $val ) {
			if ( $val !== '' ) {
				$insertData = [
					'HI_BRANCH' => $HI_BRANCH,
					'HI_LAN' => $HI_LAN,
					'HI_TITLE' => $HI_TITLE[$index],
					'HI_IMG_PATH' => $val,
					'HI_SORT' => $index,
				];

				$inserted = $this->HospitalInfo_model->insertHospitalInfo($insertData);

				if($inserted) {
					$result = true;
				}

				$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_HOSPITAL_INFO', json_encode($insertData, JSON_UNESCAPED_UNICODE));
			}
		}

		if ( $result ) {
			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패하였습니다.'); location.reload();</script>";
		}

	}

}
