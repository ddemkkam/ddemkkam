<div class="ReserveComplete_reserveComplete__77sui">
	<input type="hidden" id="api_url" value="<?= $api_url ?>">
	<h3>
		<? if (isset($branch_check) && $branch_check) { ?>
		<em><?= $this->session->userdata('M_NAME') ?></em>님 <span>예약 검토 중</span><br><em class="width">예약 확정 시, 카톡 또는 문자로 알림이 발송됩니다.</em><br>잠시만 기다려주세요!
		<? } else { ?>
			<em><?= $this->session->userdata('M_NAME') ?></em>님 <br><em class="width"> 예약이 완료되었어요.</em><br>쁨에서 기다리고 있을게요!
		<? } ?>
		<span class="info">요청하신 예약 시간, 내용에 따라 시술 진행이 불가능한 경우 개별 연락드리겠습니다.</span>
	</h3>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="ReserveComplete_surgery-item-box__jUhtg">
		<? if (isset($list) && count($list) > 0) { ?>
		<div class="ReserveComplete_surgery-titlie__HIh0Z">
			<em>선택 시술</em>
			<button style="height: 50px;" onclick="resProViewText(this)">상세보기</button>
		</div>
		<div class="resProductView Divider_divider__pCsgd" style="background: rgb(246, 247, 249); height: unset;display: none;">
			<div style="width: 89%; margin: auto; padding-top: 16px; padding-bottom: 5px;">
				<?
				$totalPrice = 0;
				foreach ($list as $key => $value){ ?>
					<div class="SurgeryItems_surgery-items__-MM1k" style="overflow: visible; max-height: none; padding: 20px 16px 6px 16px; background-color: #ffffff; border-radius: 8px; border: solid 1px #e7e8eb; margin-top: 0px; margin-bottom: 10px;">
						<label style="font-size: 16px; font-weight: bold;">
							<?= $key ?>
						</label>
						<div class="SurgeryItems_surgery-item__CYpPR">
							<div>
								<?
								$total = 0;
								foreach ($value as $item) {
									$total += $item['event_ts_price'];
									?>
									<input type="hidden" name="ts" data-ts_code="<?= $item['ts_code'] ?>" data-cti_code="<?= $item['cti_code'] ?>" data-remain_yn="<?= $item['remain_yn'] ?>" data-sec_cate_code="<?= $item['sec_cate_code'] ?>" data-fir_cate_code="<?= $item['fir_cate_code'] ?>">
									<div class="just_product SurgeryItems_item-name__HkIqD" style=" margin: 18px 0 16px 0;">
										<table class="coupontable" style="width: 100%; margin-top: 10px;">
											<tr>
												<td style="font-size: 12px; color: #878e9c;"><?= $item['ts_name'] ?></td>
												<td style="text-align: right; font-size: 14px; color: #878e9c;"><?= number_format($item['event_ts_price']) ?> 원</td>
											</tr>
										</table>
									</div>
								<? } $totalPrice += $total; ?>
								<div style="text-align: right; font-size: 16px; font-weight: bold; color: #111; padding: 16px 0 16px; border-top: 1px dashed #e7e8eb;"><?= number_format($total) ?> 원</div>
							</div>
						</div>
					</div>
				<? } ?>

				<div class="Reserve_btn-area__gavvL" style="background: rgb(246, 247, 249);">
					<strong>결제 예상금액</strong>
					<p class="Reserve_total-price-box__Q94-J">
						<span class="txt">총 시술금액</span>
						<span class="Reserve_num__FbcCh"><?= isset($totalPrice) ? number_format($totalPrice) : '0' ?></span>
					</p>

					<ul class="Reserve_use-history__TJqsR">
						<li>혜택 사용<em><?=isset($benefit) && $benefit > 0 ? '-' . number_format($benefit) : '0'?></em></li>
						<li>마일리지 사용<em id="setUseMileage"><?= isset($mileage) && $mileage > 0 ? '-' . number_format($mileage) : '0' ?></em></li>
					</ul>
					<p class="Reserve_total-calculate-box__gRy6a">
						<span class="txt">총 결제 예상금액 <em class="price-vat">(VAT 별도)</em></span>
						<span class="Reserve_num__FbcCh"><?= isset($totalPrice) ? number_format($totalPrice - $benefit - $mileage) : '0' ?></span>
					</p>
					<p class="Reserve_noti__Q74Mi">*결제는 내원 시 진행돼요.</p>
				</div>
			</div>
		</div>
		<? } ?>
	</div>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="ReserveComplete_reserve-date__C2MYk">
		<? if (isset($check) && $check) { ?>
			<span>예약 신청 일자</span>
		<? } else {?>
			<span>예약 일자</span>
		<? } ?>
		<b><?= isset($date) ? $date : '' ?></b>
	</div>
	<div class="Divider_divider__pCsgd" style="margin: 0px -20px; background: rgb(246, 247, 249);"></div>
	<div class="ReserveComplete_minor-agree-box__-cq00">
		<div class="agree-title-wrap">
            <h4 class="agree-title">보호자 시술 동의서</h4>
            <a href="<?=isset($info['T_CONTEXT']) ? $info['T_CONTEXT'] : '' ?>">
                <button class="ReserveComplete_downloadBtn__JO8pY">다운로드</button>
                <img src="/common/homepage/img/common/icon-down.svg" alt="" class="img_download">
            </a>
        </div>
        <p>미성년자 예약 시 보호자 동의서를 작성해서 내원해주세요.</p>
        <p class="emphasis">*동의서 미지참 시 시술 진행이 불가할 수 있다는 점 양해 부탁드려요.</p>        
	</div>
	<div class="ReserveComplete_btn-area__YkWyz">
		<button class="edit-btn" onclick="modifyResViewShowFun()">예약수정</button>
		<a href="/">
			<button class="ReserveComplete_home-btn__5btIH">홈으로</button>
		</a>
	</div>
</div>


<div id="modifyResView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
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
						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111; line-height: 1.4;">예약 변경을 원하시나요?<br>변경이 필요한 부분을 선택해 주세요</li>
						<li>
							<div class="modifyDateBtnAll modifyDateBtnOn" data-id="date_modify" onclick="modifyDateBtnOnOffFun(this)" style="cursor: pointer;">날짜변경</div>
						</li>
						<li>
							<div class="modifyDateBtnAll modifyDateBtnOff" data-id="res_cancel" style="height: 85px; line-height: 15px; padding-top: 15px;cursor: pointer;" onclick="modifyDateBtnOnOffFun(this)">
								예약취소
								<div style="color: #878e9c; font-size: 12px; margin-top: 10px; line-height: 1.4;">예약 완료 후, 옵션 변경은 불가능해요.<br />취소 후 다시 예약해 주세요.</div>
							</div>
						</li>
						<li>
							<button class="modifyDateBtn" onclick="modifyReservation2('<?=isset($res_number) ? $res_number : ''?>')">확인</button>
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
						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111; line-height: 1.4;">
							<p id="message_target"><?= isset($date) ? $date : '' ?></p>
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
	function modifyResViewShowFun2(){
		$("#modifyResView").show();
	}

	function modifyReservation2(r_number){
		let id = '';
		$('.modifyDateBtnAll').each(function(){
			if ( $(this).hasClass('modifyDateBtnOn') ) {
				id = $(this).data('id');
			}
		});

		if ( id == "date_modify" ) {
			let tsCode = [];
			let remain = [];

			let eventTsCode = [];
			let eventTsSectCateCode = [];
			let eventRemain = [];

			$("input[name='ts']").each(function(index, item){
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
			$("#cancelResView").show();
		}

	}

	function cancelSend()
	{
		$.ajax({
			contentType: "application/json;",
			method: 'DELETE',
			url: $("#api_url").val(),
			dataType: 'json',
			success:function(result) {
				location.href = '/selectSurgery';
			}
		});
	}

	function cancelResViewHideFun(){
		$("#cancelResView").hide();
	}

	function resProViewText(that){
		if ( $('.resProductView').css('display') === 'block' ){
			$(".resProductView").hide();
			$(that).text('상세보기');
		} else {
			$(".resProductView").show();
			$(that).text('접기');
		}
	}
</script>
