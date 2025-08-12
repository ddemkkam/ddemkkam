<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {
	public $menu = 'menu6';
	public $nav = array('navTitle' => '지점설정', 'navLink1' => '/home_admin/branch', 'navTitle2' => '', 'navLink2' => '');

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model( '/home_admin/Branch_model', 'Branch_model' );
	}

	public function index()
	{
		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";
		$data['branch'] = $this->input->get('branch');
		$result['branchList'] = $this->Branch_model->getBranchList($data);
		$result['placeList'] = $this->Branch_model->getPlaceList($data);
//		echo '<pre>'; print_r($result['branchList']); echo '</pre>';

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/branch', count($result['branchList']), $data['page'], $data['page_num'], $data['list_num']);

		//return search value
		$result['branch'] = $data['branch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/branch/branch', $result, true);
	}

	public function modifyBranch()
	{
		$data['SEQ'] = $this->input->post('SEQ');
		$data['BRANCH'] = $this->input->post('BRANCH');
		$data['BRANCH_NAME'] = $this->input->post('BRANCH_NAME');
		$data['EVENT_MAP'] = $this->input->post('EVENT_MAP');
		$data['MAIN_BRANCH'] = $this->input->post('MAIN_BRANCH');
		$data['SORT'] = $this->input->post('SORT');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->Branch_model->modifyBranch($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_BRANCH', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('수정하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('수정에 실패하였습니다.'); location.reload();</script>";
		}

	}


	public function deleteBranch()
	{
		$data['BRANCH'] = $this->input->post('BRANCH');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->Branch_model->deleteBranch($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_BRANCH', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패하였습니다.'); location.reload();</script>";
		}

	}

	public function addBranchView()
	{
		$result = null;
		$this->load->view('/admin/content/branch/branchAddView', $result);
	}

	public function addBranch()
	{
		$data['BRANCH'] = $this->input->post('BRANCH');
		$data['BRANCH_NAME'] = $this->input->post('BRANCH_NAME');
		$data['EVENT_MAP'] = $this->input->post('EVENT_MAP');
		$data['MAIN_BRANCH'] = $this->input->post('MAIN_BRANCH');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->Branch_model->addBranch($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_BRANCH', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('추가 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('추가에 실패하였습니다.'); location.reload();</script>";
		}
	}



}
