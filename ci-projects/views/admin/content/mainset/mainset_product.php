<style>
	.sort_li { background-color: #ffffff; clear: both; height: 80px; border: 1px solid #000000; border-radius: 5px; margin-bottom: 5px; }
</style>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<div class="col-xs-6">
					<table class="table table-bordered table-hover" id="list_table">
						<colgroup>
							<col width="10%">
							<col width="90%">
						</colgroup>
						<tbody>
							<tr>
								<th class="text-center" colspan="2">
									CRM 카테고리를 변경 하였을때 기존의 상품 카테고리와 일치 하지 않을경우 기존 설정된 상품은 노출 되지 않습니다.
								</th>
							</tr>
							<tr>
								<th class="text-center">
									카테고리
								</th>
								<td><?=isset($crmCateName) ? $crmCateName : '' ?>&nbsp;(<?=isset($crmCate) ? $crmCate : '' ?>)</td>
							</tr>
							<tr>
								<th class="text-center">
									테마 <br />
									<input type="button" class="btn btn-yahoo btn-sm" value="미리보기" data-toggle="modal" data-target="#modal-branch" />
								</th>
								<td>
									<select name="themaId" id="themaId" class="form-control">
										<option value="">선택하세요.</option>
										<option value="thema1">테마1</option>
									</select>
								</td>
							</tr>
							<tr>
								<th class="text-center">
									상품
								</th>
								<td>
									<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
										<?for ( $i = 0; $i < 3; $i++ ) {?>
											<li class="sort_li">
												<div class="col-xs-12" style="padding-left: 0px;">
													<table class="col-xs-12 table" style="margin-bottom: 0px;">
														<tr>
															<td class="text-left" colspan="3">상품명</td>
															<td class="text-right" rowspan="2">
																<input type="button" class="btn btn-default btn-sm" value="삭제" onclick="delete_crm_product(this)">
															</td>
														</tr>
														<tr>
															<td class="text-left">가격1 : </td>
															<td class="text-left">가격2 : </td>
															<td class="text-left">가격3 : </td>
														</tr>
													</table>
												</div>
											</li>
										<?}?>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<?//CRM상품?>
				<div class="col-xs-6">CRM상품</div>
			</div>

			<div class="modal-footer text-center" style="text-align: center;">
				<button type="button" class="btn btn-primary" id="add_main_save" >저장</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
			</div>

		</div>

	</div>
</div>


<div class="modal fade" id="modal-branch">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-content">
			<div class="modal-body">
				<div id="modal_contents">
					<table id="division_table" class="table table-bordered">
						<tr>
							<th>테마1</th>
						</tr>
						<tr>
							<td>
								<img src="<?=getenv(IMG_PATH_THEMA)?>/thema1.png" />
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$("#viewId").sortable();

	})

	function delete_crm_product(that){
		//console.log($(that).parent().parent().parent().parent().parent().parent().html());
		$(that).parent().parent().parent().parent().parent().parent().remove();
	}
</script>
