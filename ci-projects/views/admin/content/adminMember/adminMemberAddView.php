<form id="modal_frm">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modal-title">계정 등록</h4>
	</div>
	<div class="modal-body">
		<div id="modal_contents">
			<table id="division_table" class="table table-bordered">
				<colgroup>
					<col width="15%" />
					<col width="35%" />
				</colgroup>
				<tbody>
					<tr>
						<th class="active text-center">지점</th>
						<td>
							<select class="form-control" name="BRANCH" id="BRANCH">
								<option value="all" >선택하세요</option>
								<?if(isset($branchList)){?>
									<?foreach ( $branchList as $index => $row ) {?>
										<option value="<?=$row['BRANCH']?>"
											<?=isset($searchBranch) && $row['BRANCH'] === $searchBranch ? 'selected' : '' ?>
										>
											<?=$row['BRANCH_NAME']?>(<?=$row['BRANCH']?>)
										</option>
									<?}?>
								<?}?>
							</select>
						</td>
					</tr>
					<tr>
						<th class="active text-center">아이디</th>
						<td>
							<input type="text" class="form-control" name="ADMIN_ID" id="ADMIN_ID" value="" />
						</td>
					</tr>
					<tr>
						<th class="active text-center">패스워드</th>
						<td>
							<input type="text" class="form-control" placeholder="" name="PSDWD" id="PSDWD" value="" />
						</td>
					</tr>
					<tr>
						<th class="active text-center">이름</th>
						<td>
							<input type="text" class="form-control" placeholder="" name="ADMIN_NAME" id="ADMIN_NAME" value="" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="branch_save">저장</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	</div>
</form>

<div id="add_ajax_result"></div>

<script>
	$(document).ready(function() {
		$('#branch_save').on("click", function () {
			if ( $("#BRANCH").val() == 'all' ) {
				alert("지점을 선택하세요.");
				$("#BRANCH").focus();
				return false;
			}

			if ( $("#ADMIN_ID").val() == '' ) {
				alert("아이디를 입력하세요.");
				$("#ADMIN_ID").focus();
				return false;
			}

			if ( $("#PSDWD").val() == '' ) {
				alert("지점명을 입력하세요.");
				$("#PSDWD").focus();
				return false;
			}

			if ( $("#ADMIN_NAME").val() == '' ) {
				alert("이름을 입력하세요.");
				$("#ADMIN_NAME").focus();
				return false;
			}

			var regType1 = /^[A-Za-z0-9+]*$/;
			if ( !regType1.test( $("#ADMIN_ID").val() ) ) {
				alert('아이디는 영문 숫자만 입력해주세요.');
				return false;
			}

			sendAjaxHtml("/home_admin/adminMember/add_member", $("#modal_frm").serialize(), 'POST', 'add_ajax_result');
		});
	});
</script>
