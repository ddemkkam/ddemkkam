var isDebug = true;

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
			console.log(data);
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

	console.log("total : " + total + ", frm : " + frm + ", pageNum : " + pageNum + ", listNum : " + listNum + ", pageListId : " + pageListId);
	console.log("curPage : " + curPage + ", totalBlock : " + totalBlock + ", block : " + block + ", firstPage : " + firstPage + ", lastPage : " + lastPage);

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

	console.log("pagelist : " + pageList);

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

	console.log("total : " + total + ", frm : " + frm + ", pageNum : " + pageNum + ", listNum : " + listNum + ", pageListId : " + pageListId);
	console.log("curPage : " + curPage + ", totalBlock : " + totalBlock + ", block : " + block + ", firstPage : " + firstPage + ", lastPage : " + lastPage);

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

	console.log("pagelist : " + pageList);

	$("#" + pageListId).html(pageList);
}
