<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeforeAfter extends CI_Controller
{
	public $menu = 'menu3';
	public $nav = array('navTitle' => '방송/언론보도', 'navLink1' => '/home_admin/media', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
		$this->load->model('/home_admin/BoAf_model', 'BoAf_model');

	}

	public function index()
	{
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '50';
		$data['start'] = ( $data['page'] - 1 ) * $data['list_num'];
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}
		$result['totalData'] = $this->BoAf_model->getBFListTotal($data);
		$result['branchListData'] = $this->BoAf_model->getBFList($data);
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/BeforeAfter', count($result['totalData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/beforeAfter/beforeAfter', $result, true);
	}

	public function delete()
	{
		$data['SEQ'] = $this->input->post('SEQ');

		$updateData['M_DEL_YN'] = 'Y';
		$updateData['DEL_DATE'] = date("Y-m-d H:i:s");

		$res = $this->Media_model->delete_media($updateData, $data['SEQ']);
		$updateData['SEQ'] = $data['SEQ'];

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_MEDIA', json_encode($updateData, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패 하였습니다.'); </script>";
		}
	}

	public function modifymedia($SEQ = null)
	{
		if ( isset($SEQ) && $SEQ !== '' ) {
			$info = $this->Media_model->getMediaInfo($SEQ);
			//echo "<pre>"; print_r($info); echo "</pre>";
			$result['info'] = $info[0];
		}

		$result['branchList'] = $this->Branch_model->getBranchList();

		//
		$result['SEQ'] = $SEQ;

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/media/media_register', $result, true);
	}

	public function save()
	{
		$data['SEQ'] = $this->input->post('SEQ');
		$data['M_LANGUAGE'] = $this->input->post('M_LANGUAGE');
		$data['M_TITLE'] = $this->input->post('M_TITLE');
		$data['M_CONTEXT'] = $this->input->post('M_CONTEXT');
		$data['M_COUNT'] = $this->input->post('M_COUNT');
		$data['M_REG_ID'] = $this->input->post('M_REG_ID');

		if ( isset($data['SEQ']) && $data['SEQ'] !== '' ) {
			//modify
			$updateData['M_LANGUAGE'] = $data['M_LANGUAGE'];
			$updateData['M_TITLE'] = $data['M_TITLE'];
			$updateData['M_CONTEXT'] = $data['M_CONTEXT'];
			$updateData['M_COUNT'] = $data['M_COUNT'];
			$updateData['M_REG_ID'] = $data['M_REG_ID'];

			$res = $this->Media_model->update_media($updateData, $data['SEQ']);

			if ( $res ) {
				$this->HistoryData = new HistoryData();
				$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_MEDIA', json_encode($data, JSON_UNESCAPED_UNICODE));

				echo "<script>alert('수정 하였습니다.'); location.replace('/home_admin/media/');</script>";
			} else {
				echo "<script>alert('수정에 실패 하였습니다.'); </script>";
			}
		} else {
			//insert
			$insertData['M_LANGUAGE'] = $data['M_LANGUAGE'];
			$insertData['M_TITLE'] = $data['M_TITLE'];
			$insertData['M_CONTEXT'] = $data['M_CONTEXT'];
			$insertData['M_COUNT'] = $data['M_COUNT'];
			$insertData['M_REG_ID'] = $data['M_REG_ID'];

			$res = $this->Media_model->insert_media($insertData);

			if ( $res ) {
				$this->HistoryData = new HistoryData();
				$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_MEDIA', json_encode($insertData, JSON_UNESCAPED_UNICODE));

				echo "<script>alert('저장 하였습니다.'); location.replace('/home_admin/media/');</script>";
			} else {
				echo "<script>alert('저장에 실패 하였습니다.'); </script>";
			}
		}

	}

}
