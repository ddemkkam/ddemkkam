<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<input type="hidden" name="HI_BRANCH" value="<?=isset($BASEBRANCH) ? $BASEBRANCH : '' ?>" />
					<input type="hidden" name="HI_LAN" value="<?=isset($BASELAN) ? $BASELAN : '' ?>" />

					<div class="col-xs-8">
						<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
							<?for ( $i = 0; $i < 10; $i++ ) {?>
								<li style='background-color: #ffffff; clear: both; height: 275px; border: 1px solid #F5F5F5;'>
									<div class="col-xs-12" style="padding-left: 0px; padding-right: 0px;">
										<div class="col-xs-12" style="padding-left: 0px; padding-right: 0px;">
											<table class="table table-bordered table-hover">
												<tr>
													<colgroup>
														<col width="15%">
														<col width="85%">
													</colgroup>
												</tr>
												<tr>
													<th class="active text-center">위치</th>
													<td>
														<input type="text" name="HI_TITLE[]" class="form-control P_TITLE_<?=$i?>" value="<?=isset($info[$i]['HI_TITLE']) ? $info[$i]['HI_TITLE'] : '' ?>" />
													</td>
												</tr>
												<tr>
													<th class="active text-center">이미지</th>
													<td>
														<div class="col-xs-6" id="viewId<?=$i?>">
															<?if ( $info[$i]['HI_IMG_PATH'] ) {?>
																<img src='<?=$info[$i]['HI_IMG_PATH']?>' style='width: 150px; height: 150px;' />
																<input type='hidden' name='HI_IMG_PATH[]' value='<?=$info[$i]['HI_IMG_PATH']?>'><br />
																<input type='button' value='삭제' onclick='deleteZip("viewId<?=$i?>")'>
															<?} else {?>
																upload image
															<?}?>
														</div>
														<div class="col-xs-6">
															<iframe
																src="/fileUpload/uploadView?id=hospital&cnt=1&viewId=viewId<?=$i?>"
																frameborder="0"
																style="width: 100%; height: 200px;"
															></iframe>
														</div>
													</td>
												</tr>
											</table>
										</div>

									</div>
								</li>
							<?}?>
						</ul>
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
			$("#" + viewId).find("input[name='HI_IMG_PATH[]']").val("");
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
						sendAjaxHtml("/home_admin/HospitalInfo/save", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>
