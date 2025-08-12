<style>
	.tr_style {border:3px solid #000000;}
</style>
<script src="/publish/bower_components/ckeditor/ckeditor.js"></script>
<div class="row">
	<div class="col-xs-10">
		<div class="box">
			<div class="box-body">
				<form id="event_frm" action="/home_admin/termsset/saveTermsSet" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="T_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" />
					<input type="hidden" name="T_TYPE" value="<?=isset($TYPE) ? $TYPE : '' ?>" />
					<input type="hidden" name='T_LAN' id='T_LAN' value="<?=isset($LAN) ? $LAN : '' ?>" readonly />
					<table id="division_table" class="table table-bordered">
						<colgroup>
							<col width="10%">
							<col width="*">
						</colgroup>
						<tbody>

							<tr>
								<th class="active text-center">내용</th>
								<td>
									<textarea id="editor1" name="T_CONTEXT" rows="10" cols="80">
										<?=isset($info['T_CONTEXT']) ? $info['T_CONTEXT'] : '' ?>
									</textarea>

								</td>
							</tr>
							<?if ( isset($BRANCH) && $BRANCH !== 'all' ) {?>
								<tr>
									<th class="active text-center">과거내역</th>
									<td>
										<?if ( isset($infoFiles) && count($infoFiles) > 0 ){?>
											<select id="history" class="form-control" style="width: 200px;">
												<?foreach ( $infoFiles as $index => $row ) {?>
													<option value="<?=$row['SEQ']?>" <?=isset($SEQ) && $SEQ == $row['SEQ'] ? 'selected' : '' ?> ><?=$row['REG_DATE']?></option>
												<?}?>
											</select>
										<?} else {?>
											과거내역 없음.
										<?}?>
									</td>
								</tr>
							<?}?>

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
		<?if ( $TYPE !== 'child' ) {?>
			// Replace the <textarea id="editor1"> with a CKEditor
			// instance, using default configuration.
			//CKEDITOR.replace('editor1');
			CKEDITOR.replace('editor1', {
				width: '100%',
				height: "450px",
				filebrowserUploadMethod: 'form',
				filebrowserUploadUrl: "/FileUpload/board"
			});
			//bootstrap WYSIHTML5 - text editor
			$('.textarea').wysihtml5();
		<?}?>


		$("#history").change(function(){
			let regDate = $(this).val();
			<?
			$REQUEST_URI = explode('/', $_SERVER['REQUEST_URI']);
			?>
			location.href="/<?=$REQUEST_URI[1].'/'.$REQUEST_URI[2].'/'.$REQUEST_URI[3]?>/<?=$BASEBRANCH?>/<?=$BASELAN?>/"+regDate;
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
						$("#event_frm").submit();
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
