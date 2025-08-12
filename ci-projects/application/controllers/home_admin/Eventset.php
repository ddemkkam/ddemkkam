<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventset extends CI_Controller
{
	public $menu = 'menu2';
	public $nav = array('navTitle' => '이벤트 비쥬얼 설정', 'navLink1' => '/home_admin/eventset', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/EventSet_model', 'EventSet_model');
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
		$this->layout->view('/admin/content/eventset/eventset', $result, true);
	}

	public function modifyevent($BRANCH, $LANGAUE)
	{
		$result['BRANCH'] = $BRANCH;
		$result['LANGAUE'] = $LANGAUE;
		$getBranchInfo = $this->Branch_model->getBranchInfo($BRANCH);
		$result['BRANCH_NAME'] = $getBranchInfo[0]['BRANCH_NAME'];

		$result['info'] = $this->EventSet_model->getEventData($BRANCH, $LANGAUE);

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/eventset/eventset_modify', $result, true);
	}

	public function save()
	{
		$data['P_BRANCH'] = $this->input->post('P_BRANCH');
		$data['P_LANGUAGE'] = $this->input->post('P_LANGUAGE');
		$data['P_TITLE'] = $this->input->post('P_TITLE');
		$data['P_LINK'] = $this->input->post('P_LINK');
		$data['P_START_DATE'] = $this->input->post('P_START_DATE');
		$data['P_START_TIME'] = $this->input->post('P_START_TIME');
		$data['P_FINISH_DATE'] = $this->input->post('P_FINISH_DATE');
		$data['P_FINISH_TIME'] = $this->input->post('P_FINISH_TIME');
		$data['P_IMG_PATH_PC'] = $this->input->post('P_IMG_PATH_PC');
		$data['P_IMG_PATH_MO'] = $this->input->post('P_IMG_PATH_MO');

		//echo "<pre>"; print_r($data); echo "</pre>";

		//이미지 없는거 정리
		for ( $i = 0, $j = 0; $i < count($data['P_IMG_PATH_PC']); $i++ ) {
			if ( isset($data['P_IMG_PATH_PC'][$i]) && $data['P_IMG_PATH_PC'][$i] !== '' && isset($data['P_IMG_PATH_MO'][$i]) && $data['P_IMG_PATH_MO'][$i] !== '' ) {
				$insertData[$j]['P_IMG_PATH_PC'] = $data['P_IMG_PATH_PC'][$i];
				$insertData[$j]['P_IMG_PATH_MO'] = $data['P_IMG_PATH_MO'][$i];
				$insertData[$j]['P_BRANCH'] = $data['P_BRANCH'];
				$insertData[$j]['P_LANGUAGE'] = $data['P_LANGUAGE'];
				$insertData[$j]['P_TITLE'] = $data['P_TITLE'][$i];
				$insertData[$j]['P_LINK'] = $data['P_LINK'][$i];
				$insertData[$j]['P_START_DATE'] = $data['P_START_DATE'][$i];
				$insertData[$j]['P_START_TIME'] = $data['P_START_TIME'][$i];
				$insertData[$j]['P_FINISH_DATE'] = $data['P_FINISH_DATE'][$i];
				$insertData[$j]['P_FINISH_TIME'] = $data['P_FINISH_TIME'][$i];
				$insertData[$j]['P_SORT'] = $j;

				$j++;
			}
		}

		//기존 데이터 초기화
		$this->EventSet_model->deleteEvent($data['P_BRANCH'], $data['P_LANGUAGE']);

		//데이터 세팅
		foreach ( $insertData as $index => $row ) {
			$res = $this->EventSet_model->insertEvent($row);
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_EVENT_SET', json_encode($insertData, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}

	}

}
