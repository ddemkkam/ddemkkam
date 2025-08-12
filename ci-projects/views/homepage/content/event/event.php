<div class="Event_event-list__Exwr3">
	<ul id="eventListTarget">
		<input type="hidden" name="last_page" value="<?= $list['last_page'] ?>">
		<input type="hidden" name="current_page" value="<?= $list['current_page'] ?>">

		<? foreach($list['data'] as $key => $value) {

			?>
			<li <? if (date('Y-m-d') > $value['TSE_END_DATETIME']) { ?> class="Event_finished__qB1W1" <? } else { ?> onclick="eventInfo('<?= $value['tse_code'] ?>')" style="cursor: pointer;"<? } ?>>
				<div class="Event_banner__U-v-1">
					<? if (!is_null($value['cci_host'])) { ?>
						<img src="<?= $value['cci_host'] . '/' . $value['cci_path'] . '/' . $value['cci_file'] ?>" alt="<?= $value['cci_name'] ?>">
					<? } else { ?>
						<img src="/common/homepage/img/event/event_banner_sample01.jpg" alt="">
					<? } ?>
				</div>
				<div class="Event_event-title__WF+-0"><?= $value['tse_name'] ?><!--<br>~<?php /*= $value['price'] */?>%혜택--></div>
				<? if ($value['TSE_START_DATETIME'] == "0000-00-00 00:00:00" && $value['TSE_END_DATETIME'] == "9999-12-31 23:59:59") { ?>
					<div class="Event_event-date__W24Pr">상시 진행</div>
				<? } else { ?>
					<div class="Event_event-date__W24Pr"><?= $value['start_date'] ?> ~ <?= $value['end_date'] ?></div>
				<? } ?>
			</li>
		<? } ?>
	</ul>
</div>

<script>
	let timer;
	if (!timer) {
		timer = setTimeout(() => {
			timer = null;
			/* 실행될 이벤트 */
		}, 1000);
	}

	window.addEventListener("scroll", () => {
		if (window.innerHeight + window.scrollY >= document.body.scrollHeight) {
			if (Number($("input[name='last_page']").val()) > Number($("input[name='current_page']").val())) {
				if (!timer) {
					timer = setTimeout(() => {
						timer = null;

						const today = new Date();
						const year = today.getFullYear();
						const month = (today.getMonth() + 1).toString().padStart(2, '0');
						const day = today.getDate().toString().padStart(2, '0');
						let yyyy_mm_dd = `${year}-${month}-${day}`;

						$.ajax({
							url: '<?= isset($homeApiUrl) ? $homeApiUrl : '' ?>?page=' + (Number($("input[name='current_page']").val()) + 1),
							method: 'get',
							dataType:'json',
							success:function(result) {
								console.log(result);
								$("input[name='current_page']").val(result.current_page);

								$.each(result.data, function(index, item) {
									let c = '';
									if (yyyy_mm_dd > item.TSE_END_DATETIME) {
										c = 'class="Event_finished__qB1W1"';
									}
									let html = '<li ' + c +'>' +
										'<div class="Event_banner__U-v-1"><img src="/common/homepage/img/event/event_banner_sample01.jpg" alt=""></div>' +
										'<div class="Event_event-title__WF+-0">' + item.tse_name + '<br>~' + item.price + '%혜택</div>' +
										'<div class="Event_event-date__W24Pr">' + item.start_date + ' ~ ' + item.end_date + '</div>' +
										'</li>';

									$("#eventListTarget").append(html);
								});
							}
						});
					}, 500);
				}
			}
		}
	});

	function eventInfo(tse_code)
	{
		window.location = "/event/info?tse_code=" + tse_code;
	}

</script>


<script>
// 이벤트 리스트 PC & Mobile font-size 조절
document.addEventListener("DOMContentLoaded", function () {
  const titles = document.querySelectorAll('.Event_event-list__Exwr3 li .Event_event-title__WF\\+-0');
  const dates = document.querySelectorAll('.Event_event-list__Exwr3 li .Event_event-date__W24Pr');

  if (window.innerWidth <= 569) {
    titles.forEach(title => title.classList.add('small-title'));
    dates.forEach(date => date.classList.add('small-date'));
  } else {
    titles.forEach(title => title.classList.add('large-title'));
    dates.forEach(date => date.classList.add('large-date'));
  }
});
</script>
