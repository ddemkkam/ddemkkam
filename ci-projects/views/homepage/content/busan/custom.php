<style>

	.swiper {
		width: 100%;
		height: 300px;
		margin-left: auto;
		margin-right: auto;
	}

	.swiper-slide {
		background-size: cover;
		background-position: center;
	}

	.mySwiper2 {
		/*height: 80%;*/
		width: 100%;
	}

	.mySwiper {
		height: 20%;
		box-sizing: border-box;
		padding: 10px 0;
	}

	.mySwiper .swiper-slide {
		width: 25%;
		height: 38px;
		/* padding: 12px 16px; */
		border-radius: 4px;
		background-color: #FFFFFF;
		color: #111111;
		text-align: center;
		font-size: 14px;
		border: solid 1px #d0d6de;
		line-height: 38px;
	}

	.mySwiper .swiper-slide-thumb-active {
		/*width: 100%;*/
		height: 38px;
		/* padding: 12px 16px; */
		border-radius: 4px;
		background-color: #111;
		color: #fff;
		text-align: center;
		font-size: 14px;
		line-height: 38px;
	}

	.swiper-slide img {
		display: block;
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
</style>

<script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>
<img src="/common/homepage/img/busan/rectangle.jpg" style="width: 100%; max-width: var(--max-width);">
<div class="About_about__Nw80V">
	<div id="section3" class="About_section__iti2X About_section3__VJ9zN" style="margin-top: 40px;">
		<h4>오시는 길</h4>
		<div class="About_map__wPrbF" style="text-align: center;">
			<!--			<img src="/common/homepage/img/about/naver_map.3900e5d0f5181f10a5c0.jpg" alt="네이버 지도">-->
			<div class="map_box">
				<?=isset($branchInfo['H_KAKAO_MAP_KEY']) ? $branchInfo['H_KAKAO_MAP_KEY'] : ''?>
			</div>
		</div>
		<dl style="border-bottom: 0px;">
			<dt>위치</dt>
			<dd><?=isset($branchInfo['H_LOCATION1']) ? $branchInfo['H_LOCATION1'] : ''?></dd>
			<dd></dd>
			<dd style="margin-top: 24px;"><?=isset($branchInfo['H_LOCATION2']) ? $branchInfo['H_LOCATION2'] : ''?></dd>
			<dd><?=isset($branchInfo['H_LOCATION3']) ? $branchInfo['H_LOCATION3'] : ''?></dd>
			<p class="About_map-sort__NiEU+" style="margin-top: 24px;">
				<?if ( isset($branchInfo['H_NAVER_MAP']) ) {?>
					<button class="About_naver__KYHGH" role="link" onclick="window.open('<?=$branchInfo['H_NAVER_MAP']?>', '네이버지도')">네이버지도</button>
				<?}?>
				<?if ( isset($branchInfo['H_KAKAO_MAP']) ) {?>
					<button class="About_kakao__lSMsv" role="link" onclick="window.open('<?=$branchInfo['H_KAKAO_MAP']?>', '카카오맵')">카카오맵</button>
				<?}?>
				<?if ( isset($branchInfo['H_T_MAP']) ) {?>
					<button class="About_tmap__5eW0P" role="link" onclick="window.open('<?=$branchInfo['H_T_MAP']?>', '티맵')">티맵</button>
				<?}?>
			</p>
		</dl>
		<div class="About_map__wPrbF" style="margin-top: -20px;">
			<div style="padding-left: 60px; padding-right: 60px;padding-bottom: 50px;">
				<?
				$H_PARKING = json_decode($branchInfo['H_PARKING']);
				if ( count($H_PARKING) > 1 ) { ?>
					<div style="width: 100%; height: 30px; margin: 50px 0 20px; /*padding: 0 33px 0 0;*/ border-radius: 15px; background-color: #c2c3c7; color: #fff;">
						<? foreach ( $H_PARKING as $index => $row ){?>
							<div class="about_park_menu <?=$index === 0 ? 'about_park_menu_left about_park_menu_on' : 'about_park_menu_right' ?>" onclick="parkView('parkView<?=$index?>', this)">
								<?=$row->H_PARK_TITLE?>
							</div>
						<? } ?>
					</div>
				<? } ?>
			</div>
			<?foreach ( $H_PARKING as $index => $row ){?>
				<div class="parkView parkView<?=$index?>" style="<?=$index !== 0 ? 'display: none;' : ''?>">
					<!--				<img src="/common/homepage/img/about/naver_map.3900e5d0f5181f10a5c0.jpg" alt="네이버 지도">-->
					<div class="map_box">
						<?=isset($row->H_PARK_KAKAO_MAP) ? $row->H_PARK_KAKAO_MAP : ''?>
					</div>
					<?//=isset($branchInfo['H_KAKAO_MAP_KEY']) ? $branchInfo['H_KAKAO_MAP_KEY'] : ''?>
				</div>
			<?}?>
		</div>

		<?foreach ( $H_PARKING as $index => $row ){?>
			<div class="parkView parkView<?=$index?>" style="<?=$index !== 0 ? 'display: none;' : ''?>" >
				<dl style="border-bottom: 0px;">
					<dt><?=$row->H_PARK_TITLE?></dt>
					<dd><?=$row->H_PARK_ADDRESS?></dd>
				</dl>
				<dl style="border-bottom: 0px; padding: 0 20px">
					<?if (isset($row->H_PARK_DESC1) && !empty($row->H_PARK_DESC1)) {?>
						<dd>
							<div style="line-height: 15px; float:left; font-size: 4px;">●&nbsp;&nbsp;</div>
							<span style="margin-left: 10px;"><?= $row->H_PARK_DESC1 ?></span>
						</dd>
					<?}?>
					<?if (isset($row->H_PARK_DESC2) && !empty($row->H_PARK_DESC2)) {?>
						<dd>
							<div style="line-height: 15px; float:left; font-size: 4px;">●&nbsp;&nbsp;</div>
							<span style="margin-left: 10px;"><?= $row->H_PARK_DESC2 ?></span>
						</dd>
					<?}?>
					<?if (isset($row->H_PARK_DESC3) && !empty($row->H_PARK_DESC3)) {?>
						<dd>
							<div style="line-height: 15px; float:left; font-size: 4px;">●&nbsp;&nbsp;</div>
							<span style="margin-left: 10px;"><?= $row->H_PARK_DESC3 ?></span>
						</dd>
					<?}?>
				</dl>

				<p class="About_map-sort__NiEU+" style="margin-top: 30px;">
					<?if ( isset($row->H_PARK_NAVER) ) {?>
						<button class="About_naver__KYHGH" role="link" onclick="window.open('<?=$row->H_PARK_NAVER?>', '네이버지도')">네이버지도</button>
					<?}?>
					<?if ( isset($row->H_PARK_KAKAO) ) {?>
						<button class="About_kakao__lSMsv" role="link" onclick="window.open('<?=$row->H_PARK_KAKAO?>', '카카오맵')">카카오맵</button>
					<?}?>
					<?if ( isset($row->H_PARK_TMAP) ) {?>
						<button class="About_tmap__5eW0P" role="link" onclick="window.open('<?=$row->H_PARK_TMAP?>', '티맵')">티맵</button>
					<?}?>
				</p>
				<dl style="border-bottom: 8px solid #f6f7f9;"></dl>
			</div>
		<?}?>

		<dl style="border-bottom: 8px solid #f6f7f9;">
			<dt>진료시간</dt>
			<?if ( isset($branchInfo['H_OFFICE_HOUR1_1']) && $branchInfo['H_OFFICE_HOUR1_1'] !== '' ) {?>
				<dd>
					<em><?=isset($branchInfo['H_OFFICE_HOUR1_1']) ? $branchInfo['H_OFFICE_HOUR1_1'] : ''?></em>&nbsp;
					<?=isset($branchInfo['H_OFFICE_HOUR1_2']) ? $branchInfo['H_OFFICE_HOUR1_2'] : ''?>
				</dd>
			<?}?>
			<?if ( isset($branchInfo['H_OFFICE_HOUR2_1']) && $branchInfo['H_OFFICE_HOUR2_1'] !== '' ) {?>
				<dd class="holiday">
					<em><?=isset($branchInfo['H_OFFICE_HOUR2_1']) ? $branchInfo['H_OFFICE_HOUR2_1'] : ''?></em>&nbsp;
					<?=isset($branchInfo['H_OFFICE_HOUR2_2']) ? $branchInfo['H_OFFICE_HOUR2_2'] : ''?>
				</dd>
			<?}?>
			<?if ( isset($branchInfo['H_OFFICE_HOUR3_1']) && $branchInfo['H_OFFICE_HOUR3_1'] !== '' ) {?>
				<dd class="holiday">
					<em><?=isset($branchInfo['H_OFFICE_HOUR3_1']) ? $branchInfo['H_OFFICE_HOUR3_1'] : ''?></em>&nbsp;
					<?=isset($branchInfo['H_OFFICE_HOUR3_2']) ? $branchInfo['H_OFFICE_HOUR3_2'] : ''?>
				</dd>
			<?}?>
			<?if ( isset($branchInfo['H_OFFICE_HOUR4_1']) && $branchInfo['H_OFFICE_HOUR4_1'] !== '' ) {?>
				<dd class="holiday">
					<em><?=isset($branchInfo['H_OFFICE_HOUR4_1']) ? $branchInfo['H_OFFICE_HOUR4_1'] : ''?></em>&nbsp;
					<?=isset($branchInfo['H_OFFICE_HOUR4_2']) ? $branchInfo['H_OFFICE_HOUR4_2'] : ''?>
				</dd>
			<?}?>
		</dl>

	</div>
</div>



<div class="popup-wrap" id="popupWrap" style="display: none;">
	<div class="dim-bg"></div>
	<div class="popup-box" style="max-width: 330px;">
		<div class="pop-container">
			<div class="pop-conts" style="background-color: white;text-align:center;font-size:15px;">
				<p style="padding-top:60px;font-weight:bold;">전문 상담원과</p>
				<p style="padding-bottom: 40px;font-weight:bold;">전화 상담하세요.</p>
				<p style="padding-bottom: 40px;display:flex; align-items:center; gap:5px;margin-left:70px;"><img src="/common/homepage/img/busan/call2.png">051-807-3400</p>
				<div class="btn-box" style="padding:0;border-top: 1px solid gray;">
					<button style="width: 50%;font-size:12px;height:50px;border-right: 1px solid gray;" onclick="copyText('051-807-3400')">전화번호 복사</button>
					<button style="width: 50%;font-size:12px;" onclick="document.getElementById('popupWrap').style.display = 'none';">확인</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function copyText(text) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			// 최신 브라우저 + HTTPS or localhost
			navigator.clipboard.writeText(text)
				.then(() => {
					alert('복사되었습니다.');
				})
				.catch(err => {
					console.error('복사 실패:', err);
				});
		} else {
			// 구형 브라우저 및 HTTP 환경 대응
			const tempInput = document.createElement('textarea');
			tempInput.value = text;
			document.body.appendChild(tempInput);
			tempInput.select();
			document.execCommand('copy');
			document.body.removeChild(tempInput);
			alert('복사되었습니다.');
		}
	}
</script>
