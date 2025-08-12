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
							<th class="text-center">위치</th>
							<td>
								<div class="col-xs-12" style="padding-left: 0px;">
									<input
										type="text"
										class="form-control"
										placeholder="서울특별시 강남구 강남대로 470 808타워 12층~15층, 13층(상담/접수)"
										name="H_LOCATION1"
										value="<?=isset($info['H_LOCATION1']) ? $info['H_LOCATION1'] : '' ?>"
									>
								</div>
								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<input
										type="text"
										class="form-control"
										placeholder="2호선 강남역 11번 출구 도보 2분"
										name="H_LOCATION2"
										value="<?=isset($info['H_LOCATION2']) ? $info['H_LOCATION2'] : '' ?>"
									>
								</div>
								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<input
										type="text"
										class="form-control"
										placeholder="신분당선 신논현역 6번 출구 도보 5분"
										name="H_LOCATION3"
										value="<?=isset($info['H_LOCATION3']) ? $info['H_LOCATION3'] : '' ?>"
									>
								</div>

							</td>
						</tr>


						<tr>
							<th class="text-center">진료시간</th>
							<td>
								<div class="col-xs-12" style="padding-left: 0px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="평일"
											name="H_OFFICE_HOUR1_1"
											value="<?=isset($info['H_OFFICE_HOUR1_1']) ? $info['H_OFFICE_HOUR1_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6">
										<input
											type="text"
											class="form-control"
											placeholder="am 10:30 ~ pm 9:00"
											name="H_OFFICE_HOUR1_2"
											value="<?=isset($info['H_OFFICE_HOUR1_2']) ? $info['H_OFFICE_HOUR1_2'] : '' ?>"
										>
									</div>
								</div>

								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="토요일"
											name="H_OFFICE_HOUR2_1"
											value="<?=isset($info['H_OFFICE_HOUR2_1']) ? $info['H_OFFICE_HOUR2_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6" style="">
										<input
											type="text"
											class="form-control"
											placeholder="am 10:30 ~ pm 5:00"
											name="H_OFFICE_HOUR2_2"
											value="<?=isset($info['H_OFFICE_HOUR2_2']) ? $info['H_OFFICE_HOUR2_2'] : '' ?>"
										>
									</div>
								</div>

								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="일요일"
											name="H_OFFICE_HOUR3_1"
											value="<?=isset($info['H_OFFICE_HOUR3_1']) ? $info['H_OFFICE_HOUR3_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6" style="">
										<input
											type="text"
											class="form-control"
											placeholder="am 11:00 ~ pm 5:00"
											name="H_OFFICE_HOUR3_2"
											value="<?=isset($info['H_OFFICE_HOUR3_2']) ? $info['H_OFFICE_HOUR3_2'] : '' ?>"
										>
									</div>
								</div>

								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="공휴일"
											name="H_OFFICE_HOUR4_1"
											value="<?=isset($info['H_OFFICE_HOUR4_1']) ? $info['H_OFFICE_HOUR4_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6" style="">
										<input
											type="text"
											class="form-control"
											placeholder="am 11:00 ~ pm 5:00"
											name="H_OFFICE_HOUR4_2"
											value="<?=isset($info['H_OFFICE_HOUR4_2']) ? $info['H_OFFICE_HOUR4_2'] : '' ?>"
										>
									</div>
								</div>
							</td>
						</tr>



						<tr>
							<th class="text-center">
								카카오 지도
								<br />
								(430 x 215)
								<br />
								카카오맵 > 공유하기 > HTML 태그 복사 > 소스생성하기 > 이미지지도
							</th>
							<td>
								<textarea
									class="form-control"
									name="H_KAKAO_MAP_KEY"
									style="height: 100px;"
								><?=isset($info['H_KAKAO_MAP_KEY']) ? $info['H_KAKAO_MAP_KEY'] : '' ?></textarea>
							</td>
						</tr>



						<tr>
							<th class="text-center">네이버 MAP</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_NAVER_MAP"
									value="<?=isset($info['H_NAVER_MAP']) ? $info['H_NAVER_MAP'] : '' ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="text-center">카카오 MAP</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_KAKAO_MAP"
									value="<?=isset($info['H_KAKAO_MAP']) ? $info['H_KAKAO_MAP'] : '' ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="text-center">T MAP</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_T_MAP"
									value="<?=isset($info['H_T_MAP']) ? $info['H_T_MAP'] : '' ?>"
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
						sendAjaxHtml("/home_admin/comeoffice/modify_default", data, 'POST', 'result_content');
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
