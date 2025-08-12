<?if ( isset($rtData) ) {?>
	<table class="table table-bordered table-hover ">
		<?foreach ( $rtData as $index => $row ) {
			if ( $row['is_reserve'] ){?>
			<tr>
				<td class="text-center" style="width: 50px;">
					<input type="radio" name="res_time" value="<?=$row['seq']?>" data-time="<?=$row['time']?>" style="width: 15px; height: 15px;" />
				</td>
				<td class="text-center">
					<?=$row['time']?>
				</td>
				<td class="text-center">
					<?=$row['name']?>(<?=$row['now_count']. ' / '.$row['max_count'] ?>)
				</td>
			</tr>
			<?}?>
		<?}?>
	</table>
<?} else {
	echo "예약 가능한 시간 & 진료실, 상담실이 없습니다.";
}?>


