<?if (
$_SERVER['REQUEST_URI'] === '/'
|| strpos($_SERVER['REQUEST_URI'], '/home') !== false || strpos($_SERVER['REQUEST_URI'], '/selectSurgery') !== false
|| strpos($_SERVER['REQUEST_URI'], '/basket') !== false || strpos($_SERVER['REQUEST_URI'], '/notice') !== false
|| strpos($_SERVER['REQUEST_URI'], '/about') !== false || strpos($_SERVER['REQUEST_URI'], '/doctor') !== false
|| strpos($_SERVER['REQUEST_URI'], '/signUpComplete') !== false || strpos($_SERVER['REQUEST_URI'], '/notandum') !== false
|| strpos($_SERVER['REQUEST_URI'], '/mypage') !== false || strpos($_SERVER['REQUEST_URI'], '/event') !== false
|| strpos($_SERVER['REQUEST_URI'], '/search') !== false
) {?>
	<footer>
		<a href="/">
			<img src="<?= isset($branchInfo['H_FOOTER_LOGO']) ? $branchInfo['H_FOOTER_LOGO'] : '' ?>" alt="footer_logo">
		</a>
		<div class="Footer_footer_cont__e+P-7">
			<ul>
				<li><a href="/notice">공지사항</a></li>
				<li><a href="javascript:serviceView('use');">이용약관</a></li>
				<li><a href="javascript:serviceView('public')">개인정보처리방침</a></li>
				<li class="Footer_company__xCnC1" onclick="footerInfoView()">사업자정보확인</li>
				<li class="footerInfo" style="height: 30px; padding-top: 10px;">
					<div style="clear: both;">
						<div class="footerTitle1">상호명</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_NAME']) ? $branchInfo['H_NAME'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo" style="height: 30px; padding-top: 10px;">
					<div style="clear: both;">
						<div class="footerTitle1">대표번호</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_CONTACT_M']) ? $branchInfo['H_CONTACT_M'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo">
					<div style="clear: both;">
						<div class="footerTitle1">대표자</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_CEO']) ? $branchInfo['H_CEO'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo">
					<div style="clear: both;">
						<div class="footerTitle1">사업자등록번호</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_OFFICENUMBER']) ? $branchInfo['H_OFFICENUMBER'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo address">
					<div style="clear: both;">
						<div class="footerTitle1">주소</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_ADDRESS']) ? $branchInfo['H_ADDRESS'] : '' ?>
						</div>
					</div>
				</li>
			</ul>

			<p class="Footer_copyright__iygxI">Copyright ©2024 PPEUMCLINIC. All Rights Reserved</p>
		</div>

	</footer>
<?} elseif ( strpos($_SERVER['REQUEST_URI'], '/info') !== false) {?>
	<footer>
		<a href="/">
			<img src="<?= isset($branchInfo['H_FOOTER_LOGO']) ? $branchInfo['H_FOOTER_LOGO'] : '' ?>" alt="footer_logo">
		</a>
		<div class="Footer_footer_cont__e+P-7">
			<ul>
				<li><a href="/notice">공지사항</a></li>
				<li><a href="javascript:serviceView('use');">이용약관</a></li>
				<li><a href="javascript:serviceView('public')">개인정보처리방침</a></li>
				<li><a href="javascript:void(0)">사업자정보확인</a></li>
				<li><a href="/common/homepage/ppeum.zip">미성년자동의서다운로드</a></li>
				<li class="footerInfo" style="height: 30px; padding-top: 10px;display: block !important">
					<div style="clear: both;">
						<div class="footerTitle1">상호명</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_NAME']) ? $branchInfo['H_NAME'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo" style="display: block !important">
					<div style="clear: both;">
						<div class="footerTitle1">대표번호</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_CONTACT_M']) ? $branchInfo['H_CONTACT_M'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo" style="display: block !important">
					<div style="clear: both;">
						<div class="footerTitle1">대표자</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_CEO']) ? $branchInfo['H_CEO'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo" style="display: block !important">
					<div style="clear: both;">
						<div class="footerTitle1">사업자등록번호</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_OFFICENUMBER']) ? $branchInfo['H_OFFICENUMBER'] : '' ?>
						</div>
					</div>
				</li>
				<li class="footerInfo address" style="display: block !important">
					<div style="clear: both;">
						<div class="footerTitle1">주소</div>
						<div class="footerDesc">
							<?=isset($branchInfo['H_ADDRESS']) ? $branchInfo['H_ADDRESS'] : '' ?>
						</div>
					</div>
				</li>
			</ul>

			<p class="Footer_copyright__iygxI">Copyright ©2024 PPEUMCLINIC. All Rights Reserved</p>
		</div>

	</footer>
<?}?>

<?if ( isset($main) && $main == 'Y' ){?>
	<script>
		// const mainImgHeight = $('#mainImg').height();
		// $('footer').css('margin-top', '225px');
	</script>
<?}?>
