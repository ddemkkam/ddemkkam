<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller
{
	public $menu = '검색';
	private $BranchInfo;
	private $ApiHostNamu;
	private $BasketInfo;
	private $publicCi;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BasketInfo = new BasketInfo();

		//사용자 조회
		$this->publicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	/**
	 * 검색 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//검색어 설정
		$text = $this->input->get('text');
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//검색된 상품 리스트
		$productList = array();
		$list = array();
		//해시태그 리스트
		$hashTag = array();

		//검색어가 있을때
		if (!is_null($text) && !empty($text)) {
			//문자열 디코딩
			$text = urldecode(base64_decode($text));
			//검색된 상품 정보 조회
			$productList = $this->ApiHostNamu->getTextSearch($text);
		}

		//조회된 상품 정보가 없을 때, 해시태그 노출
		$data = $this->ApiHostNamu->getSearch();
		$list = $data['search'];
		if (count($productList) == 0) {
			$hashTag = $data['hash_tag'];
		}

		$result = array(
			'menu' => $this->menu,
			'cnt' => $basketCnt,
			'branchInfo' => $branchInfo,
			'productList' => $productList,
			'list' => $list,
			'hashTag' => $hashTag,
			'text' => $text
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/search/search', $result, true);
		//상품 정보 합산 뷰
		$this->load->view('/homepage/include/select_product_info');
	}
}
