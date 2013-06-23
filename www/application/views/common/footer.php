
  <!--========== footer ===============-->
  <footer>
    
  </footer>
  <!--========== footer end ===============--> 
</div>
<?php
	$js_str_footer = '';
	if(is_array($foot_jsarr) && !empty($foot_jsarr)){
		foreach($foot_jsarr as $v){
			$js_str_footer .= "<script language='javascript' src='".base_url()."static/js/".$v."'></script>";
		}
	}
?>
<?php echo isset($js_str_footer) ? $js_str_footer : "";?>
</body>
</html>