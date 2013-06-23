	jQuery.validator.setDefaults({
		  debug: false,
		  success: "valid",
		  errorElement: "em",
	});
	
	$(document).ready(function(){
		$('#pwd_submit').click(function(){
			$('#pwd_form').submit();
		})

	    $("#pwd_form").validate({
	        submitHandler: function(form){
	    		var url = AJAXBASEURL+'user/changepwd';
	    		var data = $('#pwd_form').serialize();
	    	  	$.ajax({
	    		  	type: "post",
	    		  	data:data,
	    		  	url: url,
	    		  	dataType:'json',
	    		  	success: function(data){
	    			 	if(parseInt(data.error)==0){
	    			 		window.location.href = WWWBASEURL+'user';
	    			 	}
	    			 	else{
	    			 		alert(data.msg);
	    			 	}	
	    		  	}
	    	  	});	
	        },
	        errorPlacement:function(error,element){
	        	element.parent().find('span').html(error);
	        },
	        rules: {
	          "old":{
	           					required:true,
	           					minlength:6,
	           					maxlength:20,
	           					remote: {
	           			            data: {
	           			              password:function(){
	        					   			return $.trim($('#old').val());
	        				   		  },
	        					   	  username:function(){
	        					   			return $.trim($('#username').val());
	        				   		  },
	           			              type:'login_password',
	           			              ajax:1,
	           			            },
	           			            url: AJAXBASEURL+'user/ajaxcheck/',
	           			            type: "post",
	           			        }
	           		 },
	          "new":{
	           					required:true,
	           					minlength:6,
	           					maxlength:20,
	           		},
	          "rnew":{
              					required:true,
              					minlength:6,
              					maxlength:20,
              					equalTo: "#new",
	              	 },
	        },
	        messages: {
	          "old":{
								required:'请输入昵称',
								minlength:'密码太短啦，至少要6位哦',
								mixlength:'密码太长啦，不要超过20位哦',
								remote:'密码错误',
	          				   },
	          "new":{
	           					required:'请填写密码',
								minlength:'密码太短啦，至少要6位哦',
								mixlength:'密码太长啦，不要超过20位哦',
	             				},
	          "rnew":{
	               					required:'请重复输入你的密码',
									minlength:'密码太短啦，至少要6位哦',
									mixlength:'密码太长啦，不要超过20位哦',
	               					equalTo:'两次输入的密码不一致',
	                 		   },             		
	         }
	    });		
	})