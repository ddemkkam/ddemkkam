<div class="row">
	<div class="col-xs-12" style="padding-bottom:15px; padding-left: 0px;">
		<form id="searchFrm" method="get" class="">
			<div class="col-xs-4 left" style="padding-left: 0px;">
				<div class="col-xs-6 left">
					<select class="form-control" name="searchBranch" id="searchBranch">
						<option value="all" >전체</option>
						<?if(isset($branchList)){?>
							<?foreach ( $branchList as $index => $row ) {?>
								<option value="<?=$row['BRANCH']?>"
									<?=isset($searchBranch) && $row['BRANCH'] === $searchBranch ? 'selected' : '' ?>
								>
									<?=$row['BRANCH_NAME']?>
								</option>
							<?}?>
						<?}?>
					</select>
				</div>

				<div class="col-xs-2 left">
					<input type="submit" class="btn btn-success" value="검색">
				</div>
			</div>
		</form>

		<div class="col-xs-8 right" style="padding-right: 0px;">
			<a href="#"
			   class="btn btn-dark"
			   id="add_btn"
			   style="float:right"
			   data-toggle="modal"
			   data-target="#modal-branch"
			   onclick="mainAdd()"
			>
				추가
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="5%">
						<col width="7%">
						<col width="5%">
						<col width="3%">
						<col width="12%">
						<col width="12%">
						<col width="10%">
						<col width="10%">
						<col width="7%">
						<?if ( $this->session->userdata('ADMIN_LEVEL') === '99' ) {?>
							<col width="5%">
						<?}?>
					</colgroup>
					<thead>
					<tr>
						<th class="text-center"></th>
						<th class="text-center">branch</th>
						<th class="text-center">지점명</th>
						<th class="text-center">언어</th>
						<th class="text-center">CRM카테고리</th>
						<th class="text-center">서브타이틀</th>
						<th class="text-center">우선</th>
						<th class="text-center">노출</th>
						<th class="text-center">관리</th>
						<?if ( $this->session->userdata('ADMIN_LEVEL') === '99' ) {?>
							<th class="text-center">주요지점</th>
						<?}?>
					</tr>
					</thead>
					<tbody>
					<?if ( isset($getMainSetData) ) {?>
						<?foreach ( $getMainSetData as $index => $row ) {?>
							<form id="trData_<?=$index?>">
								<input type="hidden" name="SEQ" value="<?=$row['SEQ']?>" />
								<tr class="data_list">
									<td>
										<select name="M_USE_YN" class="form-control">
											<option value="Y" <?=$row['M_USE_YN'] === 'Y' ? 'selected' : '' ?> >Y</option>
											<option value="N" <?=$row['M_USE_YN'] === 'N' ? 'selected' : '' ?> >N</option>
										</select>
									</td>
									<td class='text-center'>
										<input type="hidden"
											   value="<?=$row['SEQ'] ?>"
										/>
										<input type="text"
											   class="form-control"
											   name="M_BRANCH"
											   readonly
											   value="<?=$row['M_BRANCH'] ?>"
										/>
									</td>
									<td class='text-center'>
										<?=$row['M_BRANCH_NAME'] ?>
									</td>
									<td class='text-center'>
										<?=$row['M_LANGUAGE'] ?>
									</td>
									<td class='text-center'>
										<?if ( isset($crmCategory) ){
											if ( is_array($crmCategory) ) {	?>
												<select name="M_TITLE" class="form-control">
													<option value="">선택하세요.</option>
													<?foreach ($crmCategory as $index2 => $caRow) {?>
														<option value="<?=$caRow['uid']?>" <?=$row['M_TITLE'] === $caRow['uid'] ? 'selected' : '' ?> >
															<?=$caRow['name']?>
														</option>
													<?}?>
												</select>
											<?}?>
										<?}?>
									</td>
									<td class='text-center'>
										<input type="text" name="M_SUB_TITLE" class="form-control" value="<?=$row['M_SUB_TITLE'] ?>">
									</td>
									<td class='text-center'>
										<table class="col-xs-12" style="padding-left: 0px; padding-left: 0px;">
											<tr>
												<td><input type="date" name="M_FIRST_START_DATE" class="form-control" value="<?=$row['M_FIRST_START_DATE'] ?>"></td>
												<td><input type="time" name="M_FIRST_START_TIME" class="form-control" value="<?=$row['M_FIRST_START_TIME'] ?>"></td>
											</tr>
											<tr>
												<td><input type="date" name="M_FIRST_FINISH_DATE" class="form-control" value="<?=$row['M_FIRST_FINISH_DATE'] ?>"></td>
												<td><input type="time" name="M_FIRST_FINISH_TIME" class="form-control" value="<?=$row['M_FIRST_FINISH_TIME'] ?>"></td>
											</tr>
										</table>
									</td>
									<td class='text-center'>
										<table class="col-xs-12" style="padding-left: 0px; padding-left: 0px;">
											<tr>
												<td><input type="date" name="M_VIEW_START_DATE" class="form-control" value="<?=$row['M_VIEW_START_DATE'] ?>"></td>
												<td><input type="time" name="M_VIEW_START_TIME" class="form-control" value="<?=$row['M_VIEW_START_TIME'] ?>"></td>
											</tr>
											<tr>
												<td><input type="date" name="M_VIEW_FINISH_DATE" class="form-control" value="<?=$row['M_VIEW_FINISH_DATE'] ?>"></td>
												<td><input type="time" name="M_VIEW_FINISH_TIME" class="form-control" value="<?=$row['M_VIEW_FINISH_TIME'] ?>"></td>
											</tr>
										</table>
									</td>
									<td class="text-center">
										<button type='button' class='btn btn-warning' onclick="modifyMainSet('trData_<?=$index?>')">수정</button>&nbsp;
										<button type='button' class='btn btn-default' onclick="deleteMainSet('<?=$row['SEQ']?>')">삭제</button>&nbsp;
										<button type='button' class='btn btn-twitter' onclick="mainProductSet('trData_<?=$index?>', '<?=$row['M_TITLE'] ?>')" >상품설정</button>&nbsp;
									</td>
									<?if ( $this->session->userdata('ADMIN_LEVEL') === '99' ) {?>
										<td class='text-center'>
											<?=$row['M_MAIN_BRANCH'] === 'Y' ? '주요' : '' ?>
										</td>
									<?}?>
								</tr>
							</form>
						<?}?>
					<?}?>
					</tbody>
				</table>
				<p id="list_empty" class="hidden text-center" style="margin:20px 0">등록된 데이터가 없습니다!</p>
			</div>
			<div class="box-footer clearfix text-center" id="pagingation">
				<?=isset($pagenation) ? $pagenation : '' ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-branch">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-content">

		</div>
	</div>
</div>

<div id="add_ajax_result"></div>

<script>
	$(document).ready(function() {
		$("#searchBranch").change(function () {
			//let BRANCH = $(this).val();
			$("#searchFrm").submit();

		});
	})

	function mainAdd(){
		sendAjaxHtml("/home_admin/mainset/addMainView", null, 'GET', 'modal-content');
	}

	function modifyMainSet(tr_id){
		//console.log(tr_id);
		//console.log($("#"+tr_id).serializeArray());
		//return false;
		let data = $("#"+tr_id).serialize();

		$.confirm({
			title: "",
			content: "수정 하시겠습니까?",
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
						sendAjaxHtml("/home_admin/mainset/modify", data, 'POST', 'add_ajax_result');
					}
				},
				close: function () {
				}
			}
		});
	}

	function deleteMainSet(SEQ){
		let data = { "SEQ" : SEQ };

		$.confirm({
			title: "",
			content: "삭제 하시겠습니까?",
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "삭제",
			buttons: {
				tryAgain: {
					text: "삭제",
					btnClass: 'btn-blue',
					action: function(){
						sendAjaxHtml("/home_admin/mainset/delete", data, 'POST', 'add_ajax_result');
					}
				},
				close: function () {
				}
			}
		});
	}

	function mainProductSet(tr_id, crmCate){
		let ogData = $("#"+tr_id).serializeArray();
		let og_M_TITLE = crmCate;
		let M_TITLE = "";
		let SEQ = "";
		console.log(ogData);
		$.each(ogData, function (index, val) {
			if ( val.name === 'SEQ' ) {
				SEQ = val.value;
			}

			if ( val.name === 'M_TITLE' ) {
				M_TITLE = val.value;
			}
		});

		if ( og_M_TITLE !== M_TITLE ) {
			alert("CRM 카테고리가 변경을 하였습니다.\n수정후 상품설정을 진행하시기 바랍니다.");
			return false;
		} else {
			location.href = "/home_admin/mainset/mainProductSet/"+SEQ+"/"+crmCate;
		}

		console.log(ogData['M_TITLE']);

	}
</script>
