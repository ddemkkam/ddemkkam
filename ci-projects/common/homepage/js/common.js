var swiper_cate1;
var swiper_cate2;
var swiper_cate3;
var swiper_caladar;

$(document).ready(function() {
	swiper_cate1 = new Swiper("#swipercate1 > .swiper", {
		// height: 56,
		speed: 400,
		slidesPerView: 'auto',
	});

	swiper_cate2 = new Swiper("#swipercate2 > .swiper", {
		// height: 56,
		speed: 400,
		slidesPerView: 'auto',
	});

	swiper_cate3 = new Swiper("#swipercate3 > .swiper", {
		// height: 56,
		direction: 'vertical',
		// spaceBetween: 8,
		speed: 400,
		slidesPerView: 1,
		loop: true,
		autoplay: {
			delay: 4000,
			disableOnInteraction: false,
		},
	});

	swiper_caladar = new Swiper("#swiper_caladar > .swiper", {
		// height: 56,
		speed: 400,
		slidesPerView: 'auto',
		// between: 10
	});



});

//팝업창
function alertViewShow(text, type){

	if ( type === 'productDetail' ) {
		productDetailViewHide();
	}

	if ( type === 'couponList' ) {
		$("#productCouponView").hide();
	}

	// $("#no_pop").show();
	const pop_div = document.createElement("div");
	pop_div.setAttribute("id", "no_pop");
	document.getElementById('main').appendChild(pop_div);
	sendAjaxHtml("/defaultPop/", {'text' : text, 'type' : type}, 'POST', 'no_pop');

	// $("#no_pop").show();
}

function alertViewDoubleShow(text, type){

	if ( type === 'productDetail' ) {
		productDetailViewHide();
	}

	if ( type === 'couponList' ) {
		$("#productCouponView").hide();
	}

	const pop_div = document.createElement("div");
	pop_div.setAttribute("id", "no_pop");
	document.getElementById('main').appendChild(pop_div);
	sendAjaxHtml("/defaultPop/double", {'text' : text, 'type' : type}, 'POST', 'no_pop');
}

function alertViewThreeShow(text, type){

	if ( type === 'productDetail' ) {
		productDetailViewHide();
	}

	if ( type === 'couponList' ) {
		$("#productCouponView").hide();
	}

	const pop_div = document.createElement("div");
	pop_div.setAttribute("id", "no_pop");
	document.getElementById('main').appendChild(pop_div);
	sendAjaxHtml("/defaultPop/three", {'text' : text, 'type' : type}, 'POST', 'no_pop');
}

function alertViewHide(type){
	$("#no_pop").remove();

	if ( type === 'productDetail' ) {
		$("#productDetailView").show();
	}

	if ( type === 'couponList' ) {
		$("#productCouponView").show();
	}

	if ( type === 'locationBasket' ) {
		location.href="/basket";
	}

	if ( type === 'locationReload') {
		location.reload();
	}

	if ( type === 'locationSurgery') {
		location.href="/selectSurgery";
	}

}


function alertViewShowConfirm(text, type)
{
	const pop_div = document.createElement("div");
	pop_div.setAttribute("id", "no_pop");
	document.getElementById('main').appendChild(pop_div);
	sendAjaxHtml("/defaultPop/confirm", {'text' : text, 'type' : type}, 'POST', 'no_pop');
}

function alertViewShowReservation(text, type)
{
	const pop_div = document.createElement("div");
	pop_div.setAttribute("id", "no_pop");
	document.getElementById('main').appendChild(pop_div);
	sendAjaxHtml("/defaultPop/reservation", {'text' : text, 'type' : type}, 'POST', 'no_pop');
}

function resProView(that){
	if ( $('.resProductView').css('display') === 'block' ){
		$(".resProductView").hide();
		$(that).removeClass('resBtnOn');
		$(that).addClass('resBtnOff');
	} else {
		$(".resProductView").show();
		$(that).removeClass('resBtnOff');
		$(that).addClass('resBtnOn');
	}
}

function setProductFreeCoupon(){
	let ii = 0;
	$('.couponCheckBoxFreeCls').each(function(){
		if ( $(this).prop('checked') ) {
			let setHtml = "";
			setHtml += "<tr>";
			setHtml += "<td class='freeCText' style='width: 95%; vertical-align: middle;'>"+$(this).data('proname')+"</td>";
			setHtml += "<td style='text-align: right; width: 10%;'>";
			setHtml += "<img src='/common/homepage/img/common/ico_close.svg' style='width: 20px; height: 20px;' onclick='deleteFreeCoupon(this)'>";
			setHtml += "</td>";
			setHtml += "</tr>";

			$(".freeCouponList").append(setHtml);
			ii++;
		}
	});

	if ( ii > 0 ) {
		$(".freeCouponList").show();
	} else {
		alertViewShow('혜택을 선택해주세요.', 'couponList');
		return false;
	}

	productFreeCouponViewHide();
}

function deleteFreeCoupon(that){
	// console.log($(that).parent().parent().parent().html());
	$(that).parent().parent().remove();
}

//예약 상품상세 리스트
function productDetailViewShow(muid, suid) {
	const data = { 'category1' : muid, 'category2': suid };
	// console.log(data);
	sendAjaxHtml("/selectSurgery/getApiProductListView", data, 'GET', 'productDetailViewback');
}

function productDetailViewBasketShow(muid, suid) {
	let productArr = new Array();
	$('.detailProduct').each(function (){
		if ( $(this).data('b_category1') == muid && $(this).data('b_category2') == suid ) {
			productArr.push($(this).data('id'));
		}
	});

	// console.log(productArr); return false;
	const data = { 'category1' : muid, 'category2': suid, 'productArr' : productArr };
	// console.log(data);
	sendAjaxHtml("/basket/getApiProductListView", data, 'GET', 'productDetailViewback');
}

function productDetailViewHide() {
	if ( $("#productDetailView").css('display') === 'block' ) {
		$("#productDetailView").hide();
	} else {
		$("#productDetailView").show();
	}
}

function basketReservationFun(){
	let productArr = new Array();
	let productArr2 = new Array();
	$('.detailProduct').each(function (){
		if ( $(this).prop('checked') ) {

			productArr2 = new Object();
			productArr2.code = $(this).data('id');
			productArr2.b_category1 = $(this).data('b_category1');
			productArr2.b_category2 = $(this).data('b_category2');
			productArr2.category2_nm = $(this).data('category2_nm');
			productArr2.b_remains_pro = $(this).data('b_remains_pro');
			productArr2.product_nm = $(this).data('product_nm');
			productArr2.detail_total_price = $(this).data('detail_total_price');
			productArr2.price = $(this).val();

			productArr.push(productArr2);
		}
	});

	// 총 시술금액 : og_subProPrice
	// 혜택 금액 : discountPrice
	// 총 결제 예상 금액 : subProPrice
	let og_subProPrice = $('.og_subProPrice').text().replace(',', '');
	let discountPrice = $('.discountPrice').text().replace(',', '');
	let subProPrice = $('.subProPrice').text().replace(',', '');

	// let data = { 'productArr' : productArr, 'og_subProPrice' : og_subProPrice, 'discountPrice' : discountPrice, 'subProPrice' : subProPrice };

	var form = document.createElement("form");
	form.setAttribute("charset", "UTF-8");
	form.setAttribute("method", "POST");  //Post 방식
	form.setAttribute("action", "/reservation"); //요청 보낼 주소

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'productArr');
	console.log(productArr);
	hiddenField.setAttribute("value", JSON.stringify(productArr));
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'og_subProPrice');
	hiddenField.setAttribute("value", og_subProPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'discountPrice');
	hiddenField.setAttribute("value", discountPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'subProPrice');
	hiddenField.setAttribute("value", subProPrice);
	form.appendChild(hiddenField);

	document.body.appendChild(form);
	form.submit();

}

function couponViewOpen(proCode = null){
	let couponArr = $("#input_"+proCode).data('coupon');
	let productArr = $("#input_"+proCode).data('product');
	let category1_uid = $("#input_"+proCode).data('category1_uid');
	let category2_uid = $("#input_"+proCode).data('category2_uid');
	console.log(proCode);
	console.log(productArr);

	sendAjaxHtml("/selectSurgery/getApiProductCouponView", { 'code' : proCode, 'couponArr' : couponArr, 'productArr' : productArr, 'category1_uid' : category1_uid, 'category2_uid' : category2_uid }, 'POST', 'productCouponViewback');

	$("#productDetailView").hide();
	//$("#productCouponView").show();
}

function productCouponViewHide(){
	$("#productDetailView").show();
	$("#productCouponView").hide();
}

function productFreeCouponViewHide(){
	$("#freeProductCouponViewback").hide();
}

//예약 페이지 전체 카테고리
function totalCategoryViewShowFun(){
	$("#totalCategoryView").show();
}
function totalCategoryViewHideFun(){
	$("#totalCategoryView").hide();
}

//예약 페이지 전체 카테고리
function allRankShowFun(){
	$("#allRankView").show();
}
function allRankHideFun(){
	$("#allRankView").hide();
}

//상담
function counselViewShowFun(){
	$("#counselView").show();
}
function counselViewHideFun(){
	$("#counselView").hide();
}

function pvlCounselViewShowFun(){
	$("#pvlCounselView").show();
}

function pvlCounselViewHideFun(){
	$("#pvlCounselView").hide();
}

//상담
function modifyResViewShowFun(){
	$("#modifyResView").show();
}
function modifyResViewHideFun(){
	$("#modifyResView").hide();
}

function all_menu_fun_show(){
	$('#all_menu').css('right', '0px');
}

function all_menu_fun_hide(){
	$('#all_menu').css('right', '-100%');
}

function surgeryBenefitViewHideFun(){
	$("#surgeryBenefitView").hide();
}


//html ajax 공통 모듈
function sendAjaxHtml(action, param = null, method, id, async = true) {
	//$("#"+id).html("");

	$.ajax({
		url: action,
		data: param,
		method: method,
		dataType: 'html',
		async: async,
		success:function(data){
			console.log(data);
			$("#"+id).html(data);
		}
		,fail:function(xhr, status, errorThrown) {
			console.log("오류가 발생하였습니다.<br>");
			console.log("오류명: " + errorThrown + "<br>");
			console.log("상태: " + status);
		}
	});
}



//예약페이지 메인 카테고리
function mainCategoryFun(uid, view_id, index){
	const m_val = uid;

	$(".v_i").hide();

	if ( uid === 'event' ) {
		$(".v_" + m_val).show();
	}

	$(".m_i").removeClass('SelectSurgery_active__Yw0Oo');
	$(".m_i_"+uid).addClass('SelectSurgery_active__Yw0Oo');

	$(".b_m_i_"+uid).prop('checked', true);

	var indexCnt = 0;
	if ( index !== 0 ) {
		indexCnt = index - 1;
	} else {
		indexCnt = index;
	}
	swiper_cate1.slideTo(indexCnt, 500, false);

	if ( uid !== 'event' ) {
		$(".SelectSurgery_depth2-wrap__ItSPM").hide();
	} else {
		$(".SelectSurgery_depth2-wrap__ItSPM").show();
	}

	/*상품 리스트
	 * 이벤트가 아닌 카테고리의 경우 리스트 노출
	 */
	let type = 'event';
	if ( uid !== 'event' ) {
		type = 'sub';
	}
	const data = {'type' : type, 'uid' : uid};
	sendAjaxHtml("/selectSurgery/getSubCategoryProductList", data, 'GET', view_id);
	// if ( uid !== 'event' ) {
	// 	// 카테고리가 event 가 아닌경우
	// 	const data = {'type' : 'sub', 'uid' : uid};
	// 	sendAjaxHtml("/selectSurgery/getSubCategoryProductList", data, 'GET', view_id);
	// } else {
	// 	// 카테고리가 event 인경우
	// 	var eventSuid = '';
	// 	$(".v_i").each(function(idx) {
	// 		if ( idx === 0 ) {
	// 			eventSuid = $(this).data('uid');
	// 		}
	// 	});
	//
	// 	subCategoryFun(eventSuid, view_id);
	// }

	$("#totalCategoryView").hide();
}

function reservationDayChange(uid, index){
	$(".m_i").removeClass('ReserveDate_selected__vHeMH');
	$(".m_i_"+uid).addClass('ReserveDate_selected__vHeMH');

	var indexCnt = 0;
	if ( index !== 0 ) {
		indexCnt = index - 1;
	} else {
		indexCnt = index;
	}
	swiper_caladar.slideTo(indexCnt, 500, false);

	$('#datepicker').val(uid);

	let treatment_shop = $('#proArr').val();
	let is_counsel = '';
	if ( $('#is_counsel').prop('checked') ) {
		is_counsel = 'N';
	} else {
		is_counsel = 'Y';
	}

	$('#reserve-date').val(uid);

	let data = { 'date' : uid, 'treatment_shop' : treatment_shop, 'is_counsel' : is_counsel };
	sendAjaxHtml("/reservation/getSurgeryRoomTime", data, 'GET', 'timeView');
}

//예약페이지 서브 카테고리
function subCategoryFun(uid, view_id){
	const m_val = uid;

	$(".v_i").removeClass('SelectSurgery_active__Yw0Oo');
	$(".v_i").each(function (){
		if ( $(this).data('uid') === uid ) {
			$(this).addClass('SelectSurgery_active__Yw0Oo');
		}
	});

	const data = {'type' : 'event', 'uid' : uid};
	sendAjaxHtml("/selectSurgery/getSubCategoryProductList", data, 'GET', view_id);

}

//예약페이지 예약 상세
function proAddBtnFun(that){
	$(that).parent().find(".proAddView").toggle(100);

	if ( $(that).hasClass('SelectSurgery_more__YTC3C') ) {
		$(that).removeClass('SelectSurgery_more__YTC3C');
		$(that).addClass('SelectSurgery_more__YTC3C_on');
	} else {
		$(that).removeClass('SelectSurgery_more__YTC3C_on');
		$(that).addClass('SelectSurgery_more__YTC3C');
	}

}

function loginWithKakao(reffer = '/') {

	// 로그인 창을 띄웁니다.
	Kakao.Auth.login({
		success: function(authObj) {
			var access_token = Kakao.Auth.getAccessToken();
			Kakao.API.request({
				url: '/v2/user/me',
				success: function (res) {
					// console.log(access_token);
					// console.log(res.id);
					// console.log(res.kakao_account.name);
					// console.log(res.kakao_account.phone_number);
					// console.log(res.kakao_account.email);
					// console.log(res.kakao_account.birthyear);
					// console.log(res.kakao_account.birthday);
					// return false;

					$.post("/registMember", {
							"access_token": access_token
							, "type" : "kakao"
							, "id": res.id
							, "name": res.kakao_account.name
							, "phone_number": res.kakao_account.phone_number
							, "email": res.kakao_account.email
							, "birthyear": res.kakao_account.birthyear
							, "birthday": res.kakao_account.birthday
							, 'reffer' : reffer
						},
						function (response) {
							var res = JSON.parse(response);
							console.log(res); return false;
							if ( res.res === 'success' ) {
								location.replace('/');
							} else {
								// console.log(res.data.type); return false;
								var form = document.createElement("form");
								form.setAttribute("charset", "UTF-8");
								form.setAttribute("method", "POST");  //Post 방식
								form.setAttribute("action", "/signup"); //요청 보낼 주소
								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'type');
								hiddenField.setAttribute("value", res.data.type);
								form.appendChild(hiddenField);

								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'id');
								hiddenField.setAttribute("value", res.data.id);
								form.appendChild(hiddenField);

								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'name');
								hiddenField.setAttribute("value", res.data.name);
								form.appendChild(hiddenField);

								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'phone_number');
								hiddenField.setAttribute("value", res.data.phone_number);
								form.appendChild(hiddenField);

								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'email');
								hiddenField.setAttribute("value", res.data.email);
								form.appendChild(hiddenField);

								var hiddenField = document.createElement("input");
								hiddenField.setAttribute("type", 'hidden');
								hiddenField.setAttribute("name", 'birthday');
								hiddenField.setAttribute("value", res.data.birthday);
								form.appendChild(hiddenField);

								document.body.appendChild(form);
								// console.log($("from")); return false;
								form.submit();
								return false;
							}
							//
						}
					);
				}
			});
		},
		fail: function(err) {
			alert(JSON.stringify(err));
		}
	});

}

function signup_allCheck(that){
	if ( $(that).prop("checked") === true ) {
		$(".agree").prop("checked", true);

		agree1Click();
		agree2Click();
	} else {
		$(".agree").prop("checked", false);
		$("#agree1").prop("checked", true);

		agree1Click();
		agree2Click();
	}
}

function serviceView(type){
	const data = {'type' : type};

	sendAjaxHtml("/service/serviceInfo", data, 'GET', 'serviceView');

	$('#serviceView').toggle(400);
}

function serviceViewClose(){
	$('#serviceView').toggle(400);
	// $("#serviceView").html("");
}

function registBtn() {

	if ( !$("#agree1").prop("checked") ) {
		$("#agree1").focus();
		return false;
	}

	if ( !$("#agree2").prop("checked") ) {
		$("#agree2").focus();
		return false;
	}

	const data = {
		'id' : $("#id").val()
		, 'type' : $("#type").val()
		, 'userName' : $("#userName").val()
		, 'birth' : $("#birth").val()
		, 'phone' : $("#phone").val()
		, 'agree1' : $("#agree1").prop("checked") ? 'Y' : 'N'
		, 'agree2' : $("#agree2").prop("checked") ? 'Y' : 'N'
		, 'agree3' : $("#agree3").prop("checked") ? 'Y' : 'N'
	}

	sendAjaxHtml("/registMember/registMember", data, 'POST', 'serviceView');
}

function handleOnInput(el, maxlength) {
	if(el.value.length > maxlength)  {
		el.value = el.value.substr(0, maxlength);
	}
}


function onlyhangul(that) {
	$(that).val($(that).val().replace(/[^가-힣ㄱ-ㅎㅏ-ㅣa-zA-Z\u1100-\u1112\u318D\u119E\u11A2\u2022\u2025\u00B7\uFE55\s]/g, ''));
}

//이용약관 체크
//start
function agree1Click(){
	if ( !$("#agree1").prop("checked") ) {
		$("#agree1_warn_text").show();
	} else {
		$("#agree1_warn_text").hide();
	}

}

function agree2Click(){
	if ( !$("#agree2").prop("checked") ) {
		$("#agree2_warn_text").show();
	} else {
		$("#agree2_warn_text").hide();
	}
}
//end

function detailProductPrice(){
	let sumPrice = 0;
	let og_sumPrice = 0;
	$(".detailProduct").each(function (){
		if ( $(this).prop("checked") ) {
			sumPrice = Number(sumPrice) + Number($(this).val());

			og_sumPrice = Number(og_sumPrice) + Number($(this).data('price'));
			// console.log($(this).data('coupon'));
		}
	});

	$(".subProPrice").text(commaPrice(sumPrice));
}

function detailProductPriceBasket(){
	let discountPrice = 0;
	let TotalDiscountPrice = 0;
	let og_sumPrice = 0;
	let sumPrice = 0;
	let codeArr = new Array();
	let mPriceArr = new Array();
	let oPriceArr = new Array();
	let oPriceArr_asdf = new Array();
	$(".detailProduct").each(function (){
		if ( $(this).prop("checked") ) {
			codeArr.push($(this).data('id'));
		}
	});

	$.each(codeArr, function (index, val) {
		oPriceArr.push( $.trim($('#price_' + val).text()).replace(',', '') );
		console.log($('#tr_' + val).css("display"));
		if ( $('#tr_' + val).css("display") == 'table-row' ) {
			mPriceArr.push( $.trim($('#text_' + val).text()).replace(',', '') );
		} else {
			mPriceArr.push('0');
		}
	});

	// console.log(codeArr);
	// console.log(oPriceArr);
	// console.log(mPriceArr);

	$.each(oPriceArr, function (index, val) {
		og_sumPrice = Number(og_sumPrice) + Number(val);
		if ( mPriceArr[index] != '0' ) {
			discountPrice = Number(discountPrice) + Number(val) - Number(mPriceArr[index]);
		}
	});
	$.each(mPriceArr, function (index, val) {
		TotalDiscountPrice = Number(TotalDiscountPrice) + Number(val);
	});

	// console.log('og_sumPrice - ' + og_sumPrice);
	// console.log('discountPrice - ' + discountPrice);
	// console.log('TotalDiscountPrice - ' + TotalDiscountPrice);

	sumPrice = Number(og_sumPrice) - Number(discountPrice);
	if ( discountPrice > 0 ) {
		$(".discountPrice").text('-' + commaPrice(discountPrice));
		$(".subProPrice").text(commaPrice(sumPrice));
	} else {
		$(".discountPrice").text('0');
		$(".subProPrice").text(commaPrice(og_sumPrice));
	}

	$(".og_subProPrice").text(commaPrice(og_sumPrice));

	// console.log(og_sumPrice);
	// console.log(sumPrice);
	// console.log(discountPrice);

}


function addBasket(){
	let basketCnt = 0;
	let basketArray = new Array();
	let couponArray = new Array();
	let category1 = $("#category1").val();
	let category2 = $("#category2").val();
	$(".detailProduct").each(function (){
		if ( $(this).prop("checked") ) {
			basketArray.push($(this).data("id").replace('input_', ''));
			couponArray.push($(this).data("coupon"))
			basketCnt++;
		}
	});

	if ( basketCnt === 0 ) {
		alertViewShow('옵션을 선택해주세요.', 'productDetail');
		return false;
	} else {
		// alertViewShow('장바구니로 이동합니다.', 'productDetail');
	}

	const data = { 'basketArr' : basketArray, 'couponArr': couponArray, 'category1' : category1, 'category2' : category2 };

	sendAjaxHtml("/basket/registBasket", data, 'POST', 'serviceView');
}

function addBasketIn(){
	let basketCnt = 0;
	let basketArray = new Array();
	let couponArray = new Array();
	let category1 = $("#category1").val();
	let category2 = $("#category2").val();
	$(".basketDetailProduct").each(function (){
		if ( $(this).prop("checked") ) {
			basketArray.push($(this).data("id").replace('basket_', ''));
			couponArray.push($(this).data("coupon"))
			basketCnt++;
		}
	});

	if ( basketCnt === 0 ) {
		alertViewShow('옵션을 선택해주세요.', 'productDetail');
		return false;
	} else {
		// alertViewShow('장바구니로 이동합니다.', 'productDetail');
	}

	const data = { 'basketArr' : basketArray, 'couponArr': couponArray, 'category1' : category1, 'category2' : category2, 'type' : 'basket' };

	sendAjaxHtml("/basket/registBasket", data, 'POST', 'serviceView');
}

function basketDeleteProduct(code, category1, category2){
	const data = { 'code' : code, 'category1' : category1, 'category2' : category2 };

	sendAjaxHtml("/basket/deleteProduct", data, 'POST', 'serviceView');
}

function selectBasketDelete(){

	let basketArray = new Array();
	let category1Array = new Array();
	let category2Array = new Array();

	$(".detailProduct").each(function (){
		if ( $(this).prop("checked") ) {
			basketArray.push($(this).data("id").replace('basket_', ''));
			category1Array.push($(this).data("category1_uid"));
			category2Array.push($(this).data("category2_uid"));
		}
	});

	// console.log(basketArray);
	// console.log(category1Array);
	// console.log(category2Array);

	const data = { 'basketArray' : basketArray, 'category1Array': category1Array, 'category2Array' : category2Array };
	sendAjaxHtml("/basket/selectDeleteProduct", data, 'POST', 'serviceView');
}

function selectBasketAll(){
	$(".detailProduct").each(function (){
		if ( $('#basketAllCheck').prop('checked') ) {
			$(this).prop('checked', true);
		} else {
			$(this).prop('checked', false);
		}
	});

	detailProductPriceBasket();
}

function footerInfoView(){
	// console.log($(".footerInfo").css('display'));
	if ( $(".footerInfo").css('display') === 'block' ) {
		$(".footerInfo").attr('style', 'display: none !important');
	} else {
		$(".footerInfo").attr('style', 'display: block !important');
	}
}

function couponCheckBox(couponSeq, is_overlap){
	/*
	 * 1. 중복 사용 체크
	 */
	if ( is_overlap === '0' ) {
		// 중복불가
		$(".couponCheckBoxCls").each(function(){
			if ( $(this).val() === couponSeq ) {
				if ( $(this).prop("checked") ) {
					$(this).prop("checked", false);
				} else {
					$(this).prop("checked", true);
				}
			} else {
				$(this).prop("checked", false);
				// $(this).attr("disabled", true);
			}
		});
	} else {
		$(".couponCheckBoxCls").each(function(){
			if ( $(this).data('is_overlap') === 0 ) {
				$(this).prop("checked", false);
			} else {
				if ($(this).val() === couponSeq) {
					if ( $(this).prop("checked") ) {
						$(this).prop("checked", false);
					} else {
						$(this).prop("checked", true);
					}
				}
			}
		});
	}

}

function saveCouponToProduct() {
	//적용 쿠폰 정리
	let i = 0;
	var couponArr = new Array();
	$(".couponCheckBoxCls").each(function(){
		if ( $(this).prop('checked') ) {
			couponArr[i] = new Array();
			couponArr[i]['seq'] = $(this).val();
			couponArr[i]['is_overlap'] = $(this).data('is_overlap');
			couponArr[i]['discount_type'] = $(this).data('discount_type');
			couponArr[i]['discount_price_type'] = $(this).data('discount_price_type');
			couponArr[i]['discount_category1_seq'] = $(this).data('discount_category1_seq');
			couponArr[i]['discount_category2_seq'] = $(this).data('discount_category2_seq');
			couponArr[i]['discount_per'] = $(this).data('discount_per');
			couponArr[i]['discount_price'] = $(this).data('discount_price');
			couponArr[i]['discount_max_price'] = $(this).data('discount_max_price');
			couponArr[i]['discount_min_price'] = $(this).data('discount_min_price');
			couponArr[i]['discount_shop_code'] = $(this).data('discount_shop_code');
			couponArr[i]['discont_event_code'] = $(this).data('discont_event_code');

			i++;
		}
	});

	// console.log(couponArr); return false;

	// 선택된 쿠폰이 없다면 alert
	if ( i == 0 ) {
		// console.log(i);
		alertViewShow('혜택을 선택해주세요.', 'couponList');
	}

	// console.log(couponArr);

	// 상품에 관련하여 전체 할인 및 단품 할인, 카테고리 할인 적용
	let proArr = new Array();
	$(".detailProduct").each(function(aa){
		// console.log(couponArr);
		let row = $(this);
		proArr[aa] = new Array();
		proArr[aa]['code'] = row.data('id');
		proArr[aa]['og_price'] = row.data('og_price');
		proArr[aa]['price'] = row.data('price');
		proArr[aa]['detail_total_price'] = row.data('detail_total_price');
		proArr[aa]['category1_uid'] = row.data('category1_uid');
		proArr[aa]['category2_uid'] = row.data('category2_uid');
		proArr[aa]['coupon'] = new Array();

		let couponArr2 = new Array();
		$.each(couponArr, function (index, val){
			// console.log(val);
			if ( val['discount_type'] === '단품할인' ) {
				//단품할인 경우
				if ( val['discount_shop_code'] === proArr[aa]['code'] ) {
					couponArr2.push(val);
				}
			} else if ( val['discount_type'] === '주문금액' ) {
				//주문금액 경우
				couponArr2.push(val);
			} else if ( val['discount_type'] === '카테고리할인' ) {
				//카테고리할인 경우
				if ( proArr[aa]['category1_uid'] === val['discount_category1_seq'] && proArr[aa]['category2_uid'] === val['discount_category2_seq'] ) {
					couponArr2.push(val);
				}
				// } else if ( val['discount_type'] === '' || val['discount_type'] === '이벤트할인' ) {
			} else if ( val['discount_type'] === '이벤트할인' ) {
				//이벤트할인 경우
				if ( proArr[aa]['category2_uid'] === val['discont_event_code'] ) {
					couponArr2.push(val);
				}
			}
			// console.log(couponArr2);
		});
		proArr[aa]['coupon'].push(couponArr2);

	});

	// console.log(proArr);

	//각 상품에 적용
	$.each(proArr, function (index, row){
		// console.log(row['coupon']);
		if ( row['coupon'].length > 0 ) {
			let stsPrice = 0;
			let resPrice = 0;
			let price49per = 0;
			let changePrice = 'false';
			let couponArray = new Array();
			$.each(row['coupon'], function (index2, row2){
				console.log(row);
				console.log(row2);
				$.each(row2, function (index3, row3){
					if ( row3['discount_price_type'] == '정액' ) {
						stsPrice += Number(row3['discount_price']);
					} else if ( row3['discount_price_type'] == '정률' ) {
						stsPrice += Number( (Number(row['price']) / Number(row3['discount_per'])) );
					}

					couponArray.push(row3['seq']);
					changePrice = 'true';
				});
				// resPrice = Number(row['price']) - Number(stsPrice);
				resPrice = Number(row['detail_total_price']) - Number(stsPrice);

				// console.log(row['price']);
				price49per = Number(row['price']) - ((Number(row['price']) * 49) / 100);
				console.log('price49per - '+price49per + " , resPrice - "+ resPrice);

				if ( price49per > resPrice ) {
					resPrice = price49per;
				}
			});
			// console.log(changePrice);
			if ( resPrice !== row['price'] && changePrice == 'true' ) {
				resPrice = Math.floor(resPrice / 100) * 100;
				$("#text_" + row['code']).text(commaPrice(resPrice));
				$("#input_" + row['code']).val(resPrice);
				$("#tr_" + row['code']).show();
				$("#input_" + row['code']).data("coupon", couponArray.toString());
			}
			// input_<?=$row['code']?>
			// text_<?=$row['code']?>
		}
	});

	productCouponViewHide();
	// console.log(proArr);
	detailProductPriceBasket();
}

function commaPrice(text){
	return text.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}

function couponCancelBtn(proCode){
	const ogPrice = $("#input_" + proCode).data('og_price');

	$("#tr_" + proCode).hide();
	$("#input_" + proCode).val(ogPrice);

	detailProductPriceBasket();
}

function couponCancelBtnBasket(proCode){
	$("#tr_" + proCode).hide();

	detailProductPriceBasket();
}

function noticeView(noticeSeq){
	if ( $(".ckeditorView_"+noticeSeq).css('display') === 'none' ) {
		$(".ckeditorView_"+noticeSeq).toggle(400);
		$(".ckeditorImg_"+noticeSeq).attr('src', '/common/homepage/img/common/ico_more_off.svg');
	} else {
		$(".ckeditorView_"+noticeSeq).toggle(400);
		$(".ckeditorImg_"+noticeSeq).attr('src', '/common/homepage/img/common/ico_more_on.svg');
	}

}

function parkView(id, that){
	$(".about_park_menu").removeClass('about_park_menu_on');
	$(that).addClass('about_park_menu_on');

	$(".parkView").hide();
	$("."+id).show();
}

function hinfoViewFun(that){
	$(".hinfoViewBtn").removeClass('hinfoViewBtn_On');
	$(".hinfoViewBtn").addClass('hinfoViewBtn_Off');
	$(that).removeClass('hinfoViewBtn_Off');
	$(that).addClass('hinfoViewBtn_On');
}

function aboutMoveView(viewId, that) {
	// $(".aboutMenu").removeClass('About_active__FX0hg');
	// $(that).addClass('About_active__FX0hg');

	// console.log(viewId);

	$('html, body').animate( { scrollTop : $("#"+viewId).offset().top - 200 }, 500 );
}


function freeCouponViewOpen(){
	// let couponArr = $("#input_"+proCode).data('coupon');
	// let productArr = $("#input_"+proCode).data('product');
	// let category1_uid = $("#input_"+proCode).data('category1_uid');
	// let category2_uid = $("#input_"+proCode).data('category2_uid');
	// console.log(proCode);
	// console.log(productArr);

	sendAjaxHtml("/reservation/getApiFreeCouponView", null, 'POST', 'freeProductCouponViewback');

	// $("#productDetailView").hide();
	$('#freeProductCouponViewback').show();
}

function mileageCheck(that){
	$(that).val($(that).val().replace(/[^0-9.]/g, ''));

	let userMileage = $('#userMileage').text();
	let mileage = $('#mileage').val();
	let setUseMileage = 0

	if ( mileage >= userMileage ) {
		// $('#mileage').val();
		setUseMileage = userMileage;
	} else {
		setUseMileage = mileage;
	}
	$('#mileage').val(setUseMileage);
	$('#setUseMileage').text('-' + setUseMileage);
}

function setTimeData(roomSeq, timeData, index){
	if ( $('.t_i_'+index).hasClass('timeButtonDisable') ) {
		return false;
	}

	$('.t_i').removeClass('timeButtonEnableSelect');
	$('.t_i_'+index).addClass('timeButtonEnableSelect');

	$('#room_seq').val(roomSeq);
	$('#reserve-time').val(timeData);

}

function setReservation() { //
	let before_reserve_number = $('#before_reserve_number').val();
	let reserve_date = $('#reserve-date').val();
	let reserve_time = $('#reserve-time').val();
	let treament_shop = $('#treament_shop').val();
	// console.log(treament_shop); return false;
	let treament_item = $('#treament_item').val();
	let is_counsel = $('#is_counsel').prop('checked') ? 'Y' : 'N';
	let room_seq = $('#room_seq').val();
	let useCoupon = '';
	let mileage = $('#mileage').val() == '' ? '0' : $('#mileage').val(); // 마일리지
	let og_subProPrice = $('#og_subProPrice').val(); // 총 시술금액
	let discountPrice = $('#discountPrice').val(); // 혜택
	let subProPrice = $('#subProPrice').val(); // 총 결제 예상금액
	let product = $('#product').val(); // 총 결제 예상 상품

	if ( reserve_time == '' ) {
		alertViewShow('시간을 선택해주세요', 'couponList');
		return false;
	}

	$('.freeCText').each(function(index) {
		if ( index != 0 ) {
			useCoupon += ',' + $(this).text();
		} else {
			useCoupon += $(this).text();
		}
	});

	let memo = '';
	memo += '마일리지 - ' + mileage + ', ';
	memo += '쿠폰 - ' + useCoupon + '';


	var form = document.createElement("form");
	form.setAttribute("charset", "UTF-8");
	form.setAttribute("method", "POST");  //Post 방식
	form.setAttribute("action", "/reservation/setReservation"); //요청 보낼 주소

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'before_reserve_number');
	hiddenField.setAttribute("value", before_reserve_number);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'reserve_date');
	hiddenField.setAttribute("value", reserve_date);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'reserve_time');
	hiddenField.setAttribute("value", reserve_time);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'treament_shop');
	hiddenField.setAttribute("value", treament_shop);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'treament_item');
	hiddenField.setAttribute("value", treament_item);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'room_seq');
	hiddenField.setAttribute("value", room_seq);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'memo');
	hiddenField.setAttribute("value", memo);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'is_counsel');
	hiddenField.setAttribute("value", is_counsel);
	form.appendChild(hiddenField);

	//
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'mileage');
	hiddenField.setAttribute("value", mileage);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'og_subProPrice');
	hiddenField.setAttribute("value", og_subProPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'discountPrice');
	hiddenField.setAttribute("value", discountPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'subProPrice');
	hiddenField.setAttribute("value", subProPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'product');
	hiddenField.setAttribute("value", product);
	form.appendChild(hiddenField);
	//

	document.body.appendChild(form);
// console.log($("from")); return false;
	form.submit();
	return false;

}

function reservationDayChangeCounsel(){
	// $(".m_i").removeClass('ReserveDate_selected__vHeMH');
	let uid = '';
	let index = '';
	$(".m_i").each(function(){
		if ( $(this).hasClass('ReserveDate_selected__vHeMH') ) {
			uid = $(this).data('uid');
			index = $(this).data('index');
		}
	});

	reservationDayChange(uid, index);
}

function mapagePageMove(id, that) {

	$(".MyMenu_ac").removeClass('MyMenu_active__AG2o+');
	$("."+id).addClass('MyMenu_active__AG2o+');

	if ( id == 'coupon' ) {
		sendAjaxHtml("/mypage/coupon", null, 'GET', 'changeMypage');
	} else if ( id == 'res' ) {
		sendAjaxHtml("/mypage/reservationInfo", null, 'GET', 'changeMypage');
	} else if ( id == 'remains' ) {
		sendAjaxHtml("/mypage/remainsItem", null, 'GET', 'changeMypage');
	}

}

function modifyDateBtnOnOffFun(that){

	$('.modifyDateBtnAll').removeClass('modifyDateBtnOn');
	$('.modifyDateBtnAll').addClass('modifyDateBtnOff');
	$(that).removeClass('modifyDateBtnOff');
	$(that).addClass('modifyDateBtnOn');

}

function modifyReservation(res_num){
	let id = '';
	$('.modifyDateBtnAll').each(function(){
		if ( $(this).hasClass('modifyDateBtnOn') ) {
			id = $(this).data('id');
		}
	});
	// console.log(id);

	if ( id == 'date_modify' ) {
		// 날짜변경
		modifyLocationReservation()
	} else {
		// 예약취소
	}

}

function modifyLocationReservation(){
	const res_num = $('#res_num').val();
	const productArr = $('#productArr').val();
	const og_subProPrice = $('#og_subProPrice').val();
	const discountPrice = $('#discountPrice').val();
	const subProPrice = $('#subProPrice').val();

	// console.log(res_num);
	// console.log(productArr);
	// console.log(og_subProPrice);
	// console.log(discountPrice);
	// console.log(subProPrice);
	// return false;

	var form = document.createElement("form");
	form.setAttribute("charset", "UTF-8");
	form.setAttribute("method", "POST");  //Post 방식
	form.setAttribute("action", "/reservation/"); //요청 보낼 주소

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'res_num');
	hiddenField.setAttribute("value", res_num);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'productArr');
	hiddenField.setAttribute("value", productArr);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'og_subProPrice');
	hiddenField.setAttribute("value", og_subProPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'discountPrice');
	hiddenField.setAttribute("value", discountPrice);
	form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", 'hidden');
	hiddenField.setAttribute("name", 'subProPrice');
	hiddenField.setAttribute("value", subProPrice);
	form.appendChild(hiddenField);

	document.body.appendChild(form);
// console.log($("from")); return false;
	form.submit();
	return false;
}

/**
 * 장바구니 전체 선택 이벤트
 */
function basketSelectAll() {
	$(".detailProduct").each(function (){
		if ( $('#basketAllCheck').prop('checked') ) {
			if (!$(this).prop('disabled')) {
				$(this).prop('checked', true);
			}
		} else {
			$(this).prop('checked', false);
		}
	});

	let count = $(".detailProduct:checked").length;
	$("#basketCount").html(count);
	if (count > 0) {
		$("#basketCount").show();
	} else {
		$("#basketCount").hide();
	}

	//장바구니 금액 계산
	basketTotalPriceSet();
}

/**
 * 장바구니 개별 선택 이벤트
 */
function basketSelect() {
	let total_count = 0;
	let count = 0;

	$(".detailProduct").not(':disabled').each(function(index,item){
		total_count += 1;
		if (item.checked) count += 1;
	});

	if (count === total_count) {
		$('#basketAllCheck').prop('checked', true);
	} else  {
		$('#basketAllCheck').prop('checked', false);
	}

	basketTotalPriceSet();

	$("#basketCount").html(count);
	if (count > 0) {
		$("#basketCount").show();
	} else {
		$("#basketCount").hide();
	}
}

/**
 * 장바구니 금액 계산
 */
function basketTotalPriceSet() {
	let totalPrice = 0;
	let totalDcPrice = 0;

	$(".detailProduct").each(function(index,item){
		if (item.checked) {
			totalPrice += Number(item.value);
			totalDcPrice += Number(item.dataset.dc_price);
		}
	});

	$(".og_subProPrice").text(totalPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	$(".discountPrice").text((totalPrice - totalDcPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))
	$(".subProPrice").text(totalDcPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
}

/**
 * 장바구니 개별 상품 삭제
 * @param cateCode
 * @param tsCode
 */
function basketDelete(cateCode, tsCode, ctiCode) {
	sendAjaxHtml("/basket/setBasketDelete", {'tsCode': tsCode, 'cateCode': cateCode, 'ctiCode': ctiCode}, 'POST');

	setTimeout(function () { location.reload(); }, 500);
}

/**
 * 장바구니 선택된 상품 삭제
 */
function basketSelectDelete() {
	let check = false;
	$(".detailProduct").each(function(index,item){
		if (item.checked) {
			check = true;
			if (item.dataset.cti_code == '') {
				sendAjaxHtml("/basket/setBasketDelete", {'tsCode': item.dataset.ts_code, 'cateCode': item.dataset.sec_cate_code, 'ctiCode': ''}, 'POST');
			} else {
				sendAjaxHtml("/basket/setBasketDelete", {'tsCode': '', 'cateCode': '', 'ctiCode': item.dataset.cti_code}, 'POST');
			}

		}
	});

	if (check) {
		setTimeout(function () { location.reload(); }, 500);
	} else {
		alertViewShow('없앨 상품을 선택해 주세요');
	}
}

/**
 * 장바구니 상품 혜택 정보 보기
 * @param tsCode
 * @param cateCode
 */
function benefitView(tsCode, cateCode) {
	let arr = [];
	$(".dc_price").each(function (index, item) {
		arr.push(item.dataset.id);
	});

	sendAjaxHtml("/basket/getBenefitView", {'tsCode': tsCode, 'cateCode': cateCode, 'arr': arr}, 'POST', 'freeProductCouponViewback');

	$('#freeProductCouponViewback').show();
}

/**
 * 장바구니 상품 혜택 등록
 * @param tsCode
 * @param cateCode
 */
function setBenefitInsert(tsCode, cateCode) {

	if ($(".benefitCheckBox:checked").length > 0) {
		sendAjaxHtml("/basket/setBenefitInert", {'coupon': $(".benefitCheckBox:checked").val(),'tsCode': tsCode, 'cateCode': cateCode}, 'POST');

		setTimeout(function () { location.reload(); }, 1000);
	}
}

/**
 * 장바구니 상품 혜택 삭제
 * @param cateCode
 * @param tsCode
 */
function benefitDelete(cateCode, tsCode) {
	sendAjaxHtml("/basket/setBenefitDelete", {'tsCode': tsCode, 'cateCode': cateCode}, 'POST');

	setTimeout(function () { location.reload(); }, 500);
}

/**
 * 장바구니 상품 혜택 모달내 선택 이벤트
 * @param id
 */
function benefitCheck(id) {
	let target = $("#" + id);

	if (!target.prop('checked')) {
		target.prop('checked', false);
	} else {
		$("#benefitView input[type='checkbox']:checked").each(function(e, i) {
			i.checked = false;
		});

		target.prop('checked', true);
	}
}


function basketProductChange(cateCode) {
	sendAjaxHtml("/basket/getBasketProduct", { 'cateCode': cateCode }, 'GET', 'productDetailViewback');
}

function basketCheckSend() {
	let checkList = [];
	$(".detailProduct:checked").each(function(index,item){
		if (item.dataset.sec_cate_code.indexOf('EVT') !== -1) {
			checkList.push(item.dataset.ts_code);
		}
	});

	if (checkList.length > 0) {
		$.ajax({
			url: '/basket/checkEventUsed',
			data: {
				'list': checkList
			},
			method: 'post',
			dataType:'json',
			success:function(result){
				console.log(result);
				if (result.eventUsed == 'used') {
					alertViewDoubleShow('1회 체험가 상품을 이미 이용하셨어요<br>다시 확인 후, 예약해 주세요', 'locationReload');
				} else {
					basketSend();
				}
			}
		});
	} else {
		basketSend();
	}
}

function basketSend() {
	if ($(".detailProduct:checked").length > 0) {
		let tsCode = [];
		let remain = [];

		let eventTsCode = [];
		let eventTsSectCateCode = [];
		let eventRemain = [];

		$(".detailProduct:checked").each(function(index,item){
			if (item.dataset.sec_cate_code.indexOf('EVT') !== -1) {
				eventTsCode.push(item.dataset.ts_code);
				eventTsSectCateCode.push(item.dataset.sec_cate_code);
				eventRemain.push(item.dataset.remain_yn === 'Y' ? item.dataset.cti_code : '');
			} else {
				tsCode.push(item.dataset.ts_code);
				remain.push(item.dataset.remain_yn === 'Y' ? item.dataset.cti_code : '');
			}
		});

		document.cookie = 'resTsCode=' + tsCode.join() + ';path=/';
		document.cookie = 'resRemain=' + remain.join() + ';path=/';
		document.cookie = 'resEventTsCode=' + eventTsCode.join() + ';path=/';
		document.cookie = 'resEventTsSectCateCode=' + eventTsSectCateCode.join() + ';path=/';
		document.cookie = 'resEventRemain=' + eventRemain.join();
		document.cookie = 'resBasketYn=Y;path=/';
		document.cookie = "resNumber=;path=/";

		location.href="/reservation";
	} else {
		alertViewShow('상품을 선택해주세요.', 'productDetail');
	}

}

function reservation()
{
	let tsCode = [];
	let remain = [];

	let eventTsCode = [];
	let eventTsSectCateCode = [];
	let eventRemain = [];

	document.cookie = 'resTsCode=' + tsCode.join() + '; path=/';
	document.cookie = 'resRemain=' + remain.join() + '; path=/';
	document.cookie = 'resEventTsCode=' + eventTsCode.join() + '; path=/';
	document.cookie = 'resEventTsSectCateCode=' + eventTsSectCateCode.join() + '; path=/';
	document.cookie = 'resEventRemain=' + eventRemain.join() + '; path=/';
	document.cookie = 'resBasketYn=N;path=/';
	document.cookie = "resNumber=;path=/";

	location.href="/reservation";
}


/**
 * 장바구니 상품 추가 이벤트
 */
function basketProductAddEvent()
{
	let list = [];
	$(".productList input[type='checkbox']:checked").each(function(k,v) {
		list.push({
			'fir_cate_code': v.dataset.fir
			,'sec_cate_code': v.dataset.sec
			,'ts_code': v.dataset.code
			,'tse_end_datetime': v.dataset.end
		});
	});

	if (list.length > 0) {
		$("#basketSnackbarTrue").hide();
		$("#basketSnackbarFalse").hide();

		//장바구니 담기
		$.ajax({
			url: '/selectSurgery/setBasketProduct',
			data: {
				'list' : list
			},
			method: 'post',
			dataType: 'json',
			success:function(data){
				if (data.dup_check) {
					$("#basketSnackbarTrue").show();
				} else {
					$("#basketSnackbarFalse").show();
					$("#bastket_cnt").show();
					$("#bastket_cnt").text(data.basket_cnt > 9 ? '9+' : data.basket_cnt);
				}
				// 장바구니 스낵바 표시
				let basketSnackbar = document.getElementById("basketSnackbar");
				basketSnackbar.className = "show";
				// 3초 후에 스낵바 숨김
				setTimeout(function() {
					basketSnackbar.className = basketSnackbar.className.replace("show", "");
				}, 3000);
			}
		});

	} else {
		alertViewShow('상품을 선택해주세요.', 'productDetail');
	}
}

/**
 * 예약하기 이벤트
 */
function reservationProductAddEvent()
{
	if ($(".productList input[type='checkbox']:checked").length > 0) {
		let tsCode = [];
		let remain = [];

		let eventTsCode = [];
		let eventTsSectCateCode = [];
		let eventRemain = [];
		$(".productList input[type='checkbox']:checked").each(function(k,v) {
			if (v.dataset.sec.indexOf('EVT') !== -1) {
				eventTsCode.push(v.dataset.code);
				eventTsSectCateCode.push(v.dataset.sec);
				eventRemain.push('');
			} else {
				tsCode.push(v.dataset.code);
				remain.push('');
			}
		});

		document.cookie = 'resTsCode=' + tsCode.join() + ';path=/';
		document.cookie = 'resRemain=' + remain.join() + ';path=/';
		document.cookie = 'resEventTsCode=' + eventTsCode.join() + ';path=/';
		document.cookie = 'resEventTsSectCateCode=' + eventTsSectCateCode.join() + ';path=/';
		document.cookie = 'resEventRemain=' + eventRemain.join() + ';path=/';
		document.cookie = 'resBasketYn=N;path=/';
		document.cookie = "resNumber=;path=/";

        localStorage.setItem('reservationCompleted', 'true');

        location.href="/reservation";
    } else {
        alertViewShow('상품을 선택해주세요.', 'productDetail');
    }
}

window.onpageshow = function(event) {
    if (event.persisted || localStorage.getItem('reservationCompleted') === 'true') {
        location.reload();
        localStorage.removeItem('reservationCompleted');
    }
};

/**
 * 상품리스트 체크 이벤트
 * @param target
 */
function productListCheckEvent(target)
{
	if (target.checked) {
		$("#totalPrice").number(Number($("#totalPrice").text().replaceAll(",", "")) + Number(target.dataset.price));
	} else {
		$("#totalPrice").number(Number($("#totalPrice").text().replaceAll(",", "")) - Number(target.dataset.price));
	}

	if ($(".productList input[type='checkbox']:checked").length > 0) {
		$("#productDetailView").show();
	} else {
		$("#productDetailView").hide();
	}
}

/**
 * 메인 img height에 따라 footer 위치 조정
 */
function adjustFooterPosition() {
    const mainImgBottom = $('#mainImg').offset().top + $('#mainImg').outerHeight();
    const footer = $('footer');
    footer.css('top', mainImgBottom + 51 + 'px');
}

// 페이지 로딩 후 footer 위치 자동 조절
window.onload = function() {
    adjustFooterPosition();
    // resize 
    $(window).resize(function() {
        adjustFooterPosition();
    });
};

function getCookieByName(name) {
	const cookies = document.cookie.split(';');
	for (let cookie of cookies) {
		cookie = cookie.trim();
		if (cookie.startsWith(name + '=')) {
			return cookie.substring(name.length + 1);
		}
	}
	return null;
}
