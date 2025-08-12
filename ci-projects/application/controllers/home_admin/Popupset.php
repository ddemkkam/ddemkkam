<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popupset extends CI_Controller
{
	public $menu = 'menu1';
	public $nav = array('navTitle' => '팝업 설정', 'navLink1' => '/home_admin/popupset', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/PopupSet_model', 'PopupSet_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index()
	{
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		$result['branchListData'] = $this->Branch_model->getBranchCountryList($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/termsSet', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/popupset/popupset', $result, true);
	}

	public function modifypopup($BRANCH, $LANGAUE)
	{
		$result['BRANCH'] = $BRANCH;
		$result['LANGAUE'] = $LANGAUE;
		$getBranchInfo = $this->Branch_model->getBranchInfo($BRANCH);
		$result['BRANCH_NAME'] = $getBranchInfo[0]['BRANCH_NAME'];

		$result['info'] = $this->PopupSet_model->getPopupData($BRANCH, $LANGAUE);

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $BRANCH;
		$result['BASELAN'] = $LANGAUE;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($BRANCH, $LANGAUE);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/popupset/popupset_modify', $result, true);
	}

	public function save()
	{
		$data['P_BRANCH'] = $this->input->post('P_BRANCH');
		$data['P_LANGUAGE'] = $this->input->post('P_LANGUAGE');
		$data['P_TITLE'] = $this->input->post('P_TITLE');
		$data['P_START_DATE'] = $this->input->post('P_START_DATE');
		$data['P_START_TIME'] = $this->input->post('P_START_TIME');
		$data['P_FINISH_DATE'] = $this->input->post('P_FINISH_DATE');
		$data['P_FINISH_TIME'] = $this->input->post('P_FINISH_TIME');
		$data['P_IMG_PATH'] = $this->input->post('P_IMG_PATH');
		$data['P_IS_ALWAYS'] = $this->input->post('P_IS_ALWAYS');

		//echo "<pre>"; print_r($data); echo "</pre>";

		//이미지 없는거 정리
		for ( $i = 0, $j = 0; $i < count($data['P_IMG_PATH']); $i++ ) {
			if ( isset($data['P_IMG_PATH'][$i]) && $data['P_IMG_PATH'][$i] !== '' ) {
				$insertData[$j]['P_IMG_PATH'] = $data['P_IMG_PATH'][$i];
				$insertData[$j]['P_BRANCH'] = $data['P_BRANCH'];
				$insertData[$j]['P_LANGUAGE'] = $data['P_LANGUAGE'];
				$insertData[$j]['P_TITLE'] = $data['P_TITLE'][$i];
				$insertData[$j]['P_START_DATE'] = $data['P_START_DATE'][$i];
				$insertData[$j]['P_START_TIME'] = $data['P_START_TIME'][$i];
				$insertData[$j]['P_FINISH_DATE'] = $data['P_FINISH_DATE'][$i];
				$insertData[$j]['P_FINISH_TIME'] = $data['P_FINISH_TIME'][$i];
				$insertData[$j]['P_SORT'] = $j;
				$insertData[$j]['P_IS_ALWAYS'] = $data['P_IS_ALWAYS'][$i] == "checked" ? "true" : "false";

				$j++;
			}
		}

		//기존 데이터 초기화
		$this->PopupSet_model->deletePopup($data['P_BRANCH'], $data['P_LANGUAGE']);

		//데이터 세팅
		foreach ( $insertData as $index => $row ) {
			$res = $this->PopupSet_model->insertPopup($row);
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_POPUP_SET', json_encode($insertData, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}

	}

}
