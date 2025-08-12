<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventinfo extends CI_Controller
{
	public $menu = 'menu4';
	public $nav = array('navTitle' => '이벤트 이미지 설정', 'navLink1' => '/home_admin/eventinfo/eventDetail', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'apiHostNamu', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
		$this->load->model('/home_admin/RankSet_model', 'RankSet_model');
		$this->load->model('/home_admin/EventInfo_model', 'EventInfo_model');

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();

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
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/eventinfo', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/eventinfo/eventinfo', $result, true);
	}


	public function eventDetail($B_BRANCH = null, $B_LAN = null)
	{
		$data['BRANCH'] = $B_BRANCH;
		$data['LANGUAGE'] = $B_LAN;
		$getBranchInfo = $this->Branch_model->getBranchInfo($B_BRANCH);

		$area = $data['LANGUAGE'] == 'KR' ? 'domestic' : 'foreign';

		$resEventData = $this->ApiHostNamu->getAdminEventList($data['BRANCH'], $area);
//		echo "<pre>"; print_r($resEventData); echo "</pre>";

		foreach ( $resEventData['data'] as $index => $row ) {
			$eventInfo = $this->EventInfo_model->getEventInfo($data['BRANCH'], $row['tse_code']);
//			echo "<pre>"; print_r($eventInfo); echo "</pre>";
			$resEventData['data'][$index]['E_IMG_PATH'] = $eventInfo[0]['E_IMG_PATH'];
		}

//		echo "<pre>"; print_r($resEventData['data']); echo "</pre>";

		//result
		$result['BRANCH'] = $data['BRANCH'];
		$result['LANGUAGE'] = $data['LANGUAGE'];
		$result['BRANCH_NAME'] = $getBranchInfo[0]['BRANCH_NAME'];
		$result['EVENTRESULT'] = $resEventData['data'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/eventinfo/eventinfo_detail', $result, true);
	}

	public function eventInfoSave()
	{
		$data['E_BRANCH'] = $this->input->post('E_BRANCH');
		$data['E_IMG_PATH'] = $this->input->post('E_IMG_PATH');
		$data['TSE_CODE'] = $this->input->post('TSE_CODE');
		$data['E_LANGUAGE'] = $this->input->post('E_LANGUAGE');

//		echo "<pre>"; print_r($data); echo "</pre>"; exit();

		//데이터 초기화
		$this->EventInfo_model->deleteEventInfo($data['E_BRANCH'], $data['E_LANGUAGE']);

		/*
		 * 데이터 저장
		 * 이미지 설정이 되어 있지 않은 데이터는 저장 X
		 */
		foreach ( $data['E_IMG_PATH'] as $index => $val ) {
			if ( $val !== '' ) {
				$insertData['E_BRANCH'] = $data['E_BRANCH'];
				$insertData['TSE_CODE'] = $data['TSE_CODE'][$index];
				$insertData['E_LANGUAGE'] = $data['E_LANGUAGE'];
				$insertData['E_IMG_PATH'] = $val;
				$insertData['E_SORT'] = $index;

				$result = $this->EventInfo_model->insertEventInfo($insertData);
			}
		}

		if ( $result ) {
			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패하였습니다.'); location.reload();</script>";
		}

	}

}

?>
