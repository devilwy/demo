<?php
require_once SYSLIB.'libraries/base.lib.php';

/**
 * controller
 */
class Controller extends baseController {
	public $common_conf;
	public function __construct() {
		parent :: __construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->model('muser');
		$this->config->load('common');
		$this->common_conf = $this->config->item('common');
	}

}
