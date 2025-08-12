<script>
	var currUrl = "{_SERVER['REQUEST_URI']}";
	if(currUrl.indexOf('?') != -1){
		currUrl = currUrl.substring(0, currUrl.indexOf('?'));
	}

	$(document).ready(function(){
		$(".sidebar-menu").find("a").each(function(){
			//console.log("- find : " + $(this).html());
			if($(this).attr("href")==currUrl){
				$(this).children("i").addClass("text-yellow");	// 현재 뎁스 동그라미 색상 변경
				$(this).parent().addClass("active");			// 현재 뎁스 활성화표시 (3레벨 또는 2레벨)
				$(this).parent().addClass("menu-open");			// 현재 뎁스 메뉴펼치기 (3레벨 또는 2레벨)
				$(this).parent().parent().parent().addClass("active");	// 바로 위 상위뎁스 활성화표시 (2레벨 또는 1레벨)
				$(this).parent().parent().parent().addClass("menu-open"); // 바로 위 상위뎁스 메뉴펼치기 (2레벨 또는 1레벨)
				var parentName = $(this).parent().parent().parent().parent().parent().get(0).tagName;
				// 현재 메뉴가 총 3레벨인 경우 1레벨 활성화표시
				//console.log(" -- parentName : "+parentName);
				if(parentName=='li' || parentName=='LI'){
					$(this).parent().parent().parent().parent().parent().addClass("active");
					$(this).parent().parent().parent().parent().parent().addClass("menu-open");
				}
			}
		});
	});
</script>
<style>
	.skin-purple .sidebar-menu>li.active>a {
		border-left-color: transparent;
	}
</style>
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<?include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left_all.php';?>

		<!-- sidebar menu: : style can be found in sidebar.less -->
		<!--  text-yellow -->

		<ul class="sidebar-menu" data-widget="tree">

			<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/eventinfo/') !== false ? 'active menu-open' : ''?>">
				<a href="/home_admin/eventinfo/index/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
					<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/eventinfo') !== false ? 'text-aqua' : ''?>"></i>
					<span>이벤트 이미지 설정</span>
				</a>
			</li>

		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
