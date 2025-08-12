<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	private $BasketModel;
	private $RankSetModel;
	private $MainImgModel;
	private $BasketInfo;
	private $RankSetInfo;
	private $BranchInfo;
	private $ApiHostNamu;
	private $publicCi;
	private $branch;
	private $lan;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'apiHostNamu', 'BasketInfo', 'RankSetInfo', 'BranchInfo'));

		$this->load->model('/homepage/Basket_model');
		$this->load->model('/homepage/Rankset_model');
		$this->load->model('/homepage/Mainimg_model');

		$this->BasketModel = new Basket_model();
		$this->RankSetModel = new RankSet_model();
		$this->MainImgModel = new Mainimg_model();
		$this->BasketInfo = new BasketInfo();
		$this->RankSetInfo = new RankSetInfo();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();

		//사용자 조회
		$this->publicCi = $this->BranchInfo->getUser();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
		//국가 코드
		$this->lan = 'KR';
	}

	/**
	 * 홈페이지 메인 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//현재
		$now = Date('Y-m-d H:i:s');
		//메인 이미지 조회
		$mainImageList = $this->MainImgModel->getMainImage($this->branch, $this->lan, $now);
		//메인 팝업 이미지 조회
		$popupImageList = $this->MainImgModel->getMainPopupImage($this->branch, $this->lan, $now);
		//실시간 검색 순위 조회
		$rankData = $this->RankSetModel->getRankList($this->branch);
		$rankList = $this->ApiHostNamu->getRankList($rankData);
//		echo '<pre>'; print_r($rankList); echo '</pre>';
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->publicCi, $this->branch);

		$result = array(
			'cnt' => $basketCnt,
			'rankList' => $rankList,
			'main' => 'Y',
			'branchInfo' => $branchInfo,
			'mainImageList' => $mainImageList,
			'popupImageList' => $popupImageList
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/main/main', $result, true);
	}
}
