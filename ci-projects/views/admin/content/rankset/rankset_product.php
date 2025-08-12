<table class="table table-bordered table-hover">
	<tr>
		<colgroup>
			<col width="10%">
			<col width="10%">
		</colgroup>
	</tr>
	<tr>
		<th class="active text-center">대분류</th>
		<th class="active text-center">중분류</th>
	</tr>
	<tr>
		<td class="text-center">
			<select class="form-control" id="category1" onchange="LCategoryChange();">
				<option value="not">선택하세요.</option>
				<?
				if ( isset( $firstCate ) ) {
					foreach ($firstCate as $key => $value) {?>
						<option value="<?= $value['fir_cate_code'] ?>" >
							<?= $value['fir_cate_name'] ?>
						</option>
					<?
					}
				} ?>
			</select>
		</td>
		<td class="text-center">
			<select class="form-control" id="category2" onchange="SCategoryChange();">
				<option value="not">선택하세요.</option>
				<?
				if ( isset( $secondCate ) ) {
					foreach ($secondCate as $key => $row) {?>
							<option class="sc2 sc_<?=$row['fir_cate_code']?>" value="sc_<?=$row['sec_cate_code']  ?>" style="display: none;" >
								<?=$row['sec_cate_name'] ?>
							</option>
					<?
					}
				} ?>
			</select>
		</td>
	</tr>
</table>

<table class="table table-bordered table-hover">
	
	<tr>
		<colgroup>
			<col width="15%">
			<col width="15%">
			<col width="10%">
			<col width="5%">
		</colgroup>
	</tr>
	<tr >
		<th class="active text-center">상품명</th>
		<th class="active text-center">기간</th>
		<th class="active text-center">가격</th>
		<th class="active text-center"></th>
	</tr>
	<?
	$count = 0;
	if ( isset($list)) {
		foreach($list as $key => $item) {	?>
			<tr class="p_p p_sc_<?= $item['sec_cate_code'] ?> <?='aa_'.$item['sec_cate_code'].'_'.$item['ts_code'] ?>" style="display: none;">
				<td class="text-center"><?= $item['ts_name'] ?></td>
				<td class="text-center">
					<?= isset($item['tse_start_datetime']) ? $item['tse_start_datetime'] : '' ?>
					<br />
					<?= isset($item['tse_end_datetime']) ? $item['tse_end_datetime'] : '' ?>
				</td>
				<td class="text-center">
					<? if ( $item['fir_cate_code'] == 'event' ) {
						echo number_format($item['ts_price']);
						echo '<br />';
						echo number_format($item['event_ts_price']);
					} else {
						if ( $item['event_ts_price'] != $item['ts_price'] ) {
							echo number_format($item['event_ts_price']);
							echo '<br />';
							echo number_format($item['ts_price']);
						} else {
							echo number_format($item['event_ts_price']);
						}

					}?>
				</td>
				<td class="text-center p_input">
					<span class="p_input_text">설정완료</span>
					<input
						type="button"
						class="btn btn-success addProductViewSetBtn"
						data-fir_cate_code="<?= $item['fir_cate_code'] ?>"
						data-sec_cate_code="<?= $item['sec_cate_code'] ?>"
						data-ts_code="<?= $item['ts_code'] ?>"
						data-ts_name="<?= $item['ts_name'] ?>"
						data-view_id="<?= isset($view_id) ? $view_id : '0' ?>"
						onclick="addProductViewSet(this)"
						value="추가">
				</td>
			</tr>
		<?
		}
	}?>

</table>


<script>
	productColorSet();

	function LCategoryChange(){
		let category1 = $('#category1').val();
		$('.sc2').hide();
		$('.sc_'+category1).show();
	}

	function SCategoryChange(){
		let category2 = $('#category2').val();
		$('.p_p').hide();
		$('.p_'+category2).show();
	}

	function addProductViewSet(e){
		const fir_cate_code = $(e).data('fir_cate_code');
		const sec_cate_code = $(e).data('sec_cate_code');
		const ts_code = $(e).data('ts_code');
		const ts_name = $(e).data('ts_name');
		const view_id = $(e).data('view_id');
		console.log(view_id);

		// const data = {
		// 	'fir_cate_code' : fir_cate_code
		// 	, 'sec_cate_code' :sec_cate_code
		// 	, 'ts_code' : ts_code
		// 	, 'view_id' : view_id
		// 	, 'ts_name' : ts_name
		// };

		$('.v_'+view_id+'_fir_cate_code').val(fir_cate_code);
		$('.v_'+view_id+'_sec_cate_code').val(sec_cate_code);
		$('.v_'+view_id+'_ts_code').val(ts_code);
		$('.v_'+view_id+'_sec_ts_code').val(sec_cate_code+'_'+ts_code);
		$('.P_TITLE_'+view_id+'').val(ts_name);

		productColorSet();
		return false;
	}

	// function productColorSet(){
	// 	$('.p_p').css('background-color', '#ffffff');
	//
	// 	$('.sec_ts_code').each(function ( index ) {
	// 		//console.log($(this).val());
	// 		$('.aa_'+$(this).val()).css('background-color', 'yellow');
	// 	})
	// }
</script>
