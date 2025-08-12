<div class="row">
	<div class="col-xs-8" style="padding-bottom:15px; padding-left: 0px;">
		<form id="frm" method="get" class="">
			<div class="col-xs-7 left" style="padding-left: 0px;">
				<div class="col-xs-6 left">
					<input
						type="text" name="branch"
						placeholder="지점명을 입력하세요."
						class="form-control"
						value="<?=isset($branch) && $branch !== '' ? $branch : '' ?>"
					/>
				</div>

				<div class="col-xs-2 left">
					<input type="submit" class="btn btn-success" value="검색">
				</div>
			</div>
		</form>

		<div class="col-xs-5 right" style="padding-right: 0px;">
			<a href="#"
			   class="btn btn-dark"
			   id="add_btn"
			   style="float:right; "
			   data-toggle="modal"
			   data-target="#modal-branch"
			   onclick="branchAdd()"
			>
				신규등록
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-8">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" id="total_count">Total : <?=isset($branchList) ? count($branchList) : '0'?></h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
					<tr>
						<th class="text-center">branch</th>
						<th class="text-center">지점명</th>
						<th class="text-center">장소</th>
						<th class="text-center">DB맵핑코드</th>
						<th class="text-center">주요지점</th>
						<th class="text-center">순서</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if ( isset($branchList) ) {?>
						<?foreach ( $branchList as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<input type="hidden"
										   value="<?=$row['SEQ'] ?>"
									/>
									<input type="text"
										   class="form-control"
										   name="BRANCH"
										   readonly
										   value="<?=$row['BRANCH'] ?>"
									/>
								</td>
								<td class='text-center'>
									<input type="text"
										   class="form-control"
										   name="BRANCH_NAME"
										   value="<?=$row['BRANCH_NAME'] ?>"
									/>
								</td>
								<td class='text-center'>
									<select name="PLACE_NAME" class="form-control">
										<option value="" >선택하세요.</option>
										<?if ( isset($placeList) ) {
											foreach ( $placeList as $index2 => $row2 ) {?>
												<option value="Y" <?=$row['PLACE_ID'] === $row2['SEQ'] ? 'selected' : '' ?> ><?=$row2['PLACE_NAME']?></option>
										<?	}
										}?>
									</select>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="EVENT_MAP"
										   class="form-control"
										   value="<?=$row['EVENT_MAP'] ?>"
									/>
								</td>
								<td class='text-center'>
									<select name="MAIN_BRANCH" class="form-control">
										<option value="Y" <?=$row['MAIN_BRANCH'] === 'Y' ? 'selected' : '' ?> >Y</option>
										<option value="N" <?=$row['MAIN_BRANCH'] === 'N' ? 'selected' : '' ?> >N</option>
									</select>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="SORT"
										   class="form-control"
										   value="<?=$row['SORT'] ?>"
									/>
								</td>
								<td class='text-center'>
									<button type='button' class='btn btn-warning' onclick='branchModify(this, "m")'>수정</button>&nbsp;
									<button type='button' class='btn btn-default' onclick='branchModify(this, "d")'>삭제</button>
								</td>
							</tr>
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

<div id="result_content"></div>

<script>
	function branchAdd(){
		sendAjaxHtml("/home_admin/branch/addBranchView", null, 'GET', 'modal-content');
	}



	function branchModify(that, mode){
		//console.log($(that).parent().parent().html());
		let SEQ = $(that).parent().parent().find("td > input[name='SEQ']").val();
		let BRANCH = $(that).parent().parent().find("td > input[name='BRANCH']").val();
		let BRANCH_NAME = $(that).parent().parent().find("td > input[name='BRANCH_NAME']").val();
		let SORT = $(that).parent().parent().find("td > input[name='SORT']").val();
		let EVENT_MAP = $(that).parent().parent().find("td > input[name='EVENT_MAP']").val();
		let MAIN_BRANCH = $(that).parent().parent().find("td > select[name='MAIN_BRANCH']").val();
		let title = "";
		let data = {};

		if ( mode === 'm' ) {
			data = {
				"SEQ": SEQ
				, "BRANCH": BRANCH
				, "BRANCH_NAME": BRANCH_NAME
				, "SORT": SORT
				, "EVENT_MAP": EVENT_MAP
				, "MAIN_BRANCH": MAIN_BRANCH
			}
			titleMsg = "수정 하시겠습니까?";
			title = "수정";
		} else if ( mode === 'd' ) {
			data = {
				"BRANCH": BRANCH
			}
			titleMsg = "삭제 하시겠습니까?<br />삭제 후에는 복구할 수 없습니다.";
			title = "삭제";
		}
		//console.log(data); return false;

		$.confirm({
			title: "",
			content: titleMsg,
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "닫기",
			buttons: {
				tryAgain: {
					text: title,
					btnClass: 'btn-blue',
					action: function(){
						if ( mode === 'm' ) {
							sendAjaxHtml("/home_admin/branch/modifyBranch", data, 'POST', 'result_content');
						} else if ( mode === 'd' ) {
							sendAjaxHtml("/home_admin/branch/deleteBranch", data, 'POST', 'result_content');
						}

					}
				},
				close: function () {
				}
			}
		});
	}


</script>
