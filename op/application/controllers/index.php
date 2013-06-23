<?php
class Index extends Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}
	
	public function index(){
		echo $this->op;exit;
	}
	
	
	
}