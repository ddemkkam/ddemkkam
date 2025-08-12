<?php/*
<div class="row">
	<div class="col-xs-8" style="padding-bottom:15px; padding-left: 0px;">
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

	</div>
</div>
*/?>
<div class="row">
	<div class="col-xs-8">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" id="total_count">Total : <?=isset($branchListData) ? count($branchListData) : '0'?></h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
					<tr>
						<th class="text-center">branch</th>
						<th class="text-center">지점명</th>
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
									<input type="hidden" name="BRANCH" value="<?=$row['BRANCH'] ?>" />
									<?=$row['BRANCH'] ?>
								</td>
								<td class='text-center'>
									<?=$row['BRANCH_NAME'] ?>
								</td>
								<td class='text-center'>
									<a href="/home_admin/termsset/modifyuseset/<?=isset($BASEBRANCH) ? $BASEBRANCH : ''?>/<?=isset($BASELAN) ? $BASELAN : ''?>">
										<button type='button' class='btn btn-twitter'>이용약관</button>&nbsp;
									</a>
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

<script>
	$(document).ready(function() {
		$("#searchBranch").change(function () {
			//let BRANCH = $(this).val();
			$("#searchFrm").submit();

		});
	})
</script>
