<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller
{
	public $menu = 'menu2';
	public $nav = array('navTitle' => '의료진 소개', 'navLink1' => '/home_admin/doctor', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Doctor_model', 'Doctor_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$baseBranch = $B_BRANCH;
		$data['searchBranch'] = $this->input->get('searchBranch');
		$data['searchLan'] = $B_LAN;

		$doctorList = $this->Doctor_model->selectDoctorInfo($baseBranch, $data);
//		echo "<pre>"; print_r($doctorList); echo "</pre>";
		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/doctor', count($doctorList), $data['page'], $data['page_num'], $data['list_num']);

		//return search value
		$result['searchBranch'] = $baseBranch;
		$result['searchBranch2'] = $data['searchBranch'];
		$result['doctorList'] = $doctorList;

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
		$this->layout->view('/admin/content/doctor/doctor', $result, true);

	}

	public function registDoctor($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/doctor/doctor_modify', $result, true);
	}

	public function doctorModify($B_BRANCH = null, $B_LAN = null, $seq = null)
	{
//		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;
		$data['SEQ'] = $seq;

		$result['branchList'] = $this->Branch_model->getBranchList();

		$doctorList = $this->Doctor_model->selectDoctorInfo($B_BRANCH, $data);
		$result['info'] = $doctorList[0];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/doctor/doctor_modify', $result, true);
	}

	public function save()
	{
		$SEQ = $this->input->post('SEQ');
		$data['D_BRANCH'] = $this->input->post('D_BRANCH');
		$data['D_LAN'] = $this->input->post('D_LAN');
		$D_IMG_PATH = $this->input->post('D_IMG_PATH');
		$data['D_IMG_PATH'] = $D_IMG_PATH[0];
		$data['D_NAME'] = $this->input->post('D_NAME');
		$data['D_NAME_EN'] = $this->input->post('D_NAME_EN');
		$data['D_DESC'] = $this->input->post('D_DESC');
		$data['D_MAIN_YN'] = $this->input->post('D_MAIN_YN');
		$data['D_USE_YN'] = $this->input->post('D_USE_YN');
		$data['SD_BRANCH'] = $this->input->post('SD_BRANCH');

		if ( isset($SEQ) && $SEQ !== '' ) {
			$data['MOD_DATE'] = date('Y-m-d H:i:s');
			$result = $this->Doctor_model->updateDoctorInfo($data, $SEQ);
		} else {
			$result = $this->Doctor_model->insertDoctorInfo($data);
		}

		if ( $result ) {
			$mode = isset($SEQ) && $SEQ !== '' ? 'UPDATE' : 'INSERT';
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData($mode, 'ppeum_homepage.P_DOCTOR_INFO', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/doctor/index/".$data['SD_BRANCH']."/".$data['D_LAN']."');</script>";
		} else {
			echo "<script>alert('저장에 실패하였습니다.'); location.reload();</script>";
		}

	}


	public function delete()
	{
		$SEQ = $this->input->post('SEQ');

		$data['D_DEL_YN'] = 'Y';
		$data['MOD_DATE'] = date('Y-m-d H:i:s');
		$result = $this->Doctor_model->deleteDoctorInfo($data, $SEQ);


		if ( $result ) {
			$data['SEQ'] = $SEQ; // PK 포함
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_DOCTOR_INFO', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제 하였습니다.'); location.replace('/home_admin/doctor/index/".$data['D_BRANCH']."/".$data['D_LAN']."');</script>";
		} else {
			echo "<script>alert('삭제에 실패하였습니다.'); location.reload();</script>";
		}

	}

}
