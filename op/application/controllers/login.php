<?php
class Login extends Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}
	
	public function index(){
		$data = $this->input->post();
		if(!empty($data)){
			$result = $this->op->login($data);
			echo json_encode($result);
		}
		else{
			if($this->op->rem == 1)
				$data['adminname'] = $this->op->remname;
			else
				$data['adminname'] = '';
			$this->load->view('login/v_index',$data);
		}
	}
	
	
	
}