<?
/*
if( !isset($_SESSION['member']['id']) ) {
	header('location:/');
	exit();
} else if (isset($_SESSION['member']['master']) && $_SESSION['member']['master'] != 'Y') {
	header('location:/front/state');
	exit();
}
*/
?>
<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/header.php'; ?>

<body>

<div id="root">

	<?if ( strpos($_SERVER['REQUEST_URI'], '/login') === false 
		|| strpos($_SERVER['REQUEST_URI'], '/login/kakaoOauth') === 0  ) {?>
		<div>
			<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/top.php'; ?>
		</div>
	<?}?>

	<main id="main">
		<?=$content_for_layout ?>

		<?// 전체 카테고리 불러오기 (임의로 레이아웃에 넣음 다른곳에서도 활용할지도 몰라서)?>
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/selectSurgery/totalCategory.php'; ?>
		<?// 상품 리스트?>
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/selectSurgery/productDetailList.php'; ?>
		<?// 쿠폰 리스트?>
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/selectSurgery/productCouponList.php'; ?>
		<?// 무료 쿠폰 리스트?>
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/selectSurgery/freeProductCouponList.php'; ?>
		<?//인기검색 전체?>
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/main/all_rank.php'; ?>
	</main>

	<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/counsel.php'; ?>

	<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/pvl_counsel.php'; ?>

	<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/modifyReservation.php'; ?>

	<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/footer.php'; ?>

	<?//전체메뉴?>
	<div id="all_menu">
		<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/content/allmenu/all_menu.php'; ?>
	</div>

</div>

<?// include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/footer.php'; ?>
<? include $_SERVER['DOCUMENT_ROOT'].'views/homepage/include/footer_fixed.php'; ?>

<?
/*
 * 이용약관, 개인정보 취급방침, 마케팅 약관
 */?>
<div id="serviceView"></div>



</body>

<? include $_SERVER['DOCUMENT_ROOT'].'script/naver_script.php';?>

</html>
