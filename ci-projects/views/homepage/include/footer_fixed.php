<?if (
	$_SERVER['REQUEST_URI'] === '/'
	|| strpos($_SERVER['REQUEST_URI'], '/home') !== false || strpos($_SERVER['REQUEST_URI'], '/selectSurgery') !== false
	|| strpos($_SERVER['REQUEST_URI'], '/basket') !== false || strpos($_SERVER['REQUEST_URI'], '/notice') !== false
	|| strpos($_SERVER['REQUEST_URI'], '/about') !== false || strpos($_SERVER['REQUEST_URI'], '/doctor') !== false
	|| strpos($_SERVER['REQUEST_URI'], '/signUpComplete') !== false || strpos($_SERVER['REQUEST_URI'], '/notandum') !== false
	|| strpos($_SERVER['REQUEST_URI'], '/mypage') !== false || strpos($_SERVER['REQUEST_URI'], '/event') !== false
	|| strpos($_SERVER['REQUEST_URI'], '/search') !== false
) {?>
<footerfix >
	<ul id="footerfixul" class="Footer_bottom-quick__hfOZB Footer_show__oKAVW" style="background-color: #fff;">
		<li class="<?=strpos($_SERVER['REQUEST_URI'], '/selectSurgery') !== false ? 'Footer_reserve__zrW6W_on' : 'Footer_reserve__zrW6W'?>">
			<a href="/selectSurgery">시술예약</a>
		</li>
		<li class="Footer_call__zrW6W">
			<a href="javascript:counselViewShowFun()">상담</a>
		</li>
		<li class="<?=strpos($_SERVER['REQUEST_URI'], '/event') !== false ? 'Footer_event__wCoCH_on' : 'Footer_event__wCoCH'?>">
			<a href="/event">이벤트</a>
		</li>
		<li class="<?=strpos($_SERVER['REQUEST_URI'], '/about') !== false ? 'Footer_about__N9PUs_on' : 'Footer_about__N9PUs'?>">
			<a href="/about">병원 소개</a>
		</li>
		<li class="<?=strpos($_SERVER['REQUEST_URI'], '/mypage') !== false ? 'Footer_my__3ZfQX_on' : 'Footer_my__3ZfQX'?>">
			<a href="/mypage">마이페이지</a>
		</li>
	</ul>
</footerfix>
<?} else if (strpos($_SERVER['REQUEST_URI'], '/info') !== false){?>
	<footerfix >
		<ul id="footerfixul" class="Footer_bottom-quick__hfOZB Footer_show__oKAVW" style="background-color: #fff;">
			<li class="Footer_call__zrW6W" style="width:50%;">
				<button style="width: 90%;margin-left:5%; height: 52px; margin-top: 10px; text-align: center; border-radius: 8px; background-color: #fae100;
									font-size: 14px; font-weight: 600; color: #111;">
					<a href="/kakaoChat" target="_blank" style="background-image:none;font-size: 14px;padding-top:18px;color:black;">
						<table style="margin: auto;">
							<tr>
								<td><img src="/common/homepage/img/common/ico_kakao.svg" /></td>
								<td style="vertical-align: middle; padding-top: 2px; padding-left: 5px;">카카오톡 상담</td>
							</tr>
						</table>
					</a>
				</button>
			</li>
			<li class="Footer_call__zrW6W" style="width:50%;">
				<button style="width: 90%;right: 5%; height: 52px; margin-top: 10px; text-align: center; border-radius: 8px; background-color: #48CB6B;
									font-size: 14px; font-weight: 600; color: #111;">
					<a href="javascript:void(0)" onclick="callOrPopup()" style="background-image:none;font-size: 14px;padding-top:18px;color:white;">
						<table style="margin: auto;">
							<tr>
								<td><img src="/common/homepage/img/common/ico_call.svg" /></td>
								<td style="vertical-align: middle; padding-top: 2px; padding-left: 5px;">전화 상담</td>
							</tr>
						</table>
					</a>
				</button>
			</li>
		</ul>
	</footerfix>

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
<?} else {?>

<?}?>
