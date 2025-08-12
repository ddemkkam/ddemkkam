<div class="auth_register-form__yn4Z0">
	<div>
		<div class="mypageDiv">
			<? if (!empty($type)) { ?>
				<div class="mypageDivHeader1" style="height: 70px;">
					<div class="nameText">
						<p>고객님 환영합니다!</p>
						<p>내원 이력 관리를 위해</p>
						<p>이름과 전화번호를 입력해주세요.</p>
					</div>
				</div>
			<? } else { ?>
				<div class="mypageDivHeader1">
					<div class="nameText">
						<p>고객님 환영합니다!</p>
						<p>이름과 전화번호를 입력하여 로그인 해 주세요</p>
					</div>
				</div>
				<div class="mypageDivHeader1">
					<p class="info">병원 시스템에 등록된 정보가 있는 경우,<br/>동일한 정보를 입력하여 연동이 가능합니다.<br/>기존에 등록된 정보에 대한 문의는 해당 지점으로 연락 부탁드립니다.</p>
				</div>
			<? } ?>

		</div>
	</div>
	<div class="Divider_divider__pCsgd" style="margin: 0; background: rgb(246, 247, 249);"></div>
	<form method="post" id="regist_form">
		<input type="hidden" name="checkCode" value="">
		<input type="hidden" name="checkConfirm" value="N">
		<input type="hidden" name="type" value="<?= !empty($type) ? $type : 'normal' ?>">
		<input type="hidden" name="id" value="<?= !empty($id) ? $id : '' ?>">
		<div class="auth_form-group__ftcht">
			<label for="userName" class="">이름</label>
			<div>
				<input id="userName" type="text" name="userName" placeholder="이름을 입력해 주세요" onkeyup="onlyhangul(this);checkInput();" value="">
				<p style="padding:6px 0 0 4px;color:#888;clear:both;">*한글,영문 대/소문자를 사용. 특수기호,공백 사용 불가</p>
			</div>
		</div>
		<div class="auth_form-group__ftcht">
			<label for="birth" class="">휴대폰 번호</label>
			<div class="auth-checkbox">
				<input id="birth" type="number" name="phoneNumber" placeholder="전화번호를 입력해 주세요" oninput='handleOnInput(this, 11);checkInput();' style="width: calc(68% - 12px);" value="<?= $number ?>">
				<button type="button" id="codeSendBtn" onclick="sendRegistCode()" disabled class="code-btn">인증번호 발송</button>
			</div>
		</div>
		<div class="auth_form-group__ftcht" id="numberCheck" style="display: none;">
			<div class="auth-checkbox">
				<input id="birth" type="number" name="code" placeholder="인증번호 입력해 주세요" oninput='handleOnInput(this, 8)' style="width: calc(68% - 12px);">
                <span class="count-number"><span id="countNumberVis"><span id="countNumber">90</span>초</span></span>
                <button type="button" id="checkCodeBtn" class="check-code-btn">인증번호 입력</button>
			</div>
			<p class="info" id="checkText">*인증번호를 정확하게 입력해 주세요</p>
		</div>
	</form>

	<div class="agreeBar agreeShow" style="display: none;"></div>

	<div class="agreeDiv agreeShow" style="display: none;">
		<p style="font-size: 16px;color:#111;font-weight: bold;">회원가입을 위한 약관에 동의해주세요</p>
		<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
			<div class="SurgeryItems_item-name__HkIqD">
				<input type="checkbox" id="allCheck" name="allCheck" class="allCheck" onclick="signup_allCheck(this)">
				<label for="allCheck" class="allAgreeText">모두 동의해요</label>
			</div>
		</div>

		<div class="allAgreeBox">
			<table class="signup_table">
				<tr>
					<td>
						<div class="allAgreeText SurgeryItems_surgery-item__CYpPR">
							<div class="agreeText1 SurgeryItems_item-name__HkIqD">
								<input type="checkbox" id="agree1" class="agree" name="" onclick="agree1Click();">
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
								<input type="checkbox" id="agree2" class="agree" name="" onclick="agree2Click();">
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
								<input type="checkbox" id="agree3" class="agree" name="" onclick="agree3Click();">
								<label for="agree3" class="agreeText">[선택] 마케팅 활용/광고성 정보 수신 동의</label>
							</div>
						</div>
					</td>
					<td onclick="serviceView('marketing')">약관보기</td>
				</tr>
			</table>
		</div>
	</div>
	<input type="hidden" name="hasCount">
	<input type="hidden" name="cNumber">
	<div style="margin: 20px">
        <button class="btn member_submit_btn agreeShow" type="button" style="width: 100%;display: none;" onclick="registBtn()">확인</button>
    </div>
</div>

<script>
	let = intervaild = null;
	function sendRegistCode() {
		let name = $("input[name='userName']").val();
		let number = $("input[name='phoneNumber']").val();

		if (name !== '') {
			if (number !== '') {
				let randomNum = {};

				randomNum.random = function (n1, n2) {
					return parseInt(Math.random() * (n2 - n1 + 1)) + n1;
				}

				randomNum.authNo = function (n) {
					let value = "";
					for (let i=0; i<n; i++) {
						value += randomNum.random(0, 9);
					}
					return value;
				}

				let code = randomNum.authNo(4);
				$("input[name='checkCode']").val(code);

				let data = {
					'dest_phone': number,
					'message': '<?= $sendCodeTitle ?>인증코드 : ' + code,
					'template_code': '<?= $template ?>'
				};

				$.ajax({
					url: 'https://<?= $branch ?>.api.namucrm.com/v1/public/send',
					data: JSON.stringify(data),
					method: 'post',
					dataType: 'json',
					success:function(data){
						console.log(data);
					}
				});

				$("#numberCheck").show();
				$("#codeSendBtn").html("인증번호 재발송");

				clearInterval(intervaild);
				$("#countNumber").html('90');
				intervaild = setInterval(() => {
					countDown()
				}, 1000);
			} else {
				alert('전화번호를 입력해 주세요.');
			}
		} else {
			alert('이름을 입력해 주세요.');
		}
	}

	function countDown() {
		let count = Number($("#countNumber").html());
		let check = $("input[name='checkConfirm']").val();

		if (count > 0 && check === 'N') {
			let result = count - 1;
			$("#countNumber").html(result);
		} else if (count === 0  && check === 'N') {
			$("#numberCheck").hide();
			$("input[name='code']").val('');
			$("#codeSendBtn").html("인증번호 발송");
		}
	}

	$("#checkCodeBtn").on('click', function () {
		if ($("input[name='code']").val() == $("input[name='checkCode']").val()) {
			$("input[name='checkConfirm']").val("Y");
			$("#countNumberVis").hide();
			$("#codeSendBtn").css('background-color', '#d0d6de');
            $("#codeSendBtn").css('border', '1px solid #d0d6de');
            $("#codeSendBtn").css('color', '#fff');
			$("#codeSendBtn")[0].disabled = true;
			$("#checkCodeBtn").html('인증 완료');
			$("#checkCodeBtn").css('background-color', '#d0d6de');
            $("#checkCodeBtn").css('border', '1px solid #d0d6de');
            $("#checkCodeBtn").css('color', '#fff');
			$("#checkCodeBtn")[0].disabled = true;
			$("#checkText").hide();
			$("input[name='code']")[0].disabled = true;
			$("input[name='phoneNumber']")[0].disabled = true;
			$("input[name='userName']")[0].disabled = true;

			let data = {
				'type': $("input[name='type']").val(),
				'id': $("input[name='id']").val(),
				'name': $("input[name='userName']").val(),
				'number': $("input[name='phoneNumber']").val()
			};

			$.ajax({
				url: '/registMember/setRegistCode',
				data: data,
				method: 'post',
				dataType: 'json',
				success:function(data){
					$("input[name='hasCount']").val(data.cnt);

					if (data.cnt === 0) {
						$(".agreeShow").show();
					} else if (data.cnt === 1) {
						if (data.cPublicCi === '' || data.cPublicCi === 'good_vibe') {
							$("input[name='cNumber']").val(data.cNumber);
							$(".agreeShow").show();
						} else {
							$.ajax({
								url: '/registMember/setRegistMemberLogin',
								data: {
									'cPublicCi': data.cPublicCi
								},
								method: 'post',
								dataType: 'json',
								success:function(data){
									console.log(data);
									location.href = data.referer;
								}
							});
						}
					} else if (data.cnt > 1) {
						location.href = '/registMember/signUpError?name=' + $("input[name='userName']").val() + '&number=' + $("input[name='phoneNumber']").val();
					}
				}
			});
		} else {
			$("#checkText").show();
		}
	});

	function signup_allCheck(that){
		if ( $(that).prop("checked") === true ) {
			$(".agree").prop('checked', true);
			agree1Click();
			agree2Click();
		} else {
			$(".agree").prop('checked', false);
			agree1Click();
			agree2Click();
		}
	}

	function agree1Click(){
		if ( !$("#agree1").prop("checked") ) {
			$("#allCheck").prop('checked', false);
		} else {
			if ($(".agree:checked").length === 3) {
				$("#allCheck").prop('checked', true);
			}
			$("#agree1_warn_text").hide();

		}
	}

	function agree2Click(){
		if ( !$("#agree2").prop("checked") ) {
			$("#allCheck").prop('checked', false);
		} else {
			if ($(".agree:checked").length === 3) {
				$("#allCheck").prop('checked', true);
			}
			$("#agree2_warn_text").hide();
		}
	}

	function agree3Click() {
		if ( !$("#agree3").prop("checked") ) {
			$("#allCheck").prop('checked', false);
		} else {
			if ($(".agree:checked").length === 3) {
				$("#allCheck").prop('checked', true);
			}
		}
	}


	function registBtn() {

		let check = true;
		if ( !$("#agree1").prop("checked")) {
			$("#agree1_warn_text").show();
			check = false;
		}

		if (!$("#agree2").prop("checked")) {
			$("#agree2_warn_text").show();
			check = false;
		}

		if (check) {
			const data = {
				'name' : $("input[name='userName']").val()
				,'id': $("input[name='id']").val()
				,'type': $("input[name='type']").val()
				,'hp' : $("input[name='phoneNumber']").val()
				,'m_pub' : $("#agree1").prop("checked") ? 'Y' : 'N'
				,'m_use' : $("#agree2").prop("checked") ? 'Y' : 'N'
				,'m_market' : $("#agree3").prop("checked") ? 'Y' : 'N'
				,'hasCount': $("input[name='hasCount']").val()
				,'cNumber': $("input[name='cNumber']").val()
			}

			$.ajax({
				url: '/registMember/setRegistMember',
				data: data,
				method: 'post',
				dataType: 'json',
				success:function(data){
					location.href = '/registMember/signUpComplete';
				}
			});
		}
	}
	function checkInput() {
		if ($("input[name='userName']").val() != '' && $("input[name='phoneNumber']").val() != '') {
			$("#codeSendBtn").css('background-color', '#fff');
            $("#codeSendBtn").css('border', '1px solid #cf2f75');
            $("#codeSendBtn").css('color', '#cf2f75')
			$("#codeSendBtn")[0].disabled = false;
		} else {
			$("#codeSendBtn").css('background-color', '#fff');
            $("#codeSendBtn").css('border', '1px solid #cf2f75');
            $("#codeSendBtn").css('color', '#cf2f75');
			$("#codeSendBtn")[0].disabled = true;
		}
	}
</script>
