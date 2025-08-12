<?php if (isset($list) && count($list) > 0) { ?>
	<? foreach($list as $key2 => $value2) { ?>
		<div id="prd_<?= str_replace(" ","",preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $key2)); ?>" class="productList">
			<p class="product-title"><?= $key2 ?></p>
			<div class="SelectSurgery_surgery-item-box__FZCzG">
				<? foreach($value2 as $item) { ?>
					<div class="SelectSurgery_surgery-item__qcCf+">
						<div class="SurgeryItems_surgery-item__CYpPR">
							<input id="prd_<?= $item['fir_cate_name_group'] . '_' . $item['fir_cate_code'] . '_' . $item['ts_code'] ?>" type="checkbox" autocomplete="off"
								   data-fir="<?= $item['fir_cate_code'] ?>"
								   data-sec="<?= $item['sec_cate_code'] ?>"
								   data-code="<?= $item['ts_code'] ?>"
								   data-name="<?= $item['ts_name'] ?>"
								   data-end="<?= $item['tse_end_datetime'] ?>"
								   data-price="<?= $item['event_ts_price'] != $item['ts_price'] ? $item['event_ts_price'] : $item['ts_price'] ?>"
								   onclick="productListCheckEvent(this)"
								<?= isset($item['pay']) && $item['pay'] == '결제' ? 'disabled' : '' ?>
							>
							<label for="prd_<?= $item['fir_cate_name_group'] . '_' . $item['fir_cate_code'] . '_' .$item['ts_code'] ?>" style="cursor: pointer;"><?= $item['ts_name'] ?></label>
							<? if (isset($item['pay']) && $item['pay'] == '결제') { ?>
								<p class="one-time">(1회 체험가 상품은 최초 1회만 이용이 가능해요.)</p>
							<? }?>
						</div>
						<div class="surgery_info_box">
							<div class="SelectSurgery_headline__EPqv0"><?= $item['ts_comment'] ?></div>
							<button class="proAddBtn SelectSurgery_more__YTC3C" onclick="proAddBtnFun(this)">더보기</button>
							<div class="proAddView SelectSurgery_detail__FHOtA">
								<p class="SelectSurgery_info__haZi4">시술정보</p>
								<dl>
									<dd><?= nl2br($item['ts_comment']) ?></dd>
								</dl>
								<ul>
									<? foreach(explode('#', $item['ts_hash']) as $key => $hash) { ?>
										<? if ($key > 0) { ?>
											<li><?= '#' . $hash ?></li>
										<? } ?>
									<? } ?>
								</ul>
							</div>
						</div>
						<div class="SelectSurgery_select-box__0sLK7">
							<?if ( $item['fir_cate_code'] == 'event' ){?>
								<span><?= number_format($item['event_ts_price']) ?>원</span>
								<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
									<span class="priceDecoration"><?= number_format($item['ts_price']) ?>원</span>
									<span class="priceOri"><?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%</span>
								<?}?>
							<?} else {?>
								<span><?= number_format($item['ts_price']) ?>원</span>
							<?}?>
						</div>
					</div>
				<? } ?>
			</div>
		</div>
	<? } ?>
<?php } else { ?>
	<div style="height: 200px;">
		<p class="no-result">일치하는 결과가 없어요</p>
	</div>
<?php } ?>
