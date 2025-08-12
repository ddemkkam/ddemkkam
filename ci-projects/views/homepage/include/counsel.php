
<div id="counselView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
				<span class="react-modal-sheet-drag-indicator" onclick="counselViewHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 20px;">
				<?//echo "<pre>"; print_r($branchInfo); echo "<pre>";?>
				<div class="Categorys_categorys__5pPjP">
					<ul>

						<? if ($branchInfo['BRANCH'] != 'ppeum27') { ?>
							<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111;">
								상담 유형을 선택하세요.
							</li>
						<? } else { ?>
							<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111;">
								전문 상담원과 전화 상담하세요.
							</li>
						<? } ?>
						<?
						$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
						if( preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) ) {	?>
							<li>
								<a href="tel:<?=isset($branchInfo['H_CONTACT1_2']) ? $branchInfo['H_CONTACT1_2'] : ''?>">
                                    <button style="width: 100%; height: 52px; margin-top: 24px; text-align: center; border-radius: 8px; background-color:  #2dd54f;
										font-size: 14px; font-weight: 600; color: #fff;">
										<table style="margin: auto;">
											<tr>
												<td><img src="/common/homepage/img/common/ico_call.svg" /></td>
												<td style="vertical-align: middle; padding-top: 2px; padding-left: 5px;">전화걸기</td>
											</tr>
										</table>
									</button>
								</a>
							</li>
						<?}?>
						<? if ($branchInfo['BRANCH'] != 'ppeum27') { ?>
							<li>
								<button style="width: 100%; height: 52px; margin-top: 10px; text-align: center; border-radius: 8px; background-color: #fae100;
									font-size: 14px; font-weight: 600; color: #111;">
										<a href="/kakaoChat" target="_blank">
											<table style="margin: auto;">
												<tr>
													<td><img src="/common/homepage/img/common/ico_kakao.svg" /></td>
													<td style="vertical-align: middle; padding-top: 2px; padding-left: 5px;">카카오톡</td>
												</tr>
											</table>
										</a>
								</button>
							</li>
						<? } else { ?>
							<? if( !preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) ) { ?>
								<li>
									<table>
										<tr>
											<td style="background-color: #2dd54f; border-radius: 50%;padding:5px;"><img src="/common/homepage/img/common/ico_call.svg" /></td>
											<td style="vertical-align: middle; padding-left: 10px;font-size: 18px; font-weight: 600;">02-564-8881</td>
										</tr>
									</table>
								</li>
							<? } ?>
						<? } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="counselViewHideFun()"></button>
</div>
