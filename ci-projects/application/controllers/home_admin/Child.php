<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Child extends CI_Controller
{
	public $menu = 'menu1';
	public $nav = array('navTitle' => '미성년자 동의서 설정', 'navLink1' => '/home_admin/termsSet', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/TermsSet_model', 'TermsSet_model');

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

	public function modify($B_BRANCH = null, $B_LAN = null, $SEQ = null)
	{
		$data['searchBranch'] = $B_BRANCH;
		$data['searchLan'] = $B_LAN;

		$result['BRANCH'] = $B_BRANCH;
		$result['TYPE'] = 'child';
		$result['LAN'] = $B_LAN;
		$result['SEQ'] = $SEQ;

		$branchData = $this->Branch_model->getBranchInfo($result['BRANCH']);
		$result['branchData'] = $branchData[0];

		$infoFile = $this->TermsSet_model->getTermsList($result['BRANCH'], $result['TYPE'], $result['LAN'], $SEQ);
		$info = $infoFile[0];

		$infoFiles = $this->TermsSet_model->getTermsList($result['BRANCH'], $result['TYPE'], $result['LAN']);

		$result['info'] = $info;
		$result['infoFiles'] = $infoFiles;

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/tremsset/child_regist', $result, true);
	}

	public function saveTermsSet()
	{
		$data['T_BRANCH'] = $this->input->post('T_BRANCH');
		$data['T_TYPE'] = $this->input->post('T_TYPE');
		$data['T_LAN'] = $this->input->post('T_LAN');
		$data['T_CONTEXT'] = $this->input->post('T_CONTEXT');

		if ( $data['T_BRANCH'] === 'all' ) {
			//delete set
			$res = $this->TermsSet_model->deleteTerms($data['T_TYPE']);
		}

		$res = $this->TermsSet_model->insertTerms($data);


		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_TERMS_DETAIL', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/child/modify/".$data['T_BRANCH']."/".$data['T_LAN']."');</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}
	}

}
