<div class="Surgery_mySurgery__I+6XI">
	<? if(isset($remain) && count($remain) > 0) { ?>
		<? if($branch['BRANCH'] != 'ppeum37') { ?>
			<div>
				<table>
					<tr>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">※&nbsp;</td>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">
						<?= date('Y년 m월 d일', strtotime($branch['H_DATA_TRANSFER_DATE'])) ?> 이전 방문 고객의 경우 일부 잔여시술이 미 노출될 수 있습니다.
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">
							잔여시술이 노출되지 않을 경우 해당 지점으로 연락 부탁드립니다.
						</td>
					</tr>
				</table>
			</div>
		<? } ?>
		<table class="Surgery_list__CjWZG">
			<thead>
				<tr>
					<th>선택</th>
					<th>패키지/상품명</th>
					<th>잔여/전체</th>
				</tr>
			</thead>
			<tbody>
			<? foreach($remain['data'] as $item) { ?>
				<tr class="MySurgery_surgeryItme__Cuie1">
					<td>
						<input type="checkbox" id="<?= $item['cti_code'] . '_' . $item['ts_code'] ?>" name="surgery" value="<?= $item['cti_code'] ?>" data-ts_code="<?= $item['ts_code'] ?>" onclick="changeCount()">
						<label for="<?= $item['cti_code'] . '_' . $item['ts_code'] ?>"><?= $item['cti_name'] ?></label>
					</td>
					<td>
						<label for="<?= $item['cti_code'] . '_' . $item['ts_code'] ?>" style="cursor: pointer;"><b><?= $item['cti_name'] ?></b><?= $item['ts_name'] ?></label>
					</td>
					<td>
						<strong><?= $item['count'] - $item['use_count'] ?></strong>/<?= $item['count'] ?>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>

		<div class="Surgery_btn-area__xus93">
			<button type="button" class="Surgery_btn-cart__6R2oA" onclick="basketProductAdd()">장바구니</button>
			<button type="button" class="Surgery_btn-reserve__i6hVf" onclick="setReservation()">
				<span id="remainCount" class="Surgery_selectQty__JTwJO" style="display: none;">1</span>
				예약 하기
			</button>
			<div id="basketSnackbar">
				<p id="basketSnackbarFalse" style="display: none;">장바구니에 상품을 담았습니다.</p>
				<p id="basketSnackbarTrue" style="display: none;">이미 장바구니에 있는 상품입니다.</p>
				<a href="/basket">바로가기</a>
			</div>
		</div>
	<? } else { ?>
		<? if($branch['BRANCH'] != 'ppeum37') { ?>
			<div style="height: 56px;">
				<table>
					<tr>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">※&nbsp;</td>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">
						<?= date('Y년 m월 d일', strtotime($branch['H_DATA_TRANSFER_DATE'])) ?> 이전 방문 고객의 경우 일부 잔여시술이 미 노출될 수 있습니다.
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">
							잔여시술이 노출되지 않을 경우 해당 지점으로 연락 부탁드립니다.
						</td>
					</tr>
				</table>
			</div>
		<? } ?>
		<div class="Cart_cart-empty__7e2tB">
			<div class="MyNone_my-none__9dt6+">
				<img src="/common/homepage/img/basket/ico_none.svg" alt="">
				<p class="MyNone_my-none__text__24uCV">잔여 시술이 없어요</p>
				<a href="selectSurgery">
					<button class="MyNone_btn-white__kyEV8">상품 둘러보기</button>
				</a>
			</div>
		</div>
	<? } ?>

</div>

<script>
	function changeCount()
	{
		let count = $("input[name='surgery']:checked").length;

		$("#remainCount").html(count);
		if (count > 0) {
			$("#remainCount").show();
		} else {
			$("#remainCount").hide();
		}
	}

	function setReservation()
	{
		let tsCode = [];
		let remain = [];

		let eventTsCode = [];
		let eventTsSectCateCode = [];
		let eventRemain = [];

		$("input[name='surgery']:checked").each(function(index, item){
			tsCode.push(item.dataset.ts_code);
			remain.push(item.value);
		});

		document.cookie = 'resTsCode=' + tsCode.join() + ';path=/';
		document.cookie = 'resRemain=' + remain.join() + ';path=/';
		document.cookie = 'resEventTsCode=' + eventTsCode.join() + ';path=/';
		document.cookie = 'resEventTsSectCateCode=' + eventTsSectCateCode.join() + ';path=/';
		document.cookie = 'resEventRemain=' + eventRemain.join() + ';path=/';
		document.cookie = 'resBasketYn=N;path=/';
		document.cookie = 'resNumber=;path=/';
		window.location = '/reservation';
	}

	function basketProductAdd()
	{
		let list = [];
		$("input[name='surgery']:checked").each(function(k,v) {
			list.push({
				'fir_cate_code': null
				,'sec_cate_code': null
				,'ts_code': null
				,'tse_end_datetime': null
				,'remain_code': v.value
			});
		});

		if (list.length > 0) {
			$("#basketSnackbarTrue").hide();
			$("#basketSnackbarFalse").hide();

			//장바구니 담기
			$.ajax({
				url: '/selectSurgery/setBasketProduct',
				data: {
					'list' : list
				},
				method: 'post',
				dataType: 'json',
				success:function(data){
					if (data.dup_check) {
						$("#basketSnackbarTrue").show();
					} else {
						$("#basketSnackbarFalse").show();
						$("#bastket_cnt").show();
						$("#bastket_cnt").text(data.basket_cnt);
					}
					// 장바구니 스낵바 표시
					let basketSnackbar = document.getElementById("basketSnackbar");
					basketSnackbar.className = "show";
					// 3초 후에 스낵바 숨김
					setTimeout(function() {
						basketSnackbar.className = basketSnackbar.className.replace("show", "");
					}, 3000);
				}
			});
		} else {
			alertViewShow('상품을 선택해주세요.', 'productDetail');
		}
	}
</script>
