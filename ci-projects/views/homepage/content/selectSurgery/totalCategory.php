
<div id="totalCategoryView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="totalCategoryViewHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<div class="Categorys_categorys__5pPjP">
					<h3>전체 카테고리</h3>
					<ul>
						<?if (isset($list)) { ?>
							<?
							$count = 0;
							foreach ($list as $key => $row) {
								$cateName = str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key));
								?>
								<li>
									<input type="radio" name="category" id="ra_<?= $cateName ?>" class=" b_m_i_b_m_i_<?= $cateName ?>" value="ra_<?= $cateName ?>" onclick="changeListClose('<?= $cateName ?>', <?= $count ?>, '<?= str_replace(" ", "", preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", key($row))) ?>')" <?= $count > 0 ? '' : 'checked' ?>>
									<label for="ra_<?= $cateName ?>"><?= $key ?></label>
								</li>
							<? $count++; } ?>
						<? } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="totalCategoryViewHideFun()"></button>
</div>

<script>
	function changeListClose(key, index, key2) {
		changeList(key, key2);
		let indexCnt = index !== 0 ? index - 1 : index;
		swiper_cate1.slideTo(indexCnt, 500, false);
		$("#totalCategoryView").hide();
	}
</script>
