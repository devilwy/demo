<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="e87b4a31495ff2e7" />
<title>ckgsb用户登录</title>
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
	<form action="<?php echo site_url('user/login'); ?>" method="post" id="login_form" name="login_form">
	<table>
		<tr>
			<td>用户名:</td>
			<td><input type="text" id="usernamne" name="username"><span></span></td>
		</tr>
		<tr>
			<td>密码:</td>
			<td><input type="password" name="password" id="password"><span></span></td>
		</tr>
		<tr>
			<td>验证码:</td>
			<td>
				<input style="width:60px" type="text"  name="seccode" id="seccode" class="text">
            	<img src="<?php echo site_url('common/seccode')?>" alt="" id='img_seccode'>&nbsp;看不清，<a style='cursor:pointer;' id='change_auth' class="blue">换一张</a>
            	<span></span>
            </td>
		</tr>
		<tr>
			<td><a style="cursor:pointer;" id="login_submit">登录</a>&nbsp;&nbsp;<a href="<?php echo site_url('user/reg/');?>" target='_self'>注册</a></td>
		</tr>
	</table>
	</form>
</div>


  <!--========== footer ===============-->
  <footer>
    
  </footer>
  <!--========== footer end ===============--> 
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery.validate.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/login.js"></script>

</body>
</html>

