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
						<select class="form-control" name="L_BRANCH" id="L_BRANCH">
							<option value="all" >선택하세요</option>
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
					</td>
				</tr>
				<tr>
					<th class="active text-center">메뉴명</th>
					<td>
						<input type="text" class="form-control" name="L_TITLE" id="L_TITLE" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">URL</th>
					<td>
						<input type="text" class="form-control" placeholder="" name="L_URL" id="L_URL" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">순서</th>
					<td>
						<input type="number" class="form-control" placeholder="" name="L_SORT" id="L_SORT" value="" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="l_menu_save">저장</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	</div>
</form>

<div id="add_ajax_result"></div>

<script>
	$(document).ready(function() {
		$('#l_menu_save').on("click", function () {
			if ( $("#L_BRANCH").val() == 'all' ) {
				alert("지점을 선택하세요.");
				$("#BRANCH").focus();
				return false;
			}

			if ( $("#L_TITLE").val() == 'all' ) {
				alert("메뉴명을 입력하세요.");
				$("#BRANCH").focus();
				return false;
			}

			// if ( $("#L_URL").val() == 'all' ) {
			// 	alert("URL을 입력하세요.");
			// 	$("#BRANCH").focus();
			// 	return false;
			// }

			if ( $("#L_SORT").val() == 'all' ) {
				alert("순서를 입력하세요.");
				$("#BRANCH").focus();
				return false;
			}

			sendAjaxHtml("/home_admin/menuset/addMenuset", $("#modal_frm").serialize(), 'POST', 'add_ajax_result');
		});
	});
</script>
