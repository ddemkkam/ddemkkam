<div id="productCouponView" style=" position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(55% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="true" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="productCouponViewHide()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<div class="SurgeryItems_surgery-items__-MM1k">
					<?if ( isset($pData) ) {?>
						<?// echo "<pre>"; print_r($pData[0]['couponList']); echo "</pre>"; ?>
						<?foreach ( $pData as $index => $row ) {?>
							<div class="SurgeryItems_surgery-item__CYpPR">
								<div class="SurgeryItems_item-name__HkIqD">
									<input
										type="checkbox"
										id="<?=$row['CPC_SQ']?>"
										data-id="<?=$row['CPC_SQ']?>"
										data-is_overlap="<?=$row['CPC_IS_OVERLAP']?>"
										data-discount_type="<?=$row['CPC_DISCOUNT_TYPE']?>"
										data-discount_price_type="<?=$row['CPC_DISCOUNT_PRICE_TYPE']?>"
										data-discount_category1_seq="<?=$row['CPC_DISCOUNT_CATEGORY1_SQ']?>"
										data-discount_category2_seq="<?=$row['CPC_DISCOUNT_CATEGORY2_SQ']?>"
										data-discount_per="<?=$row['CPC_DISCOUNT_PER']?>"
										data-discount_price="<?=$row['CPC_DISCOUNT_PRICE']?>"
										data-discount_max_price="<?=$row['CPC_DISCOUNT_MAX_PRICE']?>"
										data-discount_min_price="<?=$row['CPC_DISCOUNT_MIN_PRICE']?>"
										data-discount_shop_code="<?=$row['CPC_DISCOUNT_SHOP_CODE']?>"
										data-discont_event_code="<?=$row['CPC_DISCOUNT_EVENT_CODE']?>"
										<?=isset($row['checked']) ? $row['checked'] : '' ?>
										name="item-name"
										class="couponCheckBoxCls"
										onclick="couponCheckBox('<?=$row['CPC_SQ']?>', '<?=$row['CPC_IS_OVERLAP']?>')"
										value="<?=$row['CPC_SQ']?>"
									/>
									<label
										for="<?=$row['CPC_SQ']?>"
										onclick="couponCheckBox('<?=$row['CPC_SQ']?>', '<?=$row['CPC_IS_OVERLAP']?>')"
									>
										<?=$row['CPC_NAME']?>
										<?=$row['CPC_IS_OVERLAP'] === '1' ? '(중복가능)' : '(중복불가)' ?>
									</label>
								</div>
							</div>
						<?}?>
					<?}?>
				</div>

				<div class="SurgeryItems_total-price-box__H6NKl">
					<div class="SurgeryItems_btn-wrap__VdsxS">
						<button
							class="Button_btn__3u27s"
							type="button"
							style="float: left; width: 32.5%; border: 1px solid var(--color-primary); background: rgb(255, 255, 255); color: var(--color-primary); margin-right: 8px;"
							onclick="productCouponViewHide()"
						>
							취소
						</button>
						<button
							class="Button_btn__3u27s"
							type="button"
							style="width: calc(67.5% - 8px);"
							onclick="saveCouponToProduct('<?=isset($code) ? $code : ''?>')"
						>적용</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="productCouponViewHide()"></button>
</div>

<script>
	$(document).ready(function() {
		// $(".react-modal-sheet-container").toggle(100);
	})
</script>
