<style>
	.tr_style {border:3px solid #000000;}
</style>
<script src="/publish/bower_components/ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-body">
				<form id="event_frm" action="/home_admin/MyPage/save_mobile_img_set" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="M_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" />
					<input type="hidden" name='M_LAN' id='T_LAN' value="<?=isset($LAN) ? $LAN : '' ?>" readonly />
					<div class="col-xs-12">
						이미지 가 없으면 저장 되지 않습니다.
					</div>
					<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
						<?for ( $i = 0; $i < 10; $i++ ) {?>
							<li style='background-color: #ffffff; clear: both; height: 260px; border: 1px solid #F5F5F5;'>
								<div class="col-xs-12" style="padding-left: 0px;">
									<div class="col-xs-5">
										<table class="table table-bordered table-hover">
											<tr>
												<colgroup>
													<col width="15%">
													<col width="85%">
												</colgroup>
											</tr>
											<tr>
												<th class="active text-center">제목</th>
												<td>
													<input type="text" name="TITLE[]" class="form-control TITLE_<?=$i?>" value="<?=isset($info[$i]['TITLE']) ? $info[$i]['TITLE'] : '' ?>" />
												</td>
											</tr>
											<tr>
												<th class="active text-center">시작기간</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="date" name="START_DATE[]" class="form-control START_DATE_<?=$i?>" value="<?=isset($info[$i]['START_DATE']) ? $info[$i]['START_DATE'] : '' ?>" />
													</div>
													<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
														<input type="time" name="START_TIME[]" class="form-control START_TIME_<?=$i?>" value="<?=isset($info[$i]['START_TIME']) ? $info[$i]['START_TIME'] : '' ?>" />
													</div>
												</td>
											</tr>
											<tr>
												<th class="active text-center">종료기간</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="date" name="FINISH_DATE[]" class="form-control FINISH_DATE_<?=$i?>" value="<?=isset($info[$i]['FINISH_DATE']) ? $info[$i]['FINISH_DATE'] : '' ?>" />
													</div>
													<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
														<input type="time" name="FINISH_TIME[]" class="form-control FINISH_TIME_<?=$i?>" value="<?=isset($info[$i]['FINISH_TIME']) ? $info[$i]['FINISH_TIME'] : '' ?>" />
													</div>
												</td>
											</tr>
											<tr>
												<th class="active text-center">상시진행</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="checkbox" name="IS_ALWAYS[]" class="is-always" data-index="<?=$i?>" value="checked" <? if ($info[$i]['FINISH_DATE'] == "9999-12-31") { ?> checked <?}?>>
													</div>
												</td>
											</tr>
											<tr>
												<th class="active text-center">LINK</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;margin-bottom: 5px;">
														<select name="LINK_TARGET[]" class="form-control">
															<option value="page" <?= (isset($info[$i]['LINK_TARGET']) && $info[$i]['LINK_TARGET'] == 'page') ? 'selected' : '' ?>>페이지 이동</option>
															<option value="blank" <?= (isset($info[$i]['LINK_TARGET']) && $info[$i]['LINK_TARGET'] == 'blank') ? 'selected' : '' ?>>새탭 노출</option>
														</select>
													</div>
													<div>
														<input type="text" name="LINK[]" class="form-control LINK_<?=$i?>" placeholder="www.ppeum.com" value="<?=isset($info[$i]['LINK']) ? $info[$i]['LINK'] : '' ?>" />
													</div>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<input type='button' class="btn btn-default" value='초기화' onclick='deleteMainView(<?=$i?>)' />
												</td>
											</tr>
										</table>
									</div>

									<div class="col-xs-2" id="viewId<?=$i?>">
										<?if ( $info[$i]['IMG_SRC'] ) {?>
											<img src='<?=$info[$i]['IMG_SRC']?>' class="IMG_PATH_<?=$i?>" style='width: 150px; height: 150px;' />
											<input type='hidden' name='M_IMG_PATH[]' class="M_IMG_PATH_<?=$i?>" value='<?=$info[$i]['IMG_SRC']?>'><br />
											<input type='button' value='삭제' onclick='deleteZip("<?=$i?>")'>
										<?} else {?>
											upload image
										<?}?>
									</div>

									<div class="col-xs-4">
										<iframe
											src="/fileUpload/uploadView?id=mypagemobile&cnt=1&viewId=viewId<?=$i?>"
											frameborder="0"
											style="width: 100%; height: 200px;"
										></iframe>
									</div>


								</div>
							</li>
						<?}?>
					</ul>
					<div class="text-center">
						<button type="button" class="btn btn-primary" onclick="saveTerms()">저장</button>
						<button type="button" class="btn btn-default" onclick="history.back()">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="ajax_json_div"></div>

<script type="text/javascript">

	$(document).ready(function() {
		$("#viewId").sortable();

		$('.is-always').on('change', function() {
			var idx       = $(this).data('index');
			var $dateInp  = $('.FINISH_DATE_' + idx);
			var $timeInp  = $('.FINISH_TIME_' + idx);

			if ($(this).is(':checked')) {
				// 체크 시: 날짜와 시간을 고정값으로 설정
				$dateInp.val('9999-12-31');
				$timeInp.val('23:59');
			} else {
				// 체크 해제 시: 초기화 (원하는 기본값이 있으면 여기에 넣기)
				$dateInp.val('');
				$timeInp.val('');
			}
		});
	})

	function saveTerms() {
		$.confirm({
			title: "",
			content: "저장하시겠습니까?",
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "닫기",
			buttons: {
				tryAgain: {
					text: '저장',
					btnClass: 'btn-blue',
					action: function(){
						//sendFile("/v1/event/eventSave", 'event_frm', eventSaveResultFunc);
						$("#event_frm").submit();
					}
				},
				close: function () {
				}
			}
		});
	}
	function file_upload_open(){
		window.open('/fileUpload/uploadView?id=mypagemobile&cnt=1&viewId=viewId', '이미지 업로드', 'width=700px,height=250px,scrollbars=yes');
	}
	function deleteMainView(i){
		//$("#viewId").html("");
		$('.TITLE_' + i).val('');
		$('.START_DATE_' + i).val('');
		$('.START_TIME_' + i).val('');
		$('.FINISH_DATE_' + i).val('');
		$('.FINISH_TIME_' + i).val('');
		$('.M_IMG_PATH_' + i).val('');
		$('.LINK_' + i).val('');
		$('select[name="LINK_TARGET[]"]').eq(i).val('page');
		$('.IMG_PATH_' + i).attr('src', '');
	}

	function deleteZip(i){
		$('.M_IMG_PATH_' + i).val('');
		$('.IMG_PATH_' + i).attr('src', '');
	}

</script>
