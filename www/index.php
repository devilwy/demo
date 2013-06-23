<?php
define('ENVIRONMENT', 'development');
define('ROOTPATH', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));
define('APPPATH', ROOTPATH.'application/');
require_once '../syslib/config/define.php';
require_once BASEPATH.'core/public.php';
require_once BASEPATH.'core/CodeIgniter.php';
/* End of file index.php */
/* Location: ./index.php */