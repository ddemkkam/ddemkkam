<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getBasketData($M_PUBLIC_CI = null, $B_BRANCH = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_BASKET a
            where 
                1 = 1
            	and a.B_PUBLIC_CI = '%s'
            	and a.B_BRANCH = '%s'
            group by 
            	a.B_PRODUCT_ID
        ", $M_PUBLIC_CI, $B_BRANCH);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function deleteBasketData($M_PUBLIC_CI = null, $B_BRANCH = null, $category1 = null, $category2 = null) {
		$this->db->where('B_BRANCH', $B_BRANCH);
		$this->db->where('B_PUBLIC_CI', $M_PUBLIC_CI);
		$this->db->where('B_CATEGORY1', $category1);
		$this->db->where('B_CATEGORY2', $category2);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_BASKET');

		return $result;
	}

	public function deleteBasketDataOne($M_PUBLIC_CI = null, $B_BRANCH = null, $category1 = null, $category2 = null, $code = null) {
		$this->db->where('B_BRANCH', $B_BRANCH);
		$this->db->where('B_PUBLIC_CI', $M_PUBLIC_CI);
		$this->db->where('B_CATEGORY1', $category1);
		$this->db->where('B_CATEGORY2', $category2);
		$this->db->where('B_PRODUCT_ID', $code);
		$result = $this->db->delete(getenv('DATABASE_NAME').'.P_BASKET');

		return $result;
	}

	public function insertBasketData($insertData) {
		$result = $this->db->insert(getenv('DATABASE_NAME').'.P_BASKET', $insertData);

		return $result;
	}


	public function getBasketGroupData($M_PUBLIC_CI = null, $B_BRANCH = null)
	{

		$query = sprintf("
            SELECT
                a.B_CATEGORY2
            FROM
                " . getenv('DATABASE_NAME') . ".P_BASKET a
            where 
                1 = 1
            	and a.B_PUBLIC_CI = '%s'
            	and a.B_BRANCH = '%s'
            group by 
            	a.B_CATEGORY2
            ORDER BY 
				a.REG_DATE desc
        ", $M_PUBLIC_CI, $B_BRANCH);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getBasketListData($M_PUBLIC_CI = null, $B_BRANCH = null)
	{

		$query = sprintf("
            SELECT
                a.*
            FROM
                " . getenv('DATABASE_NAME') . ".P_BASKET a
            where 
                1 = 1
            	and a.B_PUBLIC_CI = '%s'
            	and a.B_BRANCH = '%s'
            group by 
            	a.B_PRODUCT_ID
            ORDER BY 
				a.REG_DATE desc
        ", $M_PUBLIC_CI, $B_BRANCH);

//		echo $query;

		$queryResult = $this->db->query($query);
		$result = $queryResult->result_array();

		return $result;
	}

	public function getBasketList($M_PUBLIC_CI = null, $B_BRANCH = null)
	{
		$query = sprintf("
            SELECT
                group_concat(if (B_CATEGORY1 = 'event', ifnull(B_PRODUCT_ID, ''), null)) as event
				,group_concat(if (B_CATEGORY1 != 'event', ifnull(B_PRODUCT_ID, ''), null)) as product
				,group_concat(if (B_CATEGORY1 = 'event', ifnull(B_CATEGORY2, ''), null)) as event_category
				/*,group_concat(if (B_CATEGORY1 = 'event', B_COUPON, null)) as event_benefit
				,group_concat(if (B_CATEGORY1 != 'event', B_COUPON, null)) as product_benefit*/
            	,group_concat(if (B_CATEGORY1 = 'event', ifnull(B_REMAINS_PRO, ''), null)) as event_remain
				,group_concat(if (B_CATEGORY1 != 'event', ifnull(B_REMAINS_PRO, ''), null)) as product_remain
            FROM
                " . getenv('DATABASE_NAME') . ".P_BASKET
            WHERE B_PUBLIC_CI = '%s'
			AND B_BRANCH = '%s' 
			ORDER BY B_CATEGORY2, B_PRODUCT_ID
        ", $M_PUBLIC_CI, $B_BRANCH);

		$queryResult = $this->db->query($query);
		return $queryResult->row();
	}

	public function setBenefitDelete($mPublicCi, $branch, $cateCode, $tsCode)
	{
		$query = sprintf("
			UPDATE " . getenv('DATABASE_NAME') . ".P_BASKET
			SET B_COUPON = ''
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s' 
			AND B_CATEGORY2 = '%s' 
			AND B_PRODUCT_ID LIKE '%%%s%%' 
        ", $mPublicCi, $branch, $cateCode, $tsCode);

		$this->db->query($query);

	}

	public function setBenefitInsert($mPublicCi, $branch, $cateCode, $tsCode, $benefit)
	{
		$query = sprintf("
			UPDATE " . getenv('DATABASE_NAME') . ".P_BASKET
			SET B_COUPON = '%s'
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s' 
			AND B_CATEGORY2 = '%s' 
			AND B_PRODUCT_ID LIKE '%%%s%%' 
        ", $benefit, $mPublicCi, $branch, $cateCode, $tsCode);

		$this->db->query($query);
	}

	public function setBasketDelete($mPublicCi, $branch, $cateCode, $tsCode)
	{
		$query = sprintf("
			DELETE FROM " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s'
			AND B_CATEGORY2 = '%s' 
			AND B_PRODUCT_ID LIKE '%%%s%%' 
        ", $mPublicCi, $branch, $cateCode, $tsCode);

		$this->db->query($query);
	}

	public function setBasketItemDelete($mPublicCi, $branch, $ctiCode)
	{
		$query = sprintf("
			DELETE FROM " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s'
			AND B_REMAINS_PRO = '%s'  
        ", $mPublicCi, $branch, $ctiCode);

		$this->db->query($query);
	}

	public function getBasketProduct($mPublicCi, $branch, $cateCode)
	{
		$query = sprintf("
            SELECT
               B_PRODUCT_ID as ts_code
            FROM
                " . getenv('DATABASE_NAME') . ".P_BASKET
            WHERE B_PUBLIC_CI = '%s'
			AND B_BRANCH = '%s' 
			AND B_CATEGORY2 = '%s'
        ", $mPublicCi, $branch, $cateCode);

		$queryResult = $this->db->query($query);
		return $queryResult->result_array();
	}

	public function getBasketUseBenefit($mPublicCi, $branch)
	{
		$query = sprintf("
			SELECT 
				group_concat(B_COUPON) as benefit
			FROM
				 " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s'
			AND B_BRANCH = '%s' 
			AND B_COUPON != ''
		", $mPublicCi, $branch);

		$queryResult = $this->db->query($query);
		return $queryResult->row();
	}

	public function setBasket($insert)
	{
		$this->db->insert(getenv('DATABASE_NAME').'.P_BASKET', $insert);
	}

	public function setBasketUpdate($mPublicCi, $branch, $tsCode, $cate1, $cate2, $benefit)
	{
		$query = sprintf("
			UPDATE " . getenv('DATABASE_NAME') . ".P_BASKET
			SET B_COUPON = '%s'
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s' 
			AND B_CATEGORY1 = '%s' 
			AND B_CATEGORY2 = '%s' 
			AND B_PRODUCT_ID = '%s' 
        ", $benefit, $mPublicCi, $branch, $cate1, $cate2, $tsCode);

		$this->db->query($query);
	}

	public function getBasketCheck($mPublicCi, $branch, $tsCode, $cate1, $cate2, $remain)
	{
		$query = sprintf("
			SELECT 
				*
			FROM
				 " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s'
			AND B_BRANCH = '%s' 
			AND B_CATEGORY1 = '%s'
			AND B_CATEGORY2 = '%s'
			AND B_PRODUCT_ID = '%s'
			AND B_REMAINS_PRO = '%s'
		", $mPublicCi, $branch, $cate1, $cate2, $tsCode, $remain);

		$queryResult = $this->db->query($query);
		return $queryResult->result_array();
	}

	public function setBenefitCateDelete($mPublicCi, $branch, $cateCode)
	{
		$query = sprintf("
			DELETE FROM " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s' 
			AND B_BRANCH = '%s'
			AND B_CATEGORY2 = '%s'  
        ", $mPublicCi, $branch, $cateCode);

		$this->db->query($query);

	}

	public function getBasketCount($mPublicCi, $branch)
	{
		$query = sprintf("
			SELECT 
				count(*) as cnt
			FROM
				 " . getenv('DATABASE_NAME') . ".P_BASKET
			WHERE B_PUBLIC_CI = '%s'
			AND B_BRANCH = '%s' 
			AND (B_END_DATE >= now() or B_END_DATE = '0000-00-00 00:00:00' or B_END_DATE is null)
		", $mPublicCi, $branch);

		$queryResult = $this->db->query($query);
		return $queryResult->row();
	}

}
