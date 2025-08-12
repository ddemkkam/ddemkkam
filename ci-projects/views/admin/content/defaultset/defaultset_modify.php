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
							<th class="text-center">파비콘</th>
							<td>
<!--								<input type="button" class="btn btn-success" value="업드로" onclick="file_upload_open();" />-->
								<div>
									<iframe
										src="/fileUpload/uploadView?id=favicon&cnt=1&viewId=viewId"
										frameborder="0"
										style="width: 100%; height: 200px;"
									></iframe>

									<ul id="viewId" class="sortable" style="list-style: none; margin-left: -45px;">
										<?if ( isset($info['H_FAVICON']) ) {?>
											<li style='background-color: #ffffff;'>
												<img src='<?=$info['H_FAVICON'] ?>' style='width: 50px; height: 50px;' />
												<input type='button' class="btn btn-yahoo" value='DEL' onclick='deleteImgView(this)' />
												<input type='hidden' name='imgFile[]' value='<?=$info['H_FAVICON'] ?>'>
											</li>
										<?}?>
									</ul>

								</div>
							</td>
						</tr>

						<tr>
							<th class="text-center">타이틀</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="쁨클리닉 신논현 메가스토어점"
									name="H_TITLE"
									value="<?=isset($info['H_TITLE']) ? $info['H_TITLE'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">keywords</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="서울시 강남구 강남대로 470, 13층 위치, 신논현피부과, 슈링크, 레이저토닝, 아쿠아필, LDM, 제모, 피부관리, 튠페이스, 여드름관리, 리프팅, 필러, 보톡스, 쁨의원, 쁨클리닉, 클라앤비멤버쉽, 신논현쁨, 신논현피부과,신논현쁨클리닉, 신논현역피부과, 강남피부과, 강남역피부과, 클라앤비피부과"
									name="H_KEYWORDS"
									value="<?=isset($info['H_KEYWORDS']) ? $info['H_KEYWORDS'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">description</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="큐오필, 필러,하이코,제모,울쎄라,실리프팅,지방흡입,신논현역"
									name="H_DESCRIPTION"
									value="<?=isset($info['H_DESCRIPTION']) ? $info['H_DESCRIPTION'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">점유인증 메시지 <br>지점명</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="예쁨주의쁨의원 강남점"
									name="H_MESSAGE_TITLE"
									value="<?=isset($info['H_MESSAGE_TITLE']) ? $info['H_MESSAGE_TITLE'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">
								카카오 CLIENT ID
								<br />
								(로그인>보안>코드)
							</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="0FcYwHv2PEjHiQobekoDeX3PkTVdjR8j"
									name="H_KAKAO_CLIENT"
									value="<?=isset($info['H_KAKAO_CLIENT']) ? $info['H_KAKAO_CLIENT'] : '' ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="text-center">
								카카오 RESTAPI KEY
								<br />
								(앱키)
							</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="8bc89086fcabae399b330d31281b0e54"
									name="H_KAKAO_KEY"
									value="<?=isset($info['H_KAKAO_KEY']) ? $info['H_KAKAO_KEY'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">네이버 Client ID</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="ZE8L8uWBCrFJVl6cFKsm"
									name="H_NAVER_CLIENT"
									value="<?=isset($info['H_NAVER_CLIENT']) ? $info['H_NAVER_CLIENT'] : '' ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="text-center">네이버 Client Secret</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="K285Pu4c1s"
									name="H_NAVER_SECRET"
									value="<?=isset($info['H_NAVER_SECRET']) ? $info['H_NAVER_SECRET'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">네이버스크립트</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="s_2283b14e82"
									name="H_NAVER_SCRIPT"
									value="<?=isset($info['H_NAVER_SCRIPT']) ? $info['H_NAVER_SCRIPT'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">카카오채팅</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="http://pf.kakao.com/_xjJpbC/chat"
									name="H_KAKAO_CHAT"
									value="<?=isset($info['H_KAKAO_CHAT']) ? $info['H_KAKAO_CHAT'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">CONTACT<br/>(전화번호)</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder="010-0000-0000"
									name="H_CONTACT1_2"
									value="<?=isset($info['H_CONTACT1_2']) ? $info['H_CONTACT1_2'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">PVL 카카오채팅</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_PVL_KAKAO_CHAT"
									value="<?=isset($info['H_PVL_KAKAO_CHAT']) ? $info['H_PVL_KAKAO_CHAT'] : '' ?>"
								>
							</td>
						</tr>
						<tr>
							<th class="text-center">PVL 전화번호</th>
							<td>
								<input
									type="text"
									class="form-control"
									placeholder=""
									name="H_PVL_OFFICENUMBER"
									value="<?=isset($info['H_PVL_OFFICENUMBER']) ? $info['H_PVL_OFFICENUMBER'] : '' ?>"
								>
							</td>
						</tr>
						<?/*
						<tr>
							<th class="text-center">LOCATION</th>
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
							<th class="text-center">CONTACT</th>
							<td>
								<div class="col-xs-12" style="padding-left: 0px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="쁨클리닉"
											name="H_CONTACT1_1"
											value="<?=isset($info['H_CONTACT1_1']) ? $info['H_CONTACT1_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6">
										<input
											type="text"
											class="form-control"
											placeholder="02-593-3344"
											name="H_CONTACT1_2"
											value="<?=isset($info['H_CONTACT1_2']) ? $info['H_CONTACT1_2'] : '' ?>"
										>
									</div>
								</div>

								<div class="col-xs-12" style="padding-left: 0px; margin-top: 5px;">
									<div class="left col-xs-6" style="padding-left: 0px;">
										<input
											type="text"
											class="form-control"
											placeholder="PVL멤버십"
											name="H_CONTACT2_1"
											value="<?=isset($info['H_CONTACT2_1']) ? $info['H_CONTACT2_1'] : '' ?>"
										>
									</div>

									<div class="left col-xs-6" style="">
										<input
											type="text"
											class="form-control"
											placeholder="02-593-3344"
											name="H_CONTACT2_2"
											value="<?=isset($info['H_CONTACT2_2']) ? $info['H_CONTACT2_2'] : '' ?>"
										>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<th class="text-center">OFFICE HOUR</th>
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
													<th class="text-center">카카오 지도 html<br />(위와동일)</th>
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
						*/?>
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
						sendAjaxHtml("/home_admin/defaultset/modify_default", data, 'POST', 'result_content');
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
