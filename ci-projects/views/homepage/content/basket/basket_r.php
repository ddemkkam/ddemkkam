<? if ( isset($basketData) && count($basketData['list']) > 0 ) { ?>
	<div class="Cart_cart-empty__7e2tB" style="background-color: #f6f7f9; padding-bottom: 20px;">
		<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none;">
			<div class="SurgeryItems_surgery-item__CYpPR top-area">
				<div class="just_product SurgeryItems_item-name__HkIqD select-all" style="float:left;">
					<input type="checkbox" autocomplete="off" id="basketAllCheck" name="item-name" onclick="basketSelectAll()">
					<label for="basketAllCheck" style="cursor:pointer;">전체상품 (<?= $basketData['total_count'] ?>)</label>
				</div>
				<div class="just_product SurgeryItems_item-name__HkIqD delete-select" style="float: right; color: #878e9c;cursor: pointer" onclick="basketSelectDelete()">선택삭제</div>
			</div>
			<?
				$totalPrice = 0;
				$totalDcPrice = 0;
			?>
			<? foreach ($basketData['list'] as $cate_code => $cate_list) { ?>
				<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; margin-bottom: 30px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; ">
					<div class="SurgeryItems_surgery-item__CYpPR">
						<div style="padding: 14px 16px 24px 16px;">
							<? foreach ($cate_list as $key => $item) { ?>
								<?
									$dcPrice = $item['ts_price'];
									/*foreach ($item['benefit_use_list'] as $benefit) {
										if ($benefit['cp_dc_price_type'] == '정액') {
											if ($item['ts_price'] > $benefit['cp_dc_min_price']) {
												$dcPrice -= $benefit['cp_dc_price'];
											}
										} else if ($benefit['cp_dc_price_type'] == '정률') {
											$check = $dcPrice - ($dcPrice * ((100 - $benefit['cp_dc_per']) * 0.01));
											$dcPrice -= ($check > $benefit['cp_dc_max_price']) ? $benefit['cp_dc_max_price'] : $check;
										}

									}

									$totalPrice += isset($item['pay']) && $item['pay'] == '결제' ? 0 : $item['ts_price'];
									$totalDcPrice +=isset($item['pay']) && $item['pay'] == '결제' ? 0 : $dcPrice;*/
								?>
								<? if ($key == 0) { ?>
									<div style="margin: 10px 0 0;">
										<span style="font-size: 16px; font-weight: 500; font-stretch: normal; font-style: normal; line-height: normal; letter-spacing: -0.28px; text-align: left; color: #111;"><?= $item['sec_cate_name'] ?></span>
									</div>
								<? } ?>
								<div>
									<div class="just_product SurgeryItems_item-name__HkIqD list_name">
										<input type="checkbox" autocomplete="off" id="<?= $item['sec_cate_code'] . '_' . $item['ts_code'] . '_' . $key ?>" class="detailProduct"
											   value="<?= isset($item['event_ts_price']) ? $item['event_ts_price'] : $item['ts_price'] ?>"
											   data-sec_cate_code="<?= $item['sec_cate_code'] ?>"
											   data-ts_code="<?= $item['ts_code'] ?>"
											   data-cti_code="<?= $item['cti_code'] ?>"
											   data-remain_yn="<?= $item['remain_yn'] ?>"
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
											   data-dc_price="<?=$tts_price ?>"
											   onclick="basketSelect()" <?= isset($item['pay']) && $item['pay'] == '결제' ? 'disabled' : ''?>>
										<label for="<?= $item['sec_cate_code'] . '_' . $item['ts_code'] . '_' . $key ?>" style="cursor:pointer; font-size: 14px"><?= $item['ts_name'] ?></label>
										<? if (isset($item['pay']) && $item['pay'] == '결제') { ?>
											<p class="one-time">
												(1회 체험가 상품은 최초 1회만 이용이 가능해요.)
											</p>
										<? }?>
									</div>
									<div class="just_product SurgeryItems_item-name__HkIqD" style="float: right;cursor:pointer; margin: 18px 10px 10px 0;">
										<img src="/common/homepage/img/common/ico_close.svg" style="width: 20px; height: 20px;" onclick="basketDelete('<?= $item['sec_cate_code'] ?>', '<?= $item['ts_code'] ?>', '<?= $item['cti_code'] ?>')">
									</div>
								</div>
								<div style="clear: both; padding: 0px 10px 0px 4px;">
									<div class="SurgeryItems_item-benefit__B-eVN">
										<table class="coupontable">
											<tr>
												<td>
													<div class="just_product SurgeryItems_item-price__mTk3p">
														<?if ( $item['fir_cate_code'] == 'event' ){?>
															<span class="discount-price"><?= number_format($item['event_ts_price']) ?>원</span>
															<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
																<span class="priceDecoration"><?= number_format($item['ts_price']) ?>원</span>
																<span class="priceOri"><?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%</span>
															<?}?>
														<?} else {?>
															<?if ( $item['ts_type'] == '멤버쉽' ) {?>
																<span class="discount-price"><?= number_format($item['event_ts_price']) ?>원</span>
															<?} else {?>
																<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] > $item['ts_price'] ) {?>
																	<span class="discount-price"><?= number_format($item['ts_price']) ?>원</span>
																	<span class="priceDecoration"><?= number_format($item['event_ts_price']) ?>원</span>
																	<span class="priceOri"><?= number_format(floor(100 - (($item['ts_price'] / ($item['event_ts_price'] > 0 ? $item['event_ts_price'] : 1)) * 100))) ?>%</span>
																<?} else {?>
																	<span class="discount-price"><?= number_format($item['ts_price']) ?>원</span>
																<?}?>
															<?}?>
														<?}?>
													</div>
												</td>
												<td class="rightTd">
													<? if (false && $item['remain_yn'] == 'N') { ?>
														<? if (count($item['benefit_use_list']) > 0) { ?>
															<button class="couponBox" onclick="benefitView('<?= $item['ts_code'] ?>', '<?= $item['sec_cate_code'] ?>')">혜택변경</button>
														<? } else if (count($item['benefit_list']) > 0) { ?>
															<button class="couponBox" onclick="benefitView('<?= $item['ts_code'] ?>', '<?= $item['sec_cate_code'] ?>')">혜택적용</button>
														<? } ?>
													<? } ?>
												</td>
											</tr>
											<? if ($item['remain_yn'] == 'N' && count($item['benefit_use_list']) > 0) { ?>
												<tr>
													<td style="padding-top: 4px;">
														<div class="just_product SurgeryItems_item-price__mTk3p">
															<p class="couponJText SurgeryItems_membership-price__jyjvP">혜택 할인가 <em class="dc_price" data-id="<?php /*= $value['benefit']['CPC_SQ'] */?>" data-cate="<?= $item['sec_cate_code'] ?>"><?= number_format($dcPrice) ?> 원</em></p>
														</div>
													</td>
													<td class="rightTd">
														<button class="couponCancel" onclick="benefitDelete('<?= $item['sec_cate_code'] ?>', '<?= $item['ts_code'] ?>')">적용취소</button>
													</td>
												</tr>
											<? } ?>
										</table>
									</div>
								</div>
							<? } ?>
						</div>
						<div style="padding: 0 20px 16px; border-top: 1px solid #f0f2f5">
						<? if ($item['remain_yn'] == 'N') { ?>
							<button class="optionChange" onclick="basketProductChange('<?= $item['sec_cate_code'] ?>')">상품 변경</button>
						<? } ?>
						</div>
					</div>
				</div>
			<? } ?>

			<!--<table style="width: 100%;">
				<tr>
					<td colspan="2" class="basketThPrice" style="">결제 예상금액</td>
				</tr>
				<tr style="border-bottom: 1px solid #e7e8eb;">
					<td class="basketTotalPrice">총 시술금액</td>
					<td class="basketTotalPrice2">
						<span class="og_subProPrice"><?php /*= number_format($totalPrice) */?></span>
						<span> 원</span>
					</td>
				</tr>
			</table>-->

			<!--<table style="width: 93%; margin: auto; margin-top: 16px;">
				<tr style="">
					<td class="basketGouponLeft">혜택 사용</td>
					<td class="basketGouponRight">
						<span class="discountPrice"><?php /*= number_format($totalPrice - $totalDcPrice) */?></span>
						<span> 원</span>
					</td>
				</tr>
			</table>-->

			<table style="width: 100%;">
				<tr style="border-top: 1px solid #e7e8eb;">
					<td class="basketPayTotalVat">
						<span>총 결제 예상금액 </span>
						<span class="price-vat">(VAT 별도)</span>
					</td>
					<td class="basketPayTotalVatPrice">
						<span class="subProPrice"><?= number_format($totalDcPrice) ?></span>
						<span> 원</span>
					</td>
				</tr>
				<tr>
					<td class="basketAnotherText">* 결제는 내원 시 진행돼요.</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="Surgery_mySurgery__I+6XI" style="padding:20px;">
		<div class="Surgery_btn-area__xus93">
			<button type="button" class="Surgery_btn-reserve__i6hVf" onclick="basketCheckSend()" style="width:100%;margin:0;">
				<span id="basketCount" class="Surgery_selectQty__JTwJO" style="display:none;"></span>
				예약하기
			</button>
		</div>
	</div>
<? } else { ?>
	<div class="Cart_cart-empty__7e2tB" style="padding: 160px 20px 100px">
		<div class="MyNone_my-none__9dt6+">
			<img src="/common/homepage/img/basket/ico_none.svg" alt="">
			<p class="MyNone_my-none__text__24uCV">장바구니에 상품이 없습니다</p>
			<a href="selectSurgery">
				<button class="MyNone_btn-white__kyEV8">예약하기</button>
			</a>
		</div>
	</div>
<? } ?>

