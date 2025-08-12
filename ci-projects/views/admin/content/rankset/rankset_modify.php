<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<input type="hidden" name="P_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" />
					<input type="hidden" name="P_LANGUAGE" value="<?=isset($LANGAUE) ? $LANGAUE : '' ?>" />


					<div class="col-xs-6">
						<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
							<?for ( $i = 0; $i < 10; $i++ ) {?>
								<li style='background-color: #ffffff; clear: both; height: 195px; border: 1px solid #F5F5F5;'>
									<input type="hidden" name="fir_cate_code[]" class="fir_cate_code v_<?=$i?>_fir_cate_code"
										   value="<?=isset($infoList[$i]['fir_cate_code']) ? $infoList[$i]['fir_cate_code'] : '' ?>" />
									<input type="hidden" name="sec_cate_code[]" class="sec_cate_code v_<?=$i?>_sec_cate_code"
										   value="<?=isset($infoList[$i]['sec_cate_code']) ? $infoList[$i]['sec_cate_code'] : '' ?>" />
									<input type="hidden" name="ts_code[]" class="ts_code v_<?=$i?>_ts_code"
										   value="<?=isset($infoList[$i]['ts_code']) ? $infoList[$i]['ts_code'] : '' ?>" />
									<input type="hidden" name="sec_ts_code[]" class="sec_ts_code v_<?=$i?>_sec_ts_code"
										   value="<?=isset($infoList[$i]['sec_cate_code']) && isset($infoList[$i]['ts_code']) ? $infoList[$i]['sec_cate_code'].'_'.$infoList[$i]['ts_code'] : '' ?>" />
									<div class="col-xs-12" style="padding-left: 0px;">
										<div class="col-xs-12">
											<table class="table table-bordered table-hover tt_<?=$i?>">
												<tr>
													<colgroup>
														<col width="15%">
														<col width="85%">
													</colgroup>
												</tr>
												<tr>
													<th class="active text-center">상품명</th>
													<td>
														<div class="col-xs-9" style="padding-left: 0px; padding-right: 0px;">
															<input type="text" name="P_TITLE[]" class="form-control P_TITLE_<?=$i?>" value="<?=isset($infoList[$i]['ts_name']) ? $infoList[$i]['ts_name'] : '' ?>" readonly />
														</div>
														<div class="col-xs-3" style="text-align: right;">
															<input type="button" class="btn btn-default" value="초기화" onclick="defaultReturn('<?=$i?>')" />
														</div>
													</td>
												</tr>
												<tr>
													<th class="active text-center">시작기간</th>
													<td>
														<table class="col-xs-12">
															<tr>
																<td class="col-xs-5" style="padding-left: 0px;">
																	<input type="date" name="P_START_DATE[]" class="form-control" value="<?=isset($infoList[$i]['sDate']) ? $infoList[$i]['sDate'] : '' ?>" />
																</td>
																<td class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
																	<input type="time" name="P_START_TIME[]" class="form-control" value="<?=isset($infoList[$i]['sTime']) ? $infoList[$i]['sTime'] : '' ?>" />
																</td>
																<td class="col-xs-3 text-right" colspan="2">
																	<input type="button" class="btn btn-success" value="상품검색" onclick="product_view('<?=$i?>')" />
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
																	<input type="date" name="P_FINISH_DATE[]" class="form-control" value="<?=isset($infoList[$i]['eDate']) ? $infoList[$i]['eDate'] : '' ?>" />
																</td>
																<td class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
																	<input type="time" name="P_FINISH_TIME[]" class="form-control" value="<?=isset($infoList[$i]['eTime']) ? $infoList[$i]['eTime'] : '' ?>" />
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
												<?/*
												<tr>
													<th class="active text-center">링크</th>
													<td>
														<input type="text" name="P_LINK[]" class="form-control P_LINK_<?=$i?>" value="<?=isset($info[$i]['P_LINK']) ? $info[$i]['P_LINK'] : '' ?>" />
													</td>
												</tr>
												<tr>
													<th class="active text-center"></th>
													<td>
														<table style="width: 100%;">
															<tr>
																<td class="text-center P_PRICE1_<?=$i?>">가격1</td>
																<td class="text-center P_PRICE2_<?=$i?>">가격2</td>
																<td class="text-center P_PRICE3_<?=$i?>">가격3</td>
															</tr>
														</table>
													</td>
												</tr>
												*/?>
											</table>
										</div>

									</div>
								</li>
							<?}?>
						</ul>
					</div>

					<div class="col-xs-6" >
						<div id="productViewList" class="col-xs-4" style="position: fixed; height: 600px; overflow-y: auto; ">

						</div>
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
		$("#viewId").sortable({
			update: function(event, ui) {
				$("#viewId li").each(function(index) {
					$(this).find("input[name^='fir_cate_code']").attr('name', 'fir_cate_code[' + index + ']');
					$(this).find("input[name^='sec_cate_code']").attr('name', 'sec_cate_code[' + index + ']');
					$(this).find("input[name^='ts_code']").attr('name', 'ts_code[' + index + ']');
					$(this).find("input[name^='sec_ts_code']").attr('name', 'sec_ts_code[' + index + ']');
					$(this).find("input[name^='P_TITLE']").attr('name', 'P_TITLE[' + index + ']');
					$(this).find("input[name^='P_START_DATE']").attr('name', 'P_START_DATE[' + index + ']');
					$(this).find("input[name^='P_START_TIME']").attr('name', 'P_START_TIME[' + index + ']');
					$(this).find("input[name^='P_FINISH_DATE']").attr('name', 'P_FINISH_DATE[' + index + ']');
					$(this).find("input[name^='P_FINISH_TIME']").attr('name', 'P_FINISH_TIME[' + index + ']');
				});
			}
		});

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
			content: "저장 하시겠습니까?",
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
						sendAjaxHtml("/home_admin/rankset/save", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}

	function product_view(index){
		const branch = $("input[name='P_BRANCH']").val();
		const lan = $("input[name='P_LANGUAGE']").val();
		const data = { 'branch' : branch, 'lan' : lan, 'index' : index };

		sendAjaxHtml("/home_admin/rankset/getApiProductList", data, 'GET', 'productViewList');
	}

	function defaultReturn(seq){
		// console.log(seq);
		//$(".tt_"+seq).find('input').reset();
		$(".tt_"+seq).find('input[type=text]').val('');
		$(".tt_"+seq).find('input[type=date]').val('');
		$(".tt_"+seq).find('input[type=time]').val('');
		$(".tt_"+seq).find('input[type=checkbox]').prop('checked', false);
		$(".v_"+seq+"_fir_cate_code").val('');
		$(".v_"+seq+"_sec_cate_code").val('');
		$(".v_"+seq+"_ts_code").val('');
		$(".v_"+seq+"_sec_ts_code").val('');

		productColorSet();
	}

	function productColorSet(){
		$('.p_p').css('background-color', '#ffffff');
		// $('.p_p').find('td > .p_input > input').show();
		// console.log($('.p_p').find('.p_input > .addProductViewSetBtn').show());
		$('.p_p').find('.p_input > .addProductViewSetBtn').show();
		$('.p_p').find('.p_input > .p_input_text').hide();

		$('.sec_ts_code').each(function ( index ) {
			//console.log($(this).val());
			$('.aa_'+$(this).val()).css('background-color', 'yellow');
			$('.aa_'+$(this).val()).find('.p_input > input').hide();
			$('.aa_'+$(this).val()).find('.p_input > .p_input_text').show();
		});

		console.log('asdf');
	}
</script>
