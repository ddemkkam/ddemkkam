<div class="row">
	<div class="col-xs-12">
		<div class="text-right">
			<input
				type="button"
				class="btn btn-dark"
				id="add_btn"
				style="float:right"
				value="저장"
				onclick="save_menu()"
			/>
		</div>
		<div class="box" style="clear: both;">
			<form id="menuFrm">
				<input type="hidden" name="BRANCH" value="<?=isset($branch) ? $branch : '' ?>">
				<div class="box-body">
					<?if ( isset($menuData) && count($menuData) > 0 ) {?>
						<?foreach ( $menuData as $index => $row ) {?>
							<div class="left" style="width: 160px; float: left; padding-left: 0px; padding-right: 0px;">
								<table class="table table-bordered table-hover" id="list_table">
									<thead>
										<tr>
											<th class="text-center">
												<?=$row['A_TITLE']?>
												&nbsp;&nbsp;
												<input
													type="checkbox"
													name="A_TITLE[]"
													class="A_check"
													style="width: 20px; height: 20px;"
													value="<?=$row['A_TITLE']?>"
													checked
												/>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="background-color: #ffffff;">
												<ul class="sortable" style="list-style: none; margin-left: -45px;">
													<?foreach ( $row['AS_menu'] as $index2 => $val ) {?>
														<li style="background-color: #ffffff;">
															<div class="col-xs-9 left text-left" style="padding-left: 0px; padding-right: 0px;">
																<?=$val['AS_TITLE']?>
															</div>
															<div class="col-xs-3 right text-right" style="padding-left: 0px; padding-right: 0px;">
																<input
																	type="checkbox"
																	class="A_check_<?=$row['SEQ']?>"
																	style="width: 20px; height: 20px;"
																	data-AS_TITLE="<?=$val['AS_TITLE']?>"
																	data-A_MENU_ID="<?=$val['A_MENU_ID']?>"
																	data-AS_URL="<?=$val['AS_URL']?>"
																	checked
																/>
															</div>
														</li>
													<?}?>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?}?>
					<?}?>
				</div>
			</form>

		</div>
	</div>
</div>

<div id="add_ajax_result"></div>

<script>
	$(document).ready(function() {
		$(".sortable").sortable();

		$(".A_check").on("click", function () {
			if ( $(this).is(':checked') ) {
				$( ".A_check_"+$(this).val() ).prop("checked", true);
			} else {
				$( ".A_check_"+$(this).val() ).prop("checked", false);
			}
		});
	})

	function save_menu(){
		sendAjaxHtml("/home_admin/allmenuset/addDetailMenuSet", $("#menuFrm").serialize(), 'POST', 'add_ajax_result');
	}
</script>
