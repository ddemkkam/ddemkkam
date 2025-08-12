<?if ( isset($basketData) && count($basketData) > 0 ) {?>
	<div class="Cart_cart-empty__7e2tB" style="background-color: #f6f7f9; padding-bottom: 20px;">
		<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none;">

			<div class="SurgeryItems_surgery-item__CYpPR" style="padding: 0px;">
				<div class="just_product SurgeryItems_item-name__HkIqD" style="float:left;">
					<input type="checkbox" id="basketAllCheck" name="item-name" onclick="selectBasketAll()" />
					<label for="basketAllCheck" onclick="selectBasketAll()" >전체상품 ( <?=isset($basketDataTotal) ? $basketDataTotal : '0' ?> )</label>
				</div>
				<div class="just_product SurgeryItems_item-name__HkIqD" style="float: right; color: #878e9c;" onclick="selectBasketDelete()">
					선택삭제
				</div>
			</div>
			<?foreach ( $basketData as $index => $row ){?>
				<?//=$index?>
				<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; padding: 10px 10px 0px 10px; margin-bottom: 30px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; ">
					<div id="" class="SurgeryItems_surgery-item__CYpPR" >
						<div>

							<?foreach ( $row as $index3 => $row3 ){?>
								<?foreach ( $row3 as $index2 => $row2 ){?>
									<div>
										<div class="just_product SurgeryItems_item-name__HkIqD" style="float: left;">
											<input type="checkbox"
												   id="<?='input_'.$row2['CODE']?>"
												   data-id="<?=$row2['CODE']?>"
												   data-og_price="<?=isset($row2['TSED_PRICE']) && $row2['TSED_PRICE'] > 0 ? $row2['TSED_PRICE'] : $row2['TS_PRICE']?>"
												   data-price="<?=$row2['TS_PRICE']?>"
												   data-detail_total_price="<?=isset($row2['TSED_PRICE']) && $row2['TSED_PRICE'] > 0 ? $row2['TSED_PRICE'] : $row2['TS_PRICE']?>"
												   data-category1_uid="<?=$row2['TSC_CATEGORY1_SQ']?>"
												   data-category2_uid="<?=$row2['TSC_CATEGORY2_SQ']?>"
												   data-b_category1="<?=$row2['B_CATEGORY1']?>"
												   data-b_category2="<?=$row2['B_CATEGORY2']?>"
												   data-b_remains_pro="<?=$row2['B_REMAINS_PRO']?>"
												   data-category2_nm="<?=strpos($index, 'EVT') !== false ? $row2['TSE_SUBJECT'] : $row2['TSC_CATEGORY2_NM'] ?>"
												   data-product_nm="<?=$row2['TS_NM']?>"
												   data-coupon=""
												   data-product='<?=json_encode($row2)?>'
												   class="detailProduct"
												   onclick="detailProductPriceBasket()"
												   value="<?=isset($row2['TSED_PRICE']) && $row2['TSED_PRICE'] > 0 ? $row2['TSED_PRICE'] : $row2['TS_PRICE']?>"
											/>
											<label for="<?='input_'.$row2['CODE']?>">
												<?if ( strpos($index, 'EVT') !== false ){?>
													<?=$row2['TSE_SUBJECT']?>
												<?} else {?>
													<?=$row2['TSC_CATEGORY2_NM']?>
												<?}?>
											</label>
										</div>
										<div class="just_product SurgeryItems_item-name__HkIqD" style="float: right;">
											<img src="/common/homepage/img/common/ico_close.svg" style="width: 20px; height: 20px;"
												 onclick="basketDeleteProduct('<?=$row2['CODE']?>', '<?=$row2['TSC_CATEGORY1_SQ']?>', '<?=$row2['TSC_CATEGORY2_SQ']?>')"
											/>
										</div>
									</div>
									<div style="clear: both; padding: 0px 10px 0px 10px;">
										<div style="margin-bottom: 10px;">
											<span style="font-size: 14px; font-weight: 500; font-stretch: normal; font-style: normal; line-height: normal; letter-spacing: -0.28px; text-align: left; color: #111;">
												<?=$row2['TS_NM']?>
											</span>
										</div>
										<div class="SurgeryItems_item-benefit__B-eVN">
											<table class="coupontable">
												<tr>
													<td>

														<div class="just_product SurgeryItems_item-price__mTk3p">
															<span id="price_<?=$row2['CODE']?>" data-id="<?=$row2['TS_CODE']?>" class="dpPrice SurgeryItems_discount-price__ti3vT">
																<?=isset($row2['TSED_PRICE']) && $row2['TSED_PRICE'] > 0 ? number_format($row2['TSED_PRICE']) : number_format($row2['TS_PRICE'])?>
															</span>

															<?if ( $row2['TSED_PRICE'] > 0 && ($row2['TS_PRICE'] != $row2['TSED_PRICE']) ) {?>
																<span data-id="<?=$row2['TS_CODE']?>" class="dpePrice SurgeryItems_price__4wMf8">
																	<?=number_format($row2['TS_PRICE'])?>
																</span>
																<span data-id="<?=$row2['TS_CODE']?>" class="dppPrice SurgeryItems_discount-rate__Q5+2B">
																	<?= floor(100 - (($row2['TSED_PRICE'] / $row2['TS_PRICE']) * 100)).'%'?>
																</span>
															<?}?>
															<!--<p class="SurgeryItems_membership-price__jyjvP">멤버십 할인가 <em>72,000</em></p>-->
														</div>

													</td>
													<td class="rightTd">
														<?if ( $this->session->userdata('M_PUBLIC_CI') ) {?>
															<button class="couponBox" onclick="couponViewOpen('<?=$row2['CODE']?>')">혜택적용</button>
														<?}?>
													</td>
												</tr>
												<tr id="tr_<?=$row2['CODE']?>" style="display: none;">
													<td style="padding-top: 10px;">
														<div class="just_product SurgeryItems_item-price__mTk3p">
															<p class="couponJText SurgeryItems_membership-price__jyjvP">
																혜택 할인가 <em id="text_<?=$row2['CODE']?>">0</em>
															</p>
														</div>
													</td>
													<td class="rightTd">
														<?if ( isset($row2['couponList']) && count($row2['couponList']) > 0 ) {?>
															<button class="couponCancel" onclick="couponCancelBtnBasket('<?=$row2['CODE']?>')">혜택취소</button>
														<?}?>
													</td>
												</tr>
											</table>
										</div>
									</div>
								<?}?>
							<?}?>

							<button class="optionChange" onclick="productDetailViewBasketShow('<?=$row2['TSC_CATEGORY1_SQ']?>', '<?=$row2['TSC_CATEGORY2_SQ']?>')">
								옵션변경
							</button>
						</div>
					</div>
				</div>
			<?}?>

			<table style="width: 100%;">
				<tr>
					<td colspan="2" class="basketThPrice" style="">
						결제 예상금액
					</td>
				</tr>
				<tr style="border-bottom: 1px solid #e7e8eb;">
					<td class="basketTotalPrice">
						총 시술금액
					</td>
					<td class="basketTotalPrice2">
						<span class="og_subProPrice">0</span> 원
					</td>
				</tr>
			</table>

			<table style="width: 93%; margin: auto; margin-top: 16px;">
				<tr style="">
					<td class="basketGouponLeft">
						혜택 사용
					</td>
					<td class="basketGouponRight">
						<span class="discountPrice">0</span>
					</td>
				</tr>
			</table>

			<table style="width: 100%;">
				<tr style="border-top: 1px solid #e7e8eb;">
					<td class="basketPayTotalVat">
						총 결제 예상금액 (VAT별도)
					</td>
					<td class="basketPayTotalVatPrice" style="">
						<span class="subProPrice">0</span> 원
					</td>
				</tr>
				<tr style="">
					<td class="basketAnotherText" style="">
						* 결제는 내원 시 진행돼요.
					</td>
				</tr>
			</table>

		</div>

	</div>
	<div style="width: 100%; margin-top: 20px; background-color: #fff; padding-bottom: 20px; text-align: center;">
		<button style="width: 90%; height: 52px; border-radius: 8px; background-color: #cf2f75; font-size: 16px; text-align: center; color: #fff;"
		onclick="basketReservationFun()">
			예약하기
		</button>
	</div>
<?} else { ?>
	<div class="Cart_cart-empty__7e2tB" style="padding: 160px 20px 100px">
		<div class="MyNone_my-none__9dt6+">
			<img src="/common/homepage/img/basket/ico_none.svg" alt="">
			<p class="MyNone_my-none__text__24uCV">장바구니에 상품이 없습니다</p>
			<a href="selectSurgery">
				<button class="MyNone_btn-white__kyEV8">예약하기</button>
			</a>
		</div>
	</div>
<?}?>

