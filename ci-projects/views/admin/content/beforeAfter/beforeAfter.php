<div class="row">
	<div class="col-xs-10" style="padding-bottom:15px; padding-left: 0px;">
		<form id="searchFrm" method="get" class="">
			<div class="col-xs-7 left" style="padding-left: 0px;">
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

		<div class="col-xs-5 right" style="padding-right: 0px;">
			<a href="/home_admin/notice/modifynotice/"
			   class="btn btn-dark"
			   id="add_btn"
			   style="float:right"
			>
				추가
			</a>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" id="total_count">Total : <?=isset($branchList) ? count($branchList) : '0'?></h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="5%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
					<tr>
						<th class="text-center">언어</th>
						<th class="text-center">분류</th>
						<th class="text-center">제목</th>
						<th class="text-center">시술 전 기간</th>
						<th class="text-center">시술 후 기간</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if ( isset($branchListData) ) {?>
						<?foreach ( $branchListData as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<input type="hidden"
										   value="<?=$row['SEQ'] ?>"
									/>
									<?=$row['BA_LANGUAGE'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BA_KIND1'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BA_TITLE'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BA_BEFORE_DATE'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BA_AFTER_DATE'] ?>
								</td>
								<td class='text-center'>
									<a href="/home_admin/notice/modifynotice/<?=$row['SEQ']?>">
										<button type='button' class='btn btn-warning'>수정</button>&nbsp;
									</a>
									<button type='button' class='btn btn-default' onclick="delete_notice('<?=$row['SEQ'] ?>')">삭제</button>&nbsp;
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

<div id="add_ajax_result"></div>

<script>
	$(document).ready(function() {
		$("#searchBranch").change(function () {
			//let BRANCH = $(this).val();
			$("#searchFrm").submit();

		});
	})

	function delete_notice(SEQ){
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
						sendAjaxHtml("/home_admin/notice/delete", data, 'POST', 'add_ajax_result');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>
