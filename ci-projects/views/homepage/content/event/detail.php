<div class="event-detail">
	<div class="Event_event-top__4Kpl8">
		<div class="Event_event-title__WF+-0"><?= $list['name'] ?><!--<br>~<?php /*= $list['price'] */?>%혜택-->
			<button type="button" onclick="location.href='/event'">목록</button>
		</div>
		<? if ($list['is_always'] == 1) { ?>
			<div class="Event_event-date__W24Pr">상시 진행</div>
		<? } else { ?>
			<div class="Event_event-date__W24Pr"><?= $list['start_date'] ?> ~ <?= $list['end_date'] ?></div>
		<? } ?>
	</div>
	<? if (isset($imagePath) && !empty($imagePath)) { ?>
		<img src="<?= $imagePath ?>" alt="" class="Event_banner__U-v-1">
	<? } ?>

	<? if (isset($list['benefit']) && count($list['benefit']) > 0) { ?>
		<div class="Event_event-detail__coupon-area__CPTRi" >
			<? foreach($list['benefit'] as $key => $value) { ?>
				<div class="coupon-wrap">
					<div class="Event_event-detail__coupon-box__x+2QM">
						<p>
							<!--<em>GOLD 멤버스 쿠폰팩</em>-->
							<strong><?= $value['cp_name'] ?></strong>
							<span><?= $value['cp_dc_memo'] ?><br><?= date('y.m.d') ?> - <?= date('y.m.d', strtotime(date('Y-m-d H:i:s',time()).'+' . $value['cp_end_day'] . ' day')) ?></span>
						</p>
						<button type="button" onclick="sendBenefit(this, '<?= $apiUrl ?>', '<?= $mPublicCi ?>', '<?= $value['cp_no'] ?>')">
							<img src="/common/homepage/img/event/download.svg" alt="" class="download">
						</button>
					</div>
					<? if (isset($value['cp_has']) && !is_null($value['cp_has'])) { ?>
						<div class="eventInfoDim">발급 완료</div>
					<?}?>
				</div>
			<? } ?>
			<dl>
				<dt>쿠폰 유의사항</dt>
				<dd>· 모든 할인 쿠폰은 쁨 회원만 사용 가능합니다.</dd>
				<dd>· 쿠폰에 따라 최대 할인 금액 제한이 있을 수 있습니다.</dd>
			</dl>
		</div>
	<? } ?>

	<div class="SelectSurgery_surgery-item-box__FZCzG productList" style="margin: 0 20px;">
		<? foreach($list['ts_list'] as $key => $value) { ?>
			<div class="SelectSurgery_surgery-item__qcCf+" style="margin: 0 auto; margin-bottom: 12px;">
				<div class="SurgeryItems_surgery-item__CYpPR">
					<input id="prd_<?= $value['fir_cate_name_group'] . '_' . $value['fir_cate_code'] . '_' . $value['ts_code'] ?>" type="checkbox" autocomplete="off"
						   data-fir="<?= $value['fir_cate_code'] ?>"
						   data-sec="<?= $value['sec_cate_code'] ?>"
						   data-code="<?= $value['ts_code'] ?>"
						   data-name="<?= $value['ts_name'] ?>"
						   data-end="<?= $value['tse_end_datetime'] ?>"
						   data-price="<?= $value['event_ts_price'] != $value['ts_price'] ? $value['event_ts_price'] : $value['ts_price'] ?>"
						   onclick="productListCheckEvent(this)"
						<?= isset($value['pay']) && $value['pay'] == '결제' ? 'disabled' : '' ?>
					>
					<label for="prd_<?= $value['fir_cate_name_group'] . '_' . $value['fir_cate_code'] . '_' .$value['ts_code'] ?>" style="cursor: pointer;"><?= $value['ts_name'] ?></label>
					<? if (isset($value['pay']) && $value['pay'] == '결제') { ?>
						<p class="one-time">(1회 체험가 상품은 최초 1회만 이용이 가능해요.)</p>
					<? }?>
				</div>
				<div class="surgery_info_box">
					<div class="SelectSurgery_headline__EPqv0">
						<?= !empty($value['ts_content'])
							? $value['ts_content']
							: strip_tags($value['ts_comment']) ?>
					</div>
					<button class="proAddBtn SelectSurgery_more__YTC3C" onclick="proAddBtnFun(this)">더보기</button>
					<div class="proAddView SelectSurgery_detail__FHOtA">
						<?php if (!empty($value['ts_content'])): ?>
							<dl>
								<dd><?= nl2br($value['ts_content']) ?></dd>
							</dl>
						<?php endif; ?>
						<p class="SelectSurgery_info__haZi4">시술정보</p>
						<dl>
							<dd><?= nl2br($value['ts_comment']) ?></dd>
						</dl>
						<dl>
							<dd>
								<ul>
									<? foreach(explode('#', $value['ts_hash']) as $key => $hash) { ?>
										<? if ($key > 0) { ?>
											<li><?= '#' . $hash ?></li>
										<? } ?>
									<? } ?>
								</ul>
							</dd>
						</dl>
						<?
						$desc = json_decode($value['ts_desc'], true);
						if (!empty($desc)) { ?>
							<dl>
								<? foreach($desc as $info): ?>
									<dt><?= htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8') ?></dt>
									<dd><?= nl2br(htmlspecialchars($info['content'], ENT_QUOTES, 'UTF-8')) ?></dd>
								<? endforeach; ?>
							</dl>
						<? } ?>
						<?
						$cautions = json_decode($value['ts_caution'], true);
						if (!empty($cautions)) { ?>
							<p class="SelectSurgery_info__haZi4">주의사항</p>
							<dl>
								<? foreach($cautions as $info): ?>
									<dd><?= nl2br(htmlspecialchars($info, ENT_QUOTES, 'UTF-8')) ?></dd>
								<? endforeach; ?>
							</dl>
						<? } ?>
					</div>
				</div>
				<div class="SelectSurgery_select-box__0sLK7">
					<? if ($value['fir_cate_code'] == 'event') { ?>
						<span><?= number_format($value['event_ts_price']) ?>원</span>
						<?if ( $value['event_ts_price'] != $value['ts_price'] && $value['event_ts_price'] < $value['ts_price'] ) {?>
							<span class="priceDecoration"><?= number_format($value['ts_price']) ?>원</span>
							<span class="priceOri"><?= number_format(floor(100 - (($value['event_ts_price'] / ($value['ts_price'] > 0 ? $value['ts_price'] : 1)) * 100))) ?>%</span>
						<?}?>
					<? } else { ?>
						<span><?= number_format($value['ts_price']) ?>원</span>
					<? } ?>
				</div>
			</div>
		<? } ?>
	</div>
</div>

<script>
	function sendBenefit(target, apiUrl, mPublicCi, cp_no)
	{
		if (mPublicCi !== '') {
			$.ajax({
				method: 'post',
				url: '/event/benefitCheck',
				data: {
					'coupon_seq': cp_no
				},
				dataType: 'json',
				success:function(result) {
					if (result.has) {
						alertViewShowReservation('이미 참여한 이벤트예요<br>지금 바로 예약하고 혜택을 누려보세요!', 'locationReload');
					} else {
						$.ajax({
							contentType: "application/json;",
							method: 'POST',
							url: apiUrl,
							data: JSON.stringify({
								'public_ci': mPublicCi
								,'coupon_seq': cp_no
							}),
							dataType: 'json',
							success:function(result) {
								if (result.code === 201) {
									alertViewShowReservation('혜택이 발급되었어요<br>지금 바로 예약하고 혜택을 누려보세요!', 'locationReload');
								}
							}
						});
					}
				}
			});
		} else {
			alertViewShowConfirm('쿠폰발급은 회원만 가능해요<br>로그인하시겠어요?', '');
		}
	}

</script>

