<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menuset extends CI_Controller {
	public $menu = 'menu5';
	public $nav = array('navTitle' => '대메뉴 설정', 'navLink1' => '/home_admin/menuset', 'navTitle2' => '', 'navLink2' => '');

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model( '/home_admin/Branch_model', 'Branch_model' );
		$this->load->model( '/home_admin/Menuset_model', 'Menuset_model' );
	}

	public function index()
	{
		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		$result['menuList'] = $this->Menuset_model->get_L_Menu($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/menuset', count($result['menuList']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/menuset/menuset', $result, true);
	}

	public function menusetAddView()
	{
		$result['branchList'] = $this->Branch_model->getBranchList();

		$this->load->view('/admin/content/menuset/menusetAddView', $result);
	}

	public function addMenuset()
	{
		$data['L_BRANCH'] = $this->input->post('L_BRANCH');
		$data['L_TITLE'] = $this->input->post('L_TITLE');
		$data['L_URL'] = $this->input->post('L_URL');
		$data['L_SORT'] = $this->input->post('L_SORT');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->Menuset_model->insert_L_Menu($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_L_PPEUM_MENU', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('추가하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('추가에 실패하였습니다.'); location.reload();</script>";
		}

	}

	public function modifyMenu()
	{
		$data['SEQ'] = $this->input->post('SEQ');
		$data['L_TITLE'] = $this->input->post('L_TITLE');
		$data['L_URL'] = $this->input->post('L_URL');
		$data['L_SORT'] = $this->input->post('L_SORT');
		$data['L_USE_YN'] = $this->input->post('L_USE_YN');

		$res = $this->Menuset_model->modify_L_Menu($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_L_PPEUM_MENU', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('수정하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('수정에 실패하였습니다.'); location.reload();</script>";
		}
	}


	public function deleteMenu()
	{
		$data['SEQ'] = $this->input->post('SEQ');

		$res = $this->Menuset_model->delete_L_Menu($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_L_PPEUM_MENU', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패하였습니다.'); location.reload();</script>";
		}
	}



}
