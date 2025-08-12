$(function () {
	// 층수 설정
	$('#floor-select span').click(function() {
		$(this).siblings('ul').slideToggle(100);
		$(this).closest('ul').toggleClass('open');
	});

	// 층수 영영 외 클릭시 메뉴 닫기
	$(document).click(function(e) {
		var container = $('#floor-select');
		if (!container.is(e.target) && container.has(e.target).length === 0 && container.hasClass('open')) {
			container.find('.floor-list').slideUp(100);
			container.removeClass('open');
		}
	});
});

function sendAjaxHtml(action, param = null, method, id) {
	//console.log(param);
	if(id != 'data_list') {
		$("#"+id).html("");
	}

	//$.LoadingOverlay("show", { size: 40, maxSize: 40 });

	$.ajax({
		url: action,
		data: param,
		method: method,
		dataType: 'html',
		success:function(data){
			//console.log(data);
			$("#"+id).html(data);

			//$.LoadingOverlay("hide");
		}
		,fail:function(xhr, status, errorThrown) {
			console.log("오류가 발생하였습니다.<br>");
			console.log("오류명: " + errorThrown + "<br>");
			console.log("상태: " + status);
		}
	});
}

function sendAjaxJson(action, param = null, method) {
	//console.log(param);
	//$.LoadingOverlay("show", { size: 40, maxSize: 40 });

	$.ajax({
		url: action,
		data: param,
		method: method,
		dataType: 'html',
		success:function(data){
			console.log(data);

			//$.LoadingOverlay("hide");

			return data;
		}
		,fail:function(xhr, status, errorThrown) {
			console.log("오류가 발생하였습니다.<br>");
			console.log("오류명: " + errorThrown + "<br>");
			console.log("상태: " + status);
		}
	});
}

/* 아래 펑션은 팝업 확인용으로 임의 설정한 것이니, 개발시에는 무시하시면 됩니다! */
function modalPopShow(target) {
	$('.modal-shadow, .modal-box.'+target).fadeIn(100);
}
function modalPopHide(target) {
	if(target == 're-director') {
		$('.modal-box.'+target).fadeOut(100);
	}
	else {
		$('.modal-shadow, .modal-box.'+target).fadeOut(100);
	}
}

var isDebug = true;

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
