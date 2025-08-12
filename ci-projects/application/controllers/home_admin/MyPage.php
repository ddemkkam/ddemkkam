<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyPage extends CI_Controller
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
		$this->load->model('/home_admin/MyPageImg_model', 'MyPageImg_model');

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		$result['BRANCH'] = $B_BRANCH;
		$result['LAN'] = $B_LAN;

		$infoFile = $this->MyPageImg_model->getMyPageImgInfo($result['BRANCH'], $result['LAN']);
		$info = $infoFile;
		echo '<pre>'; print_r($infoFile); echo '</pre>';

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
		$this->layout->view('/admin/content/myPage/pc_img', $result, true);
	}

	public function pc($B_BRANCH = null, $B_LAN = null)
	{
		$result['BRANCH'] = $B_BRANCH;
		$result['LAN'] = $B_LAN;
		$this->nav['navTitle'] = '마이페이지 배너 설정(PC)';
		$infoFile = $this->MyPageImg_model->getMyPageImgInfo($result['BRANCH'], $result['LAN'], '01');
		$info = $infoFile;

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
		$this->layout->view('/admin/content/myPage/pc_img', $result, true);
	}

	public function save_pc_img_set()
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

		$this->MyPageImg_model->deleteImg($data['BRANCH'], '01', $data['LAN']);

		foreach ( $data['TITLE'] as $index => $value ) {
			if ( $IMG_SRC[$index] != '' && isset($IMG_SRC[$index]) ) {
				$insData['BRANCH'] = $data['BRANCH'];
				$insData['DEVICE_TYPE'] = '01';
				$insData['LAN'] = $data['LAN'];
				$insData['TITLE'] = $data['TITLE'][$index];
				$insData['START_DATE'] = $data['START_DATE'][$index];
				$insData['START_TIME'] = $data['START_TIME'][$index];
				$insData['FINISH_DATE'] = $data['FINISH_DATE'][$index];
				$insData['FINISH_TIME'] = $data['FINISH_TIME'][$index];
				$insData['LINK'] = $data['LINK'][$index];
				$insData['LINK_TARGET'] = $data['LINK_TARGET'][$index];
				$insData['IMG_SRC'] = $IMG_SRC[$index];
				$res = $this->MyPageImg_model->insertImg($insData);
			}
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_MYPAGE_IMG', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/MyPage/pc/".$data['BRANCH']."/".$data['LAN']."');</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}
	}
	public function mobile($B_BRANCH = null, $B_LAN = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;
		$this->nav['navTitle'] = '마이페이지 배너 설정(모바일)';
		$result['BRANCH'] = $B_BRANCH;
		$result['LAN'] = $B_LAN;

		$infoFile = $this->MyPageImg_model->getMyPageImgInfo($result['BRANCH'], $result['LAN'], '02');
		$info = $infoFile;

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
		$this->layout->view('/admin/content/myPage/mobile_img', $result, true);
	}

	public function save_mobile_img_set()
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

		$this->MyPageImg_model->deleteImg($data['BRANCH'], '02', $data['LAN']);

		foreach ( $data['TITLE'] as $index => $value ) {
			if ( $IMG_SRC[$index] != '' && isset($IMG_SRC[$index]) ) {
				$insData['BRANCH'] = $data['BRANCH'];
				$insData['DEVICE_TYPE'] = '02';
				$insData['LAN'] = $data['LAN'];
				$insData['TITLE'] = $data['TITLE'][$index];
				$insData['START_DATE'] = $data['START_DATE'][$index];
				$insData['START_TIME'] = $data['START_TIME'][$index];
				$insData['FINISH_DATE'] = $data['FINISH_DATE'][$index];
				$insData['FINISH_TIME'] = $data['FINISH_TIME'][$index];
				$insData['LINK'] = $data['LINK'][$index];
				$insData['LINK_TARGET'] = $data['LINK_TARGET'][$index];
				$insData['IMG_SRC'] = $IMG_SRC[$index];
				$res = $this->MyPageImg_model->insertImg($insData);
			}
		}

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_MYPAGE_IMG', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/MyPage/mobile/".$data['BRANCH']."/".$data['LAN']."');</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}
	}

}
