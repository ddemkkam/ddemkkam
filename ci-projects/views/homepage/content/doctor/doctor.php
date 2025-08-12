<div class="Doctor_doctor-list__UTcTl">
	<?if ( isset($doctorData) ) {?>
	<ul>
		<?foreach ( $doctorData as $index => $row ) {?>
			<? if (isset($row['DOCTOR']) && count($row['DOCTOR']) > 0) { ?>
				<li style="">
					<h4 style="font-size: 14px; font-weight: bold; color: #cf2f75; padding: 10px;">
						<?=$row['BRANCH_NAME']?>
					</h4>
					<?foreach ( $row['DOCTOR'] as $index2 => $row2 ) {?>
						<div style="clear: both; height: 132px;">
							<img src="<?=$row2['D_IMG_PATH']?>" alt="<?=$row2['D_NAME']?>" class="Doctor_img__jT4pW">
							<div class="Doctor_title__s-NUG">
								<?=$row2['D_NAME']?>
								<em><?=$row2['D_MAIN_YN'] === 'Y' ? '대표원장' : ''?></em>
							</div>
							<ul class="Doctor_desc__4Ppjv">
								<li style="line-height: 20px;"><?=nl2br($row2['D_DESC'])?></li>
							</ul>
						</div>
					<?}?>
				</li>
			<? } ?>
		<?}?>
	</ul>
	<?}?>
</div>
