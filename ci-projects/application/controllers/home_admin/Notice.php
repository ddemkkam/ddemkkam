<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller
{
	public $menu = 'menu5';
	public $nav = array('navTitle' => '공지사항', 'navLink1' => '/home_admin/notice', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
		$this->load->model('/home_admin/Notice_model', 'Notice_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();

	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		$data['branchList'] = $this->Branch_model->getBranchList();
		//echo "<pre>"; print_r($data['branchList']); echo "</pre>";

		$result['branchListData'] = $this->Notice_model->getNoticeList($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/notice', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];
		$result['branchList'] = $data['branchList'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/notice/notice', $result, true);
	}

	public function delete()
	{
		$data['SEQ'] = $this->input->post('SEQ');

		$updateData['N_DEL_YN'] = 'Y';
		$updateData['DEL_DATE'] = date("Y-m-d H:i:s");

		$res = $this->Notice_model->delete_notice($updateData, $data['SEQ']);
		$updateData['SEQ'] = $data['SEQ'];

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_NOTICE', json_encode($updateData, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패 하였습니다.'); </script>";
		}
	}

	public function modifynotice($B_BRANCH = null, $B_LAN = null, $SEQ = null)
	{
		if ( isset($SEQ) && $SEQ !== '' ) {
			$info = $this->Notice_model->getNoticeInfo($SEQ);
			$result['info'] = $info[0];
		}
//		echo "<pre>"; print_r($result['info']); echo "</pre>";

		$result['branchList'] = $this->Branch_model->getBranchList();
		$result['lanListData'] = $this->Language_model->getLanguageList();

		//$result['branchListData'] = $this->Branch_model->getBranchCountryList($data);
		//echo "<pre>"; print_r($result['branchListData']); echo "</pre>";

		//
		$result['SEQ'] = $SEQ;

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/notice/notice_register', $result, true);
	}

	public function save()
	{
		$data['SEQ'] = $this->input->post('SEQ');
		$data['N_BRANCH'] = $this->input->post('N_BRANCH');
		$data['N_LANGUAGE'] = $this->input->post('N_LANGUAGE');
		$data['N_TITLE'] = $this->input->post('N_TITLE');
		$data['N_CONTEXT'] = $this->input->post('N_CONTEXT');
		$data['N_COUNT'] = $this->input->post('N_COUNT');
		$data['N_REG_ID'] = $this->input->post('N_REG_ID');

		if ( isset($data['SEQ']) && $data['SEQ'] !== '' ) {
			//modify
			$updateData['N_BRANCH'] = $data['N_BRANCH'];
			$updateData['N_LANGUAGE'] = $data['N_LANGUAGE'];
			$updateData['N_TITLE'] = $data['N_TITLE'];
			$updateData['N_CONTEXT'] = $data['N_CONTEXT'];
			$updateData['N_COUNT'] = $data['N_COUNT'];
			$updateData['N_REG_ID'] = $data['N_REG_ID'];

			$res = $this->Notice_model->update_notice($updateData, $data['SEQ']);

			if ( $res ) {
				$this->HistoryData = new HistoryData();
				$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_NOTICE', json_encode($data, JSON_UNESCAPED_UNICODE));

				echo "<script>alert('수정 하였습니다.'); location.replace('/home_admin/notice/index/".$data['N_BRANCH']."/".$data['N_LANGUAGE']."');</script>";
			} else {
				echo "<script>alert('수정에 실패 하였습니다.'); </script>";
			}
		} else {
			//insert
			$insertData['N_BRANCH'] = $data['N_BRANCH'];
			$insertData['N_LANGUAGE'] = $data['N_LANGUAGE'];
			$insertData['N_TITLE'] = $data['N_TITLE'];
			$insertData['N_CONTEXT'] = $data['N_CONTEXT'];
			$insertData['N_COUNT'] = $data['N_COUNT'];
			$insertData['N_REG_ID'] = $data['N_REG_ID'];

			$res = $this->Notice_model->insert_notice($insertData);

			if ( $res ) {
				$this->HistoryData = new HistoryData();
				$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_NOTICE', json_encode($insertData, JSON_UNESCAPED_UNICODE));

				echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/notice/index/".$data['N_BRANCH']."/".$data['N_LANGUAGE']."');</script>";
			} else {
				echo "<script>alert('저장에 실패 하였습니다.'); </script>";
			}
		}

	}

}
