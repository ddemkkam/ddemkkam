<div class="row">
	<div class="col-xs-4">
		<div class="box">

			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
					<tr>
						<th class="text-center">언어</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if ( isset($branchListData) ) {?>
						<?foreach ( $branchListData as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<?=$row['LANGAUE'] ?>
								</td>
								<td class='text-center'>
									<a href="/home_admin/termsset/modifyuseset/all/public/<?=$row['LANGAUE']?>">
										<button type='button' class='btn btn-warning'>개인정보</button>
									</a>
									<a href="/home_admin/termsset/modifyuseset/all/child/<?=$row['LANGAUE']?>">
										<button type='button' class='btn btn-bitbucket'>미성년자</button>&nbsp;
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
