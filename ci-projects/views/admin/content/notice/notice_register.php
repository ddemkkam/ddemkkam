<style>
	.tr_style {border:3px solid #000000;}
</style>
<script src="/publish/bower_components/ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-body">
				<form id="board_frm" action="/home_admin/notice/save" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="SEQ" value="<?=isset($SEQ) ? $SEQ : '' ?>" />
					<input type="hidden" name="N_BRANCH" id="N_BRANCH" value="<?=isset($BASEBRANCH) ? $BASEBRANCH : '' ?>" />
					<input type="hidden" name="N_LANGUAGE" id="N_LANGUAGE" value="<?=isset($BASELAN) ? $BASELAN : '' ?>" />

					<table id="division_table" class="table table-bordered">
						<colgroup>
							<col width="10%">
							<col width="*">
						</colgroup>
						<tbody>

						<tr>
							<th class="active text-center">제목</th>
							<td>
								<input type="text" class="form-control" name="N_TITLE" id="N_TITLE" value="<?=isset($info['N_TITLE']) ? $info['N_TITLE'] : ''?>">
							</td>
						</tr>

						<tr style="display: none;">
							<th class="active text-center">조회수</th>
							<td>
								<input type="number" class="form-control" name="N_COUNT" id="N_COUNT" value="<?=isset($info['N_COUNT']) ? $info['N_COUNT'] : ''?>">
							</td>
						</tr>

						<tr style="display: none;">
							<th class="active text-center">작성자</th>
							<td>
								<input type="text" class="form-control" name="N_REG_ID" id="N_REG_ID"
									   value="<?=isset($info['N_REG_ID']) ? $info['N_REG_ID'] : $this->session->userdata('ADMIN_ID') ?>"
								>
							</td>
						</tr>

						<tr>
							<th class="active text-center">내용</th>
							<td>
								<textarea id="editor1" name="N_CONTEXT" rows="10" cols="80">
									<?=isset($info['N_CONTEXT']) ? $info['N_CONTEXT'] : '' ?>
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
		<?if ( isset($info['N_BRANCH']) ){?>
			changeLan('<?=$info['N_BRANCH']?>');
		<?}?>

		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		//CKEDITOR.replace('editor1');
		CKEDITOR.replace('editor1', {
			width: '100%',
			height: "450px",
			filebrowserUploadMethod: 'form',
			filebrowserUploadUrl: "/FileUpload/board/notice",
		});
		//bootstrap WYSIHTML5 - text editor
		$('.textarea').wysihtml5();

		$("#N_BRANCH").on("change", function (){
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


	function changeLan(BRANCH) {
		//console.log(BRANCH);
		let selected = '';

		$.ajax({
			url: "/home_admin/mainset/getBranchToLanData",
			data: { "BRANCH" : BRANCH },
			method: "GET",
			dataType: 'html',
			success:function(data){
				//console.log(data);
				let jsonData = JSON.parse(data);
				let add_lan_sel_html = "<option value=''>선택하세요</option>";
				$.each(jsonData, function ( index, item ) {
					//console.log(item.LANGAUE);
					selected = '';
					if ( "<?=$info['N_LANGUAGE']?>" ===  item.LANGAUE ){
						selected = 'selected';
					}
					add_lan_sel_html += "<option value='"+item.LANGAUE+"' "+selected+">"+item.LANGAUE+"</option>";
				})
				$("select[name='N_LANGUAGE']").html(add_lan_sel_html);
			}
			,fail:function(xhr, status, errorThrown) {
				console.log("오류가 발생하였습니다.<br>");
				console.log("오류명: " + errorThrown + "<br>");
				console.log("상태: " + status);
			}
		});
	}

</script>
