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
					<?if ( isset($coupponList) ) {?>
						<?// echo "<pre>"; print_r($pData[0]['couponList']); echo "</pre>"; ?>
						<?foreach ( $coupponList as $index => $row ) {?>
							<div class="SurgeryItems_surgery-item__CYpPR">
								<div class="SurgeryItems_item-name__HkIqD">
									<input
										type="checkbox"
										id="<?=$row['CPC_SQ']?>"
										name="item-name"
										class="couponCheckBoxFreeCls"
										data-proname="<?=$row['CPC_NAME']?>"
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
							onclick="productFreeCouponViewHide()"
						>
							취소
						</button>
						<button
							class="Button_btn__3u27s"
							type="button"
							style="width: calc(67.5% - 8px);"
							onclick="setProductFreeCoupon()"
						>적용</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="productFreeCouponViewHide()"></button>
</div>

<script>
	$(document).ready(function() {
		// $(".react-modal-sheet-container").toggle(100);
	})
</script>
