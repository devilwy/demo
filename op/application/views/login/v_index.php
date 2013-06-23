<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>暖阳工作室后台管理</title>
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_DOMAIN;?>op/css/style.css" />
<script src="<?php echo STATIC_DOMAIN;?>common/js/define.js"></script>
<script  src="<?php echo STATIC_DOMAIN;?>op/js/jquery.min.js"></script>
<script  src="<?php echo STATIC_DOMAIN;?>op/js/ddaccordion.js"></script>
<script>
ddaccordion.init({
	headerclass: "submenuheader", //Shared CSS class name of headers group
	contentclass: "submenu", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["suffix", "<img src='images/plus.gif' class='statusicon' />", "<img src='images/minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>
<script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>op/js/jconfirmaction.jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.ask').jConfirmAction();
	});
</script>

<script  src="<?php echo STATIC_DOMAIN;?>op/js/niceforms.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo STATIC_DOMAIN;?>op/css/niceforms-default.css" />
</head>
<body>
<div id="main_container">
	<div class="header_login">
    <div class="logo"><a style="cursor:pointer;"><img src="<?php echo STATIC_DOMAIN;?>op/images/logo.gif" alt="" title="" border="0" /></a></div>
    </div>
    <div class="login_form">
         <h3>后台管理平台登陆</h3>
         <a style="cursor:pointer;" class="forgot_pass">忘记密码?</a> 
         <form action="<?php echo site_url("login/index")?>" method="post" class="niceform" id="loginForm">
			 <fieldset>
             	<dl>
                	<dt><label for="email">用户名:</label></dt>
                    <dd><input type="text" name="username" id="username" size="54" /></dd>
                </dl>
                <dl>
                	<dt><label for="password">密码:</label></dt>
                	<dd><input type="password" name="password" id="password" size="54" /></dd>
                </dl>
                <dl>
                	<dt><label></label></dt>
                    <dd>
                    	<input type="checkbox" name="remember" id="remember" /><label class="check_label">记住我</label>
                    	<span style="margin-left:30px;" id="errorMsg" class="errorMsg"></span>
                    </dd>
                </dl>
                <dl class="submit">
                    <input type="button" name="submit" id="submit" value="登陆" />
                </dl>
           	</fieldset>      
         </form>
     </div>  
     <div class="footer_login">
    	<div class="left_footer_login">IN ADMIN PANEL | Powered by <a href="http://indeziner.com">INDEZINER</a></div>
    	<div class="right_footer_login"><a href="http://indeziner.com"><img src="<?php echo STATIC_DOMAIN;?>op/images/indeziner_logo.gif" alt="" title="" border="0" /></a></div>
    </div>
</div>	
<script src="<?php echo STATIC_DOMAIN;?>common/js/jquery.validate.js"></script>
<script>
	$(document).ready(function(){
		jQuery.validator.setDefaults({
			  debug: false,
			  success: "valid",
			  errorElement: "span",
		});

		$('#submit').click(function(){
			$('#loginForm').submit();
		});
		
	    $("#loginForm").validate({
	        submitHandler: function(form){
				var url = $('#loginForm').attr('action');
				var data = $('#loginForm').serialize();
				$.ajax({
					type:'POST',
					url:url,
					dataType:'json',
					data:data,
					error:function(){},
					success:function(return_data){
						if(parseInt(return_data.error) === 0)
							window.location.href = OP_DOMAIN+"index";
						else
							$('#errorMsg').html(return_data.msg);
					}
				});	
				return false;				
	        },
	        errorPlacement:function(error,element){
	        	$('#errorMsg').html(error);
	        },
	        rules: {
	          "username":{
	           					required:true,
	           		     },
		      "password":{
	         					required:true,
	         					minlength:6,
	         					maxlength:20,
	         			 },
	        },
	        messages: {
	          "username":{
								required:'请输入用户名',
	          			 }, 
       		  "password":{
       							required:'请输入管理员密码',
       							minlength:'密码长度为6-20位',
       							maxlength:'密码长度为6-20位',
       			 		 },           		
	        },
	    });			

				
	})
</script>
</body>
</html>