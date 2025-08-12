<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allmenuset extends CI_Controller {
	public $menu = 'menu5';
	public $nav = array('navTitle' => '전체보기 메뉴 설정', 'navLink1' => '/home_admin/allmenuset', 'navTitle2' => '', 'navLink2' => '');

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model( '/home_admin/Branch_model', 'Branch_model' );
		$this->load->model( '/home_admin/AllMenuset_model', 'AllMenuset_model' );
	}

	public function index()
	{
		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		$result['branchListData'] = $this->Branch_model->getBranchList($data);

//		foreach ( $branchList as $index => $row ) {
//			$checkA_MenuData = $this->AllMenuset_model->get_A_Menu_Check($row['BRANCH']);
//			if ( count($checkA_MenuData) > 0 ) {
//				$branchList[$index]['SET_YN'] = 'Y';
//			} else {
//				$branchList[$index]['SET_YN'] = 'N';
//			}
//		}

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/allmenuset', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/allmenuset/allmenuset', $result, true);
	}


	public function detailMenuSet($branch)
	{
		$checkA_MenuData = $this->AllMenuset_model->get_A_Menu_Check($branch);

		if ( isset($checkA_MenuData) && count($checkA_MenuData) > 0 ) {
			$menuData = $checkA_MenuData;

		} else {
			$menuData = $this->AllMenuset_model->get_A_Menu_Check('default');

		}

		foreach ( $menuData as $index => $row ) {
			$menuData[$index]['AS_menu'] = $this->AllMenuset_model->get_AS_Menu($row['SEQ']);
		}

		//echo "<pre>"; print_r($menuData); echo "</pre>";

		//
		$result['menuData'] = $menuData;
		$result['branch'] = $branch;
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/allmenuset/allmenuset_detail', $result, true);
	}


	public function addDetailMenuSet()
	{

	}



}
