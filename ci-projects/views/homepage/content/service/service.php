<div style="max-width: 570px; margin: auto;">
	<div class="rules_rules__-8x-K">
		<table class="serviceTable">
			<tr>
				<td class="serviceText">
					<?if ( $type === 'use' ) {
						echo "이용약관";
					} else if ( $type === 'public' ) {
						echo "개인정보 처리 방침";
					} else if ( $type === 'marketing' ) {
						echo "마케팅 활용/광고성 정보 수신 동의";
					}?>
				</td>
				<td class="serviceText">
					<div class="iconClose" onclick="serviceViewClose()"></div>
				</td>
			</tr>
		</table>
		<div class="contextServie">
			<? if( isset($serviceData[0]['T_CONTEXT']) ) { ?>
				<?=$serviceData[0]['T_CONTEXT']?>
			<?}?>
		</div>
	</div>
</div>
