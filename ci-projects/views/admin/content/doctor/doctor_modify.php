<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<input type="hidden" name="SEQ" value="<?=isset($info['SEQ']) ? $info['SEQ'] : '' ?>" />
					<input type="hidden" name="D_LAN" value="<?= isset($BASELAN) ? $BASELAN : '' ?>" />
					<input type="hidden" name="SD_BRANCH" value="<?= isset($BASEBRANCH) ? $BASEBRANCH : '' ?>" />

					<div class="col-xs-8">
						<table class="table table-bordered table-hover">
							<thead>
								<col width="15%">
								<col width="85%">
							</thead>
							<tr>
							<tr>
								<th class="text-center">지점</th>
								<td class="text-center">
									<?if ( isset($BRANCH) ) {?>
										<input type="text" class="form-control" name="D_BRANCH" value="<?=isset($BRANCH) ? $BRANCH : '' ?>" readonly />
									<?} else {?>
										<select name="D_BRANCH" class="form-control">
											<option value="">선택하세요.</option>
											<?
											if ( isset($branchList) ) {
												foreach ( $branchList as $index => $row ){?>
													<option value="<?=$row['BRANCH']?>" <?=$row['BRANCH'] === $info['D_BRANCH'] ? 'selected' : '' ?> >
														<?=$row['BRANCH_NAME']?>(<?=$row['BRANCH']?>)
													</option>
												<?	}
											}?>
										</select>
									<?}?>
								</td>
							</tr>
								<th class="text-center">이미지<br />(w: 320px, h: 240px)<br />3배수</th>
								<td>
									<div class="col-xs-6" id="viewId0">
										<?if ( isset($info['D_IMG_PATH']) ) {?>
											<img src='<?=$info['D_IMG_PATH']?>' style='width: 150px; height: 150px;' />
											<input type='hidden' name='D_IMG_PATH[]' value='<?=$info['D_IMG_PATH']?>'><br />
											<input type='button' value='삭제' onclick='deleteZip("viewId0")'>
										<?} else {?>
											upload image
										<?}?>
									</div>
									<div class="col-xs-6">
										<iframe
											src="/fileUpload/uploadView?id=doctor&cnt=1&viewId=viewId0"
											frameborder="0"
											style="width: 100%; height: 200px;"
										></iframe>
									</div>
								</td>
							</tr>
							<tr>
								<th class="text-center">원장님이름(KR)</th>
								<td>
									<input type="text" class="form-control" name="D_NAME" value="<?=isset($info['D_NAME']) ? $info['D_NAME'] : '' ?>" />
								</td>
							</tr>
							<tr>
								<th class="text-center">원장님이름(EN)</th>
								<td>
									<input type="text" class="form-control" name="D_NAME_EN" value="<?=isset($info['D_NAME_EN']) ? $info['D_NAME_EN'] : '' ?>" />
								</td>
							</tr>
							<tr>
								<th class="text-center">설명</th>
								<td>
									<textarea name="D_DESC" class="form-control" style="height: 100px;"><?=isset($info['D_DESC']) ? $info['D_DESC'] : '' ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="text-center">대표원장님여부</th>
								<td>
									<select name="D_MAIN_YN" class="form-control">
										<option value="Y" <?=isset($info['D_MAIN_YN']) && $info['D_MAIN_YN'] == 'Y' ? 'selected' : '' ?> >Y</option>
										<option value="N" <?=isset($info['D_MAIN_YN']) && $info['D_MAIN_YN'] == 'N' ? 'selected' : '' ?> >N</option>
									</select>
								</td>
							</tr>
							<tr>
								<th class="text-center">사용여부</th>
								<td>
									<select name="D_USE_YN" class="form-control">
										<option value="Y" <?=isset($info['D_USE_YN']) && $info['D_USE_YN'] == 'Y' ? 'selected' : '' ?> >Y</option>
										<option value="N" <?=isset($info['D_USE_YN']) && $info['D_USE_YN'] == 'N' ? 'selected' : '' ?> >N</option>
									</select>
								</td>
							</tr>
						</table>

						<div class="col-xs-12 text-center" style="margin-top: 10px;">
							<button type="button" class="btn btn-primary" id="info_save" onclick="save()">저장</button>
							&nbsp;&nbsp;&nbsp;
							<button type="button" class="btn btn-default" onclick="history.back();">취소</button>
						</div>

					</div>

				</form>

			</div>

		</div>
	</div>
</div>

<div id="result_content"></div>

<script>
	function save(){
		let data = $("#ppeumEventFrm").serialize();

		$.confirm({
			title: "",
			content: "저장 하시겠습니까?\n이미지가 없으면 저장되지 않습니다.",
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
						sendAjaxHtml("/home_admin/doctor/save", data, 'POST', 'result_content');
					}
				},
				close: function () {
				}
			}
		});
	}
</script>
