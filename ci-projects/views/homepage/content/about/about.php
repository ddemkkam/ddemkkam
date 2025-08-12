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
<div class="About_about__Nw80V">
	<?/*
	<div id="visual" class="About_visual__ayjew">
		<img src="/common/homepage/img/about/about_visual.a9694f4ab62ac3f1a0f6.jpg" alt="mooaa">
		<div class="About_txt-box__dfb5i">
			<strong>Finding<br>My Endless<br>Beauty</strong>
			<span>
				무아는 각자 자기 안에 품고 있는 <br>
				다채로운 아름다움을 끊임없이 찾아가고 <br>
				개발하는 여정을 함께 합니다.<br>
				나도 몰랐던 내 안의 아름다움을 찾고 만나게 하는 것,<br>
				이것이 무아가 존재하는 이유이자 역할입니다.
			</span>
		</div>
	</div>
	*/?>
	<ul class="About_tabs__2uksL" style="margin-top: 55px; position: fixed; top: 0; z-index: 999;">
		<li><a class="aboutMenu section3 About_active__FX0hg" onclick="aboutMoveView('section3', this)">오시는 길</a></li>
		<li><a class="aboutMenu section1" onclick="aboutMoveView('section1', this)">둘러보기</a></li>
		<li><a class="aboutMenu section2" onclick="aboutMoveView('section2', this)">의료진 소개</a></li>
	</ul>

	<div id="section3" class="About_section__iti2X About_section3__VJ9zN" style="margin-top: 110px;">
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

	<?if ( isset($hospitalList) ) {?>
		<div id="section1" class="About_section__iti2X section1">
			<h4>둘러보기</h4>
		</div>

		<div>

			<div id="section1" class="About_section__iti2X section1 preview" style="padding: 0px 20px 0px 20px;">
				<div thumbsSlider="" class="swiper mySwiper" style="padding: 0 0 10px 0; height: 62px;">
					<div class="swiper-wrapper" >
						<?foreach ( $hospitalList as $index => $row ){?>
							<div class="swiper-slide">
								<button class="" onclick="">
									<?=$row['HI_TITLE']?>
								</button>
							</div>
						<?}?>
					</div>
				</div>
			</div>

			<div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2 preview-button">
				<div class="swiper-wrapper">
					<?foreach ( $hospitalList as $index => $row ){?>
						<div class="swiper-slide">
							<img class="" src="<?=$row['HI_IMG_PATH']?>" style="" />
						</div>
					<?}?>
				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>

		</div>
	<?}?>

	<dl style="margin-top: 40px; border-bottom: 8px solid #f6f7f9;"></dl>

	<?// echo "<pre>"; print_r($doctorData); echo "</pre>"; ?>
	<?if ( isset($doctorData) ) {?>
		<div id="section2" class="About_section__iti2X section1">
			<h4>
				의료진 소개
				<a href="/doctor" >
					<button class="About_btn-doc__Ig4ym">전체보기 <img src="/common/homepage/img/common/ico_arrow_right_line.svg" style="position:relative;top:2px;"></button>
				</a>
			</h4>
			<h4 style="color: #cf2f75; font-size: 14px; padding-left: 8px;">
				<?=$doctorData->BRANCH_NAME?>
			</h4>
			<div class="About_doc-intro__sY5rP">
				<img src="<?=$doctorData->D_IMG_PATH?>" alt="">
				<div class="About_doc-txt__i9UaE">
					<p class="About_name__vKTCq">
						<?=$doctorData->D_NAME?>
						<br />
						<b><?=$doctorData->D_MAIN_YN === 'Y' ? '대표원장' : '' ?></b>
						<em><?=$doctorData->D_NAME_EN?></em>
					</p>
					<ul>
						<li><?=nl2br($doctorData->D_DESC)?></li>
					</ul>
				</div>
			</div>
		</div>
	<?}?>
</div>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
     $(document).ready(function() {
         <?if ( isset($idx) ) {?>
             aboutMoveView('section<?=$idx?>', null);
         <?}?>
     });

	function aboutMoveView(sectionId, element) {
			const offset = 100;
			const sectionTop = $("#" + sectionId).offset().top - offset;

			$('html, body').animate({
				scrollTop: sectionTop
			}, 500);
		}

	var swiper = new Swiper(".mySwiper", {
		loop: true,
		spaceBetween: 10,
		slidesPerView: 4,
		freeMode: true,
		watchSlidesProgress: true,
	});
	var swiper2 = new Swiper(".mySwiper2", {
		loop: true,
		spaceBetween: 10,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		thumbs: {
			swiper: swiper,
		},
	});

	$(window).scroll(function() {
		// 스크롤 이동 시 실행되는 코드
		// console.log($(document).scrollTop());
		/*
		 * section3 : 오시는길
		 * section1 : 둘러보기
		 * section2 : 의료진소개
		 */
		const sTop = $(document).scrollTop();
        const section1 = $("#section1").offset().top - 400;
        const section2 = $("#section2").offset().top - 400;
        const section3 = $("#section3").offset().top - 100;
		if ( sTop >= 0 && sTop < section1 ) {
			$('.aboutMenu').removeClass('About_active__FX0hg');
			$('.section3').addClass('About_active__FX0hg');
		} else if ( sTop >= section1 && sTop < section2 )  {
			 $('.aboutMenu').removeClass('About_active__FX0hg');
			$('.section1').addClass('About_active__FX0hg');
		} else if ( sTop >= section2 )  {
			 $('.aboutMenu').removeClass('About_active__FX0hg');
			$('.section2').addClass('About_active__FX0hg');
		}
	});
</script>


















