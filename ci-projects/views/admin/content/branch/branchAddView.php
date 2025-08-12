<form id="modal_branch_frm">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modal-title">지점 등록</h4>
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
						<input type="text" name="BRANCH" id="BRANCH" class="form-control" placeholder="ppeum_" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">지점명</th>
					<td>
						<input type="text" class="form-control" name="BRANCH_NAME" id="BRANCH_NAME" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">DB맵핑코드</th>
					<td>
						<input type="text" class="form-control" placeholder="" name="EVENT_MAP" id="EVENT_MAP" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">주요지점</th>
					<td>
						<select name="MAIN_BRANCH" class="form-control">
							<option value="Y" >Y</option>
							<option value="N" >N</option>
						</select>
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
			if ( $("#BRANCH").val() == '' ) {
				alert("지점을 입력하세요.");
				$("#BRANCH").focus();
				return;
			}

			if ( $("#BRANCH_NAME").val() == '' ) {
				alert("지점명을 입력하세요.");
				$("#BRANCH_NAME").focus();
				return;
			}

			if ( $("#EVENT_MAP").val() == '' ) {
				alert("DB 맵핑코드를 입력하세요.");
				$("#EVENT_MAP").focus();
				return;
			}

			sendAjaxHtml("/home_admin/branch/addBranch", $("#modal_branch_frm").serialize(), 'POST', 'add_ajax_result');
		});
	});
</script>
