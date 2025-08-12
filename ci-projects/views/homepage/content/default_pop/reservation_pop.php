<div id="no_pop_div">
	<div class="base" style="height: 230px;padding-top:10px;">
		<div style="height: 40px;">
			<img src="/common/homepage/img/common/ico_close.svg" style="width:30px; height:30px;float: right;" onclick="alertViewHide('<?=isset($type) ? $type : '' ?>')">
		</div>
		<div class="baseText">

			<b><?=isset($text) ? $text : ''?></b>
			<p class="info">발급된 혜택은 <span style="text-decoration: underline;" onclick="location.href='/mypage'">‘마이페이지>혜택’</span>에서 확인 가능해요.</p>
		</div>

		<div>
			<button class="baseBtn" onclick="alertViewHide('<?=isset($type) ? $type : '' ?>')">예약하기</button>
		</div>
	</div>
</div>

