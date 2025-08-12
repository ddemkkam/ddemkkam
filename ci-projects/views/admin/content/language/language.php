<div class="row">
<!--	<div class="col-xs-8" style="padding-bottom:15px; padding-left: 0px;">-->
<!--		<a href="#"-->
<!--		   class="btn btn-dark"-->
<!--		   id="add_btn"-->
<!--		   style="float:right; "-->
<!--		   data-toggle="modal"-->
<!--		   data-target="#modal-branch"-->
<!--		   onclick="langaueAdd()"-->
<!--		>-->
<!--			신규등록-->
<!--		</a>-->
<!--	</div>-->
</div>
<div class="row">
	<div class="col-xs-8">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" id="total_count">Total : <?=isset($lanListData) ? count($lanListData) : '0'?></h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered table-hover" id="list_table">
					<colgroup>
						<col width="15%">
						<col width="70%">
						<col width="15%">
					</colgroup>
					<thead>
					<tr>
						<th class="text-center">언어</th>
						<th class="text-center">참여지점</th>
						<th class="text-center">관리</th>
					</tr>
					</thead>
					<tbody>
					<?if ( isset($lanListData) ) {?>
						<?foreach ( $lanListData as $index => $row ) {?>
							<tr class="data_list">
								<td class='text-center'>
									<input type="hidden"
										   value="<?=$row['SEQ'] ?>"
									/>
									<?=$row['COUNTRY_NAME'] ?>
								</td>
								<td class='text-center'>
									<?foreach ( $row['BRANCHLIST'] as $index2 => $row2 ) {
										if ( $index2 !== 0 ) {
											echo ", ";
										}
										echo $row2['BRANCH_NAME'];
									}?>
								</td>
								<td class='text-center'>
									<a href="/home_admin/language/modifylanguage/<?=$row['COUNTRY']?>">
										<button type='button' class='btn btn-warning'>수정</button>
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

	});

	function languageAdd(){

	}
</script>
