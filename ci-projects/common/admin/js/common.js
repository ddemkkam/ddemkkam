var isDebug = false;

function deleteImgView(that){
	//console.log($(that).parent().parent().html());
	$(that).parent().remove();
}

function sendAjaxHtml(action, param = null, method, id, async = true) {
	$("#"+id).html("");

	$.ajax({
		url: action,
		data: param,
		method: method,
		dataType: 'html',
		async: async,
		success:function(data){
			console.log(data);
			$("#"+id).html(data);
			if(id != 'data_list') {
				$.LoadingOverlay("hide");
			}
		}
		,fail:function(xhr, status, errorThrown) {
			console.log("오류가 발생하였습니다.<br>");
			console.log("오류명: " + errorThrown + "<br>");
			console.log("상태: " + status);
		}
	});
}

function sendAjaxData(action, param = null, method, async = true) {
	$.ajax({
		url: action,
		data: param,
		method: method,
		dataType: 'html',
		async: async,
		success:function(data){
			//console.log(data);
			return data;

		}
		,fail:function(xhr, status, errorThrown) {
			console.log("오류가 발생하였습니다.<br>");
			console.log("오류명: " + errorThrown + "<br>");
			console.log("상태: " + status);
		}
	});
}

function sendPost(action, formData, func) {
	$.LoadingOverlay("show", { size: 40, maxSize: 40 });
	$.post(action, formData, function (r) {
		if (isDebug) {
			console.log("----- [debug] -----\n");
			console.log(r);
			console.log("\n\n");
		}
		func(r);

		$.LoadingOverlay("hide");
	}, "json");
}

function sendPostNoLoading(action, formData, func) {
	$.post(action, formData, function (r) {
		if (isDebug) {
			console.log("----- [debug] -----\n");
			console.log(r);
			console.log("\n\n");
		}
		func(r);
	}, "json");
}

function sendGet(action, formData, func) {
	$.LoadingOverlay("show", { size: 40, maxSize: 40 });
	$.get(action, formData, function (r) {
		func(r);
		$.LoadingOverlay("hide");
	}, "json");
}

// action : 처리 url, frm : 폼id, func : 결과 실행할 함수
function sendFile(action, frm, func){
	$.LoadingOverlay("show", { size: 40, maxSize: 40 });

	var frmObj = document.getElementById(frm);
	frmObj.method = 'POST';
	frmObj.enctype = 'multipart/form-data';

	var fileData = new FormData(frmObj);

	// ajax
	$.ajax({
		url:action,
		type:'POST',
		data:fileData,
		dataType:'json',
		async:true,
		cache:false,
		contentType:false,
		processData:false
	}).done(function(response){
		func(response);
		$.LoadingOverlay("hide");
	});
}

function getCookie(c_name) {
	var i, x, y, ARRcookies = document.cookie.split(";");
	for (i = 0; i < ARRcookies.length; i++) {
		x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
		y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
		x = x.replace(/^\s+|\s+$/g, "");
		if (x == c_name) {
			return unescape(y);
		}
	}
}

function setCookie(c_name, value, exdays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}

// =============================================================
// 페이징 처리 스크립트
function _pageCall(frm, page, dataFunc) {
	$("#" + frm).find("[name=page]").val(page);
	eval(dataFunc + "()");
}

function _pageSelectCall(frm, dataFunc) {
	var listNum = $('#list_num option:selected').val();
	console.log(" -- list_num : "+listNum);
	$("#list_num").val(listNum).prop("selected", true);
	eval(dataFunc + "("+listNum+")");
}

function _pageList(total, frm, dataFunc, pl) {
	// 폼안의 현재 페이지번호 가져온다
	var curPage = $("#" + frm).find("[name=page]").val();
	var pageNum = $("#" + frm).find("[name=page_num]").val();
	var listNum = $("#" + frm).find("[name=list_num]").val();

	if (pageNum == undefined) {
		console.log("pageNum undefined");
		pageNum = 10;
	}

	if (listNum == undefined) {
		listNum = 10;
	}

	var pageListId = 'pagelist';
	if (pl != undefined) {
		pageListId = pl;
	}

	var totalPage = 1;
	if (total > 0) {
		totalPage = Math.ceil(total / listNum);
	}

	var totalBlock = Math.ceil(totalPage / pageNum);
	var block = Math.ceil(curPage / pageNum);
	var firstPage = (pageNum) * (block - 1);
	var lastPage = (pageNum) * block;

	if (block == totalBlock)
		lastPage = totalPage;

	//console.log("total : " + total + ", frm : " + frm + ", pageNum : " + pageNum + ", listNum : " + listNum + ", pageListId : " + pageListId);
	//console.log("curPage : " + curPage + ", totalBlock : " + totalBlock + ", block : " + block + ", firstPage : " + firstPage + ", lastPage : " + lastPage);

	/*
        <ul class="pagination justify-content-end">
            <li class="page-item">
                <a class="page-link" href="#">Prev</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    */

	var pageList = "<ul class='pagination'>";
	if (block > 1 && totalBlock > 1) {
		pageList += "<li><a class='paginate_button previous' href=\"javascript:_pageCall('" + frm + "', " + firstPage + ", '" + dataFunc + "')\" title='이전'><i class='fa fa-angle-double-left'></i></a></li> ";
	}

	for (var i = firstPage + 1; i <= lastPage; i++) {
		if (curPage == i) {
			pageList += "<li class='paginate_button active'><a href='#'> " + i + "</a></li> ";
		} else {
			pageList += "<li class='paginate_button'><a href=\"javascript:_pageCall('" + frm + "', " + i + ", '" + dataFunc + "')\" title='"+i+"'>" + i + "</a></li>";
		}
	}

	if (block < totalBlock) {
		pageList += "<li class='paginate_button next'><a href=\"javascript:_pageCall('" + frm + "', " + (lastPage + 1) + ", '" + dataFunc + "')\" title='다음'><i class='fa fa-angle-double-right'></i></a></li>";
	}

	pageList += "</ul>";

	/* var pageList = "<div class='pagelist_wrap'>";
    if (block > 1 && totalBlock > 1) {
        pageList += "<a class='pagelist_txt' href=\"javascript:_pageCall('" + frm + "', 1, '" + dataFunc + "')\" title='맨처음'>맨처음</a> ";
        pageList += "<a class='pagelist_txt' href=\"javascript:_pageCall('" + frm + "', " + firstPage + ", '" + dataFunc + "')\" title='이전'>이전</a> ";
    }

    for (var i = firstPage + 1; i <= lastPage; i++) {
        if (curPage == i) {
            pageList += "<span class='pagelist on'>" + i + "</span> ";
        } else {
            pageList += "<a class='pagelist' href=\"javascript:_pageCall('" + frm + "', " + i + ", '" + dataFunc + "')\">" + i + "</a>";
        }
    }

    if (block < totalBlock) {
        pageList += "<a class='pagelist_txt' href=\"javascript:_pageCall('" + frm + "', " + (lastPage + 1) + ", '" + dataFunc + "')\" title='다음'>다음</a>";
        pageList += " <a class='pagelist_txt' href=\"javascript:_pageCall('" + frm + "', " + (totalBlock) + ", '" + dataFunc + "')\" title='맨마지막'>맨마지막</a>";
    }

    pageList += "</div>"; */

	//console.log("pagelist : " + pageList);

	$("#" + pageListId).html(pageList);
}
// ==========================================================

function _pageListSelect(total, frm, dataFunc, pl) {
	// 폼안의 현재 페이지번호 가져온다
	var curPage = $("#" + frm).find("[name=page]").val();
	var pageNum = $("#" + frm).find("[name=page_num]").val();
	var listNum = $("#" + frm).find("[name=listNum]").val();
	var listNum10 = "";
	var listNum30 = "";
	var listNum50 = "";
	var listNum100 = "";
	var listNum200 = "";

	if (pageNum == undefined) {
		console.log("pageNum undefined");
		pageNum = 10;
	}

	if (listNum == undefined) {
		console.log("listNum undefined");
		listNum = 10;
	}

	if(listNum == 10){
		listNum10 = "selected";
	}

	if(listNum == 30){
		listNum30 = "selected";
	}

	if(listNum == 50){
		listNum50 = "selected";
	}

	if(listNum == 100){
		listNum100 = "selected";
	}

	if(listNum == 200){
		listNum200 = "selected";
	}

	var pageListId = 'pagelist';
	if (pl != undefined) {
		pageListId = pl;
	}

	var totalPage = 1;
	if (total > 0) {
		totalPage = Math.ceil(total / listNum);
	}

	var totalBlock = Math.ceil(totalPage / pageNum);
	var block = Math.ceil(curPage / pageNum);
	var firstPage = (pageNum) * (block - 1);
	var lastPage = (pageNum) * block;

	if (block == totalBlock)
		lastPage = totalPage;

	//console.log("total : " + total + ", frm : " + frm + ", pageNum : " + pageNum + ", listNum : " + listNum + ", pageListId : " + pageListId);
	//console.log("curPage : " + curPage + ", totalBlock : " + totalBlock + ", block : " + block + ", firstPage : " + firstPage + ", lastPage : " + lastPage);

	var pageList = "<ul class='pagination'>";
	if (block > 1 && totalBlock > 1) {
		pageList += "<li><a class='paginate_button previous' href=\"javascript:_pageCall('" + frm + "', " + firstPage + ", '" + dataFunc + "')\" title='이전'><i class='fa fa-angle-double-left'></i></a></li> ";
	}

	for (var i = firstPage + 1; i <= lastPage; i++) {
		if (curPage == i) {
			pageList += "<li class='paginate_button active'><a href='#'> " + i + "</a></li> ";
		} else {
			pageList += "<li class='paginate_button'><a href=\"javascript:_pageCall('" + frm + "', " + i + ", '" + dataFunc + "')\" title='"+i+"'>" + i + "</a></li>";
		}
	}

	if (block < totalBlock) {
		pageList += "<li class='paginate_button next'><a href=\"javascript:_pageCall('" + frm + "', " + (lastPage + 1) + ", '" + dataFunc + "')\" title='다음'><i class='fa fa-angle-double-right'></i></a></li>";
	}

	pageList += "<li style='margin-left:10px'>";
	pageList += "<select class=\"form-control\" name=\"list_num\" id=\"list_num\" onchange=\"javascript:_pageSelectCall('" + frm + "', '" + dataFunc + "');\">";
	pageList += "<option value=\"10\" "+listNum10+">10</option>";
	pageList += "<option value=\"30\" "+listNum30+">30</option>";
	pageList += "<option value=\"50\" "+listNum50+">50</option>";
	pageList += "<option value=\"100\" "+listNum100+">100</option>";
	pageList += "<option value=\"200\" "+listNum200+">200</option>";
	pageList += "</select>";
	pageList += "</li>";
	pageList += "</ul>";

	//console.log("pagelist : " + pageList);

	$("#" + pageListId).html(pageList);
}

var branchArr = {"ppeum1":"신논현 본점",	//1
	//"강남" => "강남역점",	//2
	"ppeum9":"강남점",		//9
	"ppeum3":"신사점",	//3
	//"신사_반영구" => "신사점 반영구",	//3
	//"신사_리프팅" => "신사점 리프팅",	//3
	"ppeum4":"일산점",	//4
	//"화정" => "화정점",	//5
	"ppeum6":"홍대점",	//6
	"ppeum7":"수원인계점",	//7
	//"압구정" => "압구정점",	//8
	"ppeum10":"분당점",
	"ppeum11":"인천본점",
	"ppeum12":"범계점",
	"ppeum13":"천호점",
	"ppeum14":"부평점",
	//"ppeum15" => "운정점",
	"ppeum16":"명동점",
	"ppeum17":"건대점",
	"ppeum18":"안산점",
	"ppeum19":"청주점",
	"ppeum20":"부산점",
	"ppeum21":"대구중앙로점(1호점)", //21
	"ppeum22":"노원점", //22
	"ppeum23":"동탄점", //23
	"ppeum24":"수원역점", //24
	"ppeum25":"서울대입구점", //25
	"ppeum26":"대구동성로점(2호점)", //26
	"ppeum27":"잠실점", //27
	"ppeum28":"대전점", //28
	"ppeum29":"목동점", //29
	"ppeum30":"천안점", //30
	"ppeum31":"제주점", //31
	"ppeum_eng":"외국인(영)", //신논현 본점(외국인 영어)
	"ppeum_jp":"외국인(일)", //신논현 본점(외국인 일어)
	"ppeum_ch":"외국인(중)", //신논현 본점(외국인 중국어)
	"ppeum_thai":"외국인(태국)" //신논현 본점(외국인 태국어)
}

var branchHostArr = {"ppeum1":"https://www.ppeum1.com",	//1
	//"강남" => "강남역점",	//2
	"ppeum9":"https://www.ppeum9.com",		//9
	"ppeum3":"https://www.ppeum3.com",	//3
	//"신사_반영구" => "신사점 반영구",	//3
	//"신사_리프팅" => "신사점 리프팅",	//3
	"ppeum4":"https://www.ppeum4.com",	//4
	//"화정" => "화정점",	//5
	"ppeum6":"https://www.ppeum6.com",	//6
	"ppeum7":"https://www.ppeum7.com",	//7
	//"압구정" => "압구정점",	//8
	"ppeum10":"https://www.ppeum10.com",
	"ppeum11":"https://www.ppeum11.com",
	"ppeum12":"https://www.ppeum12.com",
	"ppeum13":"https://www.ppeum13.com",
	"ppeum14":"https://www.ppeum14.com",
	//"ppeum15" => "운정점",
	"ppeum16":"https://www.ppeum16.com",
	"ppeum17":"https://www.ppeum17.com",
	"ppeum18":"https://www.ppeum18.com",
	"ppeum19":"https://www.ppeum19.com",
	"ppeum20":"https://www.ppeum20.com",
	"ppeum21":"https://www.ppeum21.com", //21
	"ppeum22":"https://www.ppeum22.com", //22
	"ppeum23":"https://www.ppeum23.com", //23
	"ppeum24":"https://www.ppeum24.com", //24
	"ppeum25":"https://www.ppeum25.com", //25
	"ppeum26":"https://www.ppeum26.com", //26
	"ppeum27":"https://www.ppeum27.com", //27
	"ppeum28":"https://www.ppeum28.com", //28
	"ppeum29":"https://www.ppeum29.com", //29
	"ppeum30":"https://www.ppeum30.com", //30
	"ppeum31":"https://www.ppeum31.com", //31
	"ppeum_eng":"https://www.ppeum1.com", //신논현 본점 영어
	"ppeum_jp":"https://www.ppeum1.com", //신논현 본점 일어
	"ppeum_ch":"https://www.ppeum1.com", //신논현 본점 중국어
	"ppeum_thai":"https://www.ppeum1.com" //신논현 본점 태국어
}

// 지점별 네이버 스크립트 공통키
var jijumNaverKey = {
	'ppeum1':'s_2283b14e82',
	'ppeum2':'s_16df47cff619',
	'ppeum3':'s_4aeaaeacd39',
	'ppeum4':'s_396ef82ca759',
	'ppeum5':'s_51fd5f684188',
	'ppeum6':'s_5772907a3c4f',
	'ppeum7':'s_35cbe2dc7685',
	'ppeum9':'s_379df5c2a8ca',
	'ppeum10':'s_1425bb52c8c8',
	'ppeum11':'s_568a531b1c7d',
	'ppeum12':'s_3b418c53695b',
	'ppeum13':'s_16e087a8b62c',
	'ppeum14':'s_594539ff9912',
	'ppeum15':'s_4ab808fc3f0f',
	'ppeum16':'s_1b6cd7e839a0',
	'ppeum17':'s_53d085441e05',
	'ppeum18':'s_1ff97c4f0180',
	'ppeum19':'s_3c7f60256c3',
	'ppeum20':'s_3dfd3f357a02',
	'ppeum21':'s_2740c659c1ed',
	'ppeum22':'s_4c8aee9ef18e',
	'ppeum23':'s_52e8d211f838',
	'ppeum24':'s_22b4ef8df77c',
	'ppeum25':'s_3d15271e23b7',
	'ppeum26':'s_322b360ab788',
	'ppeum27':'s_10e5f53f303',
	'ppeum28':'s_27418a93f25d',
	'ppeum29':'s_2e053eda61a',
	'ppeum30':'s_dca52d000d0',
	'ppeum31':'s_35ceed1a11c0',
	'ppeum_eng':'s_2283b14e82',
	'ppeum_jp':'s_2283b14e82',
	'ppeum_ch':'s_2283b14e82',
	'ppeum_thai':'s_2283b14e82'
}

$(function () {

	$('#top_banner .btn_close').on('click',function(e){
		e.preventDefault();
		$('#top_banner').css('display','none');
	});


	var cInterval;
	var tween;

	// 오른쪽하단 빠른상담
	if($('#txt_roll').length>0){
		var qconsult_slider = $('#txt_roll ul').bxSlider({
			auto : false,
			pager : false,
			mode : 'vertical',
			pause : 2000,
			speed : 800,
		});

		$('#txt_roll').on('click',function(e){
			e.preventDefault();
			$('#qconsult').addClass('open');
			$('#txt_roll').addClass('off');
			qconsult_slider.stopAuto();
			clearInterval(cInterval);
			tween.pause();
		});

		$('#qconsult .btn_close').on('click',function(e){
			e.preventDefault();
			$('#qconsult').removeClass('open');
			clearInterval(cInterval);
			cInterval = setTimeout(txtRollStMotion,1000);
			qconsult_slider.stopAuto();
			qconsult_slider.startAuto();
			tween.restart();

		});

		tween = TweenMax.to($("#txt_roll"), 0.5, {bottom:10, yoyo:true, repeat:-1, delay:0.8});
		TweenMax.to($("#qconsult"), 0.4, {bottom:13, delay:0.5});

		cInterval = setTimeout(txtRollStMotion,3000);
		function txtRollStMotion(){
			$('#txt_roll').removeClass('off');
			qconsult_slider.stopAuto();
			qconsult_slider.startAuto();
		}
	}


	var scrollOffsetY = 20;
	var scrollTop;
	$(window).on('scroll', function() {
		scrollFn();
	});

	scrollFn();
	function scrollFn(){

		scrollTop = $(document).scrollTop();
		if (scrollTop > 0) {
			$("#header").addClass('fixed_top');
		} else {
			$("#header").removeClass('fixed_top');
		}

	}

	// Family Site
	var $family_site = $('.family_site.dropDown .sub li em');
	var family_site_inner    = '.family_site.dropDown .sub li > ul';

	$family_site.click(function () {
		$(this).parent().toggleClass('on');
		$(this).next(family_site_inner).slideToggle();
		$(this).parent().siblings().children().next().slideUp();
		$(this).parent().siblings('.family_site.dropDown .sub li').removeClass('on');
		return false;
	});

	$('.family_site.dropDown > a').click(function(e) {
		e.preventDefault();
		if($(this).parent().is('.on')) {
			$(this).parent().removeClass('on');
			$('.family_site.dropDown .sub li').removeClass('on');
			$('.family_site.dropDown .sub li > ul').hide();
			$('.family_site.dropDown .sub').slideUp(250);
		} else {
			$(this).parent().addClass('on');
			$('.family_site.dropDown .sub').slideDown(300);
		}
	});

	//family_site 영영 외 클릭시 메뉴 닫기
	$(document).click(function(e) {
		var fs_container = $('.family_site.dropDown.on');
		if (!fs_container.is(e.target) && fs_container.has(e.target).length === 0) {
			$('.family_site.dropDown').removeClass('on');
			$('.family_site.dropDown .sub li').removeClass('on');
			$('.family_site.dropDown .sub li > ul').hide();
			$('.family_site.dropDown .sub').slideUp(250);
		}
	});

});

function hide_popup(p_sID){
	var f_oLayer	= document.getElementById(p_sID);
	f_oLayer.style.display	= "none";
}
function set_cookie( name, value, expiredays ){
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
function get_cookie( name ) {
	var nameOfCookie = name + "=";
	var x = 0;
	while ( x <= document.cookie.length )
	{
	var y = (x+nameOfCookie.length);
	if ( document.cookie.substring( x, y ) == nameOfCookie ) {
	  if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
	   endOfCookie = document.cookie.length;
	  return unescape( document.cookie.substring( y, endOfCookie ) );
	}
	x = document.cookie.indexOf( " ", x ) + 1;
	if ( x == 0 )
	  break;
	}
	return "";
}

//상단 진료안내 팝업용 스크립트
$(function () {
	$('.util_menu .noti_list').on('click',function(e){
		e.preventDefault();
		noti_pop_show();
	});

	/*if($('.util_menu li').hasClass('noti_list')) {
		noti_pop_list();
	}*/
	if ($('#noti_pop .swiper-slide').length > 0) {
		noti_pop_list();
	} else {
		$('.noti_list').hide();
	}
});
function noti_pop_list() {
	var notiList = '';
	var noti_list = '';
	$('#noti_pop .swiper-slide').each(function(index) {
		notiList += '<div class="swiper-slide">'+$(this).attr('data-title')+'</div>';
		$('.util_menu .noti_list .swiper-container .swiper-wrapper').html(notiList);
	});
	if ($('.util_menu .noti_list .swiper-slide').length > 1) {
		if($('.util_menu .noti_list').hasClass('click')) {
			noti_list.slideReset(300);
		} else {
			$('.util_menu .noti_list').addClass('click');
			noti_list = new Swiper('.util_menu .noti_list .swiper-container', {
				loop: true,
				direction: 'vertical',
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
			});
		}
	}
}

function noti_pop_show() {
	$('#noti_pop').show();
	if ($('#noti_pop .swiper-slide').length > 1) {
		var noti_pop = new Swiper('#noti_pop .swiper-container', {
			loop: true,
			speed: 400,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '#noti_pop .swiper-pagination',
				clickable: true,
			},
		});
	}
}
//상단 진료안내 팝업용 스크립트//
