<div class="MyReserve_myReserve__IDxT4">
	<? if(isset($data) && count($data) > 0) { ?>
		<? foreach($data as $key => $value) { ?>
            <div class="<?= $value['state'] == '취소' ? 'MyReserve_cancel__U092s' : '' ?> myReserve_box">
				<? if ($value['state'] == '취소') { ?>
					<p style=color:#f03e3e;>[취소]예약일자 : <span id="message_<?= $value['r_number'] ?>"><?= $value['date'] ?></span></p>
				<? } else { ?>
					<? if (isset($branch_check) && $branch_check) { ?>
						<? if ($value['confirm'] == '확정') { ?>
							<p>예약일자 : <span id="message_<?= $value['r_number'] ?>"><?= $value['date'] ?></span></p>
						<? } else { ?>
							<p style=color:#0000ff;>[예약검토중]예약일자 : <span id="message_<?= $value['r_number'] ?>"><?= $value['date'] ?></span></p>
						<? } ?>
					<? } else { ?>
						<p>예약일자 : <span id="message_<?= $value['r_number'] ?>"><?= $value['date'] ?></span></p>
					<? } ?>
				<? } ?>
				<!--<p <?/* if($value['state'] == '취소') { echo 'style=color:#f03e3e;'; } */?>><?php /*= $value['state'] == '취소' ? '[취소] ' : '' */?>예약일자 : <span id="message_<?php /*= $value['r_number'] */?>"><?php /*= $value['date'] */?></span></p>-->
				<!-- 예약검토중 추가 -->
				<!--<p <?/* if($value['state'] == '예약검토중') { echo 'style=color:#0000ff;'; } */?>><?php /*= $value['state'] == '예약검토중' ? '[예약검토중] ' : '' */?>예약일자 : <span id="message_<?php /*= $value['r_number'] */?>"><?php /*= $value['date'] */?></span></p>-->
				<ul class="benefit-list">
					<?
					$total = 0;
					foreach ($value['list'] as $cate => $tsList) { ?>
						<li class="items">
							<div style="margin-bottom: 14px">
								<span class="MyReserve_left__xEryM" style="font-size: 12px;"><?= $cate ?></span>
							</div>
							<? foreach ($tsList as $item) {
								$total += $item['event_ts_price'];
								?>
								<input type="hidden" name="ts_<?= $key ?>" data-ts_code="<?= $item['ts_code'] ?>" data-cti_code="<?= $item['cti_code'] ?>" data-remain_yn="<?= $item['remain_yn'] ?>" data-sec_cate_code="<?= $item['sec_cate_code'] ?>" data-fir_cate_code="<?= $item['fir_cate_code'] ?>">
								<div>
									<div class="item-name-box">
										<span class="MyReserve_left__xEryM list_name">
											<span><?= $item['ts_name'] ?></span>
										</span>
									</div>

									<div class="item-price-box">
										<?// if ($item['fir_cate_code'] == 'event' && $item['ts_price'] > 0) { ?>
										<? if ( $item['ts_price'] != $item['event_ts_price'] ) { ?>
											<span><?= number_format($item['event_ts_price']) ?>원</span>
											<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
												<span>
                                                    <span class="priceOri">
                                                        <?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%
                                                    </span>
                                                    <span class="priceDecoration"><?= number_format($item['ts_price']) ?>원</span>
                                                </span>                                                
											<?}?>
										<? } else { ?>
											<span><?= number_format($item['ts_price']) ?>원</span>
										<? } ?>
									</div>
								</div>
							<? } ?>
						</li>
					<? } ?>
				</ul>
				<div class="MyReserve_totalPrice__S4P+r">
					<div style="margin: 14px 0 8px 0;">
                        <span style="font-size: 12px;font-weight: bold;">총 결제 예상금액</span>
                        <em><?= number_format($total - $value['cpc'] - $value['mileage']) ?> 원</em>
					</div>
					<span style="font-size: 12px; color: #878e9c">* 결제는 내원 시 진행돼요. (VAT 별도)</span>
				</div>

				<? if ($value['state'] == '예약') { ?>
					<div class="MyReserve_btn-area__zVSnX">
						<button type="button" class="MyReserve_cancel__U092s" id="cancel_<?= $value['r_number'] ?>" onclick="cancel('<?= $api_url ?>', '<?= $value['r_number'] ?>')">예약취소</button>
						<button type="button" class="MyReserve_change__VZRK6" onclick="change('<?= $value['r_number'] ?>', 'ts_<?= $key ?>')">예약변경</button>
					</div>
				<? } ?>
			</div>
		<? } ?>
	<? } else { ?>
		<div style="height: 76px"></div>
		<div class="Cart_cart-empty__7e2tB">
			<div class="MyNone_my-none__9dt6+">
				<img src="/common/homepage/img/basket/ico_none.svg" alt="">
				<p class="MyNone_my-none__text__24uCV">예약 정보가 없어요</p>
				<a href="selectSurgery">
					<button class="MyNone_btn-white__kyEV8">예약하기</button>
				</a>
			</div>
		</div>
	<? } ?>
</div>

<div id="modifyResView" style="position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<input type="hidden" name="target">
	<input type="hidden" name="r_number">
	<div class="react-modal-sheet-container " style="height: 400px; z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
				<span class="react-modal-sheet-drag-indicator" onclick="modifyResViewHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; /*overflow-y: auto;*/">
			<div style="height: auto; padding: 20px;">
				<div class="">
					<ul>

						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111;">
							예약 변경을 원하시나요?
							<br />
							변경이 필요한 부분을 선택해 주세요
						</li>

						<li>
							<div class="modifyDateBtnAll modifyDateBtnOff" id="date_modify" data-id="date_modify" onclick="modifyDateBtnOnOffFun(this)">
								날짜변경
							</div>
						</li>

						<li>
							<div
								class="modifyDateBtnAll modifyDateBtnOff"
								id="res_cancel"
								data-id="res_cancel"
								style="height: 85px; line-height: 15px; padding-top: 15px;"
								onclick="modifyDateBtnOnOffFun(this)">
								예약취소
								<div style="color: #878e9c; font-size: 12px; margin-top: 10px;">예약 완료 후, 옵션 변경은 불가능해요.<br>취소 후 다시 예약해 주세요.</div>
							</div>
						</li>

						<li>
							<button class="modifyDateBtn" onclick="send()">확인</button>
						</li>

					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="modifyResViewHideFun()"></button>
</div>

<div id="cancelResView" style="position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<input type="hidden" name="cancel_target">
	<div class="react-modal-sheet-container " style="height: 200px; z-index: 2; position: absolute; left: 0; bottom: 0; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
				<span class="react-modal-sheet-drag-indicator" onclick="cancelResViewHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; /*overflow-y: auto;*/">
			<div style="height: auto; padding: 20px;">
				<div class="">
					<ul>
						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111;">
							<p id="message_target"></p>
							<p>예약을 정말 취소 할까요?</p>
						</li>
						<li>
							<button class="modifyDateBtn" style="width: 49%;float: left;background-color:rgb(255,238,245);color:#cf2f75;" onclick="cancelSend()">예</button>
							<button class="modifyDateBtn" style="width: 49%;float: right;" onclick="cancelResViewHideFun()">아니오</button>
						</li>

					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="cancelResViewHideFun()"></button>
</div>

<script>
	function nonReservationChecked(r_number)
	{
		let res = false;

		$.ajax({
			method: 'post',
			url: '/mypage/nonReservationChecked',
			data: {
				'rNumber' : r_number
			},
			dataType: 'json',
			success:function(result) {
				if (result.nonReservationChecked == 'true') {
					res = true;
				}
			}
		});

		return res;
	}

	function change(r_number, target)
	{
		if (nonReservationChecked(r_number)) {
			alertViewShow('해당 예약은 미 방문 처리되었습니다.','locationReload');
		} else {
			$('.modifyDateBtnAll').removeClass('modifyDateBtnOn');
			$('#date_modify').addClass('modifyDateBtnOn');
			$("#modifyResView").show();
			$("input[name='target']").val(target);
			$("input[name='r_number']").val(r_number);
		}
	}

	function send()
	{
		let target = $("input[name='target']").val();
		let r_number = $("input[name='r_number']").val();

		//날짜변경,예약취소 여부 확인
		if ($(".modifyDateBtnOn")[0].id == 'date_modify') {
			let tsCode = [];
			let remain = [];

			let eventTsCode = [];
			let eventTsSectCateCode = [];
			let eventRemain = [];

			$("input[name='" + target + "']").each(function(index, item){
				if (item.dataset.sec_cate_code.indexOf('EVT') !== -1) {
					eventTsCode.push(item.dataset.ts_code);
					eventTsSectCateCode.push(item.dataset.sec_cate_code);
					eventRemain.push(item.dataset.remain_yn === 'Y' ? item.dataset.cti_code : '');
				} else {
					tsCode.push(item.dataset.ts_code);
					remain.push(item.dataset.remain_yn === 'Y' ? item.dataset.cti_code : '');
				}
			});

			document.cookie = 'resTsCode=' + tsCode.join() + ';path=/';
			document.cookie = 'resRemain=' + remain.join() + ';path=/';
			document.cookie = 'resEventTsCode=' + eventTsCode.join() + ';path=/';
			document.cookie = 'resEventTsSectCateCode=' + eventTsSectCateCode.join() + ';path=/';
			document.cookie = 'resEventRemain=' + eventRemain.join() + ';path=/';
			document.cookie = 'resBasketYn=N;path=/';
			document.cookie = "resNumber=" + r_number + ';path=/';
			window.location = "/reservation";
		} else {
			modifyResViewHideFun();
			$("#cancel_" + r_number).click();
		}

	}

	function cancel(api_url, r_number)
	{
		if (nonReservationChecked(r_number)) {
			alertViewShow('해당 예약은 미 방문 처리되었습니다.','locationReload');
		} else {
			$("#cancelResView").show();
			$("#message_target").text($("#message_" + r_number).text());
			$("input[name='cancel_target']").val(api_url + r_number);
		}
	}

	function cancelSend()
	{
		$.ajax({
			contentType: "application/json;",
			method: 'DELETE',
			url: $("input[name='cancel_target']").val(),
			dataType: 'json',
			success:function(result) {
				mapagePageMove('res');
			}
		});
	}

	function cancelResViewHideFun(){
		$("#cancelResView").hide();
	}
</script>
