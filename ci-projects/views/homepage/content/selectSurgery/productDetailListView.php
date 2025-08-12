<div id="productDetailView" style=" position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(75% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="true" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="productDetailViewHide()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<div class="SurgeryItems_surgery-items__-MM1k">
					<input type="hidden" id="category1" value="<?=isset($category1) ? $category1 : ''?>" />
					<input type="hidden" id="category2" value="<?=isset($category2) ? $category2 : ''?>" />
					<?if ( isset($pData) ) {?>
						<?foreach ( $pData as $index => $row ) {?>
							<div id="<?=$row['CODE']?>" class="SurgeryItems_surgery-item__CYpPR">
								<div class="just_product SurgeryItems_item-name__HkIqD">
									<input
										type="checkbox"
										id="input_<?=$row['CODE']?>"
										data-id="<?=$row['CODE']?>"
										data-og_price="<?=isset($row['TSED_PRICE']) && $row['TSED_PRICE'] > 0 ? $row['TSED_PRICE'] : $row['TS_PRICE']?>"
										data-price="<?=$row['TS_PRICE']?>"
										data-detail_total_price="<?=isset($row['TSED_PRICE']) && $row['TSED_PRICE'] > 0 ? $row['TSED_PRICE'] : $row['TS_PRICE']?>"
										data-category1_uid="<?=isset($category1) ? $category1 : ''?>"
										data-category2_uid="<?=isset($category2) ? $category2 : ''?>"
										data-b_category1="<?=isset($category1) ? $category1 : ''?>"
										data-b_category2="<?=isset($category2) ? $category2 : ''?>"
										data-category2_nm=""
										data-product_nm="<?=isset($row['TS_NM']) ? $row['TS_NM'] : ''?>"
										data-b_remains_pro=""
										data-coupon=""
										data-product='<?=json_encode($row)?>'
										name="item-name"
										class="detailProduct"
										onclick="detailProductPrice()"
										value="<?=isset($row['TSED_PRICE']) && $row['TSED_PRICE'] > 0 ? $row['TSED_PRICE'] : $row['TS_PRICE']?>"
									/>
									<label
										for="input_<?=$row['CODE']?>"
										onclick="detailProductPrice()"
									>
										<?=$row['TS_NM']?>
									</label>
								</div>

								<div class="SurgeryItems_item-benefit__B-eVN">
									<table class="coupontable">
										<tr>
											<td>

												<div class="just_product SurgeryItems_item-price__mTk3p">
													<span data-id="<?=$row['CODE']?>" class="dpPrice SurgeryItems_discount-price__ti3vT">
														<?=isset($row['TSED_PRICE']) && $row['TSED_PRICE'] > 0 ? number_format($row['TSED_PRICE']) : number_format($row['TS_PRICE'])?>
													</span>
													<?if ( $row['TSED_PRICE'] > 0 && ($row['TS_PRICE'] !== $row['TSED_PRICE']) ) {?>
														<span data-id="<?=$row['CODE']?>" class="dpePrice SurgeryItems_price__4wMf8">
															<?=number_format($row['TS_PRICE'])?>
														</span>
														<span data-id="<?=$row['CODE']?>" class="dppPrice SurgeryItems_discount-rate__Q5+2B">
															<?= floor(100 - (($row['TSED_PRICE'] / $row['TS_PRICE']) * 100)).'%'?>
														</span>
													<?}?>
													<!--<p class="SurgeryItems_membership-price__jyjvP">멤버십 할인가 <em>72,000</em></p>-->
												</div>

											</td>
											<td class="rightTd">
												<?if ( isset($row['couponList']) && count($row['couponList']) > 0 ) {?>
													<button class="couponBox" onclick="couponViewOpen('<?=$row['CODE']?>')">혜택적용</button>
												<?}?>
											</td>
										</tr>
										<tr id="tr_<?=$row['CODE']?>" style="display: none;">
											<td style="padding-top: 10px;">
												<div class="just_product SurgeryItems_item-price__mTk3p">
													<p class="couponJText SurgeryItems_membership-price__jyjvP">
														혜택 할인가 <em id="text_<?=$row['CODE']?>">0</em>
													</p>
												</div>
											</td>
											<td class="rightTd">
												<?if ( isset($pCData) && count($pCData) > 0 ) {?>
													<button class="couponCancel" onclick="couponCancelBtn('<?=$row['CODE']?>')">혜택취소</button>
												<?}?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						<?}?>
					<?}?>
				</div>

				<div class="SurgeryItems_total-price-box__H6NKl">
					<div class="SurgeryItems_price-txt-box__sMsyw">
						<span class="SurgeryItems_title__7PEOT"><em>총 시술금액</em> <span class="price-vat">(VAT 별도)</span><br>*결제는 내원 시 진행돼요.</span>
						<span class="subProPrice SurgeryItems_total-price__IV0iQ">0</span>
					</div>
					<div class="SurgeryItems_btn-wrap__VdsxS">
						<button
							class="Button_btn__3u27s"
							type="button"
							style="float: left; width: 32.5%; border: 1px solid var(--color-primary); background: rgb(255, 255, 255); color: var(--color-primary); margin-right: 8px;"
							onclick="addBasket()"
						>
							장바구니
						</button>
						<button class="Button_btn__3u27s" type="button" style="width: calc(67.5% - 8px);" onclick="basketReservationFun()">예약하기</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="productDetailViewHide()"></button>
</div>

<script>
	$(document).ready(function() {
		// $(".react-modal-sheet-container").toggle(100);
	})
</script>
