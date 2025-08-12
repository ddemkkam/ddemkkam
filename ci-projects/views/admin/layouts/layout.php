<?
/*
if( !isset($_SESSION['member']['id']) ) {
	header('location:/');
	exit();
} else if (isset($_SESSION['member']['master']) && $_SESSION['member']['master'] != 'Y') {
	header('location:/front/state');
	exit();
}
*/
?>

<? include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/header.php'; ?>

<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">
	<? include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/top.php'; ?>
	<?
	if ( $menu === 'menu1' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left.php';
	} else if ( $menu === 'menu2' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left2.php';
	} else if ( $menu === 'menu3' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left3.php';
	} else if ( $menu === 'menu4' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left4.php';
	} else if ( $menu === 'menu5' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left5.php';
	} else if ( $menu === 'menu6' ){
		include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/left6.php';
	}
	?>

	<div class="content-wrapper">

		<section class="content-header">
			<h1>
				<?=$nav['navTitle'] ?>
				<small><?=$nav['navTitle2'] ?></small>
			</h1>

			<? if( $nav['navLink1'] != '') { ?>
				<ol class="breadcrumb">
					<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
					<li <? $nav['navLink1'] != '' && $nav['navLink2'] == ''?>class="active" style='font-weight: bold;' >
						<a href="<?=$nav['navLink1']?>">
							<?=$nav['navTitle'] ?>
						</a>
					</li>
					<? if($nav['navLink2'] != '') { ?>
						<li <? $nav['navLink2'] != '' ?>class="active" style='font-weight: bold;'>
							<a href="<?=$nav['navLink2'] ?>">
								<?=$nav['navTitle2'] ?>
							</a>
						</li>
					<? } ?>
				</ol>
			<? } ?>
		</section>

		<section class="content">
			<?=$content_for_layout ?>
		</section>
	</div>

	<? include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/footer.php'; ?>

	<div class="control-sidebar-bg"></div>

</div>


<!-- jQuery UI 1.11.4 -->
<script src="/publish/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="/publish/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="/publish/bower_components/raphael/raphael.min.js"></script>
<!-- <script src="/publish/bower_components/morris.js/morris.min.js"></script> -->
<!-- Sparkline -->
<script src="/publish/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/publish/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/publish/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/publish/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/publish/bower_components/moment/min/moment.min.js"></script>
<script src="/publish/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/publish/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/publish/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="/publish/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/publish/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/publish/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="/publish/dist/js/pages/dashboard.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="/publish/dist/js/demo.js"></script>
<!-- iCheck -->
<script src="/publish/plugins/iCheck/icheck.min.js"></script>

<script src="/common/admin/js/loading_overlay.min.js"></script>
<script src="/common/admin/js/common.js"></script>

</body>
</html>
