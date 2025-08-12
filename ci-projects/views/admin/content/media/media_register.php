<style>
	.tr_style {border:3px solid #000000;}
</style>
<script src="/publish/bower_components/ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-body">
				<form id="board_frm" action="/home_admin/media/save" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="SEQ" value="<?=isset($SEQ) ? $SEQ : '' ?>" />

					<table id="division_table" class="table table-bordered">
						<colgroup>
							<col width="10%">
							<col width="*">
						</colgroup>
						<tbody>

						<tr>
							<th class="active text-center">언어</th>
							<td>
								<select name="M_LANGUAGE" id="M_LANGUAGE" class="form-control" >
									<option value="">선택하세요</option>
									<option value="KR" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'KR' ? 'selected' : '' ?> >KR</option>
									<option value="ENG" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'ENG' ? 'selected' : '' ?> >ENG</option>
									<option value="JPN" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'JPN' ? 'selected' : '' ?> >JPN</option>
									<option value="CHN" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'CHN' ? 'selected' : '' ?> >CHN</option>
									<option value="TH" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'TH' ? 'selected' : '' ?> >TH</option>
									<option value="RU" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'RU' ? 'selected' : '' ?> >RU</option>
									<option value="VN" <?=isset($info['M_LANGUAGE']) && $info['M_LANGUAGE'] === 'VN' ? 'selected' : '' ?> >VN</option>
								</select>
							</td>
						</tr>

						<tr>
							<th class="active text-center">제목</th>
							<td>
								<input type="text" class="form-control" name="M_TITLE" id="M_TITLE" value="<?=isset($info['M_TITLE']) ? $info['M_TITLE'] : ''?>">
							</td>
						</tr>

						<tr>
							<th class="active text-center">조회수</th>
							<td>
								<input type="number" class="form-control" name="M_COUNT" id="M_COUNT" value="<?=isset($info['M_COUNT']) ? $info['M_COUNT'] : ''?>">
							</td>
						</tr>

						<tr>
							<th class="active text-center">작성자</th>
							<td>
								<input type="text" class="form-control" name="M_REG_ID" id="M_REG_ID"
									   value="<?=isset($info['M_REG_ID']) ? $info['M_REG_ID'] : $this->session->userdata('ADMIN_ID') ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="active text-center">내용</th>
							<td>
								<textarea id="editor1" name="M_CONTEXT" rows="10" cols="80">
									<?=isset($info['M_CONTEXT']) ? $info['M_CONTEXT'] : '' ?>
								</textarea>

							</td>
						</tr>


						</tbody>
					</table>
					<div class="text-center">
						<button type="button" class="btn btn-primary" onclick="saveTerms()">저장</button>
						<button type="button" class="btn btn-default" onclick="history.back()">취소</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="ajax_json_div"></div>

<script type="text/javascript">

	$(document).ready(function() {
		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		//CKEDITOR.replace('editor1');
		CKEDITOR.replace('editor1', {
			width: '100%',
			height: "450px",
			filebrowserUploadMethod: 'form',
			filebrowserUploadUrl: "/FileUpload/board/media",
		});
		//bootstrap WYSIHTML5 - text editor
		$('.textarea').wysihtml5();

		$("#M_BRANCH").on("change", function (){
			changeLan($(this).val());
		});



	})

	function saveTerms() {
		$.confirm({
			title: "",
			content: "저장하시겠습니까?",
			type: 'blue',
			typeAnimated: true,
			columnClass: 'col-md-4 col-md-offset-4',
			containerFluid: true,
			closeButton: "닫기",
			buttons: {
				tryAgain: {
					text: '저장',
					btnClass: 'btn-blue',
					action: function(){
						//sendFile("/v1/event/eventSave", 'event_frm', eventSaveResultFunc);
						$("#board_frm").submit();
					}
				},
				close: function () {
				}
			}
		});
	}

	function file_upload_open(){
		window.open('/fileUpload/uploadView?id=child&cnt=1&viewId=viewId', '이미지 업로드', 'width=700px,height=250px,scrollbars=yes');
	}

	function deleteZip(){
		$("#viewId").html("");
	}




</script>
