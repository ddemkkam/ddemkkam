<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once  $_SERVER['DOCUMENT_ROOT']."/application/libraries/PHPExcel.php";

class Excel extends PHPExcel {    
		
	public function __construct() {        
		parent::__construct();    
	}
}
