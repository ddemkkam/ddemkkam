<div class="auth_register-form__yn4Z0">
	<h1>
		고객님 환영합니다!<br>
		내원 이력 관리를 위해 이름과 전화번호를 입력해주세요
		<br />
		<br />
		<div>
			<table>
				<tr>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">※&nbsp;</td>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">병원 시스템에 등록된 정보가 있는 경우, 동일한 정보를 입력하여야 연동이 가능합니다.</td>
				</tr>
				<tr>
					<td></td>
					<td style="font-size: 12px; font-weight: normal; color: #f03e3e;">
						기존에 등록된 정보에 대한 문의는 해당 지점으로 연락 부탁드립니다.
					</td>
				</tr>
			</table>
		</div>
	</h1>
	<div class="Divider_divider__pCsgd" style="margin: 0px; background: rgb(246, 247, 249);"></div>
	<form method="post" id="regist_form">
		<input type="hidden" id="id" name="id" value="<?=isset($id) ? $id : '' ?>" />
		<input type="hidden" id="type" name="type" value="<?=isset($type) ? $type : '' ?>" />
		<div class="auth_form-group__ftcht">
			<label for="userName" class="">이름</label>
			<div class="">
				<input
					id="userName"
					type="text"
					name="userName"
					class=""
					placeholder="이름을 입력해주세요"
					onkeyup="onlyhangul(this);"
					value="<?=isset($name) ? $name : '' ?>"
				/>
			</div>
		</div>
		<!--<div class="auth_form-group__ftcht">
			<label for="birth" class="">생년월일</label>
			<div class="">
				<input
					id="birth"
					type="number"
					name="birth"
					class=""
					placeholder="생년월일 8자리를 입력해주세요"
					oninput='handleOnInput(this, 8)'
					value="<?php /*=isset($birthday) ? $birthday : '' */?>"
				/>
			</div>
		</div>-->
		<div class="auth_form-group__ftcht">
			<label for="phone" class="">휴대폰번호</label>
			<div class="">
				<input
					id="phone"
					type="number"
					name="phone"
					class=""
					placeholder="휴대폰번호를 입력해주세요."
					oninput='handleOnInput(this, 11)'
					value="<?=isset($phone_number) ? $phone_number : '' ?>">
			</div>
		</div>

	</form>

	<div class="agreeBar"></div>

	<div class="agreeDiv">
		<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
			<div class="SurgeryItems_item-name__HkIqD">
				<input type="checkbox" id="allCheck" name="allCheck" onclick="signup_allCheck(this)">
				<label for="allCheck" class="allAgreeText">모두 동의합니다.</label>
			</div>
		</div>

		<div class="allAgreeBox">
			<table class="signup_table">
				<tr>
					<td>
						<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
							<div class="agreeText1 SurgeryItems_item-name__HkIqD">
								<input type="checkbox" id="agree1" class="agree" name="" checked onclick="agree1Click();" />
								<label id="agree1_text" for="agree1" class="agreeText" onclick="agree1Click();">[필수] 이용약관</label>
								<div id="agree1_warn_text">
									<label class="agreeText">이용약관에 동의해 주시기 바랍니다.</label>
								</div>
							</div>
						</div>
					</td>
					<td onclick="serviceView('use')">약관보기</td>
				</tr>
				<tr>
					<td>
						<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
							<div class="agreeText1 SurgeryItems_item-name__HkIqD">
								<input type="checkbox" id="agree2" class="agree" name="" onclick="agree2Click();" />
								<label id="agree2_text" for="agree2" class="agreeText" onclick="agree2Click();">[필수] 개인정보 수집 및 이용에 대한 동의</label>
								<div id="agree2_warn_text">
									<label class="agreeText">개인정보 수집 및 이용에 동의해 주시기 바랍니다.</label>
								</div>
							</div>
						</div>
					</td>
					<td onclick="serviceView('public')">약관보기</td>
				</tr>
				<tr>
					<td>
						<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
							<div class="agreeText1 SurgeryItems_item-name__HkIqD">
								<input type="checkbox" id="agree3" class="agree" name="" />
								<label for="agree3" class="agreeText">[선택] 마케팅 활용/광고성 정보 수신 동의</label>
							</div>
						</div>
					</td>
					<td>약관보기</td>
				</tr>
			</table>
		</div>
	</div>

	<form>
		<button class="btn member_submit_btn" type="button" style="width: 100%;" onclick="registBtn()">확인</button>
	</form>
</div>
