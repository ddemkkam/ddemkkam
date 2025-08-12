<div id="no_pop_div">
	<div class="base" style="height: 200px;">
		<div class="baseText">
			<?=isset($text) ? $text : ''?>
		</div>

		<div>
			<button class="baseBtn" style="width: 49%;float: left;background-color:rgb(255,238,245);color:#cf2f75;" onclick="alertViewHide('<?=isset($type) ? $type : '' ?>')">아니오</button>
			<button class="baseBtn" style="width: 49%;float: right;" onclick="location.href='/login'">예</button>
		</div>
	</div>
</div>

