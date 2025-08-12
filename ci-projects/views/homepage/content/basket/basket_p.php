<?php
/*//장바구니에 사용된 혜택 목록
if (isset($exception)) {
	foreach ($exception as $item) {
*/?><!--
		<input type="hidden" name="arr[]" value="<?php /*= $item */?>">
--><?php
/*	}
}
*/?>

<div id="productDetailView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(75% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="true" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="productDetailViewHide()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<div class="SurgeryItems_surgery-items__-MM1k">
					<? if (isset($list)) { ?>
						<? foreach ($list as $cate => $item) {
							$check = false;
							if (isset($basketList)) {
								foreach ($basketList as $basket) {
									if ($basket['ts_code'] == $item['ts_code']) {
										$check = true;
										break;
									}
								}
							} ?>
							<div id="<?=$item['ts_code']?>" class="SurgeryItems_surgery-item__CYpPR productList">
								<div class="just_product SurgeryItems_item-name__HkIqD">
									<input type="checkbox" id="basket_<?= $item['ts_code'] ?>" class="basketDetailProduct"
										   data-fir_cate_code="<?= $item['fir_cate_code'] ?>"
										   data-sec_cate_code="<?= $item['sec_cate_code'] ?>"
										   value="<?= $item['ts_code'] ?>"
										<?= isset($item['pay']) && $item['pay'] == '결제' ? '' : ($check ? 'checked' : '') ?>
										<?= isset($item['pay']) && $item['pay'] == '결제' ? 'disabled' : '' ?>
										   style="cursor: pointer;">
									<label for="basket_<?= $item['ts_code'] ?>" style="cursor: pointer;"><?= $item['ts_name'] ?></label>
									<? if (isset($item['pay']) && $item['pay'] == '결제') { ?>
										<p class="one-time">(1회 체험가 상품은 최초 1회만 이용이 가능해요.)</p>
									<? }?>
								</div>

								<div class="SurgeryItems_item-benefit__B-eVN">
									<table class="coupontable">
										<tr>
											<td>
												<div class="just_product SurgeryItems_item-price__mTk3p">
													<?if ( $item['fir_cate_code'] == 'event' ){?>
														<span class="SurgeryItems_discount-price__ti3vT"><?= number_format($item['event_ts_price']) ?>원</span>
														<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
															<span class="dpPrice SurgeryItems_discount-price__ti3vT" style="text-decoration: line-through;color:#d0d6de;margin-left:5px;"><?= number_format($item['ts_price']) ?>원</span>
															<span class="priceOri" style="margin-left:5px;"><?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%</span>
														<?}?>
													<?} else {?>
														<span class="dpPrice SurgeryItems_discount-price__ti3vT"><?= number_format($item['ts_price']) ?>원</span>
													<?}?>

												</div>
											</td>
											<!--<td class="rightTd">
												<button class="couponBox benefitChange" onclick="benefitView2('<?php /*= $item['TS_CODE'] */?>')" <?php /*= $targetBenefit && $item['benefit'] ? '' : 'style="display:none;"' */?>>혜택변경</button>
												<button class="couponBox benefitAdd" onclick="benefitView2('<?php /*= $item['TS_CODE'] */?>')" <?php /*= $targetBenefit && $item['benefit'] ? '' : 'style="display:none;"' */?>>혜택적용</button>
												<button class="couponCancel benefitCancel" onclick="benefitCancle2('<?php /*= $item['TS_CODE'] */?>')" <?php /*= $targetBenefit ? '' : 'style="display:none;"' */?>>적용취소</button>
											</td>-->
										</tr>
										<!--<tr class="dcPrice" <?php /*= $dcPrice > 0 ? '' : 'style="display:none"' */?> data-id="<?php /*= $dcPrice > 0 ? $value['benefit']['CPC_SQ'] : '' */?>">
											<td style="padding-top: 10px;">
												<div class="just_product SurgeryItems_item-price__mTk3p">
													<p class="couponJText SurgeryItems_membership-price__jyjvP">혜택 할인가 <em><?php /*= number_format($dcPrice) */?> 원</em></p>
												</div>
											</td>
										</tr>-->
									</table>
								</div>
							</div>
					<?
						}
					}
					?>
				</div>
				<div class="SurgeryItems_total-price-box__H6NKl">
					<div class="SurgeryItems_btn-wrap__VdsxS">
						<button class="Button_btn__3u27s" type="button" style="width: 100%;" onclick="basketAdd('<?= $cateCode ?>')">장바구니</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="productDetailViewHide()"></button>
</div>

<?/*
if (isset($data)) {
	foreach ($data['data'] as $cate => $item) {
*/?><!--
	<div id="xpop_<?php /*= $item['TS_CODE'] */?>" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;display: none;">
		<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(55% - env(safe-area-inset-top) - 34px); transform: none;">
			<div draggable="true" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
				<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
					<span class="react-modal-sheet-drag-indicator" style="width: 18px; height: 4px; border-radius: 99px; background-color: rgb(221, 221, 221); transform: translateX(2px) rotate(0deg);"></span>
					<span class="react-modal-sheet-drag-indicator" style="width: 18px; height: 4px; border-radius: 99px; background-color: rgb(221, 221, 221); transform: translateX(-2px) rotate(0deg);"></span>
				</div>
			</div>
			<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
				<div style="height: auto; padding: 20px;">
					<div class="SurgeryItems_surgery-items__-MM1k benefitDetailList">
						<?/* foreach($item['benefitDetail'] as $benefitDetail) { */?>
							<div class="SurgeryItems_surgery-item__CYpPR benefitDetailList_<?php /*=$benefitDetail['CPC_SQ']*/?>">
								<div class="SurgeryItems_item-name__HkIqD">
									<input type="checkbox" id="xpop_<?php /*= $item['TS_CODE'] */?>_<?php /*=$benefitDetail['CPC_SQ']*/?>"
										   class="benefitCheckBox" onclick="benefitCheck2('<?php /*= $item['TS_CODE'] */?>', '<?php /*=$benefitDetail['CPC_SQ']*/?>')"
										   value="<?php /*=$benefitDetail['CPC_SQ']*/?>"
										   data-type="<?php /*=$benefitDetail['CP_DISCOUNT_TYPE']*/?>"
										   data-price="<?php /*=$benefitDetail['CP_DISCOUNT_PRICE']*/?>"
										   data-per="<?php /*=$benefitDetail['CP_DISCOUNT_PER']*/?>"
										   data-maxprice="<?php /*=$benefitDetail['CP_DISCOUNT_MAX_PRICE']*/?>"
										   data-minprice="<?php /*=$benefitDetail['CP_DISCOUNT_MIN_PRICE']*/?>"
									>
									<label for="xpop_<?php /*= $item['TS_CODE'] */?>_<?php /*=$benefitDetail['CPC_SQ']*/?>" style="cursor: pointer;"><?php /*=$benefitDetail['CP_NAME']*/?></label>
								</div>
							</div>
						<?/* } */?>
					</div>

					<div class="SurgeryItems_total-price-box__H6NKl">
						<div class="SurgeryItems_btn-wrap__VdsxS">
							<button class="Button_btn__3u27s" type="button" style="float: left; width: 32.5%; border: 1px solid var(--color-primary); background: rgb(255, 255, 255); color: var(--color-primary); margin-right: 8px;" onclick="benefitCancel('<?php /*= $item['TS_CODE'] */?>')">취소</button>
							<button class="Button_btn__3u27s" type="button" style="width: calc(67.5% - 8px);" onclick="benefitAdd('<?php /*= $item['TS_CODE'] */?>')">적용</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="benefitCancel('<?php /*= $item['TS_CODE'] */?>')"></button>
	</div>
--><?/*
	}
}
*/?>
<script>
	/*$( document ).ready(function() {
		$("input[name='arr[]']").each(function(k,i) {
			$(".benefitDetailList_" + i.value).hide();
		});

		resetButton();
	});
	function benefitView2(tsCode, cateCode) {
		$("#xpop_" + tsCode).show();
		$("#productDetailView").hide();
	}

	function benefitCancel(tsCode){
		$("#productDetailView").show();
		$("#xpop_" + tsCode).hide();
	}

	function benefitCheck2(tsCode, cpSq) {
		let target = $("#xpop_" + tsCode + "_" + cpSq);

		if (!target.prop('checked')) {
			target.prop('checked', false);
		} else {
			$("#xpop_" + tsCode + " input[type='checkbox']:checked").each(function(e, i) {
				i.checked = false;
			});

			target.prop('checked', true);
		}
	}

	function benefitAdd(tsCode) {
		if ($("#" + tsCode + " .dcPrice")[0].dataset.id != '') {
			$(".benefitDetailList_" + $("#" + tsCode + " .dcPrice")[0].dataset.id).show();
		}

		let cpSq = $("#xpop_" + tsCode + " input[type='checkbox']:checked").val();
		let dcPrice = Number($("#" + tsCode + " .dpPrice").text().replace(/[^0-9]/g, '')) * ((100 - Number( $("#xpop_" + tsCode + " input[type='checkbox']:checked")[0].dataset.per)) * 0.01);

		$("#" + tsCode + " .dcPrice")[0].dataset.id = cpSq;
		$("#" + tsCode + " .dcPrice em").text($.number(dcPrice) + " 원");
		$("#" + tsCode + " .dcPrice").show();

		$(".benefitDetailList_" + cpSq).hide();

		resetButton();

		benefitCancel(tsCode);
	}

	function benefitCancle2(tsCode) {
		$(".benefitDetailList_" + $("#" + tsCode + " .dcPrice")[0].dataset.id).show();
		$("#" + tsCode + " .dcPrice")[0].dataset.id = '';
		$("#productDetailView #" + tsCode + " .dcPrice em").text('');
		$("#productDetailView #" + tsCode + " .dcPrice").hide();

		resetButton();
	}

	function resetButton()
	{
		$(".productList").each(function(key, value) {
			let count = 0;
			$("#xpop_" + value.id + " .benefitDetailList > div").each(function(k,v) {
				if (v.style.display != 'none') count += 1;
			});

			let checkDc = $("#" + value.id + " .dcPrice")[0].style.display != 'none';
			if (count > 0) {
				if (checkDc) {
					$("#" + value.id + " .benefitAdd").hide();
					$("#" + value.id + " .benefitChange").show();
					$("#" + value.id + " .benefitCancel").show();
				} else {
					$("#" + value.id + " .benefitAdd").show();
					$("#" + value.id + " .benefitChange").hide();
					$("#" + value.id + " .benefitCancel").hide();
				}
			} else {
				$("#" + value.id + " .benefitAdd").hide();
				$("#" + value.id + " .benefitChange").hide();
				if (checkDc) {
					$("#" + value.id + " .benefitCancel").show();
				} else {
					$("#" + value.id + " .benefitCancel").hide();
				}
			}
		});
	}*/

	function basketAdd(cate_code)
	{
		let arr = [];
		$(".productList input[type='checkbox']:checked").each(function (k, v) {
			arr.push({
				'ts_code': v.value
				,'fir_cate_code': v.dataset.fir_cate_code
				,'sec_cate_code': v.dataset.sec_cate_code
			});
		});

		sendAjaxHtml("/basket/setBasket", { 'list': arr, 'cate_code': cate_code }, 'POST', 'productDetailViewback');
		setTimeout(function () { location.reload(); }, 500);
	}

</script>
