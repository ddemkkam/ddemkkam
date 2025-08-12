<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<input type="hidden" name="E_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>"  />
					<input type="hidden" name="E_LANGUAGE" value="<?=isset($LANGUAGE) ? $LANGUAGE : '' ?>"  />
					<?/*
					<div class="col-xs-12">
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
										<input type="text" class="form-control" name="E_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" readonly />
									</td>
									<td class="text-center">
										<input type="text" class="form-control" value="<?=isset($BRANCH_NAME) ? $BRANCH_NAME : '' ?>" readonly />
									</td>
									<td class="text-center">
										<input type="text" class="form-control" name="E_LANGUAGE" value="<?=isset($LANGUAGE) ? $LANGUAGE : '' ?>" readonly />
									</td>
								</tr>
							</table>
						</div>
					</div>
					*/?>

					<div class="col-xs-9">
						<?if ( isset($EVENTRESULT) ) {?>
							<?foreach ( $EVENTRESULT as $i => $row ) {?>
								<div class="col-xs-12" style="padding-left: 0px;">
									<div class="col-xs-12">
										<table class="table table-bordered table-hover">
											<tr>
												<colgroup>
													<col width="15%">
													<col width="85%">
												</colgroup>
											</tr>
											<tr>
												<th class="active text-center">이벤트명</th>
												<td>
													<?=isset($row['tse_name']) ? $row['tse_name'] : ''?>
													<input type="hidden" name="TSE_CODE[]" value="<?=isset($row['tse_code']) ? $row['tse_code'] : ''?>" />
												</td>
											</tr>
											<tr>
												<th class="active text-center">
													이미지
													<br />
													width: 570
													<br />
													height : 1000 이상
												</th>
												<td>
													<div class="col-xs-6" id="viewId<?=$i?>">
														<?if ( isset($row['E_IMG_PATH']) ) {?>
															<img src='<?=$row['E_IMG_PATH']?>' style='width: 150px; height: 150px;' />
															<input type='hidden' name='E_IMG_PATH[]' value='<?=$row['E_IMG_PATH']?>'><br />
															<input type='button' value='삭제' onclick='deleteZip("viewId<?=$i?>")'>
														<?} else {?>
															upload image
														<?}?>
													</div>
													<div class="col-xs-6">
														<iframe
															src="/fileUpload/uploadView?id=eventinfo&cnt=1&viewId=viewId<?=$i?>"
															frameborder="0"
															style="width: 100%; height: 200px;"
														></iframe>
													</div>
												</td>
											</tr>
										</table>
									</div>

								</div>

							<?}?>
						<?}?>
					</div>


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
		//$("#viewId").sortable();

		// $(".allTime").on("click", function (index){
		//
		// 	if ( $(this).prop("checked") ) {
		// 		// console.log($(this).parent().parent().find("input[name='P_FINISH_DATE[]").val());
		// 		// console.log($(this).parent().parent().find("input[name='P_FINISH_TIME[]").val());
		// 		ogDate = $(this).parent().parent().find("input[name='P_FINISH_DATE[]").val();
		// 		ogTime = $(this).parent().parent().find("input[name='P_FINISH_TIME[]").val();
		// 		$(this).parent().parent().find("input[name='P_FINISH_DATE[]").val("2030-12-31");
		// 		$(this).parent().parent().find("input[name='P_FINISH_TIME[]").val("23:59:59");
		// 		//console.log($(this).parent().parent().html());
		// 	} else {
		// 		$(this).parent().parent().find("input[name='P_FINISH_DATE[]").val("");
		// 		$(this).parent().parent().find("input[name='P_FINISH_TIME[]").val("");
		// 	}
		// });
	})

	function deleteZip(viewId){
		if ( viewId.indexOf('_2') !== -1 ) {
			$("#" + viewId).find("input[name='P_IMG_PATH_MO[]']").val("");
		} else {
			$("#" + viewId).find("input[name='P_IMG_PATH_PC[]']").val("");
		}
		$("#"+viewId).find("img").attr("src", "");
		$("#"+viewId).find("input[type='hidden']").val("");
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
						sendAjaxHtml("/home_admin/eventinfo/eventInfoSave", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>
