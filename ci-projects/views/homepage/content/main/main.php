<? if (isset($mainImageList)) { ?>
	<div id="mainImg" class="main-img-wrap">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<? foreach ($mainImageList as $item) { ?>
					<div class="swiper-slide">
						<?if ( isset($item['link']) && $item['link'] != '' ) {?>
						<a href="<?=$item['link']?>" target="<?= ($item['link_target'] == 'blank') ? '_blank' : '_self' ?>">
							<?}?>
							<img src="<?= $item['image_path'] ?>" style="width: 100%; max-width: var(--max-width);">
							<?if ( isset($item['link']) && $item['link'] != '' ) {?>
						</a>
					<?}?>
					</div>
				<? } ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
<? } ?>
<div class="SelectSurgery_select-surgery__huyL8" style="padding: 0;">
	<div id="rankDiv" class="SelectSurgery_category-wrap__+GKZq" style="position: fixed; bottom: 67px;">
		<!-- <div style="margin-bottom: 10px;">
			<table style="width: 100%;">
				<tr>
					<td class="mainRankTitle">실시간 인기 검색 순위 🏆</td>
					<td class="mainRankMore" onclick="allRankShowFun()">더보기</td>
				</tr>
			</table>
		</div> -->
		<div class="mainRankTitle">실시간 검색</div>
		<? if (isset($rankList)) { ?>
			<div id="swipercate3" style="width: 100%; height: 50px;">
				<div class="swiper swiper-initialized swiper-vertical swiper-ios">
					<div class="swiper-wrapper">
						<?$ii = 0;?>
						<? foreach ($rankList as $key => $value) {
							if ( isset($value) ){?>
								<div class="swiper-slide" style="cursor: pointer; margin-bottom: 10px;" onclick="location.href='/search?text=<?= base64_encode(urlencode($value['ts_name'])) ?>'" >
									<div class="mainRankDiv" >
										<table>
											<tr>
												<td>
													<div class="mainRankDivSetNumber">
														<?
														echo $ii + 1;
														$ii++;
														?>
													</div>
												</td>
												<td class="mainRankDivName">
													<div class="mainRankDivSetText1">
														<?/* if ( $value['event_ts_price'] != $value['ts_price'] && isset($value['sec_cate_name']) && $value['sec_cate_name'] != '' ) { ?>
															[<?=$value['sec_cate_name']?>]
														<?}*/?>
														<?= $value['ts_name'] ?>
													</div>
													<!-- <div class="mainRankDivSetText1">
														<span class="mainRankDivSetEventPrice">
															<?= number_format($value['event_ts_price']) ?>
														</span>
														<? if ( $value['event_ts_price'] != $value['ts_price']) { ?>
															<span class="mainRankDivSetTsPrice"><?= number_format($value['ts_price']) ?>원</span>
															<span class="mainRankDivSetTsPer"><?= number_format(floor(100 - (($value['event_ts_price'] / ($value['ts_price'] > 0 ? $value['ts_price'] : 1)) * 100))) ?>%</span>
														<? } ?>
													</div> -->
												</td>
											</tr>
										</table>

									</div>
								</div>
							<? } ?>
						<? } ?>
					</div>
				</div>
			</div>
		<? } ?>
		<div class="mainRankMore" onclick="allRankShowFun()">전체랭킹</div>
	</div>
</div>

<? if(isset($popupImageList) && count($popupImageList) > 0) { ?>
	<div class="popup-wrap" id="popupWrap" style="display: none">
		<div class="dim-bg"></div>
		<div class="popup-box">
			<div class="pop-container">
				<div class="pop-conts">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<? foreach($popupImageList as $item) { ?>
								<div class="swiper-slide">
									<div class="img-box">
										<img src="<?= $item['image_path'] ?>" alt="<?= $item['title'] ?>">
									</div>
								</div>
							<? } ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
					<div class="btn-box">
						<button class="btn-today-close"><span>오늘 하루 보지 않기</span></button>
						<button class="btn-close">닫기</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<? } ?>

<script>
	//팝업 하루동안 보이지 않게 처리
	$(".btn-today-close").on('click', function() {
		let date = new Date();
		date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
		document.cookie = 'mainPopup=Y;path=/;expires=' + date.toUTCString();
		closePopup();
	});

	function closePopup() {
		document.getElementById('popupWrap').style.display = 'none';
	}
	document.querySelector('.btn-close').addEventListener('click', function() {
		closePopup();
	});

	$(function () {
		//팝업 노출 여부 확인
		if (getCookieByName('mainPopup') !== 'Y') {
			$('#popupWrap').show();
		}

		if ($('#popupWrap .swiper-slide').length > 1) {
			var mainPop = new Swiper('#popupWrap .swiper-container', {
				loop: true,
				// spaceBetween: 20,
				pagination: {
					el: "#popupWrap .swiper-pagination",
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
	})
</script>

<script>
	// 메인이미지 슬라이드
	$(function () {
		if ($('#mainImg .swiper-slide').length > 1) {
			var mainPop = new Swiper('#mainImg .swiper-container', {
				loop: true,
				pagination: {
					el: "#mainImg .swiper-pagination",
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

</script>

<script>
	// var width = $('#main').width();
	// $('#rankDiv').css('max-width', (width-0) + 'px');

	// $(document).scroll(function(event) {
	//     const rankDivHeight = $('#rankDiv').height();
	//     const rankDivTop = $('#rankDiv').offset().top;
	//     const footerTop = $('footer').offset().top;

	//     if ( (rankDivTop+rankDivHeight + 20) > footerTop ) {
	//         const rankDivBottom = $('#rankDiv').css('bottom').replace('px', '');
	//         const clacRankDivBottom = (rankDivTop+rankDivHeight) - footerTop;
	//         const resultRankDivBottom = Number(rankDivBottom) + Number(clacRankDivBottom) + 20;

	//         $('#rankDiv').css('bottom', resultRankDivBottom + 'px');
	//     } else {
	//         const footerfixHeight = $('#footerfixul').height();
	//         // console.log(footerfixHeight);
	//         const resultRankDivBottom = Number(footerfixHeight) + 20;
	//         $('#rankDiv').css('bottom', resultRankDivBottom + 'px');
	//     }
	// });

	$(document).scroll(function() {
		const footerTop = $('footer').offset().top;
		const windowScrollTop = $(window).scrollTop();
		const windowHeight = $(window).height();
		const rankDivHeight = $('#rankDiv').height();

		const rankDivBottomPosition = windowScrollTop + windowHeight - rankDivHeight - 100;

		if (rankDivBottomPosition >= footerTop) {
			$('#rankDiv').css({
				position: 'absolute',
				top: footerTop - rankDivHeight - 31 + 'px',
				bottom: 'auto'
			});
		} else {
			$('#rankDiv').css({
				position: 'fixed',
				bottom: '67px',
				top: 'auto',
			});
		}
	});
</script>
