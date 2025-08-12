<?//echo "<pre>"; print_r($this->session->userdata()); echo "</pre>";?>

<header class="main-header">
	<a href="/home_admin/main/" class="logo">
		<span class="logo-mini"><b>SA</b></span>
		<span class="logo-lg"><b>PPEUM Admin</b></span>
	</a>
	<nav class="navbar navbar-static-top">
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<div style="float: left; margin-left: 100px; height: 50px; line-height: 50px;">
			<table>
				<tr>
					<td class="<?=$menu == 'menu1' ? 'topFontSelected' : 'topFont'?>">
						<?if ( $this->session->userdata('ADMIN_LEVEL') >= '80' ) {?>
							<a href="/home_admin/defaultset/modifyset/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
								기본설정
							</a>
						<?}else{?>
							<a href="/home_admin/mainimg/modify/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
								기본설정
							</a>
						<?}?>
					</td>
					<td class="<?=$menu == 'menu2' ? 'topFontSelected' : 'topFont'?>">
						<a href="/home_admin/comeoffice/modifyset/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
							병원 소개
						</a>
					</td>
					<?if ( $this->session->userdata('ADMIN_LEVEL') >= '80' ) {?>
					<td class="<?=$menu == 'menu3' ? 'topFontSelected' : 'topFont'?>">
						<a href="/home_admin/rankset/modifyrank/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
							인기 검색어
						</a>
					</td>
					<?}?>
					<td class="<?=$menu == 'menu4' ? 'topFontSelected' : 'topFont'?>">
						<a href="/home_admin/eventinfo/eventDetail/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
							이벤트관리
						</a>
					</td>
					<?if ( $this->session->userdata('ADMIN_LEVEL') >= '80' ) {?>
						<td class="<?=$menu == 'menu5' ? 'topFontSelected' : 'topFont'?>">
							<a href="/home_admin/notice/index/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
								게시물 관리
							</a>
						</td>
					<?}else{?>
						<td class="<?=$menu == 'menu5' ? 'topFontSelected' : 'topFont'?>">
							<a href="/home_admin/notandum/index/<?=isset($BASEBRANCH) ? $BASEBRANCH.'/' : ''?><?=isset($BASELAN) ? $BASELAN.'/' : ''?>">
								게시물 관리
							</a>
						</td>
					<?}?>
					<?/*
					<td class="<?=$menu == 'menu4' ? 'topFontSelected' : 'topFont'?>">
						<a href="#">회원관리</a>
					</td>
					*/?>
					<?if ( $this->session->userdata('ADMIN_LEVEL') === '99' ) {?>
						<td class="<?=$menu == 'menu6' ? 'topFontSelected' : 'topFont'?>">
							<a href="/home_admin/branch/">관리자</a>
						</td>
					<?}?>
				</tr>
			</table>
		</div>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#">
						<span class="hidden-xs">
							<?//=$_SESSION['member']['name'] ?>
							<?=$this->session->userdata('ADMIN_ID') ?>
							(<?=$this->session->userdata('ADMIN_NAME') ?>)
						</span>
					</a>
					<a href="/home_admin/login/logout">
						<i class="fa fa-power-off"></i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</header>
