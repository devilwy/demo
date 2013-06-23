<?php
if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
			break;

		case 'testing':
		case 'production':
			error_reporting(0);
			break;

		default:
			exit('The application environment is not set correctly.');
	}
}

define('SYSLIB', str_replace('config/define.php', '', str_replace('\\', '/', __FILE__)));
define('STATIC_DOMAIN', 'http://static.bdfun.cn/');
define('OP_DOMAIN', 'http://op.bdfun.cn/');
define('WWW_DOMAIN', 'http://www.bdfun.cn/');
define('UPLOAD_DOMAIN', 'http://upload.bfun.cn/');

$system_path 		= SYSLIB.'system';
$application_folder = 'application';

// Set the current directory correctly for CLI requests
if (defined('STDIN')){
	chdir(dirname(__FILE__));
}

if (realpath($system_path) !== FALSE){
	$system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path)){
	exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

date_default_timezone_set('PRC');