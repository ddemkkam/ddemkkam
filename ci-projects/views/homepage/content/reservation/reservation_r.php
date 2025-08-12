<div class="Reserve_reserve__ql53K">
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<input type="hidden" name="basket_yn" value="<?= $basket_yn ?>">
	<input type="hidden" name="res_number" value="<?= $res_number ?>">

	<?
	$lastDate = date('Y-m-d', strtotime('+90days'));
	if (isset($check)) { ?>
		<div class="Reserve_surgery-item-box__9cSX7">
			<div class="Reserve_surgery-titlie__5VCVr">
				<?
				$cnt = 0;
				if ( isset($product['list']) ) {
					$product_name = '';
					foreach ( $product['list'] as $index => $row ){
						$cnt += count($row);
						if (empty($product_name)) $product_name = $row[0]['ts_name'];
					}
				}
				?>
				<table style="width: 90%; float:left;">
					<tr>
						<td style="text-align: left;"><em>선택 시술</em></td>
						<td style="text-align: right;" class="item-name">
							<em><?= $product_name ?>&nbsp;&nbsp;<?=  $cnt > 1 ? '외 ' . ($cnt - 1) . '건' : '' ?></em>
						</td>
					</tr>
				</table>
				<button class="resBtnOff" style="height: 50px;" onclick="resProView(this)"></button>
			</div>
			<div class="Divider_divider__pCsgd" style="margin: 0 -20px 0px; background: rgb(246, 247, 249);"></div>
			<div class="resProductView Divider_divider__pCsgd" style="background: rgb(246, 247, 249); height: unset;display: none;">
				<div style="width: 89%; margin: auto; padding-top: 16px; padding-bottom: 20px;">
					<?
					$total = 0;
					//echo '<pre>'; print_r($product['list']); echo '</pre>';
					if (isset($product['list'])) { ?>
						<? foreach ($product['list'] as $cate_code => $item) { ?>
							<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; padding: 20px 16px 16px 16px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; margin-top: 0px; margin-bottom: 10px;">
								<label  style="font-size: 14px; font-weight: bold; margin-bottom: 10px; display: block"><?= $item[0]['sec_cate_name'] ?></label>
								<div class="SurgeryItems_surgery-item__CYpPR" >
									<div>
										<?
										$totalPrice = 0;
										foreach ($item as $key => $value) { ?>
											<div>
												<input type="hidden" name="price[]" value="<?= $value['ts_price'] ?>" data-fir_cate_code="<?= $value['fir_cate_code'] ?>" data-sec_cate_code="<?= $value['sec_cate_code'] ?>" data-ts_code="<?= $value['ts_code'] ?>" data-origin="<?= $value['ts_price'] ?>" data-ts_has_yn="<?= $value['ts_has_yn'] ?>" data-cti_no="<?= $value['cti_no'] ?>">
												<? foreach ($value['benefit_list'] as $benefit) { ?>
													<input type="hidden" name="benefit[]" value="<?= $benefit['cpc_code'] ?>">
												<? } ?>
												<div class="just_product SurgeryItems_item-name__HkIqD" style="margin: 5px 0;">
													<table class="coupontable" style="width: 100%; margin-top: 8px;">
														<tr>
															<td style="font-size: 12px; color: #878e9c; width: 71%"><?= $value['ts_name'] ?></td>
															<td style="text-align: right; font-size: 14px; color: #878e9c;">
																<?= number_format($value['ts_price']) ?> 원
																<? $totalPrice = $totalPrice + $value['ts_price']; ?>
															</td>
														</tr>
													</table>
												</div>
											</div>
											<?
											if ( !empty($value['end_date_time']) ) {
												if (strtotime($value['end_date_time']) < strtotime($lastDate)) {
													$lastDate = $value['end_date_time'];
												} else {
													$lastDate = $lastDate;
												}
											} else {
												$lastDate = $lastDate;
											}
											?>
										<? } ?>
										<div style="text-align: right; font-size: 16px; font-weight: bold; color: #111; padding-top: 10px; border-top: 1px dashed #e7e8eb; margin-top: 15px;"><?= number_format($totalPrice) ?> 원</div>
									</div>
								</div>
							</div>
						<? $total += $totalPrice; } ?>
						<?
						$fromDate = new DateTime(date('Y-m-d'));
						$toDate = new DateTime($lastDate);
						$resDate = $fromDate->diff( $toDate )->days;
//						echo 'asdf : '; echo '<pre>'; print_r($resDate); echo '</pre>';
						?>
					<?}?>
				</div>
			</div>
		</div>
	<? } else {
		$resDate = '89';
	} ?>
	<div class="ReserveDate_calendar-wrap__DXu8w">
		<div style="justify-content: space-between;">
			<div class="ReserveDate_ym__oAEZK" style="float: left;"><?= date('Y년 m월') ?></div>
			<button type="button" style="float: left;" onclick="changeMonth()"><img src="/common/homepage/img/common/ico-more.svg"></button>
			<input type="text" id="datepicker" class="ReserveDate_calendar-btn__G66dM" style="float:right;text-align: center;cursor: pointer;" readonly>
		</div>
		<div style="clear: both;margin-bottom: 15px;"></div>
		<div style="margin-right: -20px;">
			<div id="swiper_caladar">
				<div class="swiper swiper-initialized swiper-horizontal swiper-ios">
					<div class="swiper-wrapper" style="height: unset;">
						<?if ( isset($days) ) {?>
							<?foreach ( $days as $key => $value ){?>
								<? if ($value['ch_date'] > $lastDate) break; ?>

								<div
									class="m_i m_i_<?= $value['ch_date'] ?> swiper-slide ReserveDate_date-box__BQNmx calswiper dateTarget <?= $key == 0 ? 'ReserveDate_selected__vHeMH' : '' ?>"
									data-date="<?= $value['ch_date'] ?>"
									data-index="<?= $key ?>"
									data-ch_is="<?= $value['ch_is'] ?>"
									data-ch_is_reservation="<?= $value['ch_is_reservation'] ?>"
									<? if ($value['ch_is'] == 0 || ($value['ch_is'] == 1 && $value['ch_is_reservation'] == 1)) { ?>
										style="cursor: pointer;"
										onclick="dayChange('<?= $value['ch_date'] ?>', <?= $key ?>)"
									<? } else { ?>
										style="background-color:#f6f7f9;"
									<? } ?>
								>
									<div class="ReserveDate_date-day__5P9RI" <? if ($value['ch_is'] > 0 && $value['ch_is_reservation'] == 0) { ?>style="color:#d0d6de;"<? } ?>><?= $value['ch_yoil'] ?></div>
									<div class="ReserveDate_date-number__RSg3V" <? if ($value['ch_is'] > 0 && $value['ch_is_reservation'] == 0) { ?>style="color:#d0d6de;"<? } ?>><?= $key == 0 ? '오늘' : $value['ch_day'] ?></div>

								</div>
							<?}?>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="timeView" style="margin-top: 20px; margin-bottom: 20px; clear: both;"></div>
		<div class="Reserve_counsel-box__41sn9" style="clear: both;">
			<? if(!$remain_check && isset($check)) { ?>
				<input type="checkbox" id="is_counsel" name="is_counsel" onclick="resDayChangeCounsel()" style="cursor: pointer;">
				<label for="is_counsel" style="cursor: pointer;">상담 없이 빠른 시술 희망해요</label>
				<div class="info">
					<? if (isset($branch) && $branch == 'ppeum09') { ?>
						<p class="emphasis">* <필독> 5월 1일(목)부터 예약은 카카오톡 채널 또는 전화로만 예약이 가능합니다.</p>
						<p class="emphasis">* 피부관리 예약을 희망하실 경우, <상담 없이 빠른 시술 희망해요>를 선택하셔야 내원 시 진료가 가능해요.</p>
					<? } ?>
					<p class="emphasis">* 진료 상황에 따라 상담이 필요한 경우가 있으며 당일 진료가 불가할 수 있어요.</p>
					<p class="emphasis">* 다양한 시술의 예약을 희망하실 경우 장바구니에 담은 후, 예약해 주세요.</p>
					<? if (isset($branch) && $branch == 'ppeum27') { ?>
						<p class="emphasis">* 멤버십 예약/원장, 실장 지정 및 휴무일 문의는 전화로 문의해 주세요.</p>
					<? } else { ?>
						<p class="emphasis">* 멤버십 예약/원장, 실장 지정 및 휴무일 문의는 카카오톡 채널 또는 전화로 문의해 주세요.</p>
					<? } ?>
				</div>
			<? } ?>
		</div>
		<? if(!$remain_check && isset($check)) { ?>
			<div class="Divider_divider__pCsgd" style="margin: 10px -20px 0px; background: rgb(246, 247, 249);"></div>
			<div class="Reserve_coupon-box__4eKHF">
				<div class="Reserve_title__BhLHH">혜택</div>
				<button type="button" <? if (isset($product['benefitList']) && count($product['benefitList']) > 0) { ?>onclick="popupView('<?= isset($product['benefitList']) ? count($product['benefitList']) : 0 ?>')" <? } ?>>
					사용가능 혜택 <?= isset($product['benefitList']) && count($product['benefitList']) > 0 ? count($product['benefitList']) . '장' : '없음' ?>
				</button>
				<table class="freeCouponList" style="/*display: block;*/ width: 100%; margin-top: 10px;">

				</table>
			</div>

			<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>

			<div class="Reserve_mileage-box__Y-KEf">
				<div class="Reserve_title__BhLHH">마일리지</div>
				<span>사용가능 마일리지</span>
				<em id="userMileage"><?= isset($mileage) ? number_format($mileage) : 0 ?></em>
				<div class="Reserve_input-box__r+5ys">
					<input type="number" id="mileage" name="mileage" placeholder="0" value="" onkeyup="mileage()" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
					<button type="button" onclick="allMileage()">모두사용</button>
				</div>
			</div>
		<? } ?>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="Reserve_btn-area__gavvL">
			<strong>결제 예상금액</strong>
			<p class="Reserve_total-price-box__Q94-J">
				<span class="txt">총 시술금액</span>
				<input type="hidden" name="total" value="<?= $total ?>" data-origin="<?= $total ?>">
				<span class="Reserve_num__FbcCh"><?= number_format($total) ?></span>
			</p>
			<ul class="Reserve_use-history__TJqsR">
				<li>혜택 사용<em id="dcTotal">0</em></li>
				<li>마일리지 사용<em id="setUseMileage">0</em></li>
				<!--<li>멤버십 할인<em>-50,000</em></li>-->
			</ul>
			<p class="Reserve_total-calculate-box__gRy6a">
				<span class="txt">총 결제 예상금액 <em class="price-vat">(VAT 별도)</em></span>
				<span class="Reserve_num__FbcCh" id="totalText" data-use_mileage="0"><?= number_format($total) ?></span>
			</p>
			<p class="Reserve_noti__Q74Mi">*결제는 내원 시 진행돼요.</p>
		<div class="Reserve_btn-wrap__mzkwW">
			<button class="Button_btn__3u27s" type="submit" style="width: 100%;<? if (!isset($check)) { ?> margin-top: 30px;<? } ?>" onclick="resSend()">예약완료</button>
		</div>
	</div>
</div>
<div id="sendView" style="margin-top: 20px; margin-bottom: 20px; clear: both;"></div>
<div id="freeProductCouponViewback" style="display: none;">
	<div id="productfreeCouponView" style=" position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;">
		<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(55% - env(safe-area-inset-top) - 34px); transform: none;">
			<div draggable="true" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
				<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
				<span class="react-modal-sheet-drag-indicator" onclick="productFreeCouponViewHide()"></span>
				</div>
			</div>
			<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
				<div style="height: auto; padding: 20px;">
					<div class="SurgeryItems_surgery-items__-MM1k">
						<? if (isset($product['benefitList'])) { ?>
							<? foreach($product['benefitList'] as $key => $value) { ?>
								<div class="SurgeryItems_surgery-item__CYpPR">
									<div class="SurgeryItems_item-name__HkIqD">
										<input
											type="checkbox"
											id="<?= $value['cpc_code'] ?>"
											name="item-name"
											class="couponCheckBoxFreeCls"
											data-cp_dc_per="<?= $value['cp_dc_per'] ?>"
											data-cp_dc_price="<?= $value['cp_dc_price'] ?>"
											data-cp_dc_price_type="<?= $value['cp_dc_price_type'] ?>"
											data-cp_dc_max_price="<?= $value['cp_dc_max_price'] ?>"
											data-cp_dc_min_price="<?= $value['cp_dc_min_price'] ?>"
											data-cp_dc_type="<?= $value['cp_dc_type'] ?>"
											data-sec_cate_code="<?= $value['sec_cate_code'] ?>"
											data-event_code="<?= $value['event_code'] ?>"
											data-cp_overlap_yn="<?= $value['cp_overlap_yn'] ?>"
											data-cp_name="<?= $value['cp_name'] ?>"
											onclick="overlapCheck(this)"
											value="<?= $value['cpc_code'] ?>"
											style="cursor: pointer;"
										>
										<label for="<?= $value['cpc_code'] ?>" style="cursor: pointer;">
											<?= $value['cp_name'] ?>
											<?= $value['cp_overlap_yn'] === 'Y' ? '(중복가능)' : '(중복불가)' ?>
										</label>
									</div>
								</div>
							<? } ?>
						<? } ?>
					</div>

					<div class="SurgeryItems_total-price-box__H6NKl">
						<div class="SurgeryItems_btn-wrap__VdsxS">
							<button class="Button_btn__3u27s" type="button" style="float: left; width: 32.5%; border: 1px solid var(--color-primary); background: rgb(255, 255, 255); color: var(--color-primary); margin-right: 8px;" onclick="productFreeCouponViewHide()">취소</button>
							<button class="Button_btn__3u27s" type="button" style="width: calc(67.5% - 8px);" onclick="setProductBenefit()">혜택 적용</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="productFreeCouponViewHide()"></button>
	</div>
</div>

<script type="text/javascript">
	const datePickerOptions = {
		dateFormat: "yymmdd",
		//maxDate: new Date(),
		minDate: new Date(),
		maxDate: "+<?=$resDate?>", // 이벤트 상품이 있을시 여기에 날짜 설정
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

	$(() => {
		dayChange("<?= date('Y-m-d') ?>", 0);

		const todayDate = new Date();
		const datePicker = $("#datepicker");

		// DatePicker 옵션 설정하고 선택하였을때 이벤트 동작 처리를 추가합니다.
		datePicker.datepicker(
			Object.assign({}, datePickerOptions, {
				beforeShow : function(input,inst){
					var offset = $(input).offset();
					var height = $(input).height();
					var width = inst.dpDiv.width();
					var left = offset.left;
					window.setTimeout(function () {
						$(inst.dpDiv).css({ top: (offset.top + height) + 'px', left: (left - width + 20) + 'px' })
						// $(inst.dpDiv).css({ top: (offset.top + height) + 'px', right: offset.right + 'px' });
						//console.log(inst.dpDiv.width());
					}, 1);
				},
				beforeShowDay: function(selectedAttr) {
					const year = selectedAttr.getFullYear();
					const month = Number(selectedAttr.getMonth()) + Number(1);
					const target = $(".m_i_" + year + "-" + String(month).padStart(2, '0') + "-" + String(selectedAttr.getDate()).padStart(2, '0'));

					return [target.data('ch_is') == 0 || (target.data('ch_is') == 1 && target.data('ch_is_reservation') == 1)];
				},
				onSelect: (dataString, selectedAttr) => {
					$('#datepicker').val(dataString);
					const year = selectedAttr.currentYear;
					const month = Number(selectedAttr.currentMonth) + Number(1);
					const target = $(".m_i_" + year + "-" + String(month).padStart(2, '0') + "-" + String(selectedAttr.currentDay).padStart(2, '0'));
					$('.ReserveDate_ym__oAEZK').text(year + '년 ' + String(month).padStart(2, '0') + '월');
					target.click();
				},
			})
		);
	});

	function dayChange(date, index)
	{
		$(".ReserveDate_date-box__BQNmx").removeClass('ReserveDate_selected__vHeMH');
		$(".m_i_" + date).addClass('ReserveDate_selected__vHeMH');
		$('.ReserveDate_ym__oAEZK').text(date.split('-')[0] + '년 ' + date.split('-')[1] + '월');

		let indexCnt = index > 0 ? index - 1 : index;

		swiper_caladar.slideTo(indexCnt, 500, false);
		$("#datepicker").datepicker("setDate", new Date(date));
		let ts = [];
		let its = [];
		let isC = 'Y';

		$("input[name='price[]']").each(function (k,v) {
			if (v.dataset.ts_has_yn === "Y") {
				its.push(v.dataset.cti_no);
			}  else {
				ts.push(v.dataset.ts_code);
			}
		});

		if ($("input[name='is_counsel']").length > 0) {
			isC = $("input[name='is_counsel']").prop('checked') ? 'N' : 'Y';
		} else if (its.length > 0 && ts.length === 0) {
			isC = 'N';
		}

		let data = { 'date' : date, 'treatment_shop' : ts, 'treatment_item': its, 'is_counsel' : isC };
		sendAjaxHtml("/reservation/getResRoomTime", data, 'GET', 'timeView');
	}

	function setProductBenefit()
	{
		$(".freeCouponList").html('');
		$('#mileage').val('');
		mileage();

		let dc_target = $("#dcTotal")[0];

		let total_price = Number($("input[name='total']").val());
		let set_total_price = total_price;
		let set_total_dc_price = 0;

		if ($("#productfreeCouponView input[type='checkbox']:checked").length > 0) {
			$("#productfreeCouponView input[type='checkbox']:checked").each(function (k, v) {
				//할인 타입
				let dc_type = v.dataset.cp_dc_type;
				let dc_price_type = v.dataset.cp_dc_price_type;
				let dc_min_price = Number(v.dataset.cp_dc_min_price);
				let dc_max_price = Number(v.dataset.cp_dc_max_price);

				//최대 할인 적용가
				let total_dc_max_price = Number($("input[name='total']").val()) * 0.51;

				//적용 할인가
				let dc_price = 0;

				//최소 주문 금액
				let s_price = 0;

				//최종 적용 할인가
				let dc = 0;

				if (dc_type === '주문금액') {
					s_price = total_price;
				} else if (dc_type === '단품할인' || dc_type === '이벤트할인' || dc_type === '카테고리할인') {
					$("input[name='benefit[]'][value='" + v.id + "']").each(function (i, w) {
						let t = w.parentNode.querySelector('input[name="price[]"]');

						s_price += Number(t.value);
					});
				}

				if (dc_price_type === '정액') {
					dc_price = Number(v.dataset.cp_dc_price);
				} else {
					//let sp = s_price / (v.dataset.cp_dc_per > 0 ? v.dataset.cp_dc_per : 1);
					let sp = (s_price * (v.dataset.cp_dc_per > 0 ? v.dataset.cp_dc_per : 1)) / 100;
					dc_price = sp > dc_max_price ? dc_max_price : sp;
				}

				//혜택 적용 금액
				let total_dc_price = set_total_price - dc_price;

				//혜택 적용 최소 주문금액
				if (s_price >= dc_min_price) {
					//혜택 최대 적용 여부
					if (set_total_price > total_dc_max_price) {
						dc = total_dc_price > total_dc_max_price ? dc_price : set_total_price - (total_price - total_dc_max_price);

						set_total_price -= dc;
						set_total_dc_price -= dc;

						let text = v.dataset.cp_overlap_yn === 'Y' ? ' (중복가능)' : ' (중복불가)';
						let setHtml = "";
						setHtml += "<tr class='useBenefit' data-cp_name='" + v.dataset.cp_name + "' data-cpc_code='" + v.value + "' data-dc_price='" + dc + "'>";
						setHtml += "<td class='freeCText' style='width: 95%; vertical-align: middle;'>" + v.dataset.cp_name + text + "</td>";
						setHtml += "<td style='text-align: right; width: 10%;'>";
						setHtml += "<img src='/common/homepage/img/common/ico_close.svg' style='width: 20px; height: 20px;cursor: pointer;' onclick='deleteCoupon(this, " + dc + ", " + v.value + ")'>";
						setHtml += "</td>";
						setHtml += "</tr>";
						$(".freeCouponList").append(setHtml);
					} else {
						v.checked = false;
					}
				} else {
					v.checked = false;
				}
			});
		}

		$("#totalText").text($.number(set_total_price));
		dc_target.innerText = $.number(set_total_dc_price);

		productFreeCouponViewHide();

	}

	//중복 쿠폰 체크 처리
	function overlapCheck(t)
	{
		if (t.dataset.cp_overlap_yn === 'N') {
			$("input[name='item-name']").not('#' + t.id).prop('checked',false);
		} else {
			$("input[name='item-name']").not('[data-cp_overlap_yn=Y]').prop('checked',false);
		}
	}

	function popupView(cnt)
	{
		if (cnt > 0) {
			$("#freeProductCouponViewback").show();
		} else {
			alertViewShow('사용가능한 쿠폰이 없습니다.', 'couponList');
		}
	}

	function deleteCoupon(that, price, id)
	{
		$("#totalText").text($.number(Number($("#totalText").text().replaceAll(',', '')) + price));
		$("#dcTotal").text($.number(Number($("#dcTotal").text().replaceAll(',', '')) + price));
		$("#" + id).prop('checked', false);
		$(that).parent().parent().remove();
	}

	function mileage()
	{
		let userMileage = Number($('#userMileage').text().replaceAll(',', ''));

		let um = $('#mileage').val();
		let max = (Number($("input[name='total']").val())) - Number($("#dcTotal").text().replace('-','').replace(',',''));
		let mileage = um > max ? max : um;
		let setUseMileage = 0;

		if ( mileage >= userMileage ) {
			setUseMileage = userMileage;
		} else {
			setUseMileage = mileage;
		}
		$('#mileage').val(setUseMileage);
		if (setUseMileage > 0) {
			$('#setUseMileage').text('-' + $.number(setUseMileage));
		} else {
			$('#setUseMileage').text('0');
		}

		if ($('#mileage').val() != $("#totalText").data('use_mileage')) {
			$("#totalText").text($.number(Number($("#totalText").text().replaceAll(',', '')) + Number($("#totalText").data('use_mileage'))));
			$("#totalText").text($.number(Number($("#totalText").text().replaceAll(',', '')) - $('#mileage').val()));
			$("#totalText").data('use_mileage', $('#mileage').val());
		}
	}

	function allMileage()
	{
		let um = Number($('#userMileage').text().replaceAll(',', ''));
		let max = (Number($("input[name='total']").val())) - Number($("#dcTotal").text().replace('-','').replace(',',''));
		let userMileage = um > max ? max : um;
		$('#mileage').val(userMileage);
		if (userMileage > 0) $('#setUseMileage').text('-' + $.number(userMileage));

		if ($('#mileage').val() != $("#totalText").data('use_mileage')) {
			$("#totalText").text($.number(Number($("#totalText").text().replaceAll(',', '')) + Number($("#totalText").data('use_mileage'))));
			$("#totalText").text($.number(Number($("#totalText").text().replaceAll(',', '')) - $('#mileage').val()));
			$("#totalText").data('use_mileage', $('#mileage').val());
		}
	}

	function resSend()
	{
		if ($(".timeButtonEnableSelect").length > 0) {
			let benefit = [];
			let ts = [];
			let its = [];
			let it_ts = [];
			let ts_fir = [];
			let its_fir = [];
			let ts_sec = [];
			let its_sec = [];
			let mileage = $('#mileage').val() === undefined || $('#mileage').val() == '' ? 0 : $('#mileage').val();
			let isC = 'Y';
			let date = $(".dateTarget.ReserveDate_selected__vHeMH").data('date');
			let time = $(".timeButtonEnableSelect").data('time');
			let room = $(".timeButtonEnableSelect").data('room');
			let treatment_minute = $(".timeButtonEnableSelect").data('treatment_minute');
			let basket_yn = $("input[name='basket_yn']").val();
			let res_number = $("input[name='res_number']").val();

			$("input[name='price[]']").each(function (k,v) {
				if (v.dataset.ts_has_yn === "Y") {
					its.push(v.dataset.cti_no);
					it_ts.push(v.dataset.ts_code);
					its_fir.push(v.dataset.fir_cate_code);
					its_sec.push(v.dataset.sec_cate_code);
				}  else {
					ts.push(v.dataset.ts_code);
					ts_fir.push(v.dataset.fir_cate_code);
					ts_sec.push(v.dataset.sec_cate_code);
				}
			});

			if ($("input[name='is_counsel']").length > 0) {
				isC = $("input[name='is_counsel']").prop('checked') ? 'N' : 'Y';
			} else if (its.length > 0 && ts.length === 0) {
				isC = 'N';
			}

			let memo = '마일리지 - ' + mileage;

			$(".useBenefit").each(function(k,v) {
				benefit.push({
					'cpc_code': v.dataset.cpc_code
					,'cp_name': v.dataset.cp_name
					,'dc_price': v.dataset.dc_price
				});
				memo += ', 쿠폰 - ' + v.dataset.cp_name
			});

			let data = {
				'date': date
				,'time': time
				,'room': room
				,'benefit': benefit
				,'mileage': mileage
				,'treatment_shop': ts
				,'treatment_shop_fir_cate_no': ts_fir
				,'treatment_shop_sec_cate_no': ts_sec
				,'treatment_item': its
				,'treatment_item_fir_cate_no': its_fir
				,'treatment_item_sec_cate_no': its_sec
				,'is_counsel': isC
				,'treatment_minute': treatment_minute
				,'basket_yn': basket_yn
				,'it_ts': it_ts
				,'res_number': res_number
				,'memo': memo
			};

			$.ajax({
				url: '/reservation/setReservation',
				data: data,
				method: 'post',
				dataType:'json',
				success:function(result){
					if (result.checked == 'failed') {
						alertViewThreeShow('어떡하죠?<br>선택한 시간의 예약이 마감되었어요<br>다시 확인 후, 예약해 주세요', 'locationReload');
					}else if(result.checked == 'duplication'){
						alertViewDoubleShow('예약일 기준 하루 1회만 예약 가능합니다.<br>예약일 변경 후, 다시 예약해 주세요', 'locationReload');
					} else {
						if (result.benefitUsed == 'used') {
							alertViewDoubleShow('이미 사용된 혜택이예요<br>다시 확인해 주세요', '');
						} else if (result.eventUsed == 'used') {
							alertViewDoubleShow('1회 체험가 상품을 이미 이용하셨어요<br>다시 확인 후, 예약해 주세요', 'locationSurgery');
						} else {
							location.href = '/reservation/resComplete?r_number=' + result.r_number;
						}
					}
				}
			});
		} else {
			alertViewShow('시간을 선택해주세요', 'couponList');
		}

	}

	function resDayChangeCounsel(){
		alertViewShow('시간을 선택해 주세요.', 'resDay');
		let uid = $(".dateTarget.ReserveDate_selected__vHeMH").data('date');
		let index = $(".dateTarget.ReserveDate_selected__vHeMH").data('index');

		resDayChange(uid, index);
	}

	function resDayChange(uid, index){
		$(".m_i").removeClass('ReserveDate_selected__vHeMH');
		$(".m_i_"+uid).addClass('ReserveDate_selected__vHeMH');

		var indexCnt = 0;
		if ( index !== 0 ) {
			indexCnt = index - 1;
		} else {
			indexCnt = index;
		}
		swiper_caladar.slideTo(indexCnt, 500, false);

		$('#datepicker').val(uid);

		let ts = [];
		let its = [];
		let isC = 'Y';

		$("input[name='price[]']").each(function (k,v) {
			if (v.dataset.ts_has_yn === "Y") {
				its.push(v.dataset.cti_no);
			}  else {
				ts.push(v.dataset.ts_code);
			}
		});

		if ($("input[name='is_counsel']").length > 0) {
			isC = $("input[name='is_counsel']").prop('checked') ? 'N' : 'Y';
		} else if (its.length > 0 && ts.length === 0) {
			isC = 'N';
		}

		let data = { 'date' : uid, 'treatment_shop' : ts, 'treatment_item': its, 'is_counsel' : isC };
		sendAjaxHtml("/reservation/getResRoomTime", data, 'GET', 'timeView');
	}

	function resSetTime(index){
		if ($('.t_i_' + index).hasClass('timeButtonDisable')) {
			return false;
		} else {
			$('.t_i').removeClass('timeButtonEnableSelect');
			$('.t_i_' + index).addClass('timeButtonEnableSelect');
		}
	}

	function changeMonth() {
		let target = $(".ReserveDate_selected__vHeMH ").data('date');
		let date = new Date(target);

		// 다음 달로 이동
		date.setMonth(date.getMonth() + 1);
		let year = date.getFullYear();
		let month = ('0' + (date.getMonth() + 1)).slice(-2);

		let dateString = null;
		let index = null;

		// 1일부터 31일까지 순차적으로 탐색
		for (let d = 1; d <= 31; d++) {
			let day = ('0' + d).slice(-2);
			let tryDate = `${year}-${month}-${day}`;
			let $el = $(`[data-date=${tryDate}]`);

			// 유효한 날짜 + 휴무일이 아니면 선택
			if ($el.length && $el.data('ch_is_reservation') !== 0) {
				dateString = tryDate;
				index = $el.data('index');
				break;
			}
		}

		// 조건 만족 시 dayChange 호출
		if (dateString) {
			let max = new Date();
			max.setMonth(max.getMonth() + 3);

			if (new Date(dateString) <= max) {
				dayChange(dateString, index);
			}
		}
	}
</script>

