<div id="no_pop_div">
	<div class="base">
		<div class="baseText">
			<?=isset($text) ? $text : ''?>
		</div>

		<div>
			<button class="baseBtn" onclick="alertViewHide('<?=isset($type) ? $type : '' ?>')">
				확인
			</button>
		</div>
	</div>
</div>

