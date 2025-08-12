<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DefaultPop extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		$result['text'] = $this->input->post('text');
		$result['type'] = $this->input->post('type');

		$this->load->view('/homepage/content/default_pop/default_pop', $result);
	}

	public function double()
	{
		$result['text'] = $this->input->post('text');
		$result['type'] = $this->input->post('type');

		$this->load->view('/homepage/content/default_pop/double_line_pop', $result);
	}

	public function three()
	{
		$result['text'] = $this->input->post('text');
		$result['type'] = $this->input->post('type');

		$this->load->view('/homepage/content/default_pop/three_line_pop', $result);
	}

	public function confirm()
	{
		$result['text'] = $this->input->post('text');
		$result['type'] = $this->input->post('type');

		$this->load->view('/homepage/content/default_pop/confirm_pop', $result);
	}

	public function reservation()
	{
		$result['text'] = $this->input->post('text');
		$result['type'] = $this->input->post('type');

		$this->load->view('/homepage/content/default_pop/reservation_pop', $result);
	}

}
