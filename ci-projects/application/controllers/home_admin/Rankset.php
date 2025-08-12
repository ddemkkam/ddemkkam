<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rankset extends CI_Controller
{
	public $menu = 'menu3';
	public $nav = array('navTitle' => '검색순위 설정', 'navLink1' => '/home_admin/rankset', 'navTitle2' => '', 'navLink2' => '');
	public $api_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData', 'apiHostNamu', 'adminBranch'));

		$this->load->model('/home_admin/Branch_model', 'Branch_model');
		$this->load->model('/home_admin/Language_model', 'Language_model');
		$this->load->model('/home_admin/RankSet_model', 'RankSet_model');

		$this->ApiHostNamu = new ApiHostNamu();
		$this->api_url = $this->ApiHostNamu->getNamuApiUrl();

		//adminBranch
		$this->AdminBranch = new AdminBranch();
		$this->branchList = $this->AdminBranch->checkBranch();
	}

	public function index()
	{
		$data['searchBranch'] = $this->input->get('searchBranch');

		$result['branchList'] = $this->Branch_model->getBranchList();

		$result['branchListData'] = $this->Branch_model->getBranchCountryList($data);

		//page
		$data['page'] = $this->input->get("page") ? $this->input->get("page") : '1';
		$data['page_num'] = $this->input->get("page_num") ? $this->input->get("page_num") : '10';
		$data['list_num'] = $this->input->get("list_num") ? $this->input->get("list_num") : '100';
		$result['pagenation'] = $this->pagenation->pagenationHtml2('/home_admin/rankset', count($result['branchListData']), $data['page'], $data['page_num'], $data['list_num']);

		//
		$result['searchBranch'] = $data['searchBranch'];

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/rankset/rankset', $result, true);
	}

	public function modifyrank($B_BRANCH = null, $B_LAN = null)
	{
		$result['BRANCH'] = $B_BRANCH;
		$result['LANGAUE'] = $B_LAN;
		$getBranchInfo = $this->Branch_model->getBranchInfo($B_BRANCH);
		$result['BRANCH_NAME'] = $getBranchInfo[0]['BRANCH_NAME'];

		$result['infoList'] = $this->RankSet_model->getRankData($B_BRANCH, $B_LAN);
		//echo "<pre>"; print_r($infoList); echo "</pre>";

		//navigation
		$result['nav'] = $this->nav;
		$result['menu'] = $this->menu;
		$result['BASEBRANCH'] = $B_BRANCH;
		$result['BASELAN'] = $B_LAN;
		$result['branchList'] = $this->branchList;
		//check
		$this->branchList = $this->AdminBranch->checkBranchCheck($B_BRANCH, $B_LAN);

		$this->layout->setLayout("/admin/layouts/layout");
		$this->layout->view('/admin/content/rankset/rankset_modify', $result, true);
	}

	public function apiParentsList()
	{

		$api_url = $this->api_url;
//		echo $api_url;
		$categoryData = $this->getApiCategoryList( $api_url );

		$result['mainCategory'] = $categoryData['main'];
		$result['subCategory'] = $categoryData['sub'];

		$this->load->view('/admin/content/rankset/rankset_product', $result, true);
	}

	public function getApiCategoryList( $api_url = null )
	{
		$sendUrl = $api_url."/v1/public/treatmentShopCategory";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, null);
		$jsonRes = json_decode($curl_Result);
//		echo "<pre>"; print_r($jsonRes->data->category); echo "<pre>"; exit();

		if ( $jsonRes->code === 200 ) {
			$cData['main'] = $this->ApiHostNamu->categoryParseDate($jsonRes);

			$cData['sub'] = $this->ApiHostNamu->categorySubParseDate($jsonRes);
		} else {
			$rData = $this->namuErrorCode->errorCode($jsonRes);
		}

		echo "<pre>"; print_r($cData); echo "<pre>"; exit();
	}

	public function getApiProductList( $api_url = null )
	{
		$data['branch'] = $this->input->get('branch');
		$data['lan'] = $this->input->get('lan');
		$data['index'] = $this->input->get('index');

		/*$resList = $this->ApiHostNamu->getCategory(null);

		$fCnt = 0;
		foreach ($resList as $key => $value) {
			foreach ($value as $index => $row) {
				foreach ($row as $index2 => $item) {
					if ($index2 == 0) {
						$val = $item['fir_cate_code'];
					}
				}
			}
			$firstCate[$fCnt]['fir_cate_code'] = $val;
			$firstCate[$fCnt]['fir_cate_name'] = $key;
			$fCnt++;
		}

		$sCnt = 0;
		foreach ($resList as $key => $value) {
			foreach ($value as $index => $row) {
				foreach ($row as $index2 => $item) {
					if ($index2 == 0) {
						$val = $item['fir_cate_code'];
						$val2 = $item['sec_cate_code'];
					}
				}
				$secondCate[$sCnt]['fir_cate_code'] = $val;
				$secondCate[$sCnt]['sec_cate_code'] = $val2;
				$secondCate[$sCnt]['sec_cate_name'] = $index;
				$sCnt++;
			}
		}

		$pCnt = 0;
		foreach ($resList as $key => $value) {
			foreach ($value as $index => $row) {
				foreach ($row as $index2 => $item) {
					if ($index2 == 0) {
						$val = $item['fir_cate_code'];
						$val2 = $item['sec_cate_code'];
					}

					if ( $item['fir_cate_code'] == 'event' && isset($item['fir_cate_name_group']) && isset($item['fir_cate_name_group']) != '' ) {

					} else {
						$list[$pCnt]['fir_cate_code'] = $val;
						$list[$pCnt]['sec_cate_code'] = $val2;
						$list[$pCnt]['sec_cate_name'] = $index;
						$list[$pCnt]['ts_price'] = $item['ts_price'];
						$list[$pCnt]['event_ts_price'] = $item['event_ts_price'];
						$list[$pCnt]['ts_name'] = $item['ts_name'];
						$list[$pCnt]['ts_code'] = $item['ts_code'];
						$list[$pCnt]['tse_start_datetime'] = $item['tse_start_datetime'];
						$list[$pCnt]['tse_end_datetime'] = $item['tse_end_datetime'];
						$pCnt++;
					}
				}
			}
		}

//		echo "<pre>"; print_r($list); echo "<pre>"; exit();
		$result['firstCate'] = $firstCate;
		$result['secondCate'] = $secondCate;
		$result['list'] = $list;
		$result['view_id'] = $data['index'];*/

		$area = $data['lan'] == 'KR' ? 'domestic' : 'foreign';

		$result = array(
			'list' => $this->ApiHostNamu->getCategoryBranch($data['branch'], $area),
			'view_id' => $data['index'],
		);


		$this->load->view('/admin/content/rankset/rankset_product_r', $result);
	}


	function save()
	{
		$data['BRANCH'] = $this->input->post('P_BRANCH');
		$data['LANGUAGE'] = $this->input->post('P_LANGUAGE');
		$data['fir_cate_code'] = $this->input->post('fir_cate_code');
		$data['sec_cate_code'] = $this->input->post('sec_cate_code');
		$data['ts_code'] = $this->input->post('ts_code');
		$data['ts_name'] = $this->input->post('P_TITLE');
		$data['P_START_DATE'] = $this->input->post('P_START_DATE');
		$data['P_START_TIME'] = $this->input->post('P_START_TIME');
		$data['P_FINISH_DATE'] = $this->input->post('P_FINISH_DATE');
		$data['P_FINISH_TIME'] = $this->input->post('P_FINISH_TIME');

		$this->HistoryData = new HistoryData();

		//delete
		$this->RankSet_model->deleteRank($data['BRANCH'], $data['LANGUAGE']);

		$res = false;

		//insert
		foreach ($data['fir_cate_code'] as $index => $val) {
			if ($val) {
				$insertData = [
					'BRANCH' => $data['BRANCH'],
					'LANGUAGE' => $data['LANGUAGE'],
					'fir_cate_code' => $val,
					'sec_cate_code' => $data['sec_cate_code'][$index],
					'ts_code' => $data['ts_code'][$index],
					'ts_name' => $data['ts_name'][$index],
					'SORT' => $index,
					'sDate' => isset($data['P_START_DATE'][$index]) && $data['P_START_DATE'][$index] != '' ? $data['P_START_DATE'][$index] : date('Y-m-d'),
					'eDate' => isset($data['P_FINISH_DATE'][$index]) && $data['P_FINISH_DATE'][$index] != '' ? $data['P_FINISH_DATE'][$index] : date('2025-12-31'),
					'sTime' => $data['P_START_TIME'][$index],
					'eTime' => $data['P_FINISH_TIME'][$index],
				];

				$inserted = $this->RankSet_model->insertRank($insertData);
				if ($inserted) {
					$res = true;
					$this->HistoryData->insertHistoryData('INSERT', 'ppeum_homepage.P_RANKSET', json_encode($insertData, JSON_UNESCAPED_UNICODE));
				}
			}
		}

		if ($res) {
			echo "<script>alert('저장 하였습니다.'); location.reload();</script>";
		} else {
			echo "<script>alert('저장에 실패하였습니다.'); location.reload();</script>";
		}

	}


}
