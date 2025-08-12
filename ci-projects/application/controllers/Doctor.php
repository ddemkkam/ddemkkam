<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller
{
	public $menu = '의료진 소개';
	private $AboutModel;
	private $BranchInfo;
	private $branch;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session', 'pagenation', 'apiHostNamu', 'namuErrorCode', 'branchInfo'));

		$this->load->model('/homepage/About_model');

		$this->AboutModel = new About_model();
		$this->BranchInfo = new BranchInfo();

		//지점 조회
		$this->branch = $this->BranchInfo->getBranch();
	}

	public function index()
	{
		//의료진 정보 조회
		$doctorGroupData = $this->AboutModel->selectDoctorGroupData($this->branch);
		//지점 정보 조회
		$branchInfo = $this->BranchInfo->getBranchInfo();

		$doctorData = $this->AboutModel->selectDoctorListData($this->branch);

		$returnData = array();
		foreach ( $doctorGroupData as $index => $row ) {
			$ii = 0;
			$returnData[$row['D_BRANCH']]['BRANCH_NAME'] = $row['BRANCH_NAME'];
			foreach ( $doctorData as $index2 => $row2 ) {
				if ( $row['D_BRANCH'] == $row2['D_BRANCH'] ) {
					$returnData[$row['D_BRANCH']]['DOCTOR'][$ii] = $row2;
					$ii++;
				}
			}
		}

		$result = array(
			'menu' => $this->menu,
			'branchInfo' => $branchInfo,
			'doctorData' => $returnData
		);

		$this->layout->setLayout("/homepage/layouts/layout");
		$this->layout->view('/homepage/content/doctor/doctor', $result, true);
	}

}
