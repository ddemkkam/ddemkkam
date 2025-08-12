<div style="margin-top: 10px;">
	<select class="form-control" name="baseBranch" id="baseBranch" onchange="changeBaseBranch()">
		<option value="all">지점</option>
		<?if(isset($branchList)){?>
			<?foreach ( $branchList as $index => $row ) {?>
				<option value="<?=$row['BRANCH']?>" <?=isset($BASEBRANCH) && $row['BRANCH'] === $BASEBRANCH ? 'selected' : '' ?> >
					<?=$row['BRANCH_NAME']?> (<?=$row['BRANCH']?>)
				</option>
			<?}?>
		<?}?>
	</select>
</div>

<div style="margin-top: 5px; margin-bottom: 15px;">
	<select class="form-control" name="baseLan" id="baseLan" onchange="baseLocation();">
		<option value="all">언어</option>
		<?foreach ( $branchList as $index => $row ) {
			foreach ( $row['LAN'] as $index2 => $row2 ) {?>
				<option
					class="baseBr <?='base_'.$row['BRANCH']?> <?='base_'.$row['BRANCH'].'_'.$row2['LANGAUE']?>"
					value="<?=$row2['LANGAUE']?>"
					<?=isset($BASELAN) && $row['BRANCH'].'_'.$row2['LANGAUE'] == $BASEBRANCH.'_'.$BASELAN ? 'selected' : '' ?>
					style="display: none;"
				>
					<?=$row2['COUNTRY_NAME']?>(<?=$row2['LANGAUE']?>)
				</option>
			<?}?>
		<?}?>
	</select>
</div>

<script>

	fChangeBaseLan();

	function fChangeBaseLan(){
		const branch = $('#baseBranch').val();
		const LAN = $("#baseLan").val();
		$(".base_"+branch+"_"+LAN).prop('selected', true);
		$(".base_"+branch).show();
	}

	function changeBaseBranch(){
		const branch = $('#baseBranch').val();
		//console.log('<?php //=$_SERVER['REQUEST_URI']?>//');

		// if ( $(".base_"+branch).length == 1 ) {
		// 	changeBaseLan();
		// } else {
		// 	$(".baseBr").hide();
		// 	$(".base_"+branch).show();
		// }
		changeBaseLan();
	}

	function changeBaseLan(){
		const branch = $('#baseBranch').val();
		$(".baseBr").hide();
		$(".base_"+branch+":eq(0)").prop('selected', true);
		$(".base_"+branch).show();
		baseLocation();
	}

	function baseLocation(){
		const branch = $('#baseBranch').val();
		const LAN = $("#baseLan").val();
		<?
		$REQUEST_URI = explode('/', $_SERVER['REQUEST_URI']);
		?>
		location.href = '/<?=$REQUEST_URI[1].'/'.$REQUEST_URI[2].'/'.$REQUEST_URI[3].'/'?>' + branch + '/' + LAN;
	}
</script>
