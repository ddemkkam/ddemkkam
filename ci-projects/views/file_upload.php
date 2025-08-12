<script src="/publish/bower_components/jquery/dist/jquery.min.js"></script>

<script src="/publish/plugins/dropzone-5-9-3/dropzone.min.js"></script>
<link rel="stylesheet" href="/publish/plugins/dropzone-5-9-3/dropzone.min.css" type="text/css" />

<style>
	th { height: 45px; }

	.dropzone {
		width: 98%;
		margin: 1%;
		border: 2px dashed #3498db !important;
		border-radius: 5px;
		-webkit-transition: .2s;
		transition: .2s;
		padding: 0px;
	}

	.dropzone.dz-drag-hover {
		border: 2px solid #3498db !important;
	}

	.dz-message.needsclick img {
		width: 50px;
		display: block;
		margin: auto;
		opacity: .6;
		margin-bottom: 15px;
	}

	span.plus {
		display: none;
	}

	.dropzone.dz-started .dz-message {
		display: inline-block !important;
		width: 120px;
		float: right;
		border: 1px solid rgba(238, 238, 238, 0.36);
		border-radius: 30px;
		height: 120px;
		margin: 16px;
		-webkit-transition: .2s;
		transition: .2s;
	}

	.dropzone.dz-started .dz-message span.text {
		display: none;
	}

	.dropzone.dz-started .dz-message span.plus {
		display: block;
		font-size: 70px;
		color: #AAA;
		line-height: 110px;
	}
</style>

<div class="col-xs-12" style="text-align: center;">
	<div class="dropzone" id="my-dropzone"></div>
<!--	<input type="button" class="upBtn btn btn-success" value="업로드">-->
</div>

<script>
	//import {toJSON} from "../publish/bower_components/moment/src/lib/moment/to-type";
	let sendUrl = "";
	if ( '<?=isset($id) ? $id : '' ?>' === 'favicon' ) {
		sendUrl = "/FileUpload";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'child' ) {
		sendUrl = "/FileUpload/child";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'popup' ) {
		sendUrl = "/FileUpload/popup";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'event' ) {
		sendUrl = "/FileUpload/event";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'hospital' ) {
		sendUrl = "/FileUpload/hospital";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'doctor' ) {
		sendUrl = "/FileUpload/doctor";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'eventinfo' ) {
		sendUrl = "/FileUpload/eventinfo";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'mainimg' ) {
		sendUrl = "/FileUpload/mainimg";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'mypagepc' ) {
		sendUrl = "/FileUpload/mypagepc";
	} else if ( '<?=isset($id) ? $id : '' ?>' === 'mypagemobile' ) {
		sendUrl = "/FileUpload/mypagemobile";
	}
	//console.log(sendUrl);

	Dropzone.autoDiscover = false; // deprecated 된 옵션. false로 해놓는걸 공식문서에서 명시
	const dropzone = new Dropzone('#my-dropzone', {

		url: sendUrl, // 파일을 업로드할 서버 주소 url.
		method: 'post', // 기본 post로 request 감. put으로도 할수있음
		// headers: {
		// 	// 요청 보낼때 헤더 설정
		// 	Authorization: 'Bearer ' + token, // jwt
		// },

		autoProcessQueue: true, // 자동으로 보내기. true : 파일 업로드 되자마자 서버로 요청, false : 서버에는 올라가지 않은 상태. 따로 this.processQueue() 호출시 전송
		clickable: true, // 클릭 가능 여부
		autoQueue: true, // 드래그 드랍 후 바로 서버로 전송
		createImageThumbnails: true, //파일 업로드 썸네일 생성

		thumbnailHeight: 100, // Upload icon size
		thumbnailWidth: 100, // Upload icon size

		maxFiles: <?=isset($cnt) ? $cnt : 1 ?>, // 업로드 파일수
		maxFilesize: 50, // 최대업로드용량 : 100MB
		paramName: 'ev_image[]', // 서버에서 사용할 formdata 이름 설정 (default는 file)
		parallelUploads: 5, // 동시파일업로드 수(이걸 지정한 수 만큼 여러파일을 한번에 넘긴다.)
		uploadMultiple: false, // 다중업로드 기능
		timeout: 300000, //커넥션 타임아웃 설정 -> 데이터가 클 경우 꼭 넉넉히 설정해주자

		addRemoveLinks: true, // 업로드 후 파일 삭제버튼 표시 여부
		dictRemoveFile: '삭제', // 삭제버튼 표시 텍스트
		acceptedFiles: '.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF,.ico,.ICO,.zip,.ZIP', // 이미지 파일 포맷만 허용

		init: function () {
			// 최초 dropzone 설정시 init을 통해 호출
			console.log('최초 실행');
			let myDropzone = this; // closure 변수 (화살표 함수 쓰지않게 주의)

			// 서버에 제출 submit 버튼 이벤트 등록
			// document.querySelector('.upBtn').addEventListener('click', function () {
			// 	console.log('업로드');
			//
			// 	// 거부된 파일이 있다면
			// 	if (myDropzone.getRejectedFiles().length > 0) {
			// 		let files = myDropzone.getRejectedFiles();
			// 		console.log('거부된 파일이 있습니다.', files);
			// 		return;
			// 	}
			//
			// 	myDropzone.processQueue(); // autoProcessQueue: false로 해주었기 때문에, 메소드 api로 파일을 서버로 제출
			// });

			// 파일이 업로드되면 실행
			this.on('addedfile', function (file) {
				//console.log(file);
				const addedFiles = new Array();
				// 중복된 파일의 제거
				if (this.files.length) {
					// -1 to exclude current file
					var hasFile = false;
					for (var i = 0; i < this.files.length - 1; i++) {
						if (
							this.files[i].name === file.name &&
							this.files[i].size === file.size &&
							this.files[i].lastModifiedDate.toString() === file.lastModifiedDate.toString()
						) {
							hasFile = true;
							this.removeFile(file);
						}
					}
					if (!hasFile) {
						addedFiles.push(file);
					}
				} else {
					addedFiles.push(file);
				}
			});

			// 업로드한 파일을 서버에 요청하는 동안 호출 실행
			this.on('sending', function (file, xhr, formData) {
				console.log('보내는중');
			});

			// 서버로 파일이 성공적으로 전송되면 실행
			this.on('success', function (file, responseText) {
				//console.log(file);
				console.log( responseText );
				console.log('성공');
				const jsonData = JSON.parse(responseText);
				jsonData.forEach(function (value, index){
					if ( '<?=isset($id) ? $id : '' ?>' === 'favicon' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_FAVICON")?>" + value.ef_file;

						appemHtml  = "<li style='background-color: #ffffff;'>";
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 50px; height: 50px;' />";
						appemHtml += "<input type='button' value='DEL' onclick='deleteImgView(this)' />";
						appemHtml += "<input type='hidden' name='imgFile[]' value='"+appendImgSrc+"'>";
						appemHtml += "</li>";

						//console.log(appendImg);
						//$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", opener.document).append(appemHtml);
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).append(appemHtml);
						//$("form")
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'child' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_CHILD")?>" + value.ef_file;
						appemHtml += "<input type='hidden' name='T_CONTEXT' value='"+appendImgSrc+"'>";
						appemHtml += "<a href='"+appendImgSrc+"'>"+value.ef_file+"</a>";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip()'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'popup' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_POPUP")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='P_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'event' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						var viewIdName = "";
						appendImgSrc = "<?=getenv("IMG_PATH_EVENT")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 100px; height: 100px;' />";
						if ( "<?=$viewId?>".indexOf('_2') !== -1 ) {
							viewIdName = "P_IMG_PATH_MO[]";
						} else {
							viewIdName = "P_IMG_PATH_PC[]";
						}
						appemHtml += "<input type='hidden' name='"+viewIdName+"' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'hospital' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_HOSPITAL")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='HI_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'doctor' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_DOCTOR")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='D_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'eventinfo' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_EVENTINFO")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='E_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'mainimg' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_MAINIMG")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='M_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'mypagepc' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_MYPAGEPC")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='M_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					} else if ( '<?=isset($id) ? $id : '' ?>' === 'mypagemobile' ) {
						var appendImgSrc = "";
						var appemHtml = "";
						appendImgSrc = "<?=getenv("IMG_PATH_MYPAGEMOBILE")?>" + value.ef_file;
						appemHtml += "<img src='"+appendImgSrc+"' style='width: 150px; height: 150px;' />";
						appemHtml += "<input type='hidden' name='M_IMG_PATH[]' value='"+appendImgSrc+"'><br />";
						appemHtml += "<input type='button' value='삭제' onclick='deleteZip(\"<?=$viewId?>\")'>";
						$("#<?=isset($viewId) ? $viewId : 'viewId' ?>", parent.document).html(appemHtml);
					}

					window.close();

				});

			});

			// 업로드 에러 처리
			this.on('error', function (file, errorMessage) {
				alert(errorMessage);
			});
		},

	});

</script>
