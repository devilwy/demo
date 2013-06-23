<?php
	/*
	 * user 控制器
	 * 2012 10 17 温勇
	 */
	session_start();
	class User extends Controller{
		private $userinfo = array();
		public function __construct(){
			parent::__construct();
			if($this->muser->islogin == 1)
				$this->userinfo = $this->muser->getuserinfo($this->muser->uid);
		}
		
		
		/*
		 * 2012 10 21 个人中心首页
		 * 温勇
		 */
		public function index(){
			if($this->muser->islogin == 1){
				$data = array(
								'username' => $this->muser->username,
								'uid' => $this->muser->uid,
								'utype' => $this->muser->utype,
						     );
				$this->load->view('user/index',$data);	
			}
			else
				redirect(site_url('user/login'));
						
		}
		
		/*
		 * 用户登录
		 * 2012 10 17
		 */
		public function login(){
			if($this->muser->islogin == 1)
				redirect(site_url('user'));
			else{
				if(empty($_POST)){
					$this->load->view('user/login');
				}
				else{
					$data = $this->input->post();
					$result = $this->muser->login($data);
					if($result['error'] == 0)
						redirect(site_url('user'));
					else{
						//**用户登录失败
						$error_data = array('error'=>1,'return_url'=>site_url('/'),'msg'=>$result['msg']);
						echo '<pre>';print_r($error_data);exit;
						//errorMsg($error_data);					
					}
						
				}
			}
		}
		
		/*
		 * 用户注册
		 * 2012 10 18
		 */
		public function reg(){
			if(empty($_POST)){
				$data['utype'] = $this->uri->segment(3);
				$data['utype_str'] = isset($this->common_conf['usertype'][$data['utype']])?$this->common_conf['usertype'][$data['utype']]:'student';
				$this->load->view('user/reg',$data);				
			}
			else{
				$data = $this->input->post();
				$result = $this->muser->register($data);
				if($result['error'] == 0){
					//**用户注册成功
					redirect(site_url('user/reg_emailconfirm/?email='.$data['email'].'&username='.$data['username']));
				}
				else{
					//**用户注册失败
					$error_data = array('error'=>1,'return_url'=>site_url('/'),'msg'=>$result['msg']);
					errorMsg($error_data);
				}
			}
		}
		
		/*
		 * 用户预注册成功 
		 * 
		 */
		public function reg_emailconfirm(){
			$error_data =  array('error' => 0,'return_url' => '','msg' => '');
			$data = $_GET;
			$email = isset($data['email'])?$data['email']:'';
			if(!empty($email)){
				$resend = isset($data['resend'])?intval($data['resend']):0;
				$this->load->view('user/reg_1',$data);	
			}
			else{
				$error_data = array('error' => 1,'return_url' => site_url('/'),'msg' => '非法访问');
				$this->load->view('common/error',$error_data);
			}	
		}
		/*
		 * 用户注册邮箱验证
		 * 2012 10 19
		 */
		public function emailConfirm(){
			$data = $_GET;
			$error_data = array('error' => 0,'return_url' => '','msg' => '');
			if(!empty($data)){
				$data['type'] = 'regconfirm';
				$check_reg = $this->muser->check_reg($data);
				if($check_reg['error'] == 0){
					$this->load->view('user/reg_2',$data);					
				}
				else
					$error_data = array('error' => 1,'return_url' => site_url('user/reg'),'msg' => $check_reg['msg']);
			}
			else{
				$error_data = array('error' => 1,'return_url' => site_url('/'),'msg' => '非法访问');
			}
			
			
			if($error_data['error'] == 1)
				$this->load->view('common/error',$error_data);
		}
		
		/*
		 * 用户退出
		 * 2012 10 21 
		 */
		public function logout(){
			$this->muser->logout();
			redirect(site_url('user/login'));
		}
		
		/*
		 * 用户修改密码
		 * 2012 10 21
		 */
		public function changepwd(){
			if($this->muser->islogin == 1){
				$data = $this->input->post();
				if(empty($data)){
					$this->load->view('user/changepwd',$data);					
				}
				else{
					$result = $this->muser->changepwd($data);
					if($data['ajax']==1)
						echo json_encode($result);
				}
			}
			else
				redirect(site_url('user/login'));
		}
		
		/*
		 * 个人设置
		 * 2012 10 21
		 */
		public function userinfo(){
			if($this->muser->islogin == 1){
				$data = $this->input->post();
				if(empty($data)){
					$data['username'] = $this->muser->username;
					$data['common_conf'] = $this->common_conf;
					$data['userinfo'] = $this->userinfo;
					echo '<pre>';print_r($this->userinfo);exit;
					$this->load->view('user/userinfo',$data);					
				}
				else{
					$data['uid'] = $this->muser->uid;
					$result = $this->muser->update_userinfo($data);
					if($result['error'] == 0)
						redirect(site_url('user'));
					else{
						redirect(site_url('user/userinfo'));
					}
				}
			}
			else
				redirect(site_url('user/login'));
		}
		
		/*
		 * 数据验证
		 * 2012 10 21
		 */
		public function ajaxcheck(){
			$data = $this->input->post();
			if(!empty($data)){
				header("Content-Type:text/html;charset=utf-8");
				$type = isset($data['type'])?$data['type']:'username';
				$result = $this->muser->check_reg($data);
				if($result['error'] == 0)
					echo 'true';
				else
					echo 'false';
			}
			else
				echo 'false';
		}
		
		/*
		 *  上传头像
		 */
		public function uploadHeadPic(){
			@header("Expires: 0");
			@header("Cache-Control: private, post-check=0, pre-check=0, max-age=0", FALSE);
			@header("Pragma: no-cache");
		
			$head_image = array(
					'size' => 500000,
					'size_intro' => '文件大小超过500K',
					'resize' => array(),
					'JPEG_Quality' => 75,
					'file_ext' => array('.jpg','.jpeg','.png','.jpg'),
			);
			$this->load->helper('headpic');
			$imgUpload = new ImgUpload();
			$uid = $this->muser->uid;
			$imgUpload->getPath($uid,1);
			$upload_re = $imgUpload->upload_image($_FILES['Filedata'],$head_image,$uid,0);
			if($upload_re['error'] == 0){
				$pic_id = $imgUpload->date_uid;
				$pic_abs_path = $upload_re['msg'];
				echo '<script type="text/javascript">window.parent.hideLoading();window.parent.buildAvatarEditor("'.$pic_id.'","'.$pic_abs_path.'","photo");</script>';
			}
			else
				echo '<script type="text/javascript">window.parent.hideLoading();alert("'.$upload_re['msg'].'");</script>';
		}
		
		
		
		/*
		 * 清除缓存
		 */
		public function flut_mem(){
			$this->muser->flut_mem();
		}
		
		
		
		
	

	
	
	
	}




?>