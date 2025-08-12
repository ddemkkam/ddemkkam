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
			<?if ( $this->session->userdata('ADMIN_LEVEL') >= '80' ) {?>
				<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/defaultset/modifyset') !== false ? 'active menu-open' : ''?>">
					<a href="/home_admin/defaultset/modifyset/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
						<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/defaultset/modifyset') !== false ? 'text-aqua' : ''?>"></i>
						<span>기본정보 설정</span>
					</a>
				</li>
				<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/footer/modifyfooter') !== false ? 'active menu-open' : ''?>">
					<a href="/home_admin/footer/modifyfooter/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
						<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/footer/modifyfooter') !== false ? 'text-aqua' : ''?>"></i>
						<span>푸터 설정</span>
					</a>
				</li>
				<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/termsset/') !== false ? 'active menu-open' : ''?>">
					<a href="/home_admin/termsset/modifyuseset/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
						<?/*<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/termsset') !== false ? 'text-aqua' : ''?>"></i>*/?>
						<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/termsset/') !== false ? 'text-aqua' : ''?>"></i>
						<span>이용약관 설정</span>
					</a>
				</li>
				<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/termspvchset/') !== false ? 'active menu-open' : ''?>">
					<a href="/home_admin/termspvchset/modify/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
						<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/termspvchset') !== false ? 'text-aqua' : ''?>"></i>
						<span>개인정보 처리방침 설정</span>
					</a>
				</li>
				<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/child/') !== false ? 'active menu-open' : ''?>">
					<a href="/home_admin/child/modify/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
						<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/child') !== false ? 'text-aqua' : ''?>"></i>
						<span>미성년자 동의서 설정</span>
					</a>
				</li>
			<?}?>
			<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/mainimg/') !== false ? 'active menu-open' : ''?>">
				<a href="/home_admin/mainimg/modify/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
					<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/mainimg') !== false ? 'text-aqua' : ''?>"></i>
					<span>메인 이미지 설정</span>
				</a>
			</li>
			<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/popupset/') !== false ? 'active menu-open' : ''?>">
				<a href="/home_admin/popupset/modifypopup/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
					<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/popupset') !== false ? 'text-aqua' : ''?>"></i>
					<span>팝업 설정</span>
				</a>
			</li>
			<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/MyPage/pc') !== false ? 'active menu-open' : ''?>">
				<a href="/home_admin/MyPage/pc/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
					<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/MyPage/pc') !== false ? 'text-aqua' : ''?>"></i>
					<span>마이페이지 배너 설정(PC)</span>
				</a>
			</li>
			<li class="<?=strpos($_SERVER['REQUEST_URI'], '/home_admin/MyPage/mobile') !== false ? 'active menu-open' : ''?>">
				<a href="/home_admin/MyPage/mobile/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
					<i class="fa fa-circle-o <?=strpos($_SERVER['REQUEST_URI'], '/home_admin/MyPage/mobile') !== false ? 'text-aqua' : ''?>"></i>
					<span>마이페이지 배너 설정(모바일)</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
