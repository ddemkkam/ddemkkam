<?//if ( isset($main) && $main == 'Y' ){?>
<!--<header style="">-->
<?//} else {?>
<header style="background-color: #ffffff;">
<?//}?>
	<div class="inner">
		<div class="Header_nav-items__8BPxZ" style="float: left;">
			<?if ( strpos($_SERVER['REQUEST_URI'], '/doctor') !== false 
			|| strpos($_SERVER['REQUEST_URI'], '/reservation') !== false 
			|| ($_SERVER['REQUEST_URI'] == '/registMember') !== false  ) {?>
				<a href="javascript:history.back()">
					<button class="Header_btn-back__19VBA">뒤로 가기</button>
				</a>
			<?} else {?>
				<?if ( (isset($main) && $main == 'Y') || strpos($_SERVER['REQUEST_URI'], '/info') === 0){?>
					<div class="header-logo">
						<img src="<?= $branchInfo['H_LOGO'] ?>" alt="로고"/>
					</div>
				<?} else {?>
					<?if(strpos($_SERVER['REQUEST_URI'], '/login/kakaoOauth') === 0  ) {?>
					<? }else{ ?>
					<a href="/">
						<button class="Header_page-name__N4h7w_home">home</button>
					</a>	
					<? } ?>
				<?}?>
			<?}?>
			<div class="Header_page-name__N4h7w">
				<?=isset($menu) ? $menu : ''?>
			</div>
		</div>
		<?if ( strpos($_SERVER['REQUEST_URI'], '/doctor') !== false 
		|| ($_SERVER['REQUEST_URI'] == '/registMember') !== false 
		|| strpos($_SERVER['REQUEST_URI'], '/login/kakaoOauth') === 0 ) {?>

		<?} elseif (strpos($_SERVER['REQUEST_URI'], '/info') === 0) {?>
			<div class="Header_nav-items__8BPxZ" style="margin-top:5px;">
				<a href="/kakaoChat" target="_blank" style="position: relative; z-index: 10;">
					<img src="/common/homepage/img/busan/bkakaotalk.png">
				</a>
				<a href="javascript:void(0)" onclick="callOrPopup()" style="position: relative; z-index: 10;">
					<img src="/common/homepage/img/busan/bcall.png">
				</a>
			</div>

			<script>
				function callOrPopup() {
					const phoneNumber = '051-807-3400'; // 실제 전화번호
					const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

					if (isMobile) {
						// 모바일: 전화 걸기
						window.location.href = 'tel:' + phoneNumber.replace(/-/g, '');
					} else {
						// PC: 팝업 열기
						document.getElementById('popupWrap').style.display = 'block';
					}
				}
			</script>
		<?}else {?>
			<? if ($branchInfo['BRANCH'] != 'ppeum920') { ?>
				<div class="Header_nav-items__8BPxZ">
					<button type="button" class="Header_btn-search__tWKdc" onclick="location.href='/search'">검색</button>
					<a href="/basket">
						<button class="Header_btn-cart__M9mWg">장바구니<? if (isset($cnt) && $cnt > 0) { ?><span id="bastket_cnt"><?= $cnt > 9 ? '9+' : $cnt ?></span><? } else { ?><span id="bastket_cnt" style="display: none;"></span><? } ?></button>
					</a>
					<button class="Header_btn-cart__M9mWg_total" onclick="all_menu_fun_show()">전체메뉴</button>
				</div>
			<? } ?>
		<?}?>
	</div>
</header>
