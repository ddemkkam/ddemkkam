<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumPopFrm" >
					<input type="hidden" name="P_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" readonly />
					<input type="hidden" name="P_LANGUAGE" value="<?=isset($LANGAUE) ? $LANGAUE : '' ?>" readonly />
					<div class="col-xs-12">
					이미지 가 없으면 저장 되지 않습니다.
					</div>
					<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
						<?for ( $i = 0; $i < 5; $i++ ) {?>
							<li style='background-color: #ffffff; clear: both; height: 205px; border: 1px solid #F5F5F5;'>
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
													<input type="text" name="P_TITLE[]" class="form-control P_TITLE_<?=$i?>" value="<?=isset($info[$i]['P_TITLE']) ? $info[$i]['P_TITLE'] : '' ?>" />
												</td>
											</tr>
											<tr>
												<th class="active text-center">시작기간</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="date" name="P_START_DATE[]" class="form-control P_START_DATE_<?=$i?>" value="<?=isset($info[$i]['P_START_DATE']) ? $info[$i]['P_START_DATE'] : '' ?>" />
													</div>
													<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
														<input type="time" name="P_START_TIME[]" class="form-control P_START_TIME_<?=$i?>" value="<?=isset($info[$i]['P_START_TIME']) ? $info[$i]['P_START_TIME'] : '' ?>" />
													</div>
												</td>
											</tr>
											<tr>
												<th class="active text-center">종료기간</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="date" name="P_FINISH_DATE[]" class="form-control P_FINISH_DATE_<?=$i?>" value="<?=isset($info[$i]['P_FINISH_DATE']) ? $info[$i]['P_FINISH_DATE'] : '' ?>" />
													</div>
													<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
														<input type="time" name="P_FINISH_TIME[]" class="form-control P_FINISH_TIME_<?=$i?>" value="<?=isset($info[$i]['P_FINISH_TIME']) ? $info[$i]['P_FINISH_TIME'] : '' ?>" />
													</div>
												</td>
											</tr>
											<tr>
												<th class="active text-center">상시진행</th>
												<td>
													<div class="col-xs-6" style="padding-left: 0px;">
														<input type="checkbox" name="P_IS_ALWAYS[]" class="p-is-always" data-index="<?=$i?>" value="checked" <? if ($info[$i]['P_IS_ALWAYS'] == "true") { ?> checked <?}?>>
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
										<?if ( $info[$i]['P_IMG_PATH'] ) {?>
											<img src='<?=$info[$i]['P_IMG_PATH']?>' class="IMG_PATH_<?=$i?>" style='width: 150px; height: 150px;' />
											<input type='hidden' name='P_IMG_PATH[]' class="P_IMG_PATH_<?=$i?>" value='<?=$info[$i]['P_IMG_PATH']?>'><br />
											<input type='button' value='삭제' onclick='deleteZip("viewId<?=$i?>")'>
										<?} else {?>
											upload image
										<?}?>
									</div>

									<div class="col-xs-4">
										<iframe
											src="/fileUpload/uploadView?id=popup&cnt=1&viewId=viewId<?=$i?>"
											frameborder="0"
											style="width: 100%; height: 200px;"
										></iframe>
									</div>


								</div>
							</li>
						<?}?>
					</ul>
				</form>

				<div class="col-xs-12 text-center" style="margin-top: 10px;">
					<button type="button" class="btn btn-primary" id="info_save" onclick="save()">저장</button>
					&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-default" onclick="history.back();">취소</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="result_content"></div>

<script>
	$(document).ready(function() {
		$("#viewId").sortable();

		$('.p-is-always').on('change', function() {
			var idx       = $(this).data('index');
			var $dateInp  = $('.P_FINISH_DATE_' + idx);
			var $timeInp  = $('.P_FINISH_TIME_' + idx);

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

	function deleteZip(viewId){
		$("#"+viewId).find("input[name='P_IMG_PATH[]']").val("");
		$("#"+viewId).find("img").attr("src", "");
	}

	function save(){
		let data = $("#ppeumPopFrm").serialize();

		$.confirm({
			title: "",
			content: "저장 하시겠습니까?\n이미지가 없으면 저장되지 않습니다.",
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "저장",
			buttons: {
				tryAgain: {
					text: "저장",
					btnClass: 'btn-blue',
					action: function(){
						sendAjaxHtml("/home_admin/popupset/save", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}

	function deleteMainView(i){
		//$("#viewId").html("");
		$('.P_TITLE_' + i).val('');
		$('.P_START_DATE_' + i).val('');
		$('.P_START_TIME_' + i).val('');
		$('.P_FINISH_DATE_' + i).val('');
		$('.P_FINISH_TIME_' + i).val('');
		$('.P_IMG_PATH_' + i).val('');
		$('.IMG_PATH_' + i).attr('src', '');
	}
</script>
