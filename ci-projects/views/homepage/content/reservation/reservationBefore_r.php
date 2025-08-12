<form id="set_reservation" method="get" action="/reservation/resComplete">
	<input type="hidden" name="r_number" id="res_number" value="<?= isset($r_number) ? $r_number : '' ?>">
</form>

<script>
	const sb = document.getElementById('set_reservation');
	sb.submit();
</script>
