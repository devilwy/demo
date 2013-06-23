<?php
return array(
	'spid'  	 => '7851',								// SP编号。(由MLINK端提供给SP端)
	'spsc'   	 => '00',							    // SP服务代码，可选参数，用于区分SP不同的业务。同一个SP的不同业务可以设定不同的上下行路由，业务代码由MLINK设定。默认情况取SP的缺省服务代码。
	'sppassword' => '123456',
	'sa'         => '10',
	'dc'         => '15',
	'host'       => 'esms.etonenet.com',
	'port'       => '80'
);
?>