<div class="SelectSurgery_select-surgery__huyL8" style="padding: 20px 20px 20px;">
	<h3>
		<p style="font-size: 14px; font-weight: 500;">
			<span style="font-weight: bold; font-size: 18px;">
<!--				쁨클리닉 공지사항 게시판이에요-->
				<?php
				if (isset($branchInfo)) {
					if ($branchInfo['BRANCH'] === 'ppeum920') {
						echo '쁨글로벌의원 공지사항 게시판이에요';
					} else {
						echo '예쁨주의쁨의원 공지사항 게시판이에요';
					}
				} else {
					echo '쁨글로벌의원 공지사항 게시판이에요.';
				}
				?>
			</span>
		</p>
	</h3>
</div>

<div>
	<table style="width: 100%;">
		<?if ( isset($noticeData) ) {?>
			<?foreach ( $noticeData as $index => $row ) {?>
				<tr style="border-top: 1px solid #f6f7f9;">
					<td style="font-size: 14px; font-weight: 500; padding: 20px 20px 20px; vertical-align: middle;" onclick="noticeView('<?=$row['SEQ']?>')">
						<?=$row['N_TITLE']?>
					</td>
					<td style="padding: 20px 25px 20px; text-align: center; width: 32px; height: 32px;" onclick="noticeView('<?=$row['SEQ']?>')">
						<img class="ckeditorImg_<?=$row['SEQ']?>" src="/common/homepage/img/common/ico_more_on.svg" style="width: 25px; height: 25px;">
					</td>
				</tr>
				<tr class="ckeditorView ckeditorView_<?=$row['SEQ']?>">
					<td colspan="2" style="padding: 20px; background-color: #f6f7f9;">
						<div style="width: 100%; /*background-color: #d0d6de;*/ margin-top: 12px; font-size: 14px;">
							<?=nl2br($row['N_CONTEXT'])?>
						</div>
					</td>
				</tr>
			<?}?>
		<?}?>
	</table>
</div>
