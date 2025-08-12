<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller
{
	public $menu = 'menu6';
	public $nav = array('navTitle' => '언어 설정', 'navLink1' => '/home_admin/lanage', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
	}

	public function index()
	{
		$lanListData = $this->Language_model->getLanguageList();
		foreach ( $lanListData as $index => $row ) {
			$lanListData[$index]['BRANCHLIST'] = $this->Language_model->getBranchLanguageList($row['COUNTRY']);
		}

		$result['lanListData'] = $lanListData;
		//echo "<pre>"; print_r($result['lanListData']); echo "</pre>";

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/langaue', count($result['lanListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		//$result['searchBranch'] = $data['searchBranch'];
		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		// layout 을 적용
		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/language/language', $result, true);
	}

	public function modifylanguage($lan)
	{
		$result['lan'] = $lan;

		$result['branchLanData'] = $this->Language_model->getBranchLanguageList($lan);
		//echo "<pre>"; print_r($result['branchLanData']); echo "<pre>";

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/language/language_regist', $result, true);
	}


	public function addAjax()
	{
		$result['lan'] = $this->input->post("lan");

		$result['branchList'] = $this->Branch_model->getBranchList();

		$this->load->view('/admin/content/language/addAjax', $result);
	}

	public function saveBranchMap()
	{
		//BRANCH START_DATE FINISH_DATE
		$data['LAN'] = $this->input->post("LAN");
		$data['BRANCH'] = $this->input->post("BRANCH");
		$data['START_DATE'] = $this->input->post("START_DATE");
		$data['FINISH_DATE'] = $this->input->post("FINISH_DATE");

		//초기화
		$dRes = $this->Language_model->deleteBranchLanguage($data['LAN']);

		if ( $dRes ) {
			foreach ( $data['BRANCH'] as $index => $val ) {
				if ( $val != '' ) {
					$insData['LANGAUE'] = $data['LAN'];
					$insData['BRANCH'] = $val;
					$insData['START_DATE'] = $data['START_DATE'][$index];
					$insData['FINISH_DATE'] = $data['FINISH_DATE'][$index];

					$iRes = $this->Language_model->insertBranchLanguage($insData);
				}
			}
		}

		$this->HistoryData = new HistoryData();
		$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_BRANCH_LAN', json_encode($data, JSON_UNESCAPED_UNICODE));

		echo "<script>alert('저장 하였습니다.'); location.reload();</script>";

	}

}
