<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainimg extends CI_Controller
{
	public $menu = 'menu1';
	public $nav = array('navTitle' => '메인 이미지 설정', 'navLink1' => '/home_admin/termsSet', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Maimimg_model', 'Maimimg_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		$result['branchListData'] = $this->Branch_model->getBranchCountryListGroup($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/termsSet', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];
		$result['type'] = 'public';
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/tremsset/tremsset_pvch', $result, true);
	}

	/*
	public function pvch($type = null)
	{
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		$result['branchListData'] = $this->Branch_model->getBranchCountryListGroup($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/termsSet', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];
		$result['type'] = $type;
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/tremsset/tremsset_pvch', $result, true);
	}
	*/

	public function modify($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		$result['BRANCH'] = $B_BRANCH;
		$result['LAN'] = $B_LAN;

		$infoFile = $this->Maimimg_model->getMainImgInfo($result['BRANCH'], $result['LAN']);
		$info = $infoFile;
//		echo '<pre>'; print_r($infoFile); echo '</pre>';

		$result['info'] = $info;

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/mainimg/main_img', $result, true);
	}

	public function save_img_set()
	{
		$data['BRANCH'] = $this->input->post('M_BRANCH');
//		$data['T_TYPE'] = $this->input->post('T_TYPE');
		$data['LAN'] = $this->input->post('M_LAN');
		$data['TITLE'] = $this->input->post('TITLE');
		$data['START_DATE'] = $this->input->post('START_DATE');
		$data['START_TIME'] = $this->input->post('START_TIME');
		$data['FINISH_DATE'] = $this->input->post('FINISH_DATE');
		$data['FINISH_TIME'] = $this->input->post('FINISH_TIME');
		$data['LINK'] = $this->input->post('LINK');
		$data['LINK_TARGET'] = $this->input->post('LINK_TARGET');
		$IMG_SRC = $this->input->post('M_IMG_PATH');

		$this->Maimimg_model->deleteImg($data['BRANCH'], $data['LAN']);

		foreach ( $data['TITLE'] as $index => $value ) {
			if ( $IMG_SRC[$index] != '' && isset($IMG_SRC[$index]) ) {
				$insData['BRANCH'] = $data['BRANCH'];
				$insData['LAN'] = $data['LAN'];
				$insData['TITLE'] = $data['TITLE'][$index];
				$insData['START_DATE'] = $data['START_DATE'][$index];
				$insData['START_TIME'] = $data['START_TIME'][$index];
				$insData['FINISH_DATE'] = $data['FINISH_DATE'][$index];
				$insData['FINISH_TIME'] = $data['FINISH_TIME'][$index];
				$insData['LINK'] = $data['LINK'][$index];
				$insData['LINK_TARGET'] = $data['LINK_TARGET'][$index];
				$insData['IMG_SRC'] = $IMG_SRC[$index];
				$res = $this->Maimimg_model->insertImg($insData);
			}
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_MAIN_IMG', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/mainimg/modify/".$data['BRANCH']."/".$data['LAN']."');</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}
	}

}
