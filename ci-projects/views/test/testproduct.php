<table class="table table-bordered table-hover">
		<tr>
			<th class="text-center"></th>
			<th class="text-center">상품명</th>
			<th class="text-center">원가</th>
			<th class="text-center">부가세</th>
			<th class="text-center">부가세포함</th>
			<th class="text-center">디테일가격</th>
			<th class="text-center">할인가</th>
			<th class="text-center">할인률</th>
			<th class="text-center">하이패스</th>
			<th class="text-center">슬립여부</th>
		</tr>
	<?if ( isset($pData) ) {?>
		<?foreach ( $pData as $index => $row ) {?>
			<tr>
				<td>
					<input type="checkbox" class="pro_checkbox" style="width: 15px; height: 15px;" value="<?=$row['code']?>">
				</td>
				<td><?=$row['name']?></td>
				<td><?=number_format($row['price'])?></td>
				<td><?=number_format($row['tax_price'])?></td>
				<td><?=number_format($row['total_price'])?></td>
				<td><?=number_format($row['detail_total_price'])?></td>
				<td><?=number_format($row['discount_price'])?></td>
				<td><?=number_format($row['discount_per'])?></td>
				<td><?=number_format($row['is_hipass'])?></td>
				<td><?=number_format($row['is_sleep'])?></td>
			</tr>
		<?}?>
	<?}?>
</table>
