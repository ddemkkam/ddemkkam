<style>
	.ui-datepicker{ font-size: 12px; width: 90%; height: 230px; font-size: 17px; }

</style>

<div class="Reserve_reserve__ql53K">

	<input type="hidden" name="before_reserve_number" id="before_reserve_number" value="<?=isset($res_num) && $res_num != '' ? $res_num : ''?>">
	<input type="hidden" name="reserve-date" id="reserve-date" value="">
	<input type="hidden" name="reserve-time" id="reserve-time" value="">
	<input type="hidden" name="treament_shop" id="treament_shop" value='<?=isset($treament_shop) && count($treament_shop) > 0 ? json_encode($treament_shop) : ''?>'>
	<input type="hidden" name="treament_item" id="treament_item" value='<?=isset($treament_item) && count($treament_item) > 0 ? json_encode($treament_item) : json_encode(array())?>'>
	<input type="hidden" name="room_seq" id="room_seq" value="">
	<input type="hidden" name="memo" id="memo" value="">
	<input type="hidden" name="og_subProPrice" id="og_subProPrice" value="<?=isset($og_subProPrice) ? number_format($og_subProPrice) : '0'?>">
	<input type="hidden" name="discountPrice" id="discountPrice" value="<?=isset($discountPrice) && $discountPrice != '' ? number_format($discountPrice) : '0'?>">
	<input type="hidden" name="subProPrice" id="subProPrice" value="<?=isset($subProPrice) ? number_format($subProPrice) : '0'?>">
	<input type="hidden" name="product" id="product" value='<?=isset($product) && count($product) > 0 ? json_encode($product) : json_encode(array())?>'>


	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="Reserve_surgery-item-box__9cSX7">
		<input type="hidden" name="treament_shop" id="treament_shop" value="[]">
		<div class="Reserve_surgery-titlie__5VCVr">
			<?if ( isset($product) ) {
				foreach ( $product as $index => $row ){
					$PRODUCT_NM = $row[0]['PRODUCT_NM'];
					break;
				}
			}?>
			<table style="width: 90%; float:left;">
				<tr>
					<td style="text-align: left;"><em>선택 시술</em></td>
					<td style="text-align: right;">
						<em>
							<?=$PRODUCT_NM?>
							&nbsp;&nbsp;
							<?=isset($productCnt) && $productCnt > 0 ? '외'.$productCnt.'건' : ''?>
						</em>
					</td>
				</tr>
			</table>
			<button class="resBtnOn" style="height: 50px;" onclick="resProView(this)"></button>

		</div>
		<div class="resProductView Divider_divider__pCsgd" style="margin: background: rgb(246, 247, 249); height: unset;">

			<div style="width: 89%; margin: auto; padding-top: 16px; padding-bottom: 20px;">
				<?if ( isset($product) ) {
					$proArr = array();				?>
					<?foreach ( $product as $index => $row ){ ?>
						<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; padding: 10px 10px 0px 10px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; margin-top: 0px; margin-bottom: 10px;">

							<?//echo "<pre>"; print_r($row); echo "</pre>";?>
							<label  style="font-size: 16px; font-weight: bold;"><?=$row[0]['B_CATEGORY2_NM']?></label>
							<div id="" class="SurgeryItems_surgery-item__CYpPR" >
								<div>
									<?$totalPrice = 0; ?>
									<?foreach ( $row as $index2 => $row2 ){?>
										<div>
											<div class="just_product SurgeryItems_item-name__HkIqD" style="margin: 18px 0 16px 0;">
												<table class="coupontable" style="width: 100%; margin-top: 10px;">
													<tr>
														<td style="font-size: 12px; color: #878e9c;"><?=$row2['PRODUCT_NM']?></td>
														<td style="text-align: right; font-size: 14px; color: #878e9c;">
															<?=number_format($row2['DETAIL_TOTAL_PRICE'])?>
															<?$totalPrice = $totalPrice + $row2['DETAIL_TOTAL_PRICE'];?>
														</td>
													</tr>
												</table>
											</div>
										</div>
									<?}?>
									<div style="text-align: right; font-size: 16px; font-weight: bold; color: #111; padding-right: 10px; padding-top: 10px; border-top: 1px dashed #e7e8eb;">
										<?=number_format($totalPrice)?>
										<?array_push($proArr, $row2['CODE']);?>
									</div>
								</div>
							</div>

						</div>
					<?}?>
					<input type="hidden" id="proArr" value="<?=implode(',',$proArr)?>" />
				<?}?>
			</div>

		</div>
	</div>
	<div class="ReserveDate_calendar-wrap__DXu8w">
		<div style="display: flex; margin-bottom: 15px; justify-content: space-between;">
			<div class="ReserveDate_ym__oAEZK"><?=date('Y')?>년 <?=date('m')?>월</div>
<!--			<div class="ReserveDate_calendar-btn__G66dM">달력</div>-->
			<label for="datepicker">
				<input type="text" id="datepicker" class="ReserveDate_calendar-btn__G66dM" style="text-align: center" readonly/>
			</label>
		</div>


		<div style="margin-right: -20px;">
			<div id="swiper_caladar">
				<div class="swiper swiper-initialized swiper-horizontal swiper-ios">
					<div class="swiper-wrapper" style="height: unset;">
						<?if ( isset($dataResData) ) {?>
							<?foreach ( $dataResData as $index => $row ){?>
								<div
									class="m_i m_i_<?=$row['fullDays']?> swiper-slide ReserveDate_date-box__BQNmx <?=$index == 0 ? 'ReserveDate_selected__vHeMH' : ''?> calswiper"
									data-uid="<?=$row['fullDays']?>"
									data-index="<?=$index?>"
									onclick="reservationDayChange('<?=$row['fullDays']?>', '<?=$index?>')"
								>
									<div class="ReserveDate_date-day__5P9RI"><?=$row['yoil']?></div>
									<div class="ReserveDate_date-number__RSg3V"><?=$index == 0 ? '오늘' : $row['days']?></div>
								</div>
							<?}?>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="timeView" style="margin-top: 24px; margin-bottom: 24px; clear: both;">

	</div>

	<div class="Reserve_counsel-box__41sn9" style="clear: both;">
		<input type="checkbox" id="is_counsel" name="is_counsel" value="Y" onclick="reservationDayChangeCounsel()">
		<label for="is_counsel" onclick="reservationDayChangeCounsel()">상담 없이 빠른 시술 희망.</label>

		<div style="color: #878e9c; margin-top: 5px; margin-left: 5px;">
			<span>* 진료 상황에 따라 상담이 필요한 경우가 있으며 당일 진료가 </span>
			<br/>
			<span>불가할 수 있습니다.</span>
		</div>
	</div>

	<div class="Divider_divider__pCsgd" style="margin: 10px -20px 0px; background: rgb(246, 247, 249);"></div>

	<div class="Reserve_coupon-box__4eKHF">
		<div class="Reserve_title__BhLHH">혜택</div>
		<button type="button" onclick="freeCouponViewOpen()">
			사용가능 쿠폰 <?=isset($freeCouponData) && count($freeCouponData) > 0 ? count($freeCouponData) : '0'?>장
		</button>
		<table class="freeCouponList" style="display: block; width: 100%; margin-top: 10px;">

		</table>
	</div>

	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>

	<div class="Reserve_mileage-box__Y-KEf">
		<div class="Reserve_title__BhLHH">마일리지</div>
		<span>사용가능 마일리지</span>
		<em id="userMileage"><?=isset($mileageData) && $mileageData > 0 ? $mileageData : '0'?></em>
		<div class="Reserve_input-box__r+5ys">
			<input type="number" id="mileage" name="mileage" placeholder="0" value="" onkeyup="mileageCheck(this)">
			<button type="button">모두사용</button>
		</div>
	</div>

	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>

	<div class="Reserve_btn-area__gavvL">
		<strong>결제 예상금액</strong>
		<p class="Reserve_total-price-box__Q94-J">
			<span class="txt">총 시술금액</span>
			<span class="Reserve_num__FbcCh">
				<?=isset($og_subProPrice) ? number_format($og_subProPrice) : '0'?>
			</span>
		</p>

		<ul class="Reserve_use-history__TJqsR">
			<li>혜택 사용<em><?=isset($discountPrice) && $discountPrice != '' ? number_format($discountPrice) : '0'?></em></li>
			<li>마일리지 사용<em id="setUseMileage">0</em></li>
			<?//<li>멤버십 할인<em>-50,000</em></li>?>
		</ul>
		<p class="Reserve_total-calculate-box__gRy6a">
			<span class="txt">총 결제 예상금액</span>
			<span class="Reserve_num__FbcCh"><?=isset($subProPrice) ? number_format($subProPrice) : '0'?></span>
		</p>
		<p class="Reserve_noti__Q74Mi">*결제는 내원 시 진행돼요.</p>

		<div class="Reserve_btn-wrap__mzkwW">
			<button class="Button_btn__3u27s" type="submit" style="width: 100%;" onclick="setReservation()">예약완료</button>
		</div>
	</div>

</div>


<script type="text/javascript">
	//console.log(new Date());

	const datePickerOptions = {
		dateFormat: "yymmdd",
		//maxDate: new Date(),
		minDate: new Date(),
		maxDate: "+<?=isset($dayCount) ? $dayCount : '0'?>",
		showOtherMonths: true,          // 현재 월에 속하지 않는 날짜도 달력 위젯에 표시하도록 지시
		selectOtherMonths: true,        // 이전 월 및 다음 월의 날짜를 선택할 수 있도록 함
		prevText: '이전 달',
		nextText: '다음 달',
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		dayNames: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		showMonthAfterYear: true,
		yearSuffix: '년'
	}

	/**
	 * 화면이 렌더링 된 이후에 수행됩니다.
	 */
	$(() => {
		reservationDayChange("<?=date('Ymd')?>", 0);

		const todayDate = new Date();
		const datePicker = $("#datepicker");

		// DatePicker 옵션 설정하고 선택하였을때 이벤트 동작 처리를 추가합니다.
		datePicker.datepicker(
			Object.assign({}, datePickerOptions, {
				// DatePicker 선택 하였을때 처리
				onSelect: (dataString, selectedAttr) => {
					// console.log(dataString);
					// console.log(selectedAttr);
					// 선택한게 오늘이면 다음 버튼을 비활성화 시킵니다
					// if (todayDate.getFullYear() === selectedAttr.currentYear &&
					// 	todayDate.getMonth() === selectedAttr.currentMonth &&
					// 	todayDate.getDate() === selectedAttr.currentDay) {
					// 	$('#postDate').attr("disabled", false);
					// } else {
					// 	$('#postDate').attr("disabled", true);
					// }
					$('#datepicker').val(dataString);
					// console.log(selectedAttr.currentYear);
					const year = selectedAttr.currentYear;
					const month = Number(selectedAttr.currentMonth) + Number(1);
					$('.ReserveDate_ym__oAEZK').text(year + '년 ' + month + '월');

					<? foreach ( $dataResData as $index => $row ) {?>
						if ( dataString == "<?=$row['fullDays']?>" ) {
							reservationDayChange(dataString, "<?=$index?>");
						}
					<?}?>
				},
			})
		);
		// UI 날짜를 지정합니다.
		//datePicker.datepicker("setDate", todayDate);
	});


</script>
