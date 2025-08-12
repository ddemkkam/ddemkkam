<head>
	<meta charset="utf-8">

	<? if ($branchInfo['BRANCH'] == 'ppeum37') { ?>
		<meta name="naver-site-verification" content="47ffe8badece1578fa5e4e1f64ffaa7c20fa78db" />
	<? } ?>

	<?/*<link rel="icon" href="/common/homepage/favicon.ico">*/?>
	<link rel="icon" href="<?=isset($branchInfo['H_FAVICON']) ? $branchInfo['H_FAVICON'] : '' ?>" type="image/x-icon">
	<?
	//$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
	//if( preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']) ) {	?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<?//} else {?>
<!--		<meta name="viewport" content="width=570px, initial-scale=1">-->
	<?//}?>
	<meta name="theme-color" content="#000000">
	<meta name="description" content="<?=isset($branchInfo['H_DESCRIPTION']) ? $branchInfo['H_DESCRIPTION'] : '' ?>,<?=isset($branchInfo['H_KEYWORDS']) ? $branchInfo['H_KEYWORDS'] : '' ?>">
	<link rel="apple-touch-icon" href="/logo192.png">
	<?/*
	<link rel="stylesheet" as="style" crossorigin="" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css">
	*/?>
	<link rel="stylesheet" as="style" crossorigin="" href="/common/homepage/css/pretendardvariable.css" />
		<!--
	  manifest.json provides metadata used when your web app is installed on a
	  user's mobile device or desktop. See https://developers.google.com/web/fundamentals/web-app-manifest/
	-->
<!--	<link rel="manifest" href="/manifest.json">-->

<!--	<link rel="stylesheet" as="style" crossorigin="" href="/common/homepage/css/pretendardvariable.css" />-->
	<link rel="stylesheet" href="/common/homepage/css/react.css?date=20241128" />
	<link rel="stylesheet" href="/common/homepage/css/common.css?date=20250205" />

	<link rel="stylesheet" href="/publish/bower_components/jquery-ui/themes/base/jquery-ui.min.css" />
<!--	<link rel="stylesheet" href="/common/homepage/css/selectsurgery.css" />-->
<!--	<link rel='stylesheet' href='/common/homepage/css/swiper2.min.css' media='all' />-->

	<!--
	  Notice the use of  in the tags above.
	  It will be replaced with the URL of the `public` folder during the build.
	  Only files inside the `public` folder can be referenced from the HTML.

	  Unlike "/favicon.ico" or "favicon.ico", "/favicon.ico" will
	  work correctly both with client-side routing and a non-root public URL.
	  Learn how to configure a non-root public URL by running `npm run build`.
	-->
	<title><?=isset($branchInfo['H_TITLE']) ? $branchInfo['H_TITLE'] : 'PPEUM' ?></title>
<!--	<script defer="" src="/common/homepage/js/bundle.js"></script><style>-->

	<!-- jQuery 3 -->
	<script src="/publish/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="/common/homepage/js/swiper.min.js"></script>
	<script src="/common/homepage/js/common.js?v=1"></script>

	<script src="/publish/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/publish/plugins/jquery-number/jquery-number.js"></script>

</head>
