<?php
	$js_str_header = '';
	if(!empty($head_jsarr)){
		
		foreach($head_jsarr as $v){
			$js_str_header .= "<script language='javascript' src='".base_url()."static/js/".$v."'></script>";
		}
	}
	$css_str = '';	
	if(!empty($cssarr)){
		foreach($cssarr as $v){
			$css_str .= "<link rel='stylesheet' type='text/css' href='".base_url()."static/css/".$v."' />";
		}
	}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="e87b4a31495ff2e7" />
<title><?php echo $title;?></title>
<?php echo $css_str;?>
<?php echo isset($js_str_header) ? $js_str_header : "";?>
<!–[if IE 6]>
<script type="text/javascript" src="<?php echo base_url();?>static/js/DD_belatedPNG_0.0.8a-min.js"></script>
<!–[if IE]>
<script src="<?php echo base_url();?>static/js/html5.js"></script>
<![endif]–>
<script>
	DD_belatedPNG.fix('img,.header-content,.pwd-btn,.reg,.regbody');
</script>
<![endif]–>
</head>
<body>