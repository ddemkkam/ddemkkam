<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<div class="col-xs-6">
						<table class="table table-bordered table-hover">
							<tr>
								<colgroup>
									<col width="10%">
									<col width="10%">
									<col width="10%">
								</colgroup>
							</tr>
							<tr>
								<th class="active text-center">BRANCH</th>
								<th class="active text-center">지점명</th>
								<th class="active text-center">언어</th>
							</tr>
							<tr>
								<td class="text-center">
									<input type="text" class="form-control" name="P_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" readonly />
								</td>
								<td class="text-center">
									<input type="text" class="form-control" value="<?=isset($BRANCH_NAME) ? $BRANCH_NAME : '' ?>" readonly />
								</td>
								<td class="text-center">
									<input type="text" class="form-control" name="P_LANGUAGE" value="<?=isset($LANGAUE) ? $LANGAUE : '' ?>" readonly />
								</td>
							</tr>
						</table>
					</div>
					<br />
					<div class="col-xs-12">
						PC, Mo 이미지 가 없으면 저장 되지 않습니다.
					</div>
					<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
						<?for ( $i = 0; $i < 7; $i++ ) {?>
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
													<input type="text" name="P_TITLE[]" class="form-control" value="<?=isset($info[$i]['P_TITLE']) ? $info[$i]['P_TITLE'] : '' ?>" />
												</td>
											</tr>
											<tr>
												<th class="active text-center">시작기간</th>
												<td>
													<table class="col-xs-12">
														<tr>
															<td class="col-xs-5" style="padding-left: 0px;">
																<input type="date" name="P_START_DATE[]" class="form-control" value="<?=isset($info[$i]['P_START_DATE']) ? $info[$i]['P_START_DATE'] : '' ?>" />
															</td>
															<td class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
																<input type="time" name="P_START_TIME[]" class="form-control" value="<?=isset($info[$i]['P_START_TIME']) ? $info[$i]['P_START_TIME'] : '' ?>" />
															</td>
															<td class="col-xs-2 text-right">
																&nbsp;
															</td>
															<td class="col-xs-1">
																&nbsp;
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th class="active text-center">종료기간</th>
												<td>
													<table class="col-xs-12">
														<tr>
															<td class="col-xs-5" style="padding-left: 0px;">
																<input type="date" name="P_FINISH_DATE[]" class="form-control" value="<?=isset($info[$i]['P_FINISH_DATE']) ? $info[$i]['P_FINISH_DATE'] : '' ?>" />
															</td>
															<td class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
																<input type="time" name="P_FINISH_TIME[]" class="form-control" value="<?=isset($info[$i]['P_FINISH_TIME']) ? $info[$i]['P_FINISH_TIME'] : '' ?>" />
															</td>
															<td class="col-xs-2 text-right">
																상시
															</td>
															<td class="col-xs-1">
																<input type="checkbox" class="checkbox allTime" style="width: 17px; height: 17px;" />
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<th class="active text-center">링크</th>
												<td>
													<input type="text" name="P_LINK[]" class="form-control" value="<?=isset($info[$i]['P_LINK']) ? $info[$i]['P_LINK'] : '' ?>" />
												</td>
											</tr>
											<tr>
												<th class="active text-center"></th>
												<td>
													<table style="width: 100%;">
														<tr>
															<td class="text-center">On / Off</td>
															<td>
																<select class="form-control" name="P_ONOFF">
																	<option value="ON">ON</option>
																	<option value="OFF">OFF</option>
																</select>
															</td>
															<td class="text-center">새창</td>
															<td>
																<select class="form-control" name="P_NEWWINDOW">
																	<option value="ON">ON</option>
																	<option value="OFF">OFF</option>
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="col-xs-7" >
										<table class="col-xs-12">
											<tr>
												<td class="active text-center">PC</td>
												<td class="active text-center"></td>
												<td class="active text-center">MO</td>
												<td class="active text-center"></td>
											</tr>
											<tr>
												<td class="active text-center" id="viewId<?=$i?>">
													<?if ( isset($info[$i]['P_IMG_PATH_PC']) && $info[$i]['P_IMG_PATH_PC'] != '' ) {?>
														<img src='<?=$info[$i]['P_IMG_PATH_PC']?>' style='width: 100px; height: 100px;' />
														<input type='hidden' name='P_IMG_PATH_PC[]' value='<?=$info[$i]['P_IMG_PATH_PC']?>'><br />
														<input type='button' value='삭제' onclick='deleteZip("viewId<?=$i?>")'>
													<?} else {?>
														upload image
													<?}?>
												</td>
												<td>
													<iframe
														src="/fileUpload/uploadView?id=event&cnt=1&viewId=viewId<?=$i?>"
														frameborder="0"
														style="width: 100%; height: 200px;"
													></iframe>
												</td>
												<td class="active text-center" id="viewId<?=$i?>_2">
													<?if ( isset($info[$i]['P_IMG_PATH_MO']) && $info[$i]['P_IMG_PATH_MO'] != '' ) {?>
														<img src='<?=$info[$i]['P_IMG_PATH_MO']?>' style='width: 100px; height: 100px;' />
														<input type='hidden' name='P_IMG_PATH_MO[]' value='<?=$info[$i]['P_IMG_PATH_MO']?>'><br />
														<input type='button' value='삭제' onclick='deleteZip("viewId<?=$i?>_2")'>
													<?} else {?>
														upload image
													<?}?>
												</td>
												<td >
													<iframe
														src="/fileUpload/uploadView?id=event&cnt=1&viewId=viewId<?=$i?>_2"
														frameborder="0"
														style="width: 100%; height: 200px;"
													></iframe>
												</td>
											</tr>
										</table>
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

		$(".allTime").on("click", function (index){

			if ( $(this).prop("checked") ) {
				// console.log($(this).parent().parent().find("input[name='P_FINISH_DATE[]").val());
				// console.log($(this).parent().parent().find("input[name='P_FINISH_TIME[]").val());
				ogDate = $(this).parent().parent().find("input[name='P_FINISH_DATE[]").val();
				ogTime = $(this).parent().parent().find("input[name='P_FINISH_TIME[]").val();
				$(this).parent().parent().find("input[name='P_FINISH_DATE[]").val("2030-12-31");
				$(this).parent().parent().find("input[name='P_FINISH_TIME[]").val("23:59:59");
				//console.log($(this).parent().parent().html());
			} else {
				$(this).parent().parent().find("input[name='P_FINISH_DATE[]").val("");
				$(this).parent().parent().find("input[name='P_FINISH_TIME[]").val("");
			}
		});
	})

	function deleteZip(viewId){
		if ( viewId.indexOf('_2') !== -1 ) {
			$("#" + viewId).find("input[name='P_IMG_PATH_MO[]']").val("");
		} else {
			$("#" + viewId).find("input[name='P_IMG_PATH_PC[]']").val("");
		}
		$("#"+viewId).find("img").attr("src", "");
	}

	function save(){
		let data = $("#ppeumEventFrm").serialize();

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
						sendAjaxHtml("/home_admin/eventset/save", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>
