<?php
define('SYSLIB', dirname(dirname(dirname(__FILE__))).'/syslib/');
define('BASEPATH' , dirname(dirname(__FILE__)));
define('ROOTPATH' , dirname(dirname(dirname(__FILE__))));
define('APPPATH' , '');
require_once(SYSLIB . '/config/database.php');

//道具配置
$prop_array = array(
    '17'   	   => '幸运奶牛',
	'9'    	   => '富贵树',
	'5'    	   => '幸运聚宝盆',
	'4164'     => '情书',
	'4112'     => '比基尼',
	'4118'     => '抱抱熊',
	'4113'     => '水晶鞋',
	'4105'     => '蓝色妖姬',
	'4117'     => '耍帅',
	'4101'     => '钻戒'
);

function getDBConfig($db , $params){ 
	return $db[$params];
}