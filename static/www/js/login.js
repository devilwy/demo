	jQuery.validator.setDefaults({
		  debug: false,
		  success: "valid",
		  errorElement: "em",
	});
	
	$(document).ready(function(){
		$('#login_submit').click(function(){
			$('#login_form').submit();
		})

	    $("#login_form").validate({
	        submitHandler: function(form){
				form.submit();
	        },
	        errorPlacement:function(error,element){
	        	element.parent().find('span').html(error);
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
	          "seccode":{
              					required:true,
	           					remote: {
	           			            data: {
	           			              second:function(){
	        					   			return $.trim($('#second').val());
	        				   		  },
									  ajax:1,
	           			            },
	           			            url: AJAXBASEURL+'common/seccode/',
	           			            type: "post",
	           			        }

	              	 },
	        },
	        messages: {
	          "username":{
								required:'请输入用户名',
	          			 },
	          "password":{
	           					required:'请填写密码',
								minlength:'密码太短啦，至少要6位哦',
								mixlength:'密码太长啦，不要超过20位哦',
	             		 },
	          "seccode":{
	               				required:'请输入验证码',
	               				equalTo:'两次输入的密码不一致',
	                 	},             		
	         }
	    });		
	})