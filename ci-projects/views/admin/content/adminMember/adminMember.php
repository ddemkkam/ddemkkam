<div class="row">
	<div class="col-xs-8" style="padding-bottom:15px; padding-left: 0px;">
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
				<div class="col-xs-5 left">
					<input
						type="text"
						name="nameId"
						placeholder="계정 및 이름을 입력하세요."
						class="form-control"
						value="<?=isset($nameId) && $nameId !== '' ? $nameId : '' ?>"
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
			   style="float:right"
			   data-toggle="modal"
			   data-target="#modal-branch"
			   onclick="memberAdd()"
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
				<h3 class="box-title" id="total_count">Total : <?=isset($adminList) ? count($adminList) : '0'?></h3>
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
						<th class="text-center">계정</th>
						<th class="text-center">이름</th>
						<th class="text-center">LEVEL</th>
						<th class="text-center">패스워드</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if (isset($adminList)) {?>
						<?foreach ( $adminList as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<input type="hidden"
										   name="SEQ"
										   value="<?=$row['SEQ'] ?>"
									/>
									<?=$row['BRANCH'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BRANCH_NAME'] ?>
								</td>
								<td class='text-center'>
									<input type="hidden" name="ADMIN_ID" value="<?=$row['ADMIN_ID'] ?>" />
									<?=$row['ADMIN_ID'] ?>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="ADMIN_NAME"
										   class="form-control"
										   value="<?=$row['ADMIN_NAME'] ?>"
									/>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="ADMIN_LEVEL"
										   class="form-control"
										   value="<?=$row['ADMIN_LEVEL'] ?>"
									/>
								</td>
								<td class='text-center'>
									<input type="text"
										   name="PSDWD"
										   class="form-control"
										   value="<?//=$row['PSDWD'] ?>"
									/>
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
		let ADMIN_ID = $(that).parent().parent().find("td > input[name='ADMIN_ID']").val();
		let ADMIN_NAME = $(that).parent().parent().find("td > input[name='ADMIN_NAME']").val();
		let ADMIN_LEVEL = $(that).parent().parent().find("td > input[name='ADMIN_LEVEL']").val();
		let PSDWD = $(that).parent().parent().find("td > input[name='PSDWD']").val();

		let title = "";
		let data = {};

		if ( mode === 'm' ) {
			data = {
				"SEQ": SEQ
				, "ADMIN_ID" : ADMIN_ID
				, "ADMIN_NAME": ADMIN_NAME
				, "ADMIN_LEVEL": ADMIN_LEVEL
				, "PSDWD" : PSDWD
			}
			titleMsg = "수정 하시겠습니까?";
			title = "수정";
		} else if ( mode === 'd' ) {
			data = {
				"ADMIN_ID": ADMIN_ID
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
							sendAjaxHtml("/home_admin/adminMember/modify_member", data, 'POST', 'result_content');
						} else if ( mode === 'd' ) {
							sendAjaxHtml("/home_admin/adminMember/delete_member", data, 'POST', 'result_content');
						}

					}
				},
				close: function () {
				}
			}
		});
	}


	function memberAdd(){
		sendAjaxHtml("/home_admin/adminMember/add_member_view", null, 'GET', 'modal-content');
	}


</script>
