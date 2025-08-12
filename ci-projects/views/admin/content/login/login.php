<div class="login-box">
	<div class="login-logo">
		<a href="/"><b>PPEUM ADMIN</b></a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">로그인</p>

		<form id="frm" method="post" action="/home_admin/login/loginProc">
			<div class="form-group has-feedback">
				<input type="text" name="ADMIN_ID" id="ADMIN_ID" class="form-control" placeholder="ID">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="PASSWORD" id="PASSWORD" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row" style="margin-bottom: 15px;">
				<div class="col-xs-12">
					<button type="submit" class="btn btn-success btn-block btn-flat">로그인</button>
				</div>
			</div>

		</form>
	</div>
</div>
