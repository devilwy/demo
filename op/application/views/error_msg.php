<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="<?php echo $time;?>" url="<?php echo $url;?>" />
<title>无标题文档</title>
<script type="text/javascript">
var href = "<?php echo $url;?>";
window.setTimeout("tourl('"+href+"')", "<?php echo $time*1000;?>");

function tourl(url){
	window.location.href = url;
}
</script>
</head>

<body>
<div style="border:1px solid #f00;"><?php echo $msg;?></div>
</body>
</html>
