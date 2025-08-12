<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMember extends CI_Controller {
	public $menu = 'menu6';
	public $nav = array('navTitle' => '관리자 설정', 'navLink1' => '/home_admin/adminMember', 'navTitle2' => '', 'navLink2' => '');

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model( '/home_admin/Branch_model', 'Branch_model' );
		$this->load->model( '/home_admin/AdminMember_model', 'AdminMember_model' );
	}

	public function index()
	{
		//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";
		$data['searchBranch'] = $this->input->get('searchBranch');
		$data['nameId'] = $this->input->get('nameId');

		$result['branchList'] = $this->Branch_model->getBranchList();
		$result['adminList'] = $this->AdminMember_model->getAdminMemberList($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/adminmember', count($result['branchList']), $data['page'], $data['page_num'], $data['list_num']);

		//return search value
		$result['searchBranch'] = $data['searchBranch'];
		$result['nameId'] = $data['nameId'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/adminMember/adminMember', $result, true);
	}

	public function modify_member()
	{
		$data['SEQ'] = $this->input->post('SEQ');
		$data['ADMIN_ID'] = $this->input->post('ADMIN_ID');
		$data['ADMIN_NAME'] = $this->input->post('ADMIN_NAME');
		$data['ADMIN_LEVEL'] = $this->input->post('ADMIN_LEVEL');
		$data['PSDWD'] = $this->input->post('PSDWD');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->AdminMember_model->modifyMember($data);



		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_ADMIN_MEMBER', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('수정하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('수정에 실패하였습니다.'); location.reload();</script>";
		}

	}


	public function delete_member()
	{
		$data['ADMIN_ID'] = $this->input->post('ADMIN_ID');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->AdminMember_model->deleteMember($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_ADMIN_MEMBER', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패하였습니다.'); location.reload();</script>";
		}

	}


	public function add_member_view()
	{
		$result['branchList'] = $this->Branch_model->getBranchList();

		$this->load->view('/admin/content/adminMember/adminMemberAddView', $result);
	}

	public function add_member()
	{
		$data['BRANCH'] = $this->input->post('BRANCH');
		$data['ADMIN_ID'] = $this->input->post('ADMIN_ID');
		$data['ADMIN_NAME'] = $this->input->post('ADMIN_NAME');
		$data['PSDWD'] = $this->input->post('PSDWD');

		//echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->AdminMember_model->insertMember($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_ADMIN_MEMBER', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('추가하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('추가에 실패하였습니다.'); location.reload();</script>";
		}
	}



}
