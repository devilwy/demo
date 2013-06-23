<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="e87b4a31495ff2e7" />
<title>ckgsb用户注册</title>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jsconf.js"></script>
<!–[if IE 6]>
<script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/DD_belatedPNG_0.0.8a-min.js"></script>
<!–[if IE]>
<script src="<?php echo STATIC_DOMAIN;?>www/js/html5.js"></script>
<![endif]–>
<script>
	DD_belatedPNG.fix('img,.header-content,.pwd-btn,.reg,.regbody');
</script>
<![endif]–>
</head>
<body>
<div>
	<div>HI,You are a <?php echo $utype_str;?></div>
	<form action="<?php echo site_url('user/reg'); ?>" method="post" id="reg_form" name="reg_form">
	<table>
		<tr>
			<td>用户名:</td>
			<td>
				<input type="text" id="usernamne" name="username">
				<span></span>
			</td>
		</tr>
		<tr>
			<td>密码:</td>
			<td>
				<input type="password" name="password" id="password">
				<span></span>
			</td>
		</tr>
		<tr>
			<td>密码确认:</td>
			<td>
				<input type="password" name="repassword" id="repassword">
				<span></span>
            </td>
		</tr>
		<tr>
			<td>邮箱:</td>
			<td>
				<input type="text" name="email" id="email">
				<span></span>
            </td>
		</tr>		
		<tr>
			<td>真实姓名:</td>
			<td>
				<input type="text" name="realname" id="realname">
				<span></span>
			</td>
		</tr>
		<tr>
			<td>邀请码:</td>
			<td>
				<input type="text" name="invitecode" id="invitecode">
				<span></span>
			</td>
		</tr>
		<tr>
			<td><a style="cursor:pointer;" id="reg_submit">注册</a>&nbsp;&nbsp;<a href="<?php echo site_url('user/login')?>">登录</a></td>
		</tr>
	</table>
	<input type="hidden" name="utype" value="<?php echo $utype;?>" />
	</form>
</div>

  <!--========== footer ===============-->
  <footer>
    
  </footer>
  <!--========== footer end ===============--> 
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery.validate.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/reg.js"></script>

</body>
</html>