
jQuery.validator.setDefaults({
	  debug: false,
	  success: "valid",
	  errorElement: "em",
});



$(function() {
	$('#info_submit').click(function(){
		$('#info_form').submit();
	})
});

