<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://static.179.com/common/js/jquery-1.7.1.min.js" ></script>
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
</body>
</html>

<script>
$(document).ready(function(){
	$.ajax({
		type:'POST',
		url:"/qqapi/ajax_qq_callback",
		data:{'code':"<?php echo $code;?>"},
		dataType:'json',
		error:function(){
		},
		success:function(html){
			window.location.href=html.url;
		}
	});
});
</script>