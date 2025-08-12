<style>
	.tr_style {border:3px solid #000000;}
</style>
<script src="/publish/bower_components/ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-body">
				<form id="frm" method="POST" >
					<input type="hidden" name="LAN" value="<?=isset($lan) ? $lan : '' ?>" />
					<table id="division_table" class="table table-bordered">
						<colgroup>
							<col width="5%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
						</colgroup>
						<tbody id="add_branch_tr">
							<tr>
								<th class="active text-center">언어</th>
								<th class="active text-center">BRANCH</th>
								<th class="active text-center">지점명</th>
								<th class="active text-center">시작일</th>
								<th class="active text-center">종료일</th>
								<th class="active text-center">관리</th>
							</tr>
							<tr>
								<th colspan="6" style="padding: 0px;">
									<input type="button" class="btn btn-yahoo" value="추가" style="width: 100%;" onclick="addBranch();">
								</th>
							</tr>

							<?if ( isset($branchLanData) ) {
								foreach ( $branchLanData as $index => $row ){?>
									<tr>
										<td class="active text-center">
											<?=isset($lan) ? $lan : '' ?>
										</td>
										<td class="text-center">
											<input type="text" name="BRANCH[]" class="form-control seBranch" readonly value="<?=isset($row['BRANCH']) ? $row['BRANCH'] : ''?>" />
										</td>
										<td class="text-center">
											<input type="text" class="form-control" readonly value="<?=isset($row['BRANCH_NAME']) ? $row['BRANCH_NAME'] : ''?>" />
										</td>
										<td class="text-center">
											<input type="date" class="form-control" name="START_DATE[]" value="" />
										</td>
										<td class="text-center">
											<input type="date" class="form-control"	name="FINISH_DATE[]" value="" />
										</td>
										<td class="text-center">
											<input type="button" class="btn btn-default" value="삭제" onclick="deleteBranch(this)" />
										</td>
									</tr>
							<?	}
							}?>

						</tbody>
					</table>
					<div class="text-center">
						<button type="button" class="btn btn-primary" onclick="saveLanBranch()">저장</button>
						<button type="button" class="btn btn-default" onclick="history.back()">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="add_ajax_result"></div>

<script type="text/javascript">

	$(document).ready(function() {

	})

	function addBranch() {
		//add_branch_tr
		//var add_html = '';
		let data = { "lan" : "<?=isset($lan) ? $lan : ''?>" };
		//sendAjaxHtml("/home_admin/language/addAjax", data, 'POST', 'add_ajax_result');

		$.ajax({
			url: "/home_admin/language/addAjax",
			data: data,
			method: "POST",
			dataType: 'html',
			success:function(data_html){
				console.log(data_html);
				$("#add_branch_tr").append(data_html);
			}
			,fail:function(xhr, status, errorThrown) {
				console.log("오류가 발생하였습니다.<br>");
				console.log("오류명: " + errorThrown + "<br>");
				console.log("상태: " + status);
			}
		});
	}

	function saveLanBranch(){
		//console.log('asdf'); return false;
		let data = $("#frm").serialize();
		sendAjaxHtml("/home_admin/language/saveBranchMap", data, 'POST', 'add_ajax_result');
	}

	function checkBranch(that){
		const branch = $(that).val();
		let cnt = 0;
		//console.log(branch);
		$(".seBranch").each(function() {
			//console.log(branch + " - " + $(this).val());
			if ( $(this).val() === branch ) {
				cnt++;
			}
		});
		console.log(cnt);
		if ( cnt > 1 ) {
			alert("이미 추가된 지점입니다.");
			$(that).val("");
			return false;
		}
	}

	function deleteBranch(that){
		//console.log($(that).parent().parent().html());
		$(that).parent().parent().remove();
	}



</script>
