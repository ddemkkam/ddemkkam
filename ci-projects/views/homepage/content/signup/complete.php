<div style="width: 80%; margin: auto; margin-top: 150px;">

	<div style="width: 158px; height: 180px; margin: auto;">
		<img src="/common/homepage/img/login/gift.png" style="width: 100%;"/>
	</div>

	<div style="margin-top: 20px; font-size: 22px; font-weight: bold; text-align: center; line-height: 30px;">
		<?=$this->session->userdata('M_NAME')?>님, 안녕하세요!
		<br />
		가입을 축하해요
	</div>
	<?/*
	<div style="letter-spacing: -0.28px; text-align: center; color: #878e9c; font-size: 14px; font-weight: 500; margin-top: 24px; line-height: 20px;">
		<?=$this->session->userdata('M_NAME')?>님께 첫 가입 혜택 쿠폰이 발급되었어요
		<br />
		혜택을 확인하고 예약을 시작해보세요!
	</div>
	*/?>
	<div style="width: 160px; height: 48px; margin: auto; margin-top: 24px;">
		<?//<a href="/mypage">?>
		<a href="/event">
			<button style="
				width: 100%; height: 100%;
				border-radius: 24px; border: solid 1px #cf2f75; background-color: #ffeef5; text-align: center;
				font-size: 14px; font-weight: 600; color: #cf2f75;
			">
				<?//혜택 보러가기?>
				이벤트 보러가기
			</button>
		</a>
	</div>

</div>

<?
/*
 * 네이버 스크립트 회원가입 완료
 */
?>

