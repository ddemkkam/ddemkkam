<div style="margin: auto;" class="time-box">
	<?if (isset($rtData)) {?>
		<? foreach ($rtData as $index => $row) { ?>
			<?if ( strtotime($row['time']) < strtotime('12:00') ) {?>
				<?if($index == 0){?>
					<div class="time-title morning">오전</div>
				<?}?>
			<?}?>
		<?}?>

		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) < strtotime('12:00') ) {?>
				<?if ($row['is_reserve']) { $is_reserve = 'timeButtonEnable'; } else { $is_reserve = 'timeButtonDisable'; }?>
				<div class="t_i t_i_<?=$index?> ReserveDate_date-box__BQNmx calswiper <?=$is_reserve?>"
					 style="cursor: pointer;"
					 data-time="<?= $row['time'] ?>"
					 data-room="<?= $row['seq'] ?>"
					 data-treatment_minute="<?= $row['treatment_minute'] ?>"
					 onclick="resSetTime('<?= $index ?>')">
					<?= $row['time'] ?>
				</div>
			<?}?>
		<?}?>

		<?$cnt = 0;?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) >= strtotime('12:00') ) {?>
				<?if($cnt == 0){?>
					<div class="time-title afternoon">오후</div>
				<?$cnt++;
				}?>
			<?}?>
		<?}?>
		<?foreach ( $rtData as $index => $row ) {?>
			<?if ( strtotime($row['time']) >= strtotime('12:00') ) {?>
				<?if ($row['is_reserve']) { $is_reserve = 'timeButtonEnable'; } else { $is_reserve = 'timeButtonDisable'; }?>
				<div class="t_i t_i_<?= $index ?> ReserveDate_date-box__BQNmx calswiper <?= $is_reserve ?>"
					 style="cursor: pointer;"
					 data-time="<?= $row['time'] ?>"
					 data-room="<?= $row['seq'] ?>"
					 data-treatment_minute="<?= $row['treatment_minute'] ?>"
					 onclick="resSetTime('<?= $index ?>')">
					<?= $row['time'] ?>
				</div>
			<?}?>
		<?}?>
	<?}?>
</div>
