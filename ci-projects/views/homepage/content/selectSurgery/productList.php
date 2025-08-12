<div class="SelectSurgery_surgery-item-box__FZCzG">
	<?if ( isset($subCategory) ) {
		foreach ( $subCategory as $index => $row ) { ?>
			<div class="SelectSurgery_surgery-item__qcCf+" id="613">
				<p class="SelectSurgery_title__IJf-P"><?=$row['EVENT_NAME']?> </p>
				<div class="SelectSurgery_headline__EPqv0">
					<?=isset($row['CONTENT']) ? $row['CONTENT'] : ''?>
				</div>
				<button class="proAddBtn SelectSurgery_more__YTC3C" onclick="proAddBtnFun(this)">더보기</button>
				<div class="proAddView SelectSurgery_detail__FHOtA">
					<p class="SelectSurgery_category__su2pv">
						<?=isset($row['OP_NM']) ? $row['OP_NM'] : ''?>
					</p>
					<div class="SelectSurgery_desc__qiST2">
						<?=isset($row['CONTENT']) ? str_replace("\n", "<br />", $row['CONTENT']) : ''?>
					</div>
					<p class="SelectSurgery_info__haZi4">시술정보</p>
					<dl>
						<?if ( isset($row['DESCRIPTION']) ) {?>
							<?foreach ( $row['DESCRIPTION'] as $index2 => $row2 ){?>
								<div>
									<dt><?=$row2['name']?></dt>
									<dd><?=$row2['content']?></dd>
								</div>
							<?}?>
						<?}?>
					</dl>
					<?if ( isset($row['HASHTAG']) && count($row['HASHTAG']) > 0 ) {?>
						<ul>
							<?foreach ( $row['HASHTAG'] as $index2 => $row2 ){
								if ( $index2 !== 0 ){?>
								<li><?='#'.$row2?></li>
								<?}?>
							<?}?>
						</ul>
					<?}?>
				</div>
				<div class="SelectSurgery_select-box__0sLK7">
					<span>
						<?
						if ( isset($row['EVENT_PRICE']) && $row['EVENT_PRICE'] > 0 ){
							echo number_format($row['EVENT_PRICE']).' ~ ';
						} else {
							echo number_format($row['PRICE']).' ~ ';
						}
						?>
					</span>
					<?//if ( isset($type) && $type !== 'event' ) {?>
						<button onclick="productDetailViewShow('<?=$row['DEPTH1SQ']?>', '<?=$row['DEPTH2SQ']?>')">선택</button>
					<?//}?>
				</div>
			</div>
	<?	}
	}?>
</div>
