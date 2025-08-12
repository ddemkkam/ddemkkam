<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileUpload extends CI_Controller {

	protected $dest;
	protected $ext;
	protected $chunkTime;

	public function __construct()
	{
		parent::__construct();
//		$this->load->helper(array('form', 'url'));
//		$this->load->library(array('session', 'pagenation', 'masterCheck', 'historyData'));

//		if ( !is_writable(FILES_PATH)) {
//			THROW NEW \eXCEPTION()
//		}


	}

	public function uploadView()
	{
		$result['id'] = $this->input->get("id");
		$result['cnt'] = $this->input->get("cnt");
		$result['viewId'] = $this->input->get("viewId");

		$this->load->view('/file_upload', $result);
	}

	public function fileUpload($_FILESa = null, $type)
	{
		$this->load->library('upload');
		//file upload
		//echo getenv("IMG_PATH_FAVICON"); exit();
		if ( $type === 'favicon' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_FAVICON");
		} else if ( $type === 'child' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_CHILD");
		} else if ( $type === 'popup' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_POPUP");
		} else if ( $type === 'event' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_EVENT");
		} else if ( $type === 'hospital' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_HOSPITAL");
		} else if ( $type === 'doctor' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_DOCTOR");
		} else if ( $type === 'eventinfo' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_EVENTINFO");
		} else if ( $type === 'mainimg' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_MAINIMG");
		} else if ( $type === 'mypagepc' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_MYPAGEPC");
		} else if ( $type === 'mypagemobile' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_MYPAGEMOBILE");
		}
		$config['allowed_types']        = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|ico|ICO|zip|ZIP'; //;'gif|jpg|jpeg|png';
		$config['max_size']             = 100000000;
		//$config['max_width']            = 3000;
		//$config['max_height']           = 768;

		$_FILES = $_FILESa;
		$files = $_FILES;
		$cpt = count($_FILES['ev_image']['name']);

		$_parsFiles = array();
		$k = 0;
		for( $i = 0; $i < $cpt; $i++ ) {
			if (isset($_FILES['ev_image']['name'][$i]) && $_FILES['ev_image']['name'][$i] != '') {
				list($microtime,$timestamp) = explode(' ',microtime());
				$time = $timestamp.substr($microtime, 2, 3);

				$fName = $time.'_'.$files['ev_image']['name'][$i];
				$fName = str_replace(" ", "_", $fName);
				$_FILES['ev_image']['name'] = $fName;
				$_FILES['ev_image']['type'] = $files['ev_image']['type'][$i];
				$_FILES['ev_image']['tmp_name'] = $files['ev_image']['tmp_name'][$i];
				$_FILES['ev_image']['error'] = $files['ev_image']['error'][$i];
				$_FILES['ev_image']['size'] = $files['ev_image']['size'][$i];

				$this->upload->initialize($config);
				$result = $this->upload->do_upload('ev_image');
				if( !$result ) {
					echo $this->upload->display_errors().'<br />';
				} else {
					$_parsFiles[$k]['name'] = $fName;
					$_parsFiles[$k]['type'] = $files['ev_image']['type'][$i];
					$_parsFiles[$k]['tmp_name'] = $files['ev_image']['tmp_name'][$i];
					$_parsFiles[$k]['error'] = $files['ev_image']['error'][$i];
					$_parsFiles[$k]['size'] = $files['ev_image']['size'][$i];
					if (isset($data['ev_image_id'][$i]) && $data['ev_image_id'][$i] != '') {
						$_parsFiles[$k]['ev_image_id'] = $data['ev_image_id'][$i];
					}
					$_parsFiles[$k]['ef_sdate'] = $data['ef_sdate'][$i];
					$_parsFiles[$k]['ef_edate'] = $data['ef_edate'][$i];
					$_parsFiles[$k]['ef_link'] = $data['ef_link'][$i];
					$_parsFiles[$k]['ef_sns_link'] = $data['ef_sns_link'][$i];
					$_parsFiles[$k]['ef_no'] = $k;
					$_parsFiles[$k]['ef_sort'] = $k;
					$_parsFiles[$k]['ef_source'] = $files['ev_image']['name'][$i];
					$_parsFiles[$k]['ef_file'] = $fName;
					$_parsFiles[$k]['ef_filesize'] = $files['ev_image']['size'][$i];
					$k++;
				}

			}
		}
		//echo "<pre>"; print_r($_parsFiles); echo "</pre>"; exit();
		return json_encode($_parsFiles, JSON_UNESCAPED_UNICODE);
	}

	public function index()
	{
		$result = $this->fileUpload($_FILES, 'favicon');
		echo $result;
	}

	public function child()
	{
		$result = $this->fileUpload($_FILES, 'child');
		echo $result;
	}

	public function popup()
	{
		$result = $this->fileUpload($_FILES, 'popup');
		echo $result;
	}

	public function event()
	{
		$result = $this->fileUpload($_FILES, 'event');
		echo $result;
	}

	public function hospital()
	{
		$result = $this->fileUpload($_FILES, 'hospital');
		echo $result;
	}

	public function doctor()
	{
		$result = $this->fileUpload($_FILES, 'doctor');
		echo $result;
	}

	public function eventinfo()
	{
		$result = $this->fileUpload($_FILES, 'eventinfo');
		echo $result;
	}

	public function mainimg()
	{
		$result = $this->fileUpload($_FILES, 'mainimg');
		echo $result;
	}

	public function mypagepc()
	{
		$result = $this->fileUpload($_FILES, 'mypagepc');
		echo $result;
	}

	public function mypagemobile()
	{
		$result = $this->fileUpload($_FILES, 'mypagemobile');
		echo $result;
	}


	public function board($type = null)
	{
		log_message('error', "type - ".$type);
		$this->load->library('upload');

		if ( $type === 'notice' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_NOTICE");
		} else if ( $type === 'media' ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_MEDIA");
		} else {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . getenv("IMG_PATH_BOARD");
		}
		$config['allowed_types']        = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF|ico|ICO|zip|ZIP'; //;'gif|jpg|jpeg|png';
		$config['max_size']             = 100000000;

		if (isset($_FILES['upload']['name']) && $_FILES['upload']['name'] != '') {
			list($microtime,$timestamp) = explode(' ',microtime());
			$time = $timestamp.substr($microtime, 2, 3);

			$fName = $time.'_'.$_FILES['upload']['name'];
			$fName = str_replace(" ", "_", $fName);
			$_FILES['upload']['name'] = $fName;
//			$_FILES['upload']['type'] = $_FILES['upload']['type'];
//			$_FILES['upload']['tmp_name'] = $_FILES['upload']['tmp_name'];
//			$_FILES['upload']['error'] = $_FILES['upload']['error'];
//			$_FILES['upload']['size'] = $_FILES['upload']['size'];

			$message = '';
			$funcNum = $this->input->get("CKEditorFuncNum");//$_GET['CKEditorFuncNum'];
			if ( $type === 'notice' ) {
				$imageBaseUrl = getenv("IMG_PATH_NOTICE");
			} else if ( $type === 'media' ) {
				$imageBaseUrl = getenv("IMG_PATH_MEDIA");
			} else {
				$imageBaseUrl = getenv("IMG_PATH_BOARD");
			}

			//$name = $_FILES['upload']['name'];
			$url = $imageBaseUrl . $fName ;

			//echo $funcNum; exit();

			$this->upload->initialize($config);
			$result = $this->upload->do_upload('upload');
			if( !$result ) {
				echo $this->upload->display_errors().'<br />';

				$message = '업로드된 파일이 없습니다.';
			} else {
//				$_parsFiles['name'] = $fName;
//				$_parsFiles['type'] = $_FILES['upload']['type'];
//				$_parsFiles['tmp_name'] = $_FILES['upload']['tmp_name'];
//				$_parsFiles['error'] = $_FILES['upload']['error'];
//				$_parsFiles['size'] = $_FILES['upload']['size'];

			}

			echo "<script>; window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message')</script>";

		}

	}


}
