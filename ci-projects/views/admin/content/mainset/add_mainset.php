<form id="modal_frm">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modal-title">추가</h4>
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
						<select class="form-control" name="ADD_M_BRANCH" id="ADD_M_BRANCH">
							<option value="" >선택하세요</option>
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
					<th class="active text-center">언어</th>
					<td>
						<select name="ADD_M_LANGUAGE" id="ADD_M_LANGUAGE" class="form-control">

						</select>
					</td>
				</tr>
				<tr>
					<th class="active text-center">타이틀</th>
					<td>
						<select name="ADD_M_TITLE" id="ADD_M_TITLE" class="form-control">
							<option value="">선택하세요.</option>
							<option value="cate1">카테고리1</option>
							<option value="cate2">카테고리2</option>
							<option value="cate3">카테고리3</option>
						</select>
<!--						<input type="text" class="form-control" placeholder="" name="ADD_M_TITLE" id="ADD_M_TITLE" value="" />-->
					</td>
				</tr>
				<tr>
					<th class="active text-center">서브</th>
					<td>
						<input type="text" class="form-control" placeholder="" name="ADD_M_SUB_TITLE" id="ADD_M_SUB_TITLE" value="" />
					</td>
				</tr>
				<tr>
					<th class="active text-center">우선 시작</th>
					<td>
						<div class="col-xs-6" style="padding-left: 0px;">
							<input type="date" class="form-control" placeholder="" name="ADD_M_FIRST_START_DATE" id="ADD_M_FIRST_START_DATE" value="" />
						</div>
						<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
							<input type="time" class="form-control" placeholder="" name="ADD_M_FIRST_START_TIME" id="ADD_M_FIRST_START_TIME" value="" />
						</div>
					</td>
				</tr>
				<tr>
					<th class="active text-center">우선 종료</th>
					<td>
						<div class="col-xs-6" style="padding-left: 0px;">
							<input type="date" class="form-control" placeholder="" name="ADD_M_FIRST_FINISH_DATE" id="ADD_M_FIRST_FINISH_DATE" value="" />
						</div>
						<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
							<input type="time" class="form-control" placeholder="" name="ADD_M_FIRST_FINISH_TIME" id="ADD_M_FIRST_FINISH_TIME" value="" />
						</div>
					</td>
				</tr>
				<tr>
					<th class="active text-center">노출 시작</th>
					<td>
						<div class="col-xs-6" style="padding-left: 0px;">
							<input type="date" class="form-control" placeholder="" name="ADD_M_VIEW_START_DATE" id="ADD_M_VIEW_START_DATE" value="" />
						</div>
						<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
							<input type="time" class="form-control" placeholder="" name="ADD_M_VIEW_START_TIME" id="ADD_M_VIEW_START_TIME" value="" />
						</div>
					</td>
				</tr>
				<tr>
					<th class="active text-center">노출 종료</th>
					<td>
						<div class="col-xs-6" style="padding-left: 0px;">
							<input type="date" class="form-control" placeholder="" name="ADD_M_VIEW_FINISH_DATE" id="ADD_M_VIEW_FINISH_DATE" value="" />
						</div>
						<div class="col-xs-6" style="padding-left: 0px; padding-right: 0px;">
							<input type="time" class="form-control" placeholder="" name="ADD_M_VIEW_FINISH_TIME" id="ADD_M_VIEW_FINISH_TIME" value="" />
						</div>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-primary" id="add_main_save" >저장</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	</div>
</form>

<div id="add_ajax_result"></div>

<script>

	$(document).ready(function() {
		$("#ADD_M_BRANCH").on("change", function (){
			$.ajax({
				url: "/home_admin/mainset/getBranchToLanData/",
				data: { "BRANCH" : $(this).val() },
				method: "GET",
				dataType: 'html',
				success:function(data){
					// console.log(data);
					let jsonData = JSON.parse(data);
					let add_lan_sel_html = "<option value=''>선택하세요</option>";
					$.each(jsonData, function ( index, item ) {
						console.log(item.LANGAUE);
						add_lan_sel_html += "<option value='"+item.LANGAUE+"'>"+item.LANGAUE+"</option>";
					})
					$("select[name='ADD_M_LANGUAGE']").html(add_lan_sel_html);
				}
				,fail:function(xhr, status, errorThrown) {
					console.log("오류가 발생하였습니다.<br>");
					console.log("오류명: " + errorThrown + "<br>");
					console.log("상태: " + status);
				}
			});
		});

		$("#add_main_save").on("click", function (){
			let data = $("#modal_frm").serialize();

			if ( $("#ADD_M_BRANCH").val() === '' ) {
				alert('지점을 선택하세요');
				return;
			}

			if ( $("#ADD_M_LANGUAGE").val() === '' ) {
				alert('언어를 선택하세요');
				return;
			}

			if ( $("#ADD_M_TITLE").val() === '' ) {
				alert('타이틀을 선택하세요');
				return;
			}

			$.confirm({
				title: "",
				content: "저장 하시겠습니까?",
				type: 'blue',
				typeAnimated: true,
				columnClass: 'col-md-4 col-md-offset-4',
				containerFluid: true,
				closeButton: "저장",
				buttons: {
					tryAgain: {
						text: "저장",
						btnClass: 'btn-blue',
						action: function(){
							sendAjaxHtml("/home_admin/mainset/add_save", data, 'POST', 'add_ajax_result');
						}
					},
					close: function () {
					}
				}
			});
		});



	});


</script>
