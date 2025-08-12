<div class="ReserveComplete_reserveComplete__77sui">
	<h3>
		<em><?=$this->session->userdata('M_NAME')?></em>님<br>예약이 완료되었습니다!
	</h3>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="ReserveComplete_surgery-item-box__jUhtg">
		<div class="ReserveComplete_surgery-titlie__HIh0Z">
			<em>선택 시술</em>
			<button class="resBtnOn" style="height: 50px;" onclick="resProView(this)"></button>
		</div>
		<div class="resProductView Divider_divider__pCsgd" style="background: rgb(246, 247, 249); height: unset;">

			<div style="width: 89%; margin: auto; padding-top: 16px; padding-bottom: 20px;">

				<input type="hidden" name="res_num" id="res_num" value="<?=isset($res_number) ? $res_number : ''?>" />
				<input type="hidden" name="productArr" id="productArr" value='<?=isset($product) && count($product) > 0 ? json_encode($product) : array() ?>' />
				<input type="hidden" name="og_subProPrice" id="og_subProPrice" value="<?=isset($og_subProPrice) ? $og_subProPrice : ''?>" />
				<input type="hidden" name="discountPrice" id="discountPrice" value="<?=isset($discountPrice) ? $discountPrice : ''?>" />
				<input type="hidden" name="subProPrice" id="subProPrice" value="<?=isset($subProPrice) ? $subProPrice : ''?>" />

				<?if ( isset($product) ) {
					$proArr = array();				?>
					<?foreach ( $product as $index => $row ){ ?>
						<?//echo '<pre>'; print_r($row); echo '</pre>';?>
						<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; padding: 10px 10px 0px 10px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; margin-top: 0px; margin-bottom: 10px;">

							<?//echo "<pre>"; print_r($row); echo "</pre>";?>
							<label  style="font-size: 16px; font-weight: bold;"><?=$row[0]->B_CATEGORY2_NM?></label>
							<div id="" class="SurgeryItems_surgery-item__CYpPR" >
								<div>
									<?$totalPrice = 0; ?>
									<?foreach ( $row as $index2 => $row2 ){?>
										<div>
											<div class="just_product SurgeryItems_item-name__HkIqD" style="margin-left: 16px; margin: 10px 10px 15px 0;">
												<table class="coupontable" style="width: 100%; margin-top: 10px;">
													<tr>
														<td style="font-size: 12px; color: #878e9c;"><?=$row2->PRODUCT_NM?></td>
														<td style="text-align: right; font-size: 14px; color: #878e9c;">
															<?=number_format($row2->DETAIL_TOTAL_PRICE)?>
															<?$totalPrice = $totalPrice + $row2->DETAIL_TOTAL_PRICE;?>
														</td>
													</tr>
												</table>
											</div>
										</div>
									<?}?>
									<div style="text-align: right; font-size: 16px; font-weight: bold; color: #111; padding-right: 10px; padding-top: 10px; border-top: 1px dashed #e7e8eb;">
										<?=number_format($totalPrice)?>
										<?array_push($proArr, $row2->CODE);?>
									</div>
								</div>
							</div>

						</div>
					<?}?>
					<input type="hidden" id="proArr" value="<?=implode(',',$proArr)?>" />
				<?}?>
			</>

		</div>
	</div>
	<div class="ReserveComplete_reserve-date__C2MYk">
		예약 일자
		<b>
			<?=isset($reserve_date) ? $reserve_date : ''?>
			<?
			$yoil = array("일","월","화","수","목","금","토");
			$yoilDate = $yoil[date('w', strtotime($reserve_date))];
			?>
			(<?=$yoilDate?>)
			<span><?=isset($reserve_time) ? $reserve_time : ''?></span>
		</b>
	</div>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="ReserveComplete_minor-agree-box__-cq00">
		<p><em>보호자 시술 동의서</em>미성년자 예약 시 보호자 동의서를<br>작성해서 내원해주세요.</p>
		<a href="/common/homepage/ppeum.zip">
			<button class="ReserveComplete_downloadBtn__JO8pY">다운로드</button>
		</a>
	</div>
	<div class="ReserveComplete_btn-area__YkWyz">
		<button class="edit-btn" onclick="modifyResViewShowFun('<?=isset($res_number) ? $res_number : ''?>')">예약수정</button>
		<a href="/">
			<button class="ReserveComplete_home-btn__5btIH">홈으로</button>
		</a>
	</div>
</div>
