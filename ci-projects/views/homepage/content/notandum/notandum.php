<div class="SelectSurgery_select-surgery__huyL8" style="padding: 20px 20px 20px;">
	<h3 style="margin: 0">
		<p style="font-size: 14px; font-weight: 500;">
			<span style="font-weight: bold; font-size: 18px;">
				<?=isset($menu) ? $menu : ''?>
			</span>
		</p>
	</h3>
</div>

<div class="notandum-content">
	<table style="width: 100%;">
		<?if ( isset($notandumData) ) {?>
			<?foreach ( $notandumData as $index => $row ) {?>
                <tr>
                    <td class="notandum-title" onclick="noticeView('<?=$row['SEQ']?>')">
						<?=$row['N_TITLE']?>
					</td>
                    <td class="notandum-arrow" onclick="noticeView('<?=$row['SEQ']?>')">
                        <img class="ckeditorImg_<?=$row['SEQ']?>" src="/common/homepage/img/common/ico_more_on.svg">
					</td>
				</tr>
				<tr class="ckeditorView ckeditorView_<?=$row['SEQ']?>">
					<td colspan="2">
						<div class="sub-title">
							관련시술 <br />
							<?=$row['N_SUB_TITLE']?>
						</div>
						<div class="desc">
							<?=nl2br($row['N_CONTEXT'])?>
						</div>
					</td>
				</tr>
			<?}?>
		<?}?>
	</table>
</div>
