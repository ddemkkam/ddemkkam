<div>
	<div class="mypageDiv">
		<div class="mypageDivHeader1">
			<div class="nameText"><?= $name ?>님 안녕하세요!</div>
			<div class="mypage-logout">
				<a href="/login/logout">로그아웃</a>
			</div>
		</div>
		<div class="mypageDivHeader1">연락처 : <?= $phone ?></div>

		<div class="mypageDivHeader2">
			<div class="mypageDivMi">
				<div class="mypageDivMiD">
					<div class="mypageDivMi1">즉시 사용 가능한 마일리지</div>
					<div class="mypageDivMiPrice price-box">
						<span><?= isset($data['mileage']) ? number_format($data['mileage']) : 0 ?></span> 원
					</div>
				</div>
			</div>
		</div>
	</div>

	<nav class="MyMenu_nav__+BiOe">
		<ul class="MyMenu_tabs__VBQ8v">
			<li class="MyMenu_ac coupon MyMenu_active__AG2o+" onclick="mapagePageMove('coupon')" style="cursor: pointer;">
				<em><?= isset($data['benefitCount']) ? $data['benefitCount'] : 0 ?></em>혜택
			</li>
			<li class="MyMenu_ac res" onclick="mapagePageMove('res', this)" style="cursor: pointer;">
				<em><?= isset($data['reservationCount']) ? $data['reservationCount'] : 0 ?></em>예약정보
			</li>
			<li class="MyMenu_ac remains" onclick="mapagePageMove('remains', this)" style="cursor: pointer;">
				<em><?= isset($data['remainCount']) ? $data['remainCount'] : 0 ?></em>잔여시술
			</li>
		</ul>
	</nav>

	<div id="eventBanner" class="event-banner-wrap" style="display: none;">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<? if (isset($pc)) { ?>
					<? foreach ($pc as $item) { ?>
						<div class="swiper-slide">
							<a href="<?= $item['LINK'] ?>" target="<?= ($item['LINK_TARGET'] == 'blank') ? '_blank' : '_self' ?>">
								<img src="<?= $item['IMG_SRC'] ?>" alt="my_page_image" style="width: 100%; max-width: var(--max-width);">
							</a>
						</div>
					<? } ?>
				<? } ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>

	<div id="eventBannerMobile" class="event-banner-wrap" style="display: none;">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<? if (isset($mobile)) { ?>
					<? foreach ($mobile as $item) { ?>
						<div class="swiper-slide">
							<a href="<?= $item['LINK'] ?>" target="<?= ($item['LINK_TARGET'] == 'blank') ? '_blank' : '_self' ?>">
								<img src="<?= $item['IMG_SRC'] ?>" alt="my_page_image" style="width: 100%; max-width: var(--max-width);">
							</a>
						</div>
					<? } ?>
				<? } ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>

	<div id="changeMypage">

	</div>
</div>

<script>
	$(document).ready(function() {
		const urlParams = new URLSearchParams(location.search);

		if (urlParams.get('type') === '3') {
			mapagePageMove('remains');
		} else if (urlParams.get('type') === '2') {
			mapagePageMove('res');
		} else {
			mapagePageMove('coupon');
		}
	})
</script>

<script>
	// 마이페이지 이벤트 배너 슬라이드
	$(function () {
		if ($('#eventBanner .swiper-slide').length > 1) {
			var eventBanner = new Swiper('#eventBanner .swiper-container', {
				loop: true,
				pagination: {
					el: "#eventBanner .swiper-pagination",
					type: "fraction",
					renderFraction: function (currentClass, totalClass) {
						return '<span class="' + currentClass + '"></span>' + ' | ' + '<span class="' + totalClass + '"></span>';
					},
				},
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
				speed: 600,
			});
		}

		if ($('#eventBannerMobile .swiper-slide').length > 1) {
			var eventBanner2 = new Swiper('#eventBannerMobile .swiper-container', {
				loop: true,
				pagination: {
					el: "#eventBannerMobile .swiper-pagination",
					type: "fraction",
					renderFraction: function (currentClass, totalClass) {
						return '<span class="' + currentClass + '"></span>' + ' | ' + '<span class="' + totalClass + '"></span>';
					},
				},
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
				speed: 600,
			});
		}
	});

	function updateBannerImage() {

		if (window.innerWidth <= 569) {
			$("#eventBannerMobile").show();
			$("#eventBanner").hide();
		} else {
			$("#eventBanner").show();
			$("#eventBannerMobile").hide();
		}
	}

	// 페이지 로드 시와 리사이즈 시 이미지 변경 함수 실행
	window.addEventListener('load', updateBannerImage);
	window.addEventListener('resize', updateBannerImage);
	updateBannerImage();


	// 마이페이지 이벤트 배너 PC & Mobile 이미지 조절
	/*function updateBannerImage() {
		const slides = document.querySelectorAll('.swiper-slide img');
		const smallImage = "/common/homepage/img/event/event_banner_m.jpg";
		const largeImage = "/common/homepage/img/event/event_banner_pc.jpg";

		if (window.innerWidth <= 569) {
			slides.forEach(img => img.src = smallImage);
		} else {
			slides.forEach(img => img.src = largeImage);
		}
	}

	// 페이지 로드 시와 리사이즈 시 이미지 변경 함수 실행
	window.addEventListener('load', updateBannerImage);
	window.addEventListener('resize', updateBannerImage);function updateBannerImage() {
		const slides = document.querySelectorAll('.swiper-slide img');
		const smallImage = "/common/homepage/img/event/event_banner_m.jpg";
		const largeImage = "/common/homepage/img/event/event_banner_pc.jpg";

		if (window.innerWidth <= 569) {
			slides.forEach(img => img.src = smallImage);
		} else {
			slides.forEach(img => img.src = largeImage);
		}
	}

	window.addEventListener('load', updateBannerImage);
	window.addEventListener('resize', updateBannerImage);*/
</script>
