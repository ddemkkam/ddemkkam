<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BasketInfo
{
	public $branch;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('/homepage/Basket_model', 'Basket_model');

		$this->ApiHostNamu = new ApiHostNamu();
	}

	public function getBasketCount($mPublicCi, $branch)
	{
		$basket = $this->CI->Basket_model->getBasketList($mPublicCi, $branch);
		$result = $this->ApiHostNamu->getBasket($mPublicCi, $basket);
		return $result['total_count'];
	}

}
