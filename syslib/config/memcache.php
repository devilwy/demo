<?php
require(SYSLIB.'config.php');
if($current_host == 'localhost'){
	return array(
		0 => array('ip'=>'127.0.0.1','port'=>11211),
	);
}elseif($current_host == 'test'){
	return array(
		0 => array('ip'=>'127.0.0.1','port'=>11211),
	);
}else{ 
	return array(
		0 => array('ip'=>'127.0.0.1','port'=>11211),
	);
}