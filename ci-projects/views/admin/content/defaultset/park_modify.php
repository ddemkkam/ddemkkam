<div class="row">
	<div class="col-xs-8">
		<div class="box">

			<div class="box-body">
				<form id="ppeumInfoFrm" >
					<input type="hidden" name="BRANCH" value="<?=isset($BASEBRANCH) ? $BASEBRANCH : '' ?>">
					<input type="hidden" name="LANGUAGE" value="<?=isset($BASELAN) ? $BASELAN : '' ?>">
					<input type="hidden" name="MODE" value="<?=isset($mode) ? $mode : '' ?>">
					<table class="table table-bordered table-hover" id="list_table">
						<colgroup>
							<col width="10%">
							<col width="90%">
						</colgroup>


						<tr>
							<th class="text-center">
								주차장<br />
								<button type="button" class="btn btn-primary" id="add_park" onclick="addParkFun()">추가</button>
							</th>
							<td>
								<div id="parkTable">
									<?$info['H_PARKING'] = json_decode($info['H_PARKING']);?>
									<?if ( isset($info['H_PARKING']) && $info['H_PARKING'] !== '' ) {?>
										<?foreach ( $info['H_PARKING'] as $index => $row ) {?>
											<table class="park_table park_table_<?=$index + 1?> table table-bordered table-hover" >
												<colgroup>
													<col width="30%">
													<col width="70%">
												</colgroup>
												<tr>
													<th class="text-center" style="width: 100px;">타이틀</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="내부주차장"
															name="H_PARK_TITLE[]"
															value="<?=isset($row->H_PARK_TITLE) ? $row->H_PARK_TITLE : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">위치</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="서울시 강남구 강남대로 438 (역삼동, 스타플렉스)"
															name="H_PARK_ADDRESS[]"
															value="<?=isset($row->H_PARK_ADDRESS) ? $row->H_PARK_ADDRESS : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">설명1</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="제한 차량 없음, 무료 1시간 지원"
															name="H_PARK_DESC1[]"
															value="<?=isset($row->H_PARK_DESC1) ? $row->H_PARK_DESC1 : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">설명2</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="발렛비 3,000원 / 10분 초과당, 1,000원 추가 비용 발생"
															name="H_PARK_DESC2[]"
															value="<?=isset($row->H_PARK_DESC2) ? $row->H_PARK_DESC2 : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">설명3</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="본원 도보 2분거리 위치"
															name="H_PARK_DESC3[]"
															value="<?=isset($row->H_PARK_DESC3) ? $row->H_PARK_DESC3 : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">
														카카오 지도 html<br />
														(430 x 215)
														<br />
														카카오맵 > 공유하기 > HTML 태그 복사 ><br /> 소스생성하기 > 이미지지도
													</th>
													<td>
														<textarea
															class="form-control"
															name="H_PARK_KAKAO_MAP[]"
															style="height: 100px;"
														><?=isset($row->H_PARK_KAKAO_MAP) ? $row->H_PARK_KAKAO_MAP : '' ?></textarea>
													</td>
												</tr>
												<tr>
													<th class="text-center">네이버 MAP</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="https://naver.me/5oQX1znJ"
															name="H_PARK_NAVER[]"
															value="<?=isset($row->H_PARK_NAVER) ? $row->H_PARK_NAVER : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">카카오 MAP</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="https://kko.to/siAELUEVvq"
															name="H_PARK_KAKAO[]"
															value="<?=isset($row->H_PARK_KAKAO) ? $row->H_PARK_KAKAO : '' ?>"
														>
													</td>
												</tr>
												<tr>
													<th class="text-center">T MAP</th>
													<td>
														<input
															type="text"
															class="form-control"
															placeholder="https://tmap.life/2a115bf5"
															name="H_PARK_TMAP[]"
															value="<?=isset($row->H_PARK_TMAP) ? $row->H_PARK_TMAP : '' ?>"
														>
													</td>
												</tr>
												<?if ( $index + 1 > 1 ) {?>
													<tr>
														<th class="text-center" colspan="2">
															<button type="button" class="btn btn-primary" id="deletePark" onclick="deleteParkFun('park_table_<?=$index + 1?>')">삭제</button>
														</th>
													</tr>
												<?}?>
											</table>
										<?}?>
									<?}?>
								</div>
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
						sendAjaxHtml("/home_admin/park/modify_default", data, 'POST', 'result_content');
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
			url: '/home_admin/park/addPark',
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
