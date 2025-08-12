$(function() {
	// 이벤트 상품에 ID 일괄 부여 Start
	function addId() {
		var items = $('.event_tab_cont > li');

		items.each(function (index) {
			$(this).attr('data-id','opt'+index);
		});
	}
	addId();
	// 이벤트 상품에 ID 일괄 부여 End

	// 서브 메뉴 복제 Start
	function subMenuAdd() {
		var subMenu = '';
		var link = '';
		$('.subCateBox .tabs li').each(function(index, value) {
			var dataTarget = $(this).attr('data-target');
			var dataSort = $(this).attr('data-sort');
			var txt = $(this).text();
			if($(this).hasClass('directions')) {
				link = $(this).attr('onclick');
				//console.log('link : ' +link);
				subMenu+='<div class="swiper-slide directions" onclick="'+link+'">'+txt+'</div>';
			} else {
				subMenu+='<div class="swiper-slide" data-target="'+dataTarget+'" data-sort="'+dataSort+'">'+txt+'</div>';
			}
		});
		$('.subMenuSwipe .swiper-wrapper').append(subMenu);
	}
	subMenuAdd();
	// 서브 메뉴 복제 End

	$('.btn_add').text('加入购物车');	//시술 담기
	$('.btn_view').text('查看详情');	//상세 보기

	$('.hipassSwitch').html('等候、咨询、追加付款NO！<br /><em>只做想做的项目更便捷！</em>');	//대기/상담없이 빠르게! 하이패스 시술보기

	//Top Visual
	if ($('.visual .swiper-slide').length > 1) {
		var visual = new Swiper(".visual", {
			spaceBetween: 0,
			autoHeight: true,
			loop: true,
			speed: 400,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			pagination: {
				el: ".visual .swiper-pagination",
				clickable: true,
				type: "fraction",
			},
		});
	}

	var touchRatio = '';
	if($('.mainCateBox .swiper-container .swiper-slide').length > 7) {
		touchRatio = 1;
		$('.menuBox .mainCateBox').addClass('slideViewAuto');
	} else {
		touchRatio = 0;
	}

	var mainCate = new Swiper('.mainCateBox .swiper-container', {
		speed: 400,
		slidesPerView: 'auto',
		touchRatio: touchRatio,
		breakpoints: {
			640: {
				touchRatio: 1
			}
		},
	});

	var subCate = new Swiper('.subCateBox .subMenuSwipe .swiper-container', {
		speed: 400,
		slidesPerView: 'auto',
	});

	//메뉴 펼침/닫기
	$(document).on('click','.subCateBox .all_menu',function() {
		$('.bgShadow').toggleClass('show');
		$('.subCateBox').toggleClass('subOpen');
	});
	$(document).on('click','.bgShadow.show',function() {
		subCateBoxHide();
	});

	function subCateBoxHide() {
		$('.bgShadow').removeClass('show');
		$('.subCateBox').removeClass('subOpen');
	}

	//시간선택/메신저선택 클릭
	$(document).on('click','.reserveAccordion .sectionTitle, .selectWrap .sectionTitle',function() {
		$(this).parent().find('.selectList').toggleClass('on');
		$(this).toggleClass('on');
		return false;
	});

	$(document).on('click','.timeList .radioBox',function() {
		var txt = $.trim($(this).find('label').text());
		$(this).find('input:radio').prop('checked', true);
		$(this).parents('.timeList').parent().find('.sectionTitle em').text(txt).addClass('on');
		$(this).parents('.timeList').removeClass('on');
		$(this).parents('.timeList').parent().find('.sectionTitle').removeClass('on error');
		formValid();
	});

	//메신저 옵션 선택
	$(document).on('click','.selectList .radioBox',function() {
		var txt = $.trim($(this).find('label').text());
		$(this).find('input:radio').prop('checked', true);
		$(this).parents('.selectList').parent().find('.sectionTitle em').text(txt).addClass('on');
		$(this).parents('.selectList').removeClass('on');
		$(this).parents('.selectList').parent().find('.sectionTitle').removeClass('on error');
		formValid();
	});

	//페이지 로딩시 active 삭제
	$('.ui-state-default').removeClass('ui-state-active');

	$.validator.addMethod("regex", function(value, element, regexpr) {
		return regexpr.test(value);
	}, "Please enter a valid phone number");

	var validator = $( "#q_form" ).validate({
		//$("#q_form").validate({
		rules: {
			tblStrField2: "required",
			tblStrField3: "required",
			name: {
				required: true,
				//maxlength: 20
			},
			tblStrField3: "required",
			phoneNumber: {
				minlength: 11,
				maxlength: 13,
				//regex: /^010-?([0-9]{3,4})-?([0-9]{4})$/
			}
		},
		groups: {
			username: "agree smspush"
			//username: "messengerName messengerId"
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "agree" || element.attr("name") == "smspush" ) {
				error.insertAfter("#consultForm .smspush");
			}
			else if (element.attr("name") == "tblStrField2") {
				error.insertAfter("#consultForm .reserveDate-error");
			}
			else if (element.attr("name") == "tblStrField3") {
				error.insertBefore("#consultForm .reserveTime-error");
				$('#consult_wrapper .reserveTimeWrapper.reserveAccordion .sectionTitle').addClass('error');
			}
			else if (element.attr("name") == "messengerName" ) {
				$('#consult_wrapper .selectWrap .sectionTitle').addClass('error');
				error.insertAfter("#consultForm .messengerError");
			}
			/*else if (element.attr("name") == "messengerName" || element.attr("name") == "messengerId" ) {
				$('#consult_wrapper .selectWrap .sectionTitle').addClass('error');
				error.insertAfter("#consultForm .messengerError");
			}*/
			else if (element.attr("name") == "tblStrField") {
				error.insertAfter("#consultForm .tblStrField-error");
			}
			else if (element.attr("name") == "etcDirectOpt") {
				error.insertAfter("#consultForm .etcError");
			}
			else {
				error.insertAfter( element );
			}
		},
		success: function (label, validator) {
			formValid();
		},
		messages: {
			tblStrField2: "请选择期望预约日期",	//희망 예약일을 선택해주세요.
			tblStrField3: "请选择期望预约具体时间", //희망 예약시간을 선택해주세요.
			select: "지점을 선택해주세요",
			name: "请输入姓名", //이름을 입력해주세요.
			country: "请填写您的国籍",	//국적을 입력해주세요.
			etcDirectOpt: "바로예약 상담 항목을 선택해주세요.",
			phoneNumber: {
				required: "请输入手机号码", //연락처를 입력해주세요
				minlength: "请输入手机号码",
				maxlength: "请输入手机号码"
			},
			email: {
				required: "请输入邮箱地址",
				email: "请输入正确的邮箱地址"
			},
			messengerName: "请输入可以联系到的方式，电话号码或者ID",	//연락 가능한 메신저와 계정을 검색할 수 있는 ID 혹은 전화번호를 입력해주세요
			messengerId: "请输入可以联系到的方式，电话号码或者ID",	//연락 가능한 메신저와 계정을 검색할 수 있는 ID 혹은 전화번호를 입력해주세요
			tblStrField: "진료구분을 선택해주세요.",
			agree: "请同意使用服务条款", //서비스 이용을 위해 약관에 동의해주세요.
			smspush: "请同意使用服务条款"
		},
		submitHandler: function(form) {
			var ph = $('#email').val()+','+$('#phoneNumber').val();
			$('input[name="phone"]').val(ph);

			var mi = $('input:radio[name="messengerName"]:checked').val()+'(전화번호) : '+$('input[name="messengerId"]').val();
			$('input[name="line_id"]').val(mi);

			$('.submit_btn').attr('disabled', true);
			form.submit();
		}
	});

	//연락처 표시(이메일 또는 핸드폰 번호)
	/*$(document).on('change', '#q_form input[name="contactInfo"]:radio', function() {
		if($(this).val() == 'E') {
			$('.emailBox').removeClass('hide');
			$('.phoneBox').addClass('hide');
			$('#phoneNumber').val('');		//핸드폰 번호 초기화
			$('input[name="contactType"]').val('email');
		} else {
			$('.emailBox').addClass('hide');
			$('.phoneBox').removeClass('hide');
			$('#email').val('');			//이메일 주소 초기화
			$('input[name="contactType"]').val('phone');
		}
		$('input[name="phone"]').val('');	//실제 연락처로 전송 될 값 초기화
	});

	$('#email, #phoneNumber').keyup(function() {
		$('input[name="phone"]').val($(this).val());
	});*/

	$(document).on('change', '#q_form .mobileVer .checkbox input[type="checkbox"], #q_form .selectedOpt .etc li input[type="checkbox"]', function() {
		formValid();
	});

	$(document).on('keyup','#q_form #name, #q_form #phoneNumber',function() {
		formValid();
	});

	function formValid() {
		var chkItem = $('#q_form .dbItem').length;

		var vaildItem = 0;

		if ($('#q_form #reserveDate').val()) {
			vaildItem += 1;
		}
		if ($('#q_form input:radio[name="tblStrField3"]:checked').val()) {
			vaildItem += 1;
			if($('#consult_wrapper .reserveAccordion .sectionTitle span em').hasClass('on')) {
				$('#consult_wrapper .reserveTimeWrapper.reserveAccordion .sectionTitle').removeClass('error');
			}
		}
		if ($('#q_form input:radio[name="tblStrField"]:checked').val()) {
			vaildItem += 1;
		}

		//명동점 전용 바로예약 옵션 체크
		if($('#consult_wrapper .selectedOpt ul.etc').hasClass('on')) {
			if ($('#q_form input:checkbox[name="etcDirectOpt"]:checked').val()) {
				vaildItem += 1;
				$('#etcDirectOpt-error').removeClass('error').hide();
			}
			else {
				$('#etcDirectOpt-error').text('바로예약 상담 항목을 선택해주세요.').addClass('error').show();
			}
		}

		if ($('#q_form #country').val() && !$('#q_form #country').hasClass('error')) {
			vaildItem += 1;
		}

		if ($('#q_form #name').val() && !$('#q_form #name').hasClass('error')) {
			vaildItem += 1;
		}
		if ($('#q_form #phone').val() && !$('#q_form #phoneNumber').hasClass('error')) {
			vaildItem += 1;
		}
		if ($('#q_form input:radio[name="messengerName"]:checked').val()) {
			vaildItem += 1;
			if($('#consult_wrapper .selectWrap .sectionTitle span em').hasClass('on')) {
				$('#consult_wrapper .selectWrap .sectionTitle').removeClass('error');
				$('#messengerName-error').hide();
			}
		}
		if ($('#q_form #messengerId').val() && !$('#q_form #messengerId').hasClass('error')) {
			vaildItem += 1;
		}
		if ($('#q_form input:checkbox[name="agree"]:checked').val()) {
			vaildItem += 1;
		}
		else {
			$('#username-error').text('请同意使用服务条款').show(); //서비스 이용을 위해 약관에 동의해주세요.
		}
		if ($('#q_form input:checkbox[name="smspush"]:checked').val()) {
			vaildItem += 1;
		}
		else {
			$('#username-error').text('请同意使用服务条款').show();
		}

		if ($('#q_form #email').val() && !$('#q_form #email').hasClass('error')) {
			vaildItem += 1;
		}
		if ($('#q_form #phoneNumber').val() && !$('#q_form #phoneNumber').hasClass('error')) {
			vaildItem += 1;
		}

		if(chkItem == vaildItem) {
			$('.submitBtnBox .submit_btn').removeClass('disabled');
		} else {
			$('.submitBtnBox .submit_btn').addClass('disabled');
		}
	}

	//이벤트 시술 옵션 선택
	$(document).on('click','.event_tab_cont li:not(.none_hipass) .btn_add',function(e) {
		e.preventDefault();
		var selectParent, selCnt, selCntSum=0, optId='', optName='', optPrice = 0;
		selectParent = $(this).closest('.ppeum_box_inner_box').attr('id');

		selCnt = Number($('.menuBox [data-target="' + selectParent + '"]').attr('data-selected-cnt'));

		optId = $(this).closest('li').attr('data-id');				//상품ID
		optName = $(this).closest('li').find('div strong').text();	//상품명
		optPrice = $(this).closest('li').find('div p b').text();		//상품가 또는 이벤트가

		if($(this).closest('li').hasClass('selected')) {
			$(this).closest('li').removeClass('selected');
			selCntSum = selCnt-1;

			//옵션 장바구니에서 빼기
			optSelect(optId, optName, optPrice, 'minus');

			if($(window).width() < 1480) tooltip('minus');
		} else {
			$(this).closest('li').addClass('selected');
			selCntSum = selCnt+1;

			//옵션 장바구니에서 더하기
			optSelect(optId, optName, optPrice, 'plus');

			if($(window).width() < 1480) tooltip('plus');
		}
		$('.menuBox [data-target="' + selectParent + '"]').attr('data-selected-cnt', selCntSum);
	});

	function tooltip(mode) {
		if(mode == 'plus') {
			$('.tooltip').text('项目已被加入购物车');	//시술이 담겼습니다.
			$('.tooltip').fadeIn(250);
			setTimeout(function(){ $('.tooltip').fadeOut(400); }, 2000);
		} else {
			$('.tooltip').text('取消了选择');	//선택 취소되었습니다.
			$('.tooltip').fadeIn(250);
			setTimeout(function(){ $('.tooltip').fadeOut(400); }, 2000);
		}
	}

	function optSelect(target, txt, price, status) {
		var optSel = '';
		var optPrice = price.replace(/,/g, '');		//상품가 콤마 제거

		if(!price) price = '내원 후 상담';		//내원 후 상담처럼 금액이 없는 경우

		if(status == 'plus') {	//장바구니 추가

			optSel+= '<div class="opt-item" data-target="'+target+'">'
				+'<p><span>'+txt+'</span>'
				//+'<em>'+price+'</em></p>'
				+ (
					(price == '내원 후 상담') ?
						'	<em class="txt">'+price+'</em></p>'
						:	//일반 또는 이벤트 상품
						'	<em>'+price+'</em></p>'
				)
				+'<button type="button" class="del">삭제</button></div>';

			$('.cartBox .cartBoxInner .reserve_item').remove();
			$('.cartBox .cartBoxInner').append(optSel);

		} else if(status == 'minus') {	//장바구니 삭제

			$('.cartBoxInner').find('.opt-item[data-target="'+target+'"]').remove();

		} else {						//장바구니 상품 삭제 및 상품 선택 옵션 해제

			//장바구니 삭제
			$('.cartBoxInner').find('.opt-item[data-target="'+target+'"]').remove();

			//상품 선택 옵션 해제
			var selectParent, selCnt, selCntSum=0;
			selectParent = $('li[data-id="'+target+'"]').closest('.ppeum_box_inner_box').attr('id');
			selCnt = Number($('.menuBox [data-target="' + selectParent + '"]').attr('data-selected-cnt'));
			$('.event_tab_cont').find('li[data-id="'+target+'"]').removeClass('selected');
			selCntSum = selCnt-1;
			$('.menuBox [data-target="' + selectParent + '"]').attr('data-selected-cnt', selCntSum);
		}

		var cartTxt = '';
		var checkCnt = $('.event_tab_cont li.selected').length;
		if(checkCnt == 0) {
			cartTxt = '请先选择预约项目';	//예약하실 시술을 먼저 선택해주세요.
			$('.totalSmBox .priceBox').removeClass('on');
		} else {
			cartTxt = '申请预约优惠';	//이벤트 신청하기
			$('.totalSmBox .priceBox').addClass('on');
		}
		$('.cart_view').text(cartTxt);
		$('.btn-cart > span').text(checkCnt);

		if(price != '내원 후 상담') cartTotalPrice(optPrice, status);

		//선택 상품이 없는 경우 장바구니 창 닫기
		if(checkCnt == 0) {
			$('.cartShadow').removeClass('active');
		}
	}

	//장바구니 삭제버튼
	$(document).on('click','.cartBox .opt-item .del',function(e) {
		e.preventDefault();
		var optId='', optName='', optPrice = 0, optPrice2 = 0;

		optId = $(this).closest('.opt-item').attr('data-target');	//상품ID
		optName = $(this).siblings('p').find('span').text();		//상품명
		optPrice = $(this).siblings('p').find('em').text();			//상품가 또는 이벤트가

		optSelect(optId, optName, optPrice, 'delete');
	});

	//장바구니 시술금액 합계
	function cartTotalPrice(price, status) {
		var result_price, totalPrice;
		result_price = Number($('.cartTotalBox .totalPrice').attr('data-price'));

		if(status == 'plus') {
			result_price += Number(price);
		} else {
			result_price -= Number(price);
		}

		//총 시술금액
		totalPrice = $.number(result_price);
		$('.totalPrice').text(totalPrice).attr('data-price', result_price);
	}

	//장바구니 목록보기
	$(document).on('click', '.totalSmInner', function (e) {
		var chkCnt = $('.event_tab_cont li.selected').length;
		if(chkCnt == 0) {

			e.preventDefault();
			alert('请先选择预约项目');	//예약하실 시술을 먼저 선택해주세요!
			var tabOffset = $(".event_cont_box").offset();
			if( $('.menuBox').hasClass('sticky')) {
				$('html, body').animate({scrollTop : 0}, 0);
			} else {
				$('.menuBox').addClass('sticky');
				$('html, body').animate({scrollTop : 0}, 0);
			}
			return false;

		} else {

			$('.cartShadow').addClass('active');

		}
	});

	//장바구니 창 닫기
	$(document).on('click', '.cartShadow', function (e) {
		e.preventDefault();
		$('.cartShadow').removeClass('active');
	});

	//이벤트, 전체 상품 구분
	$(document).on('click','.menuType li',function(e) {
		e.preventDefault();
		$(this).siblings().removeClass('active');
		$(this).addClass('active');

		if($(this).attr('data-option') == 'event') {
			$('.event_tab_cont li.normal').addClass('hide');
		}
		else {
			$('.event_tab_cont li.normal').removeClass('hide');
		}
	});

	//이벤트 신청 창닫기
	$('.btnConsultClose').click(function(e) {
		e.preventDefault();
		var validator = $('#q_form').validate();
		validator.resetForm();
		$('#q_form')[0].reset();
		$('.submitBtnBox .submit_btn').addClass('disabled');
		$('.timeList input[type="radio"]').removeAttr('checked');
		$('.reserveTimeWrapper .sectionTitle em').text('选择时间').removeClass('on');
		$('#consult_wrapper .reserveAccordion .reserveTimeGroup').hide();
		$('#consult_wrapper').removeClass('on');
		$('.formGroup.selectOpt').remove();
	});

	// $('.menuBox').stickySidebar({
	// 	headerSelector: '.top_visual',
	// 	contentSelector: '.content',
	// 	sidebarTopMargin: 0,
	// });

	//var scrollOffsetY = $('.top_visual').height() + 54;
	var scrollOffsetY = $('.top_visual').height() + $('.menuBox').height();
	var scrollTop;
	var lastScrollTop = 0;
	$(window).on('scroll', function() {
		scrollFn();
	});
	scrollFn();
	function scrollFn() {
		scrollTop = $(document).scrollTop();

		if (scrollTop > scrollOffsetY) {
			//$('.menuBox').addClass('sticky');
			if(!$('.menuBox').hasClass('sticky')) scroll_top();		//스크롤 top 0
		} else if (scrollTop <= 0) {
			$('.menuBox').removeClass('sticky');

			if($('.bgShadow').hasClass('show')) subCateBoxHide();
		} else {
			//$('.menuBox').addClass('sticky');
		}
	}

	$(document).on('click', '.topScroll', function (e) {
		$('.menuBox').removeClass('sticky');
		$('html, body').animate({scrollTop : 0}, 0);
		$(this).hide();
	});

	$(document).on('click', '.mainCateBox .swiper-slide', function (e) {
		var idx = $(this).index();
		$(this).siblings().removeClass('active');

		//서브 카테고리 활성화 해제
		var subItem = '.tabs li, .subCateBox .subMenuSwipe .swiper-slide';
		$(subItem).removeClass('active');

		$(this).addClass('active');
		mainCate.slideTo(idx, 0);
		/*if(idx == 0) {
			$('.mainCateBox .swiper-wrapper').css('transform','translate3d(0px, 0px, 0px)');
		}*/

		var target = $(this).attr('data-target');

		$('.ppeum_box_inner_box').removeClass('active');
		$('#'+target).addClass('active');

		if($('.subCateBox').hasClass('subOpen')) subCateBoxHide();	//전체 옵션창 닫기

		scroll_top();		//스크롤 top 0

		if($('.menuBox').hasClass('search')) {
			//검색 설정 초기화
			searchReset();
		}
	});

	//서브 카테고리 클릭
	$(document).on('click', '.tabs li, .subCateBox .subMenuSwipe .swiper-slide', function (e) {
		e.preventDefault();
		if(!$(this).hasClass('directions') && !$(this).hasClass('kakao') && !$(this).hasClass('real_model')) {
			$('.mainCateBox .swiper-slide').removeClass('active');	//메인 카테고리 활성화 해제

			var target = $(this).attr('data-target');
			var subItem = '.tabs li, .subCateBox .subMenuSwipe .swiper-slide';
			$(subItem).siblings().removeClass('active');
			$('.tabs li[data-target="' + target + '"]').addClass('active');
			$('.subCateBox .subMenuSwipe .swiper-slide[data-target="' + target + '"]').addClass('active');

			$('.ppeum_box_inner_box').removeClass('active');
			$('#'+target).addClass('active');

			/*if($(window).width()<600) {
				if($(this).hasClass('lm')) {
					$('.subCateBox .swiper-wrapper').css('transform','translate3d(0px, 0px, 0px)');
				} else {
					var wW = $(window).width() - $('.subCateBox .swiper-slide').width();
					$('.subCateBox .swiper-wrapper').css('transform','translate3d('+wW+'px, 0px, 0px)');
				}
			}*/

			scroll_top();		//스크롤 top 0

			if($('.menuBox').hasClass('search')) {
				//검색 설정 초기화
				searchReset();
			}
			var subTarget = $('.subCateBox .subMenuSwipe .swiper-slide[data-target="' + target + '"]').index();
			subCate.slideTo(subTarget, 0);

			if($('.subCateBox').hasClass('subOpen')) subCateBoxHide();	//전체 옵션창 닫기
		}
	});

	function scroll_top() {	//스크롤 Top 0
		$('html, body').animate({scrollTop : 1}, 0);
		$('.menuBox').addClass('sticky');
	}

	//하이패스 갯수 파악
	$('.ppeum_box_inner_box').each(function(index, value) {
		var hipass_cnt = 0;
		var tabId;
		$(this).find('li').each(function(index, value) {
			if($(this).find('span').hasClass('hipass')) {
				$(this).addClass('hipass');
				hipass_cnt += 1;
			}
		});

		$(this).attr('data-hipass', hipass_cnt);
		tabId = $(this).attr('id');
		if(hipass_cnt > 0) {
			$('.tabs li[data-target="' + tabId + '"]').addClass('hipass');
		} else {
			$(this).addClass('noneHipass');
		}
	});

	$(document).on('click','.hipassSwitch',function(e) {
		if($(this).hasClass('on')){
			$(this).removeClass('on')
			$(this).parent('.ppeum_box_inner_box').removeClass('onlyHipass');
		} else {
			$(this).addClass('on');
			$(this).parent('.ppeum_box_inner_box').addClass('onlyHipass');
		}

		scroll_top();		//스크롤 top 0

		//검색 설정 초기화
		//searchReset();
	});

	//검색 한 경우
	$(document).on('keyup','.searchForm input, .search_btn, .ui-menu .ui-menu-item',function(key) {
		if(key.keyCode==13) {
			searchKey();
		}
	});
	$(document).on('click','.search_btn, .ui-menu .ui-menu-item',function(e) {
		e.preventDefault();
		searchKey();
	});

	$.widget( 'app.autocomplete', $.ui.autocomplete, {

		options: {
			highlightClass: 'highlight'
		},

		_renderItem: function( ul, item ) {

			var re = new RegExp( '(' + this.term + ')', 'gi' ),
				cls = this.options.highlightClass,
				template = '<span class="' + cls + '">$1</span>',
				label = item.label.replace( re, template ),
				$li = $( '<li/>' ).appendTo( ul );

			// Create and return the custom menu item content.
			$( '<a/>' ).attr( 'href', 'javascript:void(0)' )
				.html( label )
				.appendTo( $li );

			return $li;

		}

	});

	var keywords = [];
	$('.ppeum_box_inner_box:not(.hotTen) li').each(function(index){		//HOT10 상품 제외
		keywords.push($(this).find('strong').text());
	});

	$('#keywords').autocomplete({
		highlightClass: 'boldTxt',
		delay: 200,
		source: keywords,
		select: function(event, ui) {
			//console.log(ui.item);
		},
		focus: function(event, ui) {
			return false;
		}
	});
	function searchKey() {
		var findKey = $('.searchForm input').val().toLowerCase();
		var findKeyCnt = 0;
		if(!findKey) {

			alert('请输入查找内容'); //검색어를 입력해주세요.
			$('.searchForm input').focus();
			return false;

		} else {

			//HOT10 카테고리 제외하고 검색
			$('.event_tab_cont li').removeClass('find_key');
			$('.ppeum_box_inner_box:not(.hotTen) li').each(function(index, value) {
				if ($(this).find('strong').text().toLowerCase().indexOf(findKey) != -1) {
					$(this).addClass('find_key');
					findKeyCnt += 1;
				}
			});

			if(findKeyCnt == 0) {

				alert('无一致的项目，请重新查找。'); //일치하는 시술이 없습니다. 다시 검색해 주시기 바랍니다.
				$('.searchForm input').focus();
				$('.tabs, .ppeum_box_inner').removeClass('search');
				$('.event_tab_cont li').removeClass('find_key');

			} else {

				if($('.sMenuShadow').hasClass('show')) {
					schFullBoxC();		//전체 검색창 닫기
				}

				//하이패스 설정 해제
				$('.hipassSwitch').removeClass('on');
				$('.tabs, .event_cont_box').removeClass('onlyHipass');
				//하이패스 설정 해제 //

				scroll_top();
				//$('.topScroll').show();

				$('.menuBox, .ppeum_box_inner').addClass('search');

				setTimeout(function(){
					$('.searchForm input').blur();
				},50);

			}

		}
	}

	function searchReset() {	//검색 초기화
		$('.menuBox, .ppeum_box_inner').removeClass('search');
		$('.event_tab_cont li').removeClass('find_key');
		$('.searchForm input').val('');
	}

	if($('.bannerThumb .swiper-slide').length > 1) {
		var bannerThumb = new Swiper('.bannerThumb .swiper-container', {
			loop: false,
			spaceBetween: 5,
			speed: 400,
			slidesPerView: 'auto',
			freeMode: true,
			watchSlidesProgress: true,
			navigation: {
				nextEl: '.bannerThumb .swiper-button-next',
				prevEl: '.bannerThumb .swiper-button-prev',
			},
		});
		var bannerImg = new Swiper('.bannerImg', {
			loop: true,
			spaceBetween: 0,
			speed: 400,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			thumbs: {
				swiper: bannerThumb,
			},
		});
	}

	if($('.bannerThumb .swiper-slide').length > 3) {
		$('.bannerThumb').addClass('ver2');
	}

	// 숨은 추가 메뉴 보이기
	/*$('.btnSmMenu').on('click',function(e) {
		e.preventDefault();
		$('.sMenuShadow').addClass('show');
	})*/;

	// 숨은 추가 메뉴 닫기
	$('.sMenu .close').on('click',function(e) {
		e.preventDefault();
		$('.sMenuShadow').removeClass('show');
	});

	//검색창 전체 창으로 띄우기
	$('.sMenu .search').on('click',function(e) {
		e.preventDefault();
		$('.search_box').addClass('fullBox');
	});

	//검색창 전체 창 닫기
	$(document).on('click','.fullBox .close',function(e) {
		e.preventDefault();
		schFullBoxC();
	});
	function schFullBoxC() {
		$('.search_box').removeClass('fullBox');
		$('.sMenuShadow').removeClass('show');
	}

	//상품 상세 설명 클릭
	// $(document).on('click','.event_tab_cont li > div > span.ver2',function(e) {
	// 	e.preventDefault();
	// 	$(this).toggleClass('on');
	// });

	//상품 상세 보기
	$('.btn_view').on('click',function(e) {
		e.preventDefault();
		var detailCode = $(this).attr('data-detail-code');
		var param = "code="+detailCode;
		sendPostNoLoading("/v1/front/productDetail", param, detailResultFunc);
	});

	//상품 상세 닫기
	$(document).on('click','.btnViewClose, .itemViewShadow, .itemViewClose',function(e) {
		e.preventDefault();

		var vidChk = $('#detail_area').children('.videoArea').length;
		if(vidChk != 0) {
			var vidId = $('.videoBox').children().attr('id');
			if(vidId == 'detailVideo') {
				var vid = document.getElementById("detailVideo");
				vid.pause();
			} else {
				$('iframe')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
			}
		}

		$('.itemViewBox').removeClass('showItem');
	});

	function detailResultFunc(r){
		console.log(r);
		$('#detail_area').empty();
		$('#detail_area').html(r.data.product_detail);
		$('.itemViewBox').addClass('showItem');
		$('.itemViewInner').scrollTop(0);
		$('.itemViewBox .noti > em').text('注意事項');	//주의사항

		$('.itemViewBox .infoArea dt:contains("마취시간")').text('麻醉时间');
		$('.itemViewBox .infoArea dt:contains("시술시간")').text('进行时间');
		$('.itemViewBox .infoArea dt:contains("회복기간")').text('恢复时间');
		$('.itemViewBox .infoArea dt:contains("유지기간")').text('维持时间');
		$('.itemViewBox .infoArea dt:contains("재시술주기")').text('施术周期');
	}

	// 약관 보기
	$('.learnMore').on('click',function(e) {
		e.preventDefault();
		$('.privacyShadow').addClass('on');
	});

	// 약관 닫기
	$('.privacyClose').on('click',function(e) {
		e.preventDefault();
		$('.privacyShadow').removeClass('on');
	});

	$('.scrollTop').on('click',function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, 400);
		return false;
	});

	//위챗 창 닫기
	$('.counsultBox .close').on('click',function(e) {
		e.preventDefault();
		$('.counsultBox').removeClass('on');
	});
});
