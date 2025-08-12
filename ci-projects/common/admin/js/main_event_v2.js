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

	//Top Visual
	if ($('.visual .swiper-slide').length > 1) {
		var visual = new Swiper(".visual", {
			spaceBetween: 0,
			loop: true,
			speed: 400,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			pagination: {
				el: ".visual .swiper-pagination",
				clickable: true,
			},
		});
	}

	//시간선택 클릭
	$('.reserveAccordion .sectionTitle').click(function() {
		$(this).parent().find('.selectList').toggleClass('on');
		$(this).toggleClass('on');
		return false;
	});

	$(document).on('click','.timeList .radioBox',function() {
		var txt = $.trim($(this).find('label').text());
		$(this).find('input:radio').prop('checked', true);
		$(this).parents('.timeList').parent().find('.sectionTitle em').text(txt).addClass('on');
		$(this).parents('.timeList').removeClass('on');
		$(this).parents('.timeList').parent().find('.sectionTitle').removeClass('on');
	});

	//페이지 로딩시 active 삭제
	$('.ui-state-default').removeClass('ui-state-active');

	$("#q_form").validate({
		rules: {
			tblStrField2: "required",
			tblStrField3: "required",
			name: {
				required: true,
				maxlength: 15
			},
			tblStrField3: "required",
			phone: {
				required: true,
				minlength: 11,
				maxlength: 13
			}
		},
		groups: {
			username: "agree smspush"
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "agree" || element.attr("name") == "smspush" ) {
				error.insertAfter("#consultForm .smspush");
			}
			else if (element.attr("name") == "tblStrField2") {
				error.insertAfter("#consultForm .reserveDate-error");
			}
			else if (element.attr("name") == "tblStrField3") {
				error.insertAfter("#consultForm .reserveTime-error");
			}
			else if (element.attr("name") == "tblStrField") {
				error.insertAfter("#consultForm .tblStrField-error");
			}
			else {
				error.insertAfter( element );
			}
		},
		messages: {
			tblStrField2: "희망 예약일을 선택해주세요.",
			tblStrField3: "희망 예약시간을 선택해주세요.",
			select: "지점을 선택해주세요",
			name: "이름을 입력해주세요.",
			phone: {
				required: "휴대폰 번호를 입력해주세요",
				minlength: "정확한 번호를 입력해주세요.",
				maxlength: "정확한 번호를 입력해주세요."
			},
			tblStrField: "진료구분을 선택해주세요.",
			agree: "개인정보이용과 SMS 수신 및 카톡 상담에 동의해주세요.",
			smspush: "개인정보이용과 SMS 수신 및 카톡 상담에 동의해주세요."
		}
	});

	//이벤트 시술 옵션 선택
	$(document).on('click','.event_tab_cont li:not(.none_hipass)',function(e) {
		e.preventDefault();
		var selectParent, selCnt, selCntSum=0, optId='', optName='', optPrice = 0;
		selectParent = $(this).closest('.ppeum_box_inner_box').attr('id');
		selCnt = Number($('.tabs li[data-target="' + selectParent + '"]').attr('data-selected-cnt'));

		optId = $(this).attr('data-id');				//상품ID
		optName = $(this).find('div strong').text();	//상품명
		optPrice = $(this).find('div p b').text();		//상품가 또는 이벤트가

		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
			selCntSum = selCnt-1;

			//옵션 장바구니에서 빼기
			optSelect(optId, optName, optPrice, 'minus');
		} else {
			$(this).addClass('selected');
			selCntSum = selCnt+1;

			//옵션 장바구니에서 더하기
			optSelect(optId, optName, optPrice, 'plus');
		}
		$('.tabs li[data-target="' + selectParent + '"]').attr('data-selected-cnt', selCntSum);
	});

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
			$('.cartBox .cartBoxInner').append(optSel);

		} else if(status == 'minus') {	//장바구니 삭제

			$('.cartBoxInner').find('.opt-item[data-target="'+target+'"]').remove();

		} else {						//장바구니 상품 삭제 및 상품 선택 옵션 해제

			//장바구니 삭제
			$('.cartBoxInner').find('.opt-item[data-target="'+target+'"]').remove();

			//상품 선택 옵션 해제
			var selectParent, selCnt, selCntSum=0;
			selectParent = $('li[data-id="'+target+'"]').closest('.ppeum_box_inner_box').attr('id');
			selCnt = Number($('.tabs li[data-target="' + selectParent + '"]').attr('data-selected-cnt'));
			$('.event_tab_cont').find('li[data-id="'+target+'"]').removeClass('selected');
			selCntSum = selCnt-1;
			$('.tabs li[data-target="' + selectParent + '"]').attr('data-selected-cnt', selCntSum);
		}

		var cartTxt = '';
		var checkCnt = $('.event_tab_cont li.selected').length;
		(checkCnt == 0) ? cartTxt = '시술 받으실 상품을 선택해주세요' : cartTxt = '장바구니 상품 보기';
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
	$(document).on('click', '.totalSmBox', function (e) {
		var chkCnt = $('.event_tab_cont li.selected').length;
		if(chkCnt == 0) {

			e.preventDefault();
			alert('예약하실 시술을 먼저 선택해주세요!');
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
	$('.consult_close').click(function(e) {
		e.preventDefault();
		var validator = $('#q_form').validate();
		validator.resetForm();
		$('#q_form')[0].reset();
		$('#consult_wrapper').removeClass('on');
		$('.formGroup.selectOpt').remove();
	});

	/*$('.menuBox').stickySidebar({
		headerSelector: '.top_visual',
		contentSelector: '.content',
		sidebarTopMargin: 0,
	});*/

	var scrollOffsetY = $('.visual').height();
	var scrollTop;
	$(window).on('scroll', function() {
		scrollFn();
	});
	scrollFn();
	function scrollFn() {
		scrollTop = $(document).scrollTop();
		if (scrollTop > scrollOffsetY) {
			$('.menuBox').addClass('sticky');
			$('.topScroll').hide();
		} else if (scrollTop <= 0 && $('.menuBox').hasClass('sticky')) {
			$('.topScroll').show();
		} else {
			$('.topScroll').hide();
		}
	}

	$(document).on('click', '.topScroll', function (e) {
		$('.menuBox').removeClass('sticky');
		$('html, body').animate({scrollTop : 0}, 0);
		$(this).hide();
	});

	$(document).on('click', '.tabs li', function (e) {
		e.preventDefault();
		if(!$(this).hasClass('directions') && !$(this).hasClass('kakao') && !$(this).hasClass('real_model')) {
			$(this).siblings().removeClass('active');
			$(this).addClass('active');
			var target = $(this).attr('data-target');

			$('.ppeum_box_inner_box').removeClass('active');
			$('#'+target).addClass('active');
			var offset = $('.event_cont_box').offset();
			var minusH;

			if( $('.menuBox').hasClass('sticky')) {
				$('html, body').animate({scrollTop : 0}, 0);
			} else {
				minusH = $('.menuBox').outerHeight() + $('.visual').height();
				$('.event_cont_box').animate({scrollTop : offset.top+minusH}, 0);
			}

			//검색 설정 초기화
			searchReset();
		}
	});

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

	$(document).on('click','.btn-switch input[type=checkbox]',function(e) {
		if($(this).is(':checked')){
			$('.tabs, .event_cont_box').addClass('onlyHipass');
		} else {
		 	$('.tabs, .event_cont_box').removeClass('onlyHipass');
		}

		var minusH;
		var offset = $('.event_cont_box').offset();
		if( $('.menuBox').hasClass('sticky')) {
			$('html, body').animate({scrollTop : 0}, 0);
		} else {
			minusH = $('.menuBox').outerHeight() + $('.visual').height();
			$('.event_cont_box').animate({scrollTop : offset.top+minusH}, 0);
		}

		//검색 설정 초기화
		searchReset();
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
	$('.ppeum_box_inner_box:not(#opt1_cont) li').each(function(index){		//HOT10 상품 제외
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

			alert('검색어를 입력해주세요.');
			$('.searchForm input').focus();
			return false;

		} else {

			//HOT10 카테고리 제외하고 검색
			$('.event_tab_cont li').removeClass('find_key');
			$('.ppeum_box_inner_box:not(#opt1_cont) li').each(function(index, value) {
				if ($(this).find('strong').text().toLowerCase().indexOf(findKey) != -1) {
					$(this).addClass('find_key');
					findKeyCnt += 1;
				}
			});

			if(findKeyCnt == 0) {

				alert('일치하는 시술이 없습니다. \n다시 검색해 주시기 바랍니다.');
				$('.searchForm input').focus();
				$('.tabs, .ppeum_box_inner').removeClass('search');
				$('.event_tab_cont li').removeClass('find_key');

			} else {

				//하이패스 설정 해제
				if($('.menuType').find('label').hasClass('btn-switch')) {
					$('.btn-switch input[type="checkbox"]').prop('checked' , false);
					$('.tabs, .event_cont_box').removeClass('onlyHipass');
				}
				//하이패스 설정 해제 //

				$('.menuBox').addClass('sticky');
				$('html, body').animate({scrollTop : 0}, 0);
				$('.topScroll').show();

				$('.tabs, .ppeum_box_inner').addClass('search');

				setTimeout(function(){
					$('.searchForm input').blur();
				},50);

			}

		}
	}

	function searchReset() {	//검색 초기화
		$('.tabs, .ppeum_box_inner').removeClass('search');
		$('.event_tab_cont li').removeClass('find_key');
		$('.searchForm input').val('');
	}

	

	if($('.bannerThumb .swiper-slide').length > 1) {
		var bannerThumb = new Swiper('.bannerThumb .swiper-container', {
			loop: false,
			spaceBetween: 0,
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
});