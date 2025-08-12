
<div id="modifyResView" style="position: fixed; inset: 0px; overflow: hidden; pointer-events: none; z-index: 9999999; visibility: visible; display: none;">
	<div class="react-modal-sheet-container " style="height: 400px; z-index: 2; position: absolute; left: 0px; bottom: 0px; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0px -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(100% - env(safe-area-inset-top) - 34px); transform: none;">
		<div draggable="false" style="width: 100%; height: 30px; user-select: none; touch-action: pan-x;">
			<div class="react-modal-sheet-header" style="height: 40px; width: 100%; position: relative; display: flex; align-items: center; justify-content: center;">
			<span class="react-modal-sheet-drag-indicator" onclick="modifyResViewHideFun()"></span>
			</div>
		</div>
		<div class="react-modal-sheet-scroller " style="height: 100%; /*overflow-y: auto;*/">
			<div style="height: auto; padding: 20px;">
				<?//echo "<pre>"; print_r($branchInfo); echo "<pre>";?>
				<div class="">
					<ul>

						<li style="font-size: 16px; font-weight: bold; letter-spacing: -0.32px; text-align: left; color: #111;">
							예약 변경을 원하시나요?
							<br />
							변경이 필요한 부분을 선택해 주세요.
						</li>

						<li>
							<div class="modifyDateBtnAll modifyDateBtnOff" data-id="date_modify" onclick="modifyDateBtnOnOffFun(this)">
								날짜변경
							</div>
						</li>

						<li>
							<div
								class="modifyDateBtnAll modifyDateBtnOff"
								data-id="res_cancel"
								style="height: 85px; line-height: 15px; padding-top: 15px;"
								onclick="modifyDateBtnOnOffFun(this)">
								예약취소
								<div style="color: #878e9c; font-size: 12px; margin-top: 10px;">
									예약 완료 후, 옵션 변경은 불가능해요.
									<br />
									취소 후 다시 예약해 주세요.
								</div>
							</div>
						</li>

						<li>
							<button class="modifyDateBtn" onclick="modifyReservation('<?=isset($res_number) ? $res_number : ''?>')">
								확인
							</button>
						</li>

					</ul>
				</div>
			</div>
		</div>
	</div>

	<button class="react-modal-sheet-backdrop " tabindex="0" style="z-index: 1; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); touch-action: none; border: none; pointer-events: auto; opacity: 1;" onclick="modifyResViewHideFun()"></button>
</div>
