<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainset extends CI_Controller
{
	public $menu = 'menu2';
	public $nav = array('navTitle' => '메인 설정', 'navLink1' => '/home_admin/mainset', 'navTitle2' => '', 'navLink2' => '');

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'namuErrorCode', 'apiHostNamu'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
		$this->load->model('/home_admin/MainSet_model', 'MainSet_model');

	}

	public function index()
	{
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

//		$result['branchListData'] = $this->Branch_model->getBranchCountryList($data);

		//page
//		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
//		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
//		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
//		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/termsSet', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		$result['getMainSetData'] = $this->MainSet_model->getMainSetData($data);
//		echo "<pre>"; print_r($result['getMainSetData']); echo "</pre>";

		//나무 카테고리 가져오기 start
		$this->ApiHostNamu = new ApiHostNamu();
		$api_url = $this->ApiHostNamu->getNamuApiUrl();
		$sendUrl = $api_url."/v1/public/treatmentShopCategory";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);
		//print_r($jsonRes);

		if ( $jsonRes->code === 200 ) {
			$eventCategory = $jsonRes->data->category[0]->sub_category;
			//날짜 parse
			$parData1 = $this->ApiHostNamu->parseDate($eventCategory);

			$rData = $parData1;

		} else {
			$rData = $this->namuErrorCode->errorCode($jsonRes);
		}

		$result['crmCategory'] = $rData;
		//나무 카테고리 가져오기 end

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/mainset/mainset', $result, true);
	}

	public function addMainView()
	{
		$result['branchList'] = $this->Branch_model->getBranchList();

		$this->load->view('/admin/content/mainset/add_mainset', $result);
	}


	public function getBranchToLanData()
	{
		$branch = $this->input->get('BRANCH');
		$lanList = $this->Language_model->getBranchLanguageData($branch);
		//echo "<pre>"; print_r($lanList); echo "</pre>";

		echo json_encode($lanList, JSON_UNESCAPED_UNICODE);
	}

	public function add_save()
	{
		$data['M_BRANCH'] = $this->input->post("ADD_M_BRANCH");
		$data['M_LANGUAGE'] = $this->input->post("ADD_M_LANGUAGE");
		$data['M_TITLE'] = $this->input->post("ADD_M_TITLE");
		$data['M_SUB_TITLE'] = $this->input->post("ADD_M_SUB_TITLE");
		$data['M_FIRST_START_DATE'] = $this->input->post("ADD_M_FIRST_START_DATE");
		$data['M_FIRST_FINISH_DATE'] = $this->input->post("ADD_M_FIRST_FINISH_DATE");
		$data['M_FIRST_START_TIME'] = $this->input->post("ADD_M_FIRST_START_TIME");
		$data['M_FIRST_FINISH_TIME'] = $this->input->post("ADD_M_FIRST_FINISH_TIME");
		$data['M_VIEW_START_DATE'] = $this->input->post("ADD_M_VIEW_START_DATE");
		$data['M_VIEW_FINISH_DATE'] = $this->input->post("ADD_M_VIEW_FINISH_DATE");
		$data['M_VIEW_START_TIME'] = $this->input->post("ADD_M_VIEW_START_TIME");
		$data['M_VIEW_FINISH_TIME'] = $this->input->post("ADD_M_VIEW_FINISH_TIME");

		if ( $data['M_FIRST_FINISH_TIME'] === '' ) {
			$data['M_FIRST_FINISH_TIME'] = '23:59:00';
		}

		if ( $data['M_VIEW_FINISH_TIME'] === '' ) {
			$data['M_VIEW_FINISH_TIME'] = '23:59:00';
		}
//		echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$res = $this->MainSet_model->insert_mainset($data);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_MAIN_SET', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패 하였습니다.'); </script>";
		}
	}


	public function modify()
	{
		$data['SEQ'] = $this->input->post("SEQ");
		$data['M_BRANCH'] = $this->input->post("M_BRANCH");

		$data['M_USE_YN'] = $this->input->post("M_USE_YN");
		$data['M_TITLE'] = $this->input->post("M_TITLE");
		$data['M_SUB_TITLE'] = $this->input->post("M_SUB_TITLE");
		$data['M_FIRST_START_DATE'] = $this->input->post("M_FIRST_START_DATE");
		$data['M_FIRST_START_TIME'] = $this->input->post("M_FIRST_START_TIME");
		$data['M_FIRST_FINISH_DATE'] = $this->input->post("M_FIRST_FINISH_DATE");
		$data['M_FIRST_FINISH_TIME'] = $this->input->post("M_FIRST_FINISH_TIME");
		$data['M_VIEW_START_DATE'] = $this->input->post("M_VIEW_START_DATE");
		$data['M_VIEW_START_TIME'] = $this->input->post("M_VIEW_START_TIME");
		$data['M_VIEW_FINISH_DATE'] = $this->input->post("M_VIEW_FINISH_DATE");
		$data['M_VIEW_FINISH_TIME'] = $this->input->post("M_VIEW_FINISH_TIME");

		$modifyData['M_USE_YN'] = $data['M_USE_YN'];
		$modifyData['M_TITLE'] = $data['M_TITLE'];
		$modifyData['M_SUB_TITLE'] = $data['M_SUB_TITLE'];
		$modifyData['M_FIRST_START_DATE'] = $data['M_FIRST_START_DATE'];
		$modifyData['M_FIRST_START_TIME'] = $data['M_FIRST_START_TIME'];
		$modifyData['M_FIRST_FINISH_DATE'] = $data['M_FIRST_FINISH_DATE'];
		$modifyData['M_FIRST_FINISH_TIME'] = $data['M_FIRST_FINISH_TIME'];
		$modifyData['M_VIEW_START_DATE'] = $data['M_VIEW_START_DATE'];
		$modifyData['M_VIEW_START_TIME'] = $data['M_VIEW_START_TIME'];
		$modifyData['M_VIEW_FINISH_DATE'] = $data['M_VIEW_FINISH_DATE'];
		$modifyData['M_VIEW_FINISH_TIME'] = $data['M_VIEW_FINISH_TIME'];
		$modifyData['MOD_DATE'] = date("Y-m-d H:i:s");

		$res = $this->MainSet_model->modify_mainset($modifyData, $data['SEQ']);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('UPDATE', 'ppeum_homepage.P_MAIN_SET', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('수정 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('수정에 실패 하였습니다.'); </script>";
		}
	}

	public function delete()
	{
		$data['SEQ'] = $this->input->post("SEQ");
		$data['M_DEL_YN'] = 'Y';
		$data['DEL_DATE'] = date("Y-m-d H:i:s");

		$deleteData['M_DEL_YN'] = $data['M_DEL_YN'];
		$deleteData['DEL_DATE'] = $data['DEL_DATE'];

		$res = $this->MainSet_model->delete_mainset($deleteData, $data['SEQ']);

		if ( $res ) {
			$this->HistoryData = new HistoryData();
			$this->HistoryData->insertHistoryData('DELETE', 'ppeum_homepage.P_MAIN_SET', json_encode($data, JSON_UNESCAPED_UNICODE));

			echo "<script>alert('삭제 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('삭제에 실패 하였습니다.'); </script>";
		}
	}

	public function mainProductSet($SEQ = null, $crmCate = null)
	{
		//
		$result['SEQ'] = $SEQ;
		$result['crmCate'] = $crmCate;

		//나무 카테고리 가져오기 start
//		$url = "https://ppeumapi.namucrm.com/v1/public/treatmentShopCategory";
//		$curl_Result = $this->getCurl($url, null);
//		$jsonRes = json_decode($curl_Result);
//
//		if ( $jsonRes->code === 200 ) {
//			$eventCategory = $jsonRes->data->category[0]->sub_category;
//			//날짜 parse
//			$parData1 = $this->parseDate($eventCategory);
//
//			$rData = $parData1;
//
//		} else {
//			$rData = $this->namuErrorCode->errorCode($jsonRes);
//		}
//
//		//$result['crmCategory'] = $rData;
//		foreach ( $rData as $index => $row ) {
//			if ( $row['uid'] === $crmCate ) {
//				$result['crmCateName'] = $row['name'];
//			}
//		}
		//나무 카테고리 가져오기 end

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/mainset/mainset_product', $result, true);
	}

}
