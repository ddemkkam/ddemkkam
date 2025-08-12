<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Pagenation
{
    function pagenationHtml($url, $total, $curPage = 1, $pageNum = 10, $listNum = 100, $ogPage = null){

		if (!isset($pageNum)) {
			$pageNum = 10;
		}

		if (!isset($listNum)) {
			$listNum = 100;
		}

		$totalPage = 1;
		if ($total > 0) {
			$totalPage = ceil($total / $listNum);
		}

		$totalBlock = ceil($totalPage / $pageNum);
		$block = ceil($curPage / $pageNum);
		$firstPage = ($pageNum) * ($block - 1);
		$lastPage = ($pageNum) * $block;

		if ($block == $totalBlock) {
			$lastPage = $totalPage;
		}

		$url = "/".$url;
		$resultUrl = str_replace($url, "", $_SERVER['REQUEST_URI']).'';
		if($ogPage != null) {
			$resultUrl = str_replace("page=".$ogPage, "", $resultUrl).'';
		}
		//echo $resultUrl;
		
		$pageList = "<ul class='pagination'>";
		if ($block > 1 && $totalBlock > 1) {
			$pageList .= "<li><a class='paginate_button previous' href='".$resultUrl.'&page='.$firstPage."' title='이전'><i class='fa fa-angle-double-left'></i></a></li> ";
		}

		for ($i = $firstPage + 1; $i <= $lastPage; $i++) {
			if ($curPage == $i) {
				$pageList .= "<li class='paginate_button active'><a href='#'> " . $i . "</a></li> ";
			} else {
				$pageList .= "<li class='paginate_button'><a href='".$resultUrl.'&page='.$i."' title='".$i."'>" . $i . "</a></li>";
			}
		}

		if ($block < $totalBlock) {
			$pageList .= "<li class='paginate_button next'><a href='".$resultUrl.'&page='.($lastPage + 1)."' title='다음'><i class='fa fa-angle-double-right'></i></a></li>";
		}

		$pageList .= "</ul>";

		return $pageList;
	}


	function pagenationHtml2($url, $total, $curPage = 1, $pageNum = 10, $listNum = 100, $ogPage = null){

		if (!isset($pageNum)) {
			$pageNum = 10;
		}

		if (!isset($listNum)) {
			$listNum = 100;
		}

		$totalPage = 1;
		if ($total > 0) {
			$totalPage = ceil($total / $listNum);
		}

		$totalBlock = ceil($totalPage / $pageNum);
		$block = ceil($curPage / $pageNum);
		$firstPage = ($pageNum) * ($block - 1);
		$lastPage = ($pageNum) * $block;

		if ($block == $totalBlock) {
			$lastPage = $totalPage;
		}

		$url = "/".$url."";
		//echo $url.'<br />';
		//echo $_SERVER['REQUEST_URI'].'<br />';
		if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
			$resultUrl = $_SERVER['REQUEST_URI'].'';
		} else {
			$resultUrl = $_SERVER['REQUEST_URI'].'?';
		}
//		$resultUrl = $_SERVER['REQUEST_URI'];
		//echo $resultUrl;

		if($curPage != null) {
			$resultUrl = str_replace("&page=".$curPage, "", $resultUrl).'';
		}
		//echo $resultUrl;

		$pageList = "<ul class='pagination'>";
		if ($block > 1 && $totalBlock > 1) {
			$pageList .= "<li><a class='paginate_button previous' href='".$resultUrl.'&page='.$firstPage."' title='이전'><i class='fa fa-angle-double-left'></i></a></li> ";
		}

		for ($i = $firstPage + 1; $i <= $lastPage; $i++) {
			if ($curPage == $i) {
				$pageList .= "<li class='paginate_button active'><a href='#'> " . $i . "</a></li> ";
			} else {
				$pageList .= "<li class='paginate_button'><a href='".$resultUrl.'&page='.$i."' title='".$i."'>" . $i . "</a></li>";
			}
		}

		if ($block < $totalBlock) {
			$pageList .= "<li class='paginate_button next'><a href='".$resultUrl.'&page='.($lastPage + 1)."' title='다음'><i class='fa fa-angle-double-right'></i></a></li>";
		}

		$pageList .= "</ul>";

		return $pageList;
	}
}
?>
