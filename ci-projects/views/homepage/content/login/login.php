<div class="auth_login__egzBr">
	<div class="auth_login__inner__tTIG0">
		<h1>간편하게 로그인하고<b>이벤트 혜택과 원하는 시술을</b>쉽고 빠르게 찾아보세요!</h1>
		<p>로그인 · 회원가입 시<br>고객님께 발급된 혜택, 예약 정보 등<br>차별화된 서비스를 받아 볼 수 있어요.</p>
		<?/*
		<button class="auth_kakao__bL1vI" onclick="loginWithKakao('<?=isset($reffer) ? $reffer : '/'?>');">카카오 로그인</button>
		*/?>
		<a href="<?=$kakaoConfig['login_auth_url']?>">
			<button class="auth_kakao__bL1vI" >카카오 로그인</button>
		</a>
		<a href="/registMember">
			<button class="auth_phone__bL1vI" style="background-color:#cf2f75;color:white;">이름/연락처 로그인</button>
		</a>
		<!--<a class="auth_benefit-view__mu8Q2" href="/benefitInfo">회원·멤버십 혜택 보기 ></a>-->
	</div>
</div>


<script>



</script>
