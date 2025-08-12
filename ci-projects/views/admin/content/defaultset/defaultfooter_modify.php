<div class="row">
	<div class="col-xs-6">
		<div class="box">

			<div class="box-body">
				<form id="ppeumInfoFrm" >
					<input type="hidden" name="BRANCH" value="<?=isset($BASEBRANCH) ? $BASEBRANCH : '' ?>">
					<input type="hidden" name="LANGUAGE" value="<?=isset($BASELAN) ? $BASELAN : '' ?>">
					<input type="hidden" name="MODE" value="<?=isset($mode) ? $mode : '' ?>">
					<table class="table table-bordered table-hover" id="list_table">
						<colgroup>
							<col width="20%">
							<col width="80%">
						</colgroup>

						<tr>
							<th class="text-center">상호명</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="쁨클리닉"
									name="H_NAME"
									value="<?=isset($info['H_NAME']) ? $info['H_NAME'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">대표번호</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="02-593-3344"
									name="H_CONTACT_M"
									value="<?=isset($info['H_CONTACT_M']) ? $info['H_CONTACT_M'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">대표자</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="홍길동"
									name="H_CEO"
									value="<?=isset($info['H_CEO']) ? $info['H_CEO'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">사업자등록번호</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_OFFICENUMBER"
									value="<?=isset($info['H_OFFICENUMBER']) ? $info['H_OFFICENUMBER'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">주소</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="서울특별시 강남구 강남대로 470 808타워 12층~15층, 13층(상담/접수)"
									name="H_ADDRESS"
									value="<?=isset($info['H_ADDRESS']) ? $info['H_ADDRESS'] : '' ?>"
								>
							</td>
						</tr>

					</table>
				</form>
				<div class="col-xs-12 text-center" style="margin-top: 10px;">
					<button type="button" class="btn btn-primary" id="info_save" onclick="info_save()">저장</button>
					&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-default" onclick="history.back();">취소</button>
				</div>
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
		$(".sortable").sortable();
	})

	function file_upload_open(){
		window.open('/fileUpload/uploadView?id=favicon&cnt=1&viewId=viewId', '이미지 업로드', 'width=700px,height=250px,scrollbars=yes');
	}

	function info_save(){
		//console.log($("#ppeumInfoFrm").serializeArray());
		let titleMsg = "";
		let data = $("#ppeumInfoFrm").serialize();
		let title = "";

		if ( $("input[name='MODE']").val() === 'insert' ) {
			titleMsg = "저장 하시겠습니까?";
			title = "저장";
		} else {
			titleMsg = "수정 하시겠습니까?";
			title = "수정";
		}

		$.confirm({
			title: "",
			content: titleMsg,
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "저장",
			buttons: {
				tryAgain: {
					text: title,
					btnClass: 'btn-blue',
					action: function(){
						sendAjaxHtml("/home_admin/footer/modify_default", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}

	function addParkFun(){
		let id = Number($(".park_table").length) + 1;
		console.log(id);

		$.ajax({
			url: '/home_admin/defaultset/addPark',
			data: { 'id' : id },
			method: 'GET',
			dataType: 'html',
			// async: async,
			success:function(data){
				console.log(data);
				$("#parkTable").append(data);
			}
			,fail:function(xhr, status, errorThrown) {
				console.log("오류가 발생하였습니다.<br>");
				console.log("오류명: " + errorThrown + "<br>");
				console.log("상태: " + status);
			}
		});

	}

	function deleteParkFun(id){
		$("."+id).remove();
	}


</script>
