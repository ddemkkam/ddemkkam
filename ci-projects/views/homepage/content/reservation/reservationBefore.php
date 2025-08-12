<form id="set_reservation" method="post" action="/reservation/reservationComplete">
	<input type="hidden" name="og_subProPrice" id="og_subProPrice" value="<?=isset($og_subProPrice) ? str_replace(',' , '', $og_subProPrice) : '0'?>">
	<input type="hidden" name="discountPrice" id="discountPrice" value="<?=isset($discountPrice) && $discountPrice != '' ? str_replace(',' , '', $discountPrice) : '0'?>">
	<input type="hidden" name="subProPrice" id="subProPrice" value="<?=isset($subProPrice) ? str_replace(',' , '', $subProPrice) : '0'?>">
	<input type="hidden" name="product" id="product" value='<?=isset($product) && count($product) > 0 ? json_encode($product) : json_encode(array())?>'>
	<input type="hidden" name="reserve_date" id="reserve-date" value="<?=isset($reserve_date) ? $reserve_date : '' ?>">
	<input type="hidden" name="reserve_time" id="reserve-time" value="<?=isset($reserve_time) ? $reserve_time : '' ?>">
	<input type="hidden" name="mileage" id="mileage" value="<?=isset($mileage) ? $mileage : '' ?>">
	<input type="hidden" name="res_number" id="res_number" value="<?=isset($res_number) ? $res_number : '' ?>">
</form>

<script>
	// $(document).ready(function() {
		// 화면 진입시 최초 실행
		const sb = document.getElementById('set_reservation');
		sb.submit();
		// $('#set_reservation').submit();
	// })
</script>
