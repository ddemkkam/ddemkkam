<div id="productDetailView" style="position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 99; visibility: visible;display: none;">
	<div class="react-modal-sheet-container " style="z-index: 2; position: absolute; left: 0; bottom: 0; width: 100%; background-color: rgb(255, 255, 255); border-top-right-radius: 8px; border-top-left-radius: 8px; box-shadow: rgba(0, 0, 0, 0.3) 0 -2px 16px; display: flex; flex-direction: column; pointer-events: auto; max-height: calc(75% - env(safe-area-inset-top) - 34px); transform: none;">
		<div class="react-modal-sheet-scroller" style="height: 100%; overflow-y: auto;">
			<div style="height: auto; padding: 0 20px 20px 20px;">
				<div class="SurgeryItems_total-price-box__H6NKl">
					<div class="SurgeryItems_price-txt-box__sMsyw" style="border:none;">
						<span class="SurgeryItems_title__7PEOT"><em>총 시술금액</em> <span class="price-vat">(VAT 별도)</span><br>*결제는 내원 시 진행돼요.</span>
						<span id="totalPrice" class="subProPrice SurgeryItems_total-price__IV0iQ">0</span>
					</div>
					<div class="SurgeryItems_btn-wrap__VdsxS">
						<button class="Button_btn__3u27s" type="button" onclick="basketProductAddEvent()" style="float: left; width: 32.5%; border: 1px solid var(--color-primary); background: rgb(255, 255, 255); color: var(--color-primary); margin-right: 8px;">장바구니</button>
						<button class="Button_btn__3u27s" type="button" onclick="reservationProductAddEvent()" style="width: calc(67.5% - 8px);">예약하기</button>
					</div>
					<div id="basketSnackbar">
						<p id="basketSnackbarFalse" style="display: none;">장바구니에 상품을 담았습니다.</p>
						<p id="basketSnackbarTrue" style="display: none;">이미 장바구니에 있는 상품입니다.</p>
						<a href="/basket">바로가기</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
