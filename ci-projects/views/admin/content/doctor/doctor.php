<div class="row">
	<div class="col-xs-12" style="padding-bottom:15px; padding-left: 0px;">

		<form id="searchFrm" method="get" class="">
			<div class="col-xs-7 left" style="padding-left: 0px;">
				<div class="col-xs-6 left">
					<select class="form-control" name="searchBranch" id="searchBranch">
						<option value="all" >전체</option>
						<?if(isset($branchList)){?>
							<?foreach ( $branchList as $index => $row ) {?>
								<option value="<?=$row['BRANCH']?>"
									<?=isset($searchBranch) && $row['BRANCH'] === $searchBranch2 ? 'selected' : '' ?>
								>
									<?=$row['BRANCH_NAME']?>(<?=$row['BRANCH']?>)
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
			<a href="/home_admin/doctor/registDoctor/<?=$BASEBRANCH ?>/<?=$BASELAN ?>"
			   class="btn btn-dark"
			   id="add_btn"
			   style="float:right; "
			   onclick="doctorAdd()"
			>
				등록
			</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">

				<table class="table table-bordered table-hover">
					<tr>
						<colgroup>
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="20%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
						</colgroup>
					</tr>
					<tr>
						<th class="text-center">지점</th>
						<th class="text-center">원장님이름(KR)</th>
						<th class="text-center">원장님이름(EN)</th>
						<th class="text-center">설명</th>
						<th class="text-center">대표원장님여부</th>
						<th class="text-center">사용여부</th>
						<th></th>
					</tr>
					<?if ( isset($doctorList) ) {
						foreach ( $doctorList as $index => $row ){?>
							<tr>
								<td class="text-center"><?=isset($row['BRANCH_NAME']) ? $row['BRANCH_NAME'].'('.$row['D_BRANCH'].')' : ''?></td>
								<td class="text-center"><?=isset($row['D_NAME']) ? $row['D_NAME'] : ''?></td>
								<td class="text-center"><?=isset($row['D_NAME_EN']) ? $row['D_NAME_EN'] : ''?></td>
								<td><?=isset($row['D_DESC']) ? nl2br($row['D_DESC']) : ''?></td>
								<td class="text-center"><?=isset($row['D_MAIN_YN']) ? $row['D_MAIN_YN'] : ''?></td>
								<td class="text-center"><?=isset($row['D_USE_YN']) ? $row['D_USE_YN'] : ''?></td>
								<td class="text-center">
									<a href="/home_admin/doctor/doctorModify/<?=$BASEBRANCH ?>/<?=$BASELAN ?>/<?=$row['SEQ'] ?>">
										<button type='button' class='btn btn-warning'>수정</button>&nbsp;
									</a>
									<button type='button' class='btn btn-default' onclick="deleteDoctor('<?=$row['SEQ'] ?>')">삭제</button>&nbsp;
								</td>
							</tr>
					<?	}
					}?>
				</table>

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

	function deleteDoctor(seq){
		let data = {'SEQ' : seq};

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
						sendAjaxHtml("/home_admin/doctor/delete", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>

