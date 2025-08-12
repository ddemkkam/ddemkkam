<div class="SearchAutoComplete_search__x3uio">
	<form action="" method="post">
		<div class="SearchAutoComplete_search-box__FOWLz">
			<input type="text" id="inputSearch" placeholder="찾고 계신 상품/시술을 입력해 주세요" value="<?= isset($text) ? $text : '' ?>">
			<button type="button" id="clearBtn" class="SearchAutoComplete_clear-btn__Zw7yT" aria-label="Clear input" style="<?= isset($text) && !empty($text) ? 'display: block;' : 'display: none;' ?>">Clear</button>
			<button type="button" onclick="searchEvent()" class="SearchAutoComplete_submit-btn__jlEBI">검색</button>
		</div>
	</form>
	<div id="searchTextView" class="SelectSurgery_surgery-item-box__FZCzG productList">
		<? if (isset($productList) && count($productList) > 0) { ?>
			<? foreach($productList as $key2 => $value2) { ?>
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
											<?
												if ( $item['fir_cate_code'] == 'event' ) {
													$tts_price = $item['event_ts_price'];
												} else {
													if ( $item['ts_type'] == '멤버쉽' ) {
														$tts_price = $item['event_ts_price'];
													} else {
														if ( $item['event_ts_price'] != $item['ts_price'] ) {
															$tts_price = $item['ts_price'];
														} else {
															$tts_price = $item['ts_price'];
														}
													}
												}
											?>
										   data-price="<?= $tts_price ?>"
										   onclick="productListCheckEvent(this)"
										<?= isset($item['pay']) && $item['pay'] == '결제' ? 'disabled' : '' ?>
									>
									<label for="prd_<?= $item['fir_cate_name_group'] . '_' . $item['fir_cate_code'] . '_' .$item['ts_code'] ?>" style="cursor: pointer;"><?= $item['ts_name'] ?></label>
									<? if (isset($item['pay']) && $item['pay'] == '결제') { ?>
										<p class="one-time">(1회 체험가 상품은 최초 1회만 이용이 가능해요.)</p>
									<? }?>
								</div>

								<div class="surgery_info_box">
									<div class="SelectSurgery_headline__EPqv0">
										<?= !empty($item['ts_content'])
											? $item['ts_content']
											: strip_tags($item['ts_comment']) ?>
									</div>

									<?if ( $item['ts_type'] != '멤버쉽' ) {?>
										<button class="proAddBtn SelectSurgery_more__YTC3C" onclick="proAddBtnFun(this)">더보기</button>
									<?}?>

									<div class="proAddView SelectSurgery_detail__FHOtA">
										<p class="SelectSurgery_info__haZi4">시술정보</p>
										<dl>
											<dd><?= nl2br($item['ts_comment']) ?></dd>
											<dd>
												<ul style="margin-top:15px;">
													<? foreach(explode('#', $item['ts_hash']) as $key => $hash) { ?>
														<? if ($key > 0) { ?>
															<li><?= '#' . $hash ?></li>
														<? } ?>
													<? } ?>
												</ul>
											</dd>
										</dl>
										<?
										$desc = json_decode($item['ts_desc'], true);
										if (!empty($desc)) { ?>
											<dl>
												<? foreach($desc as $info): ?>
													<dt><?= htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8') ?></dt>
													<dd><?= nl2br(htmlspecialchars($info['content'], ENT_QUOTES, 'UTF-8')) ?></dd>
												<? endforeach; ?>
											</dl>
										<? } ?>
										<?
										$cautions = json_decode($item['ts_caution'], true);
										if (!empty($cautions)) { ?>
											<p class="SelectSurgery_info__haZi4">주의사항</p>
											<dl>
												<? foreach($cautions as $info): ?>
													<dd><?= nl2br(htmlspecialchars($info, ENT_QUOTES, 'UTF-8')) ?></dd>
												<? endforeach; ?>
											</dl>
										<? } ?>
									</div>
								</div>

								<div class="SelectSurgery_select-box__0sLK7" style="font-size:16px">
									<?if ( $item['fir_cate_code'] == 'event' ){?>
										<span><?= number_format($item['event_ts_price']) ?>원</span>
										<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] < $item['ts_price'] ) {?>
											<span class="priceDecoration"><?= number_format($item['ts_price']) ?>원</span>
											<span class="priceOri"><?= number_format(floor(100 - (($item['event_ts_price'] / ($item['ts_price'] > 0 ? $item['ts_price'] : 1)) * 100))) ?>%</span>
										<?}?>
									<?} else {?>
										<?if ( $item['ts_type'] == '멤버쉽' ) {?>
											<span><?= number_format($item['event_ts_price']) ?>원</span>
										<?} else {?>
											<?if ( $item['event_ts_price'] != $item['ts_price'] && $item['event_ts_price'] > $item['ts_price'] ) {?>
												<span><?= number_format($item['ts_price']) ?>원</span>
												<span class="priceDecoration"><?= number_format($item['event_ts_price']) ?>원</span>
												<span class="priceOri"><?= number_format(floor(100 - (($item['ts_price'] / ($item['event_ts_price'] > 0 ? $item['event_ts_price'] : 1)) * 100))) ?>%</span>
											<?} else {?>
												<span><?= number_format($item['ts_price']) ?>원</span>
											<?}?>
										<?}?>
									<?}?>
								</div>
							</div>
						<? } ?>
					</div>
				</div>
			<? } ?>
		<? } else { ?>
			<? if (!is_null($text)) { ?>
				<div style="height: 200px;">
					<p class="search-null">일치하는 결과가 없어요</p>
				</div>
			<? } ?>
		<? } ?>

	</div>
	<!--<ul  class="SearchAutoComplete_search-items__T9HkW">

	</ul>-->

	<? if (isset($hashTag) && count($hashTag) > 0) { ?>
		<div id="hash_tag_list" class="SearchAutoComplete_recommend-keyword__JZ+rp">
			<h3>고객님을 위한 추천 검색어예요</h3>

			<ul>
				<? foreach ($hashTag as $item) { ?>
					<li onclick="searchTag(this)" style="cursor: pointer;"><?= $item['tc_hash_tag'] ?></li>
				<? } ?>
			</ul>
		</div>
	<? } ?>

</div>

<style>
	.ui-autocomplete {
		position: absolute;
		left: 50%;
		transform: translate(-50%);
		top: 100px;
	}
	.ui-autocomplete li {
		padding: 10px;
		cursor: pointer;
		font-size: 14px;
		color: #000;

	}

	.ui-autocomplete li strong {
		color: #0077cc;
	}
	.ui-menu .ui-menu-item a{
		background:yellow;
		height:10px;
		font-size:8px;
	}
</style>

<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script>
	$("#clearBtn").on('click', function () {
		$('#inputSearch').val('');
		$(this).hide();
	});

	$('#inputSearch').on('keyup', function () {
		if ($(this).val().length > 0) {
			$("#clearBtn").show();
		} else {
			$("#clearBtn").hide();
		}
	});

	$('#inputSearch').autocomplete({
		source: <?= json_encode($list) ?>,
		focus: function (event, ui) {
			return false;
		},
		select: function (event, ui) {
			setTimeout(function() {
				searchEvent();
			}, 200);
		},
		minLength: 1,
		delay: 100,
		autoFocus: false,
	});

	function searchEvent()
	{
		location.href = '?text=' + btoa(encodeURIComponent($('#inputSearch').val()));
	}

	$("#inputSearch").keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) {
			searchEvent();
			return false;
		}
	});

	function searchTag(target) {
		$('#inputSearch').val(target.innerText);
		$("#clearBtn").show();
		searchEvent();
	}
</script>
