<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reservation extends CI_Controller
{
	public $menu = '날짜/시간 선택';
	private $BasketModel;
	private $ReservationModel;
	private $TermsSetModel;
	private $BranchInfo;
	private $ApiHostNamu;
	private $BasketInfo;
	private $userInfo;
	private $branch;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'string'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo', 'BasketInfo'));

		$this->load->model('/homepage/Basket_model');
		$this->load->model('/homepage/Reservation_model');
		$this->load->model('/home_admin/TermsSet_model');

		$this->BasketModel = new Basket_model();
		$this->ReservationModel = new Reservation_model();
		$this->TermsSetModel = new TermsSet_model();
		$this->BranchInfo = new BranchInfo();
		$this->ApiHostNamu = new ApiHostNamu();
		$this->BasketInfo = new BasketInfo();

		//로그인된 사용자 C_PUBLIC_CI 값 조회 (비로그인시 로그인 페이지로 이동 처리)
		$this->userInfo = $this->BranchInfo->getOnlyLoginUserInfo();
		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	/*public function getApiFreeCouponView()
	{
		$data['branch'] = $this->BranchInfo->getBranch();
		$B_BRANCH = $data['branch'];

		//무료이용 쿠폰 가져오기
		$freeCouponApiData = $this->ApiHostNamu->getFreeCouponData($B_BRANCH, $this->session->userdata('M_PUBLIC_CI'));
		$freeCouponData = $this->ApiHostNamu->getFreeCouponDataParse($freeCouponApiData);

//		echo "<pre>"; print_r($freeCouponData); echo "</pre>"; exit();

		$result['coupponList'] = $freeCouponData;

		$this->load->view('/homepage/content/selectSurgery/freeproductCouponListView', $result);
	}*/


	/*public function getSurgeryRoomTime(){
		//진료실 조회 API
		$data['date'] = $this->input->get('date'); // 날짜
		$data['is_counsel'] = $this->input->get('is_counsel'); // 상담여부
		$treatment_shop = $this->input->get('treatment_shop'); // 예약상품
		$data['start_time'] = "10:00"; // 시작시간
		$data['end_time'] = "20:00"; // 종료시간
		$data['treatment_item'] = $this->input->get('treatment_item'); // 잔여시술
		$data['public_ci'] = ""; // 고객 public_ci

		$treatment_shopA = explode(',', $treatment_shop);
		$treatment_shop_arr = array();
		foreach ( $treatment_shopA as $index => $val ) {
			if ( strpos( $val, '_' ) !== false ) {
				$explodeData = explode( '_', $val );
				$treatment_shop_arr[$index] = $explodeData[0];
			} else {
				$treatment_shop_arr[$index] = $val;
			}
		}
		$data['treatment_shop'] = implode(",", $treatment_shop_arr);

//		echo "<pre>"; print_r($data); echo "</pre>"; exit();

		$sendUrl = $this->ApiHostNamu->getNamuApiUrl() . "/v1/public/reserveConfig/room";
		$curl_Result = $this->ApiHostNamu->getCurl($sendUrl, $data);
		$jsonRes = json_decode($curl_Result);
//		echo "<pre>"; print_r($jsonRes); echo "<pre>"; exit();

		if ( $jsonRes->code === 200 ) {
			$result['rtData'] = $this->ApiHostNamu->roomTimeParseDate($jsonRes, $data['is_counsel']);
		}
//		echo "<pre>"; print_r($result['rtData']); echo "<pre>"; exit();

		$this->load->view('/homepage/content/reservation/timeTable', $result);
	}*/


	/**
	 * 날짜/시간 선택 화면
	 * @return void
	 */
	public function index()
	{
		if ($this->branch == 'ppeum920') {
			redirect('/info', 'location', 302);
			return;
		}
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->userInfo->public_ci, $this->branch);
		//장바구니 화면에서 예약인지 확인
		$basketViewReservation = $this->input->cookie('resBasketYn') == 'Y' ? 'Y' : 'N';
		//예약 수정인지 확인
		$resNumber = $this->input->cookie('resNumber');
		//지점 휴일 조회
		$day = $this->ApiHostNamu->getResHoliday();
		//예약 상품 조회를 위한 데이터 설정
		$basket = array(
			'product' => $this->input->cookie('resTsCode'),
			'product_remain' => $this->input->cookie('resRemain'),
			'event' => $this->input->cookie('resEventTsCode'),
			'event_category' => $this->input->cookie('resEventTsSectCateCode'),
			'event_remain' => $this->input->cookie('resEventRemain')
		);
		//상품 정보 조회
		$data = $this->ApiHostNamu->getResProduct($this->userInfo->public_ci, $basket);
//		echo '<pre>'; print_r($data); echo '</pre>';
		//사용자 마일리지 정보 조회
		$mileageApiData = $this->ApiHostNamu->getHaveMileageData($this->userInfo->public_ci);
		$mileageData = $mileageApiData->result->C_MILEAGE;

		//잔여 시술로만 접근했는지 확인
		$productCount = count(explode(',', $basket['product']));
		$productRemainCount = 0;
		foreach(explode(',', $basket['product_remain']) as $item) {
			if (!empty($item)) $productRemainCount++;
		}
		//상품 개수 와 잔여 시술 개수가 동일한지 확인
		$remain_check = $productCount == $productRemainCount;

		$result = array(
			'product' => $data,
			'days' => $day,
			'cnt' => $basketCnt,
			'menu' => $this->menu,
			'mileage' => $mileageData,
			'basket_yn' => $basketViewReservation,
			'res_number' => $resNumber,
			'remain_check' => $remain_check,
			'branch' => $this->branch
		);

		if (!empty($basket['product']) || !empty($basket['event']) || !empty($basket['product_remain'])) {
			$result['check'] = true;
		}

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/reservation/reservation_r', $result, true);
	}

	/**
	 * 진료실 조회
	 * @return void
	 */
	public function getResRoomTime()
	{
		//반환정보
		$result = array();
		//예약날짜
		$date = $this->input->get('date');
		//상품여부
		$isCounsel = $this->input->get('is_counsel');
		//현재 날짜
		$nowDate = Date('Y-m-d');
		//현재 시간
		$nowTime = Date('H:i');

		//CRM 시간 정보 조회
		$timeParams = array('date' => $date);
		$timeResult = $this->ApiHostNamu->getNamuApi('/v1/public/reserveConfig/time', $timeParams);
		$timeData = json_decode($timeResult);

		//CRM 진료실 정보 조회
		$roomParams = array(
			'date' => $this->input->get('date'),//날짜
			'is_counsel' => $isCounsel,//상담여부
			'treatment_item' => implode(",", $this->input->get('treatment_item')),//잔여시술
			'start_time' => '10:00',//시작시간
			'end_time' => '20:00',//종료시간
			'public_ci' => '',//고객번호
			'treatment_shop' => implode(",", $this->input->get('treatment_shop'))//상품
		);
		$roomResult = $this->ApiHostNamu->getNamuApi('/v1/public/reserveConfig/room', $roomParams);
		$roomData = json_decode($roomResult);

		//진료 가능 진료실 시간 설정
		if ($roomData->code === 200) {
			$result['rtData'] = $this->ApiHostNamu->roomTimeParseDate($roomData, $isCounsel);
		}

		//진료 가능 시간 설정
		foreach ($result['rtData'] as $key => $value) {
			//오늘 지나간 시간에 대해 선택 불가 처리
			if ($value['date'] == $nowDate && $value['time'] <= $nowTime) {
				$result['rtData'][$key]['is_reserve'] = 0;
			}

			foreach ($timeData->data->config->time as $item) {
				if ($value['time'] == $item->time) {
					if ($item->max_count <= $item->count) {
						$result['rtData'][$key]['is_reserve'] = 0;
					}
					break;
				}
			}
		}

		$this->load->view('/homepage/content/reservation/timeTable_r', $result);
	}

	/**
	 * 예약완료 페이지
	 * @return void
	 */
	public function resComplete()
	{
		//장바구니 개수 조회
		$basketCnt = $this->BasketInfo->getBasketCount($this->userInfo->public_ci, $this->branch);
		//예약번호 조회
		$rNumber = $this->input->get('r_number');
		//예약취소 API 경로
		$cancelApiUrl = $this->ApiHostNamu->getNamuApiUrl() . '/v1/public/reserve/' . $this->userInfo->public_ci . '/' . $rNumber;
		//예약 정보 조회
		$data = $this->ApiHostNamu->getUserReservationRNumber($this->userInfo->public_ci, $rNumber);
		//예약시 사용한 혜택 정보 조회
		$benefit = $this->ReservationModel->getBenefit($this->userInfo->public_ci, $this->branch, $rNumber);
		//사용한 혜택 총 금액
		$cpc = 0;
		//사용한 마일리지 총 금액
		$mileage = '0';
		//예약완료 문구 지점
		$branch_check = $this->BranchInfo->getReservationReviewStatus();
		//보호자 시술 동의서 파일
		$infoFile = $this->TermsSetModel->getTermsList($this->branch, 'child', 'KR');
		$info = $infoFile[0];

		foreach ($benefit as $item) {
			if ($item['R_RESERVE_TYPE'] != '') {
				$cpc += (int)json_decode($item['R_COUPON'])->dc_price;
			} else {
				$mileage = $item['R_MILEAGE'];
			}
		}

		$result = array(
			'cnt' => $basketCnt,
			'menu' => $branch_check ? '예약 신청 완료' : '예약 완료',
			'api_url' => $cancelApiUrl,
			'list' => $data['list'],
			'date' => $data['date'],
			'benefit' => $cpc,
			'mileage' => $mileage,
			'res_number' => $rNumber,
			'branch_check' => $branch_check,
			'info' => $info
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/reservation/reservationComplete_r', $result, true);
	}

	/**
	 * 예약 처리
	 * @return void
	 */
	public function setReservation()
	{
		$check = 'failed';
		$eventUsed = null;
		$benefitUsed = null;
		$rNumber = null;

		$data['before_reserve_number'] = $this->input->post('res_number');
		$data['public_ci'] = $this->userInfo->public_ci;
		//$data['reserve_type'] = '이벤트(홈)';
		$data['reserve_type'] = '실시간예약(홈)';
		$data['reserve_date'] = $this->input->post('date');
		$data['reserve_time'] = $this->input->post('time');
		$data['room_seq'] = $this->input->post('room');
		$data['is_counsel'] = $this->input->post('is_counsel');
		$data['memo'] = $this->input->post('memo');
		$data['treatment_minute'] = $this->input->post('treatment_minute');
		$data['referrer1'] = '홈페이지';
		$type = '온라인 예약';

		$mileage = $this->input->post('mileage');
		$ts_fir_cate = $this->input->post('treatment_shop_fir_cate_no');
		$ts_sec_cate = $this->input->post('treatment_shop_sec_cate_no');
		$its_sec_cate = $this->input->post('treatment_item_sec_cate_no');

		$checkList = array(
			'benefit' => array(),
			'event' => array()
		);
		//사용한 쿠폰여부 확인
		foreach($this->input->post('benefit') as $item) {
			array_push($checkList['benefit'], $item['cpc_code']);
		}
		//사용한 1회 이벤트
		foreach ($this->input->post('treatment_shop') as $key => $value ) {
			if ($ts_fir_cate[$key] == 'event') {
				array_push($checkList['event'], $value);
			}
		}

		if (count($checkList['benefit']) > 0 || count($checkList['event']) > 0) {
			$benefitCheck = $this->ApiHostNamu->usedBenefitCheck($this->userInfo->public_ci, $checkList);
			$benefitUsed = $benefitCheck['benefit'];
			$eventUsed = $benefitCheck['eventUsed'];
		}

		if ((is_null($benefitUsed) || $benefitUsed != 'used') && (is_null($eventUsed) || $eventUsed != 'used')) {
			$treatment_shop = array();
			foreach ($this->input->post('treatment_shop') as $index => $row ) {
				$code = $row;
				if ($ts_fir_cate[$index] == 'event') {
					$type = '이벤트 예약';
					$code = $code . '_' . $ts_sec_cate[$index];
				}
				$treatment_shop[] = array(
					'code' => $code,
					'quantity' => 1
				);
			}
			$data['treatment_shop'] = $treatment_shop;

			$treatment_item = array();
			foreach ($this->input->post('treatment_item') as $item) {
				$treatment_item[] = array(
					'seq' => $item,
					'quantity' => 1
				);
			}
			$data['treatment_item'] = $treatment_item;
			$data['referrer2'] = $type;

			//나무 API로 CRM 예약 등록
			$curl_Result = $this->ApiHostNamu->setNamuApiPost('/v1/public/reserve', $data);
			$jsonRes = json_decode($curl_Result);

			if ($jsonRes->code === 201) {
				$rNumber = $jsonRes->data->number;

				//사용한 혜택 쿠폰이 있을 경우 혜택 쿠폰 사용 처리
				foreach ($this->input->post('benefit') as $item) {
					$insert = Array(
						'R_BRANCH' => $this->branch,
						'R_PUBLIC_CI' => $this->userInfo->public_ci,
						'R_RES_NUM' => $rNumber,
						'R_RESERVE_TYPE' => $data['reserve_type'],
						'R_RESERVE_DATE' => $data['reserve_date'],
						'R_RESERVE_TIME' => $data['reserve_time'],
						'R_IS_COUNSEL' => $data['is_counsel'],
						'R_TREAMENT_SHOP' => json_encode($data['treatment_shop']),
						'R_TREAMENT_ITEM' => json_encode($data['treatment_item']),
						'R_ROOM_SEQ' => $data['room_seq'],
						'R_COUPON' => json_encode($item),
						'R_MILEAGE' => '',
						'R_REFFER1' => $data['referrer1'],
						'R_REFFER2' => $data['referrer2']
					);

					$this->ReservationModel->setBenefit($insert);
				}

				//사용한 마일리지가 있을 경우 사용 처리
				if (!empty($mileage) && $mileage != '0') {
					$insert = Array(
						'R_BRANCH' => $this->branch,
						'R_PUBLIC_CI' => $this->userInfo->public_ci,
						'R_RES_NUM' => $rNumber,
						'R_RESERVE_TYPE' => '',
						'R_RESERVE_DATE' => '',
						'R_RESERVE_TIME' => '',
						'R_IS_COUNSEL' => '',
						'R_TREAMENT_SHOP' => '',
						'R_TREAMENT_ITEM' => '',
						'R_ROOM_SEQ' => '',
						'R_COUPON' => '',
						'R_MILEAGE' => $mileage,
						'R_REFFER1' => '',
						'R_REFFER2' => ''
					);

					$this->ReservationModel->setBenefit($insert);
				}

				//예약 상품이 장바구니에 있었을 경우, 장바구니에서 삭제 처리
				if ($this->input->post('basket_yn') == 'Y') {
					foreach ($this->input->post('treatment_shop') as $key => $value ) {
						$this->BasketModel->setBasketDelete($this->userInfo->public_ci, $this->branch, $ts_sec_cate[$key], $value);
					}
					foreach ($this->input->post('it_ts') as $key => $value) {
						if (!empty($value)) {
							$this->BasketModel->setBasketDelete($this->userInfo->public_ci, $this->branch, $its_sec_cate[$key], $value);
						}
					}
					foreach ($this->input->post('treatment_item') as $value) {
						$this->BasketModel->setBasketItemDelete($this->userInfo->public_ci, $this->branch, $value);
					}
				}

				$check = 'success';
			} else if ($jsonRes->code === 623 && $jsonRes->data->error_field === 'reserve_count') {
				$check = 'duplication';
			}
		}

		$result = array(
			'checked' => $check,
			'benefitUsed' => $benefitUsed,
			'eventUsed' => $eventUsed,
			'r_number' => $rNumber
		);

		echo json_encode($result);
	}

}
