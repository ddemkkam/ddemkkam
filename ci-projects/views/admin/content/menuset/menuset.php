<div class="row">
	<div class="col-xs-10" style="padding-bottom:15px; padding-left: 0px;">
		<form id="searchFrm" method="get" class="">
			<div class="col-xs-7 left">
				<div class="col-xs-4 left" style="padding-left: 0px;">
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

		<div class="col-xs-5 right" style="padding-right: 0px;">
			<a href="#"
			   class="btn btn-dark"
			   id="add_btn"
			   style="float:right"
			   data-toggle="modal"
			   data-target="#modal-branch"
			   onclick="menuAdd()"
			>
				신규등록
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" id="total_count">Total : <?=isset($menuList) ? count($menuList) : '0'?></h3>
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
						<th class="text-center">지점</th>
						<th class="text-center">메뉴명</th>
						<th class="text-center">URL</th>
						<th class="text-center">순서</th>
						<th class="text-center">사용여부</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if (isset($menuList)) {?>
						<?foreach ( $menuList as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<input type="hidden"
										   name="SEQ"
										   value="<?=$row['SEQ'] ?>"
									/>
									<?=$row['L_BRANCH'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BRANCH_NAME'] ?>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="L_TITLE"
										   class="form-control"
										   value="<?=$row['L_TITLE'] ?>"
								</td>
								<td class='text-center'>
									<input type="text"
										   name="L_URL"
										   class="form-control"
										   value="<?=$row['L_URL'] ?>"
								</td>
								<td class='text-center'>
									<input type="number"
										   name="L_SORT"
										   class="form-control"
										   value="<?=$row['L_SORT'] ?>"
									/>
								</td>
								<td class='text-center'>
									<select name="L_USE_YN" class="form-control">
										<option value="Y" <?=$row['L_USE_YN'] === 'Y' ? 'selected' : '' ?> />사용</option>
										<option value="N" <?=$row['L_USE_YN'] === 'N' ? 'selected' : '' ?> />미사용</option>
									</select>
								</td>
								<td class='text-center'>
									<button type='button' class='btn btn-warning' onclick='adminModify(this, "m")'>수정</button>&nbsp;
									<button type='button' class='btn btn-default' onclick='adminModify(this, "d")'>삭제</button>
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
	$(document).ready(function() {
		$("#searchBranch").change(function () {
			//let BRANCH = $(this).val();
			$("#searchFrm").submit();

		});
	})

	function adminModify(that, mode){
		//console.log($(that).parent().parent().html());
		let SEQ = $(that).parent().parent().find("td > input[name='SEQ']").val();
		let L_TITLE = $(that).parent().parent().find("td > input[name='L_TITLE']").val();
		let L_URL = $(that).parent().parent().find("td > input[name='L_URL']").val();
		let L_SORT = $(that).parent().parent().find("td > input[name='L_SORT']").val();
		let L_USE_YN = $(that).parent().parent().find("td > select[name='L_USE_YN']").val();

		let title = "";
		let data = {};

		if ( mode === 'm' ) {
			data = {
				"SEQ": SEQ
				, "L_TITLE" : L_TITLE
				, "L_URL": L_URL
				, "L_SORT": L_SORT
				, "L_USE_YN" : L_USE_YN
			}
			titleMsg = "수정 하시겠습니까?";
			title = "수정";
		} else if ( mode === 'd' ) {
			data = {
				"SEQ": SEQ
			}
			titleMsg = "삭제 하시겠습니까?<br />삭제 후에는 복구할 수 없습니다.";
			title = "삭제";
		}
//		console.log(data); return false;

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
							sendAjaxHtml("/home_admin/menuset/modifyMenu", data, 'POST', 'result_content');
						} else if ( mode === 'd' ) {
							sendAjaxHtml("/home_admin/menuset/deleteMenu", data, 'POST', 'result_content');
						}

					}
				},
				close: function () {
				}
			}
		});
	}


	function menuAdd(){
		sendAjaxHtml("/home_admin/menuset/menusetAddView", null, 'GET', 'modal-content');
	}


</script>
