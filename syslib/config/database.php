<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require(SYSLIB.'config.php');

$active_group = 'default';
$active_record = TRUE;
$current_host = "localhost";
switch ($current_host){
	case 'localhost':
		$host = 'localhost';
		$user = 'root';
		$password = '';
		break;
	case 'formal':
		$host = 'localhost';
		$user = 'root';
		$password = 'wyshj11202315';
		break;
}
$db['dbr']['hostname'] = $host;
$db['dbr']['username'] = $user;
$db['dbr']['password'] = $password;
$db['dbr']['database'] = 'my';
$db['dbr']['dbdriver'] = 'mysql';
$db['dbr']['dbprefix'] = '';
$db['dbr']['pconnect'] = false;
$db['dbr']['db_debug'] = TRUE;
$db['dbr']['cache_on'] = FALSE;
$db['dbr']['cachedir'] = '';
$db['dbr']['char_set'] = 'utf8';
$db['dbr']['dbcollat'] = 'utf8_general_ci';
$db['dbr']['swap_pre'] = '';
$db['dbr']['autoinit'] = false;
$db['dbr']['stricton'] = FALSE;


$db['dbw']['hostname'] = $host;
$db['dbw']['username'] = $user;
$db['dbw']['password'] = $password;
$db['dbw']['database'] = 'my';
$db['dbw']['dbdriver'] = 'mysql';
$db['dbw']['dbprefix'] = '';
$db['dbw']['pconnect'] = false;
$db['dbw']['db_debug'] = TRUE;
$db['dbw']['cache_on'] = FALSE;
$db['dbw']['cachedir'] = '';
$db['dbw']['char_set'] = 'utf8';
$db['dbw']['dbcollat'] = 'utf8_general_ci';
$db['dbw']['swap_pre'] = '';
$db['dbw']['autoinit'] = false;
$db['dbw']['stricton'] = FALSE;