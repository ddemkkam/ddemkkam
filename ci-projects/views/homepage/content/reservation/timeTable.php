<div style="margin: auto;">
	<?if ( isset($rtData) ) {?>
		<?//echo '<pre>'; print_r($rtData); echo '</pre>'; exit();?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) < strtotime('12:00') ) {?>
				<?if($index == 0){?>
					<div style="clear: both; font-size: 14px; padding-left: 10px; padding-bottom: 10px;">오전</div>
				<?}?>
			<?}?>
		<?}?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) < strtotime('12:00') ) {?>
				<?if ($row['is_reserve']) { $is_reserve = 'timeButtonEnable'; } else { $is_reserve = 'timeButtonDisable'; }?>
				<div class="t_i t_i_<?=$index?> ReserveDate_date-box__BQNmx calswiper <?=$is_reserve?>"
					 style="cursor: pointer;"
					 onclick="setTimeData('<?=$row['seq']?>', '<?=$row['time']?>', '<?=$index?>')">
					<?=$row['time']?>
				</div>
			<?}?>
		<?}?>

		<?$cnt = 0;?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) > strtotime('12:00') ) {?>
				<?if($cnt == 0){?>
					<div style="clear: both; font-size: 14px; padding-left: 10px; padding-top: 15px; padding-bottom: 10px;">오후</div>
				<?$cnt++;
				}?>
			<?}?>
		<?}?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) > strtotime('12:00') ) {?>
				<?if ($row['is_reserve']) { $is_reserve = 'timeButtonEnable'; } else { $is_reserve = 'timeButtonDisable'; }?>
				<div class="t_i t_i_<?=$index?> ReserveDate_date-box__BQNmx calswiper <?=$is_reserve?>"
					 style="cursor: pointer;"
					 onclick="setTimeData('<?=$row['seq']?>', '<?=$row['time']?>', '<?=$index?>')">
					<?=$row['time']?>
				</div>
			<?}?>
		<?}?>
	<?}?>
</div>
