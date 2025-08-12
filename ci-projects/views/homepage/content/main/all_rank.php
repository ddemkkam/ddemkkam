
<div id="allRankView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<div class="react-modal-sheet-container " style="height: 70%; overflow-y: auto; z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="allRankHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto; overflow-x: hidden;">
			<div style="height: auto; padding: 20px 16px 16px;">
				<div class="Categorys_categorys__5pPjP">
					<h3>실시간 인기 검색 순위</h3>
					<? if (isset($rankList)) {
						$ii = 0;?>
						<table style="margin-top: 8px;">
							<? foreach ($rankList as $key => $value) {
								if ( isset($value) ){?>
									<tr>
										<td style="width: 24px; text-align: center;">
											<div class="mainAllRankDivSetNumber">
												<?
												echo $ii + 1;
												$ii++;
												?>
											</div>
										</td>
										<td style="padding-left: 16px; cursor: pointer;" onclick="location.href='/search?text=<?= base64_encode(urlencode($value['ts_name'])) ?>'">
											<div class="mainAllRankDivSetText1 ranking-title">
												<?= $value['ts_name'] ?>
											</div>
											<div class="mainAllRankDivSetText1">
												<? if ( $value['product_type'] == 'event' ) { ?>
													<? if ( $value['event_ts_price'] != $value['ts_price'] && $value['event_ts_price'] < $value['ts_price'] ) { ?>
														<span class="mainAllRankDivSetEventPrice">
															<?= number_format($value['event_ts_price']) ?>원
														</span>
														<span class="mainAllRankDivSetTsPrice"><?= number_format($value['ts_price']) ?>원</span>
														<span class="mainAllRankDivSetTsPer"><?= number_format(floor(100 - (($value['event_ts_price'] / ($value['ts_price'] > 0 ? $value['ts_price'] : 1)) * 100))) ?>%</span>
													<? } ?>
												<?} else {?>
													<? if ( $value['event_ts_price'] != $value['ts_price'] && $value['event_ts_price'] > $value['ts_price'] ) { ?>
														<span class="mainAllRankDivSetEventPrice">
															<?= number_format($value['ts_price']) ?>원
														</span>
														<span class="mainAllRankDivSetTsPrice"><?= number_format($value['event_ts_price']) ?>원</span>
														<span class="mainAllRankDivSetTsPer"><?= number_format(floor(100 - (($value['ts_price'] / ($value['event_ts_price'] > 0 ? $value['event_ts_price'] : 1)) * 100))) ?>%</span>
													<? } else { ?>
														<span class="mainAllRankDivSetEventPrice">
															<?= number_format($value['ts_price']) ?>원
														</span>
													<? } ?>
												<? } ?>
											</div>
										</td>
									</tr>
								<?}?>
							<?}?>
						</table>
					<?}?>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="allRankHideFun()"></button>
</div>

<script>

</script>
