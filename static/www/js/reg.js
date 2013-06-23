jQuery.validator.setDefaults({
	  debug: false,
	  success: "valid",
	  errorElement: "em",
});

$(document).ready(function(){	
	$('#reg_submit').click(function(){
		$("#reg_form").submit();
	})	
})