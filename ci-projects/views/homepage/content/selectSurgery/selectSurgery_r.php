
<div class="SelectSurgery_select-surgery__huyL8">
	<? if ($this->session->userdata('M_PUBLIC_CI') ) { ?>
		<div class="SelectSurgery_benefit-area__d6gSo">사용 가능 혜택 (<?= number_format($hasBenefitCount) ?>)<button type="button" onclick="location.href='/mypage'">보기</button></div>
	<? } ?>
	<h3>
		<p class="top-area">
			<? if (!$this->session->userdata('M_PUBLIC_CI') ) { ?>
				<span style="letter-spacing: -0.28px;">
					지금 로그인하고<br>다양한 <b>이벤트 혜택</b>을 확인하세요
				</span>
				<a href="<?= getenv('LOGIN_URL') ?>" >
					<button class="SelectSurgery_log-btn__J1GuY">회원가입/로그인</button>
				</a>
			<? } else { ?>
				<span class="name-box">
					<?=$this->session->userdata('M_NAME')?>님 반가워요!
				</span>
				<span style="letter-spacing: -0.28px;">
					원하시는 상품을 선택해 주세요
				</span>
				<a href="reservation">
					<button class="SelectSurgery_log-btn__J1GuY btn-reserve" onclick="reservation()">바로예약</button>
				</a>
			<? } ?>
		</p>
	</h3>
	<div class="SelectSurgery_category-wrap__+GKZq" style="height: 80px;">
		<div id="swipercate1">
			<div class="swiper swiper-initialized swiper-horizontal swiper-ios SelectSurgery_depth1__olYz9">
				<div class="swiper-wrapper">
					<? if (isset($list)) { ?>
						<?
						$count = 0;
						foreach ($list as $key => $value) { ?>
							<div class="m_i m_i_<?= str_replace(" ", "", preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key)) ?> swiper-slide SelectSurgery_item__hyhX2 <?=$count == 0 ? 'SelectSurgery_active__Yw0Oo' : '' ?> productSlide" onclick="changeList('<?= str_replace(" ", "", preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key)) ?>', '<?= str_replace(" ", "", preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", key($value))) ?>')" style="cursor: pointer;">
								<?= $key ?>
							</div>
						<? $count++; } ?>
					<? } ?>
				</div>
			</div>
			<button onclick="totalCategoryViewShowFun()">전체보기</button>
		</div>
		<? if (isset($list)) { ?>
			<?
			$count = 0;
			foreach ($list as $key => $value) { ?>
				<div id="sec_<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key)); ?>" class="SelectSurgery_depth2-wrap__ItSPM sec_cate" <?= $count > 0 ? 'style="display:none;"': '' ?>>
					<div id="swipercate2">
						<div class="swiper swiper-initialized swiper-horizontal swiper-ios SelectSurgery_depth2__knR6f">
							<div class="swiper-wrapper">
								<?
								$subCount = 0;
								foreach ( $value as $index => $row ) { ?>
									<div id="sec_<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key)); ?>_<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $index)); ?>" class="v_i swiper-slide SelectSurgery_item__hyhX2 sec_cate_detail <?= $subCount > 0 ? '' : 'SelectSurgery_active__Yw0Oo' ?>" style="cursor: pointer;" onclick="secChangeList('<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key)); ?>','<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $index)); ?>')"> <?= $index ?></div>
								<? $subCount++; } ?>
							</div>
						</div>
					</div>
				</div>
			<? $count++; } ?>
		<? } ?>
	</div>

	<? if (isset($list)) { ?>
		<?
		$count = 0;
		foreach($list as $key => $value) { ?>
			<? foreach($value as $key2 => $value2) { ?>
				<div id="prd_<?= empty($value2[0]['fir_cate_name_group']) ? '이벤트' : str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $value2[0]['fir_cate_name_group'])); ?>_<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key2)); ?>" class="productList" <?= $count > 0 ? 'style="display:none;"' : '' ?>>
					<div class="SelectSurgery_surgery-item-box__FZCzG">
						<? foreach($value2 as $item) { ?>
							<div class="SelectSurgery_surgery-item__qcCf+">
								<div class="SurgeryItems_surgery-item__CYpPR">
									<input id="prd_<?= preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $item['fir_cate_name_group']) . '_' . $item['fir_cate_code'] . '_' . preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $item['sec_cate_name_group']) . '_' . $item['ts_code'] ?>" type="checkbox" autocomplete="off"
										   data-fir="<?= $item['fir_cate_code'] ?>"
										   data-sec="<?= $item['sec_cate_code'] ?>"
										   data-code="<?= $item['ts_code'] ?>"
										   data-name="<?= $item['ts_name'] ?>"
										   data-end="<?= $item['tse_end_datetime'] ?>"
										   <?
											if ( $item['fir_cate_code'] == 'event' ) {
												$tts_price = $item['event_ts_price'];
											} else {
												if ( $item['ts_type'] == '멤버쉽' ) {
													$tts_price = $item['event_ts_price'];
												} else {
													if ( $item['event_ts_price'] != $item['ts_price'] ) {
														$tts_price = $item['ts_price'];
													} else {
														$tts_price = $item['ts_price'];
													}
												}
											}
										   ?>
										   data-price="<?=$tts_price?>"
										<?= isset($item['pay']) && $item['pay'] == '결제' ? 'disabled' : '' ?>
									>
									<label for="prd_<?= preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $item['fir_cate_name_group']) . '_' . $item['fir_cate_code'] . '_' . preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $item['sec_cate_name_group']) . '_' .$item['ts_code'] ?>" style="cursor: pointer;">
										<?php if ($item['fir_cate_code'] == 'event') { ?>
											[<?= $item['sec_cate_name'] ?>] <?= $item['ts_name'] ?>
										<?php } else { ?>
											<?= $item['ts_name'] ?>
										<?php } ?>
									</label>
									<? if (isset($item['pay']) && $item['pay'] == '결제') { ?>
										<p class="one-time">(1회 체험가 상품은 최초 1회만 이용이 가능해요.)</p>
									<? }?>
								</div>
								<div class="surgery_info_box">
									<div class="SelectSurgery_headline__EPqv0">
										<?= !empty($item['ts_content'])
											? $item['ts_content']
											: strip_tags($item['ts_comment']) ?>
									</div>
									<?if ( $item['ts_type'] != '멤버쉽' ) {?>
										<button class="proAddBtn SelectSurgery_more__YTC3C" onclick="proAddBtnFun(this)">더보기</button>
										<div class="proAddView SelectSurgery_detail__FHOtA">
											<?php if (!empty($item['ts_content'])): ?>
												<dl>
													<dd><?= nl2br($item['ts_content']) ?></dd>
												</dl>
											<?php endif; ?>
											<p class="SelectSurgery_info__haZi4">시술정보</p>
											<dl>
												<dd><?= nl2br($item['ts_comment']) ?></dd>
											</dl>
											<dl>
												<dd>
													<ul>
														<? foreach(explode('#', $item['ts_hash']) as $key => $hash) { ?>
															<? if ($key > 0) { ?>
																<li><?= '#' . $hash ?></li>
															<? } ?>
														<? } ?>
													</ul>
												</dd>
											</dl>
											<?
											$desc = json_decode($item['ts_desc'], true);
											if (!empty($desc)) { ?>
												<dl>
													<? foreach($desc as $info): ?>
														<dt><?= htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8') ?></dt>
														<dd><?= nl2br(htmlspecialchars($info['content'], ENT_QUOTES, 'UTF-8')) ?></dd>
													<? endforeach; ?>
												</dl>
											<? } ?>
											<?
											$cautions = json_decode($item['ts_caution'], true);
											if (!empty($cautions)) { ?>
												<p class="SelectSurgery_info__haZi4">주의사항</p>
												<dl>
													<? foreach($cautions as $info): ?>
														<dd><?= nl2br(htmlspecialchars($info, ENT_QUOTES, 'UTF-8')) ?></dd>
													<? endforeach; ?>
												</dl>
											<? } ?>
										</div>
									<?}?>
								</div>
								<div class="SelectSurgery_select-box__0sLK7">
									<?//echo '<pre>'; print_r($item); echo '</pre>';?>
									<?if ( $item['fir_cate_code'] == 'event' ){?>
										<span><?= number_format($item['event_ts_price']) ?>원</span>
										<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
											<span class="priceDecoration"><?= number_format($item['ts_price']) ?>원</span>
											<span class="priceOri"><?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%</span>
										<?}?>
									<?} else {?>
										<?if ( $item['ts_type'] == '멤버쉽' ) {?>
											<span><?= number_format($item['event_ts_price']) ?>원</span>
										<?} else {?>
											<?if ( $item['event_ts_price'] != $item['ts_price'] ) {?>
												<span><?= number_format($item['ts_price']) ?>원</span>
												<span class="priceDecoration"><?= number_format($item['event_ts_price']) ?>원</span>
												<span class="priceOri"><?= number_format(floor(100 - (($item['ts_price'] / ($item['event_ts_price'] > 0 ? $item['event_ts_price'] : 1)) * 100))) ?>%</span>
											<?} else {?>
												<span><?= number_format($item['ts_price']) ?>원</span>
											<?}?>
										<?}?>
									<?}?>
								</div>
							</div>
						<? } ?>
					</div>
				</div>
			<? $count++; } ?>
		<? } ?>
	<? } ?>
</div>


<? if (!$this->session->userdata('M_PUBLIC_CI')) { ?>
	<div id="surgeryBenefitView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: block;">
		<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
			<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
				<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
					<span class="react-modal-sheet-drag-indicator" onclick="surgeryBenefitViewHideFun()"></span>
				</div>
			</div>
			<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
				<div style="height: auto; padding: 20px;">
					<div class="Categorys_categorys__5pPjP">
						<ul>
							<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: center; color: #111;">
								<h4 style="font-size: 18px; line-height: 30px;">
									지금 로그인하지 않으면 손해!
									<br />
									<?php
									if (isset($branchInfo)) {
										if ($branchInfo['BRANCH'] === 'ppeum920') {
											echo '쁨글로벌의원 회원에게 드리는 이 달의 스페셜 혜택';
										} else {
											echo '예쁨주의쁨의원 회원에게 드리는 이 달의 스페셜 혜택';
										}
									} else {
										echo '쁨글로벌의원 회원에게 드리는 이 달의 스페셜 혜택';
									}
									?>
								</h4>
							</li>
							<li>
								<a href="<?= getenv('LOGIN_URL') ?>">
									<button style="width: 100%; height: 52px; margin-top: 24px; text-align: center; border-radius: 8px; background-color: #cf2f75;
										font-size: 16px; font-weight: 600; color: #fff;">
										<table style="margin: auto;">
											<tr>
												<td style="vertical-align: middle;">로그인하고 모든 혜택 받기</td>
											</tr>
										</table>
									</button>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="surgeryBenefitViewHideFun()"></button>
	</div>
<? } ?>

<script>
	function changeList(key, key2)
	{
		$("#ra_" + key).prop('checked', true);
		$(".productSlide").removeClass('SelectSurgery_active__Yw0Oo');
		$(".m_i_" + key).addClass('SelectSurgery_active__Yw0Oo');
		$(".sec_cate").hide();
		$("#sec_" + key).show();
		secChangeList(key, key2);
	}

	function secChangeList(key, key2)
	{
		$(".sec_cate_detail").removeClass('SelectSurgery_active__Yw0Oo');
		$(`#sec_${key}_${key2}`).addClass('SelectSurgery_active__Yw0Oo');
		$(".productList").hide();
		$(`#prd_${key}_${key2}`).show();

		$(".productList input[type='checkbox']:checked").each(function(k,v) {
			v.checked = false;
			$("#totalPrice").number(0);
			$("#productDetailView").hide();
		});
	}

	$(".SurgeryItems_surgery-item__CYpPR>input[type ='checkbox']").on('click',function () {
		if ($(this)[0].checked) {
			$("#totalPrice").number(Number($("#totalPrice").text().replaceAll(",", "")) + Number($(this)[0].dataset.price));
		} else {
			$("#totalPrice").number(Number($("#totalPrice").text().replaceAll(",", "")) - Number($(this)[0].dataset.price));
		}

		if ($(".productList input[type='checkbox']:checked").length > 0) {
			$("#productDetailView").show();
		} else {
			$("#productDetailView").hide();
		}
	});

</script>



