<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
*{margin:0;padding:0}
html,body{overflow:hidden}
a,a:active,.a:visited{text-decoration:none}
a:hover{text-decoration:underline}
body{font:12px/1.5 \5b8b\4f53,arial}	
.container{margin:auto;width:538px;height:340px;overflow:hidden}
.hd{background:url(http://static.179.com/www/images/qq/hd.png) repeat-x;height:60px;padding-left:45px}
.wrap{height:243px;position:relative;background:#fff}
.wrap .login-now{position:absolute;top:55px;left:80px;width:349px;height:76px;background:url(http://static.179.com/www/images/qq/login_now.png) no-repeat;width:349px;height:76px}
.wrap .login-now .loading{position:absolute;left:115px;top:28px}
.wrap .login-now .pro{position:absolute;left:93px;top:65px}
.footer{background:#f5f5f5;height:37px;line-height:37px;border-top:1px solid #e0e0e0;color:#c3c3c3;text-align:right;padding:0 10px;overflow:hidden}
.footer a{color:#5181b4;margin:0 5px}
</style>
</head>

<body>
<div class="container">
	<div class="hd"><img src="http://static.179.com/www/images/qq/logo.png" /></div>
	<div class="wrap">
		<div class="login-now">
			<p class="loading"><img src="http://static.179.com/www/images/qq/loading.gif" /></p>
			<p class="pro"><img src="http://static.179.com/www/images/qq/pro.gif" /></p>
		</div>
	</div>
	<div class="footer"></div>
</div>
<input type="hidden" id="QQ_vdcID" name="QQ_vdcID" value="<?php echo isset($QQ_vdcID)?$QQ_vdcID : '';?>"/>
<input type="hidden" id="QQ_password" name="QQ_password" value="<?php echo isset($QQ_password)?$QQ_password : '';?>"/>
<input type="hidden" id="QQ_error" name="QQ_error" value="<?php echo isset($QQ_error)?$QQ_error : '';?>"/>
</body>
</html>

