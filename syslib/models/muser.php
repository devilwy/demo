<?php 
require_once SYSLIB.'libraries/dbmodel.lib.php';

class MUser extends Dbmodel{
	public $salt;
	public $uid;
	public $username;
	public $islogin;
	public $userType;
	private $domin;
	private $mem;
	private $common_conf;
	private $mem_prefix;
	public function MUser() {
        parent::__construct();
        $this->salt = 'LoginKey_ckgsb';
        $this->domin = 'www.ckgsb.com';
        $this->check_login();
        $this->config->load('common');
        $this->common_conf = $this->config->item('common'); 
        

        $this->config->load('mem_prefix');
        $this->mem_prefix = $this->config->item('mem_prefix'); 
        
    }
    
    
    
    public function login($data = array()){
//    	$this->dbr->from("");
		$result = array('error' => 1,'msg' => '');
		if(isset($data['username'])&&isset($data['password'])){
			$username = trim($data['username']);
			$password = md5(trim($data['password']));
			$type = isset($data['type'])?intval($data['type']):1;
            $this->dbr->from('user_userlogin')->where('loginname',$username)->where('password',$password)->where('status',1)->limit(1);
			$re = $this->dbr->get()->row_array();
			if(!empty($re)){
				//**获取上次登录时间
				$p_logintime = $re['logintime'];
			 	$type = $re['type'];
				//**更新userlogin表
	    		$loginip = publicfunction_getuserip();
	    		$logintime = date('Y-m-d H:i:s');
	    		$uid = $re['id'];
	    		$update_data = array('loginip'=>$loginip,'logintime'=>$logintime);
	    		if($this->dbr->from('user_userlogin')->where('loginname',$username)->set($update_data)->update()){
	    			$this->setLogin($username,$type,$uid);
	    			/*if(strtotime($p_logintime) < strtotime(date('Y-m-d 00:00:00')) && strtotime($logintime) > strtotime(date('Y-m-d 00:00:00'))){
	    				publicfunction_insertintegral($uid , 1 , 1 , 1 , 0 , 1004 , '用户登录加1积分');
	    			}*/
	    			$result = array('error' => 0,'msg' => '用户登录成功');
	    		}
	    		else
	    			$result = array('error' => 1,'msg' => '用户登录表更新失败');
			}
			else 
				$result = array('error' => 1,'msg' => '用户登录失败');
		}
		return $result;
    }
    
    
    
    public function check_reg($data = array()){
    	$type = isset($data['type'])?$data['type']:'';
    	$result = array('error' => 0,'msg' => '');
    	if(!empty($type)){
			switch($type){
				case "nickname";
					$nickname = isset($data['nickname'])?trim($data['nickname']):'';
					$this->dbr->from("user_userinfo")->where('nickname',$nickname)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(empty($re))
						$result = array('error' => 0,'msg' => '');
					else
						$result = array('error' => 1,'msg' => '昵称已存在');
					break;
				case "username":
					$username = isset($data['username'])?trim($data['username']):'';
					$this->dbr->from("user_userlogin")->where('loginname',$username)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(empty($re))
						$result = array('error' => 0,'msg' => '');
					else{
						$userinfo_val = isset($data['userinfo_val'])?intval($data['userinfo_val']):0;  //**用户修改详情页 进行验证时该参数为1
						if($userinfo_val == 1){
							$now_loginname = isset($re['loginname'])?$re['loginname']:'';
							if($now_loginname == $username)
								$result = array('error'=>0,'msg'=>'');
						}
						else
							$result = array('error' => 1,'msg' => '用户已存在');
					}
					break;
			    case "nickname_change":
			    		$uid = isset($this->uid)?$this->uid:0;
			    		$type = isset($this->userType)?$this->userType:0;
			    		$userinfo = $this->getUserInfo($uid, $type);
			    		$nickname = isset($data['nickname'])?trim($data['nickname']):'';
			    		if($nickname == $userinfo['nickname']){
			    			$result = array('error' => 0,'msg' => '');
			    		}
						else{$this->dbr->from("user_userinfo")->where('nickname',$nickname)->limit(1);
							$re = $this->dbr->get()->row_array();
							if(empty($re))
								$result = array('error' => 0,'msg' => '');
							else
								$result = array('error' => 1,'msg' => '昵称已存在');
						}
						break;					
				case "login_username":
					$username = isset($data['username'])?trim($data['username']):'';
					$this->dbr->from("user_userlogin")->where('loginname',$username)->where('status',1)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(!empty($re))
						$result = array('error' => 0,'msg' => '');
					else
						$result = array('error' => 1,'msg' => '用户不存在或未验证');
					break;
				case "login_password":
					$utype = isset($data['utype'])?trim($data['utype']):1;
					$username = isset($data['username'])?trim($data['username']):$_SESSION['userlogin_'.$utype]['username'];
					$password = isset($data['password'])?md5(trim($data['password'])):'';
					$this->dbr->from("user_userlogin")->where('loginname',$username)->where('password',$password)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(!empty($re))
						$result = array('error' => 0,'msg' => '');
					else
						$result = array('error' => 1,'msg' => '用户名或密码错误');
					break;
				case "telephone";
					$telephone = isset($data['telephone'])?trim($data['telephone']):'';
					$this->dbr->from("user_userlogin")->where('telephone',$telephone)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(empty($re))
						$result = array('error' => 0,'msg' => '');
					else{
						$userinfo_val = isset($data['userinfo_val'])?intval($data['userinfo_val']):0;  //**用户修改详情页 进行验证时该参数为1
						if($userinfo_val == 1){
							$now_telephone = isset($re['telephone'])?$re['telephone']:'';
							if($now_telephone == $telephone)
								$result = array('error'=>0,'msg'=>'');
						}else{ 
							publicfunction_insertintegral($this->uid , 1 , 5 , 5 , 0 , '1004' , "绑定手机加5积分");
							$result = array('error' => 1,'msg' => '用手机已绑定');
						}
					}
					break;
				case 'brand_username':
					$username = isset($data['username'])?trim($data['username']):$_SESSION['userlogin']['username'];
					$this->dbr->from("brand_info")->where('login_brand',$username)->limit(1);
					$re = $this->dbr->get()->row_array();
					if(empty($re))
						$result = array('error' => 0,'msg' => '');
					else
						$result = array('error' => 1,'msg' => '账户已存在');
					break;
				case 'muser':
						$username = isset($data['username'])?trim($data['username']):'';
						$this->dbr->from('user_userlogin');
						$this->dbr->where('loginname',$username);
						$this->dbr->where('type <>',4);
						$this->dbr->limit(1);
						$re = $this->dbr->get()->row_array();
						if(!empty($re))
							$result = array('error' => 0,'msg' => '');
						else
							$result = array('error' => 1,'msg' => '账户不存在');
					break;
				case 'regconfirm':
					$reg_time = $data['time'];
					$username = isset($data['username'])?trim($data['username']):'';
					$this->dbr->from('user_userlogin');
					$this->dbr->where('loginname',$username);
					$this->dbr->limit(1);
					$re = $this->dbr->get()->row_array();
					if($re['status'] == $this->common_conf['userstatu']['unconfirm']['v']){
						if(time() - $reg_time > 7200)
							$result = array('error' => 1,'msg' => '邮箱验证已过期,<a style="cursor:pointer;" id="resend">点击我，重新发送确认邮件</a>');
						else{
							$key = isset($data['key'])?$data['key']:'';
							if($key != md5($username.$this->salt.$reg_time))	
								$result = array('error' => 1,'msg' => '验证信息不正确');
							else{
								//*验证成功
								$update_data = array('status' => $this->common_conf['userstatu']['normal']['v']);
								$this->dbw->from("user_userlogin")->where('loginname',$username)->set($update_data)->update();
								$result = array('error' => 0,'msg' => '邮箱验证成功');
							}
						}
					}
					else
						$result = array('error' => 1,'msg' => '该邮箱以验证');
					break;
				default:
					
					break;
			}
    	}
    	return $result;
    }
    
    /*
     * 用户注册写入数据
     * 2012 03 24
     * wenyong
     */
    public function register($data = array()){
    	$result = array('error' => 1,'msg' => 'failed');
    	if(!empty($data)){
    		$ip = publicfunction_getuserip();
    		$regtime = date('Y-m-d H:i:s');
    		$login_data = array(
    				'loginname' => $data['username'],
    				'password'  => md5($data['password']),
    				'type' => $data['utype'],
     				'email' => $data['email'],
    				'regtime' => $regtime,
    				'regip' => $ip,
    				'logintime' => date('Y-m-d H:i:s'),
    				'loginip' => $ip,
    				'invitecode' => $data['invitecode'],
			);
    		
  		
		    if($this->dbw->from("user_userlogin")->set($login_data)->insert()){
    			$uid = $this->dbw->insert_id();
				$info_data = array(				
					'uid' => $uid,
					'username' => $data['username'],
					'realname' => $data['realname'],				
    				'email' => $data['email'],		   
				);

    			if(!$this->dbw->from("user_userinfo")->set($info_data)->insert())	
					$result = array('error' => 1,'msg' => 'userinfo表失败');	
				else{
					if($this->sendEmail($data['email'],$data['username'])){
						$result = array('error' => 0,'msg' => '用户注册成功');
					}
				    else
						$result = array('error' => 1,'msg' => '确认邮件发送失败');
				}

			}
			else 
				$result = array('error' => 1,'msg' => 'login表失败');
		}
		return $result;
	}
    
    /*
     * 用户邮箱验证
     * 2012 03 25
     * wenyong
     */

    
    /*
     * 用户登录态验证
     */
    public function check_login(){
		$str = get_cookie('login_token');
		$data = @unserialize($str);
		$token = isset($data['token'])?$data['token']:'';
		$username = isset($data['username'])?$data['username']:'';
		$uid = isset($data['uid'])?$data['uid']:'';
		$utype = isset($data['utype'])?$data['utype']:'';
		if($token == md5($username.$this->salt.$uid)){
			//**用户登录
			$this->islogin = 1;
			$this->username = $username;
			$this->uid = $uid;
			$this->utype = $utype;
		}
		else{
			$this->islogin = 0;
			$this->username = '';
			$this->uid = 0;
			$this->utype = 0;			
		}
		
		
    }
    
    public function logout(){
    	set_cookie('login_token','',-1,$this->domin);
    }
    
    
	public function sendEmail($email = '',$username = ''){
    	$time = time();
    	$key = md5($username.$this->salt.$time);
    	$data['username'] = $email;
    	$data['url'] = site_url('/user/emailConfirm?username='.$username)."&key=".$key."&time=".$time;
    	$data['urlshow'] = site_url('/user/emailConfirm');
    	$content = $this->load->view("/user/v_regemail" , $data , true);
		$mail_error = publicfunction_sendmail($email,'长江商学院注册验证',$content);	
		return $mail_error;
	}  

	private function setLogin($username = '',$type = 0,$uid = 0){
		$data = array(
						'token' => md5($username.$this->salt.$uid),
						'username' => $username,
						'uid' => $uid,
						'utype' => $type
				     );
		$str = serialize($data);
		set_cookie('login_token',$str,86400,WWW_DOMAIN);
		echo WWW_DOMAIN;exit;
	}
	
	
	

	


    
  	/**
     * 查询用户信息
     * @param $uid
     * @param $uidtype
     * **/
    public function getUserInfo($uid=0){
    	$uid = max(0 , intval($uid));
    	$userinfo = array();
    	if(!empty($uid)){
    		$mem_key = $this->mem_prefix['userinfo'].$uid;
    		$mem_userinfo = Mem::get($mem_key);
    		if(!empty($mem_userinfo))
    			$userinfo = $mem_userinfo;
    		else{
    			//$re = $this->dbr->from('user_userinfo')->where('uid',$uid)->get()->row_array();
    			$sqlstr = 'select * from user_userlogin as a left join user_userinfo as b on a.id = b.uid where a.id = '.$uid;
    			$re = $this->dbr->query($sqlstr)->row_array();
    			$result = $this->getUserInfo_format($re);
    			$userinfo = $result;
    			Mem::set($mem_key,$userinfo);
    		}
    	}
    	return $userinfo;
    }
    
    //**根据用户名获取用户
	public function getUserByName($username = '',$nickname = '', $realname = ''){
		$userinfo = array();
		if(!empty($username)){
			$userinfo = $this->dbr->from('user_userlogin')->where('loginname',$username)->get()->row_array();		
		}
		else if(!empty($nickname)){
			$userinfo = $this->dbr->from('user_userinfo')->where('nickname',$nickname)->get()->row_array();
		}else if($realname){ 
			$userinfo = $this->dbr->from('user_userinfo')->where('realname',$realname)->get()->row_array();
		}
		return $userinfo;
	}    
    
        
    public function update_userinfo($data = array()){
    	$result = array('error' => 1,'msg' => '');
    	if(!empty($data)){
    		$pic = serialize($this->getheadpicarr($data['head_image_path']));
    		$data['userinfo'] = array(
    									//'nickname' => $data['nickname'],
    									//'realname' => $data['realname'],
    									'sign' => $data['sign'],
    									//'qq' => $data['qq'],
    									//'msn' => $data['msn'],
    									//'description' => $data['description'],
										//'birth' => $data['brithday'],
    									//'addr1' => $data['addr1'],
    									//'addr2' => $data['addr2'],
    									'headpic' => $pic,
    									'gender' => $data['sex'],
    									'mobile' => isset($data['mobile'])?$data['mobile']:'',
    								  );
    		$uid = $data['uid'];
    		//$userType = $data['userType'];
    		//$userinfo = $this->getUserInfo($uid, $userType);
    		//$now_email = isset($userinfo['username'])?$userinfo['username']:'';

    		if(!$this->dbw->from('user_userinfo')->set($data['userinfo'])->where('uid',$uid)->update())
    			$result = array('error' => 1,'msg' => '用户信息更新失败');
    		else{
    			$result = array('error' => 0,'msg'=>'success');
    			//**若用户第一次上传头像
    			/*if(empty($userinfo['headpic_a']) && !empty($data['userinfo']['headpic'])){
    				publicfunction_insertintegral($uid , 1 , 2 , 2 , 0 , 1006 , '个人头像上传加2积分');
    			}
    			
    			//**若用户完善所有资料
    			$bs = 1;
    			foreach($userinfo as $v){
    				if(empty($v))
    					$bs = 0;
    			}
    				
    			$as = 1;
    			foreach($data['userinfo'] as $v){
    				if(empty($v))
    					$as = 0;
    			}
    			if($as == 1 && $bs == 0)
    				publicfunction_insertintegral($uid , 1 , 10 , 10 , 0 , 1007 , '全部个人资料完善加10积分');
    				
    			//**完善地址
    			if(empty($userinfo['addr1']) && !empty($data['userinfo']['addr1'])){
    				publicfunction_insertintegral($uid , 1 , 5 , 5 , 0 , 1005 , '常用地址完善加5积分');
    			}
    			
    			if(empty($userinfo['addr2']) && !empty($data['userinfo']['addr2'])){
    				publicfunction_insertintegral($uid , 1 , 5 , 5 , 0 , 1005 , '常用地址完善加5积分');
    			}
    			
    			if(isset($data['email'])&&isset($data['telephone'])){

    				// 若修改了邮箱 和 手机 需要 重新 进行绑定  更新 三张表  更新 用户状态为 未邮箱验证	  			
    				$userlogin_data = array('loginname' => $data['email'],'telephone' => $data['telephone']);
    				$userbase_data = array('loginname' => $data['email']);
    				$userinfo_data = array('username' => $data['email'],'isphone' => 1);
    				if($this->dbw->from('user_userlogin')->set($userlogin_data)->where('uid',$uid)->update()){
    					$this->dbw->from('user_baseinfo')->set($userbase_data)->where('uid',$uid)->update();
    					$this->dbw->from('user_userinfo')->set($userinfo_data)->where('uid',$uid)->update();
    					if($now_email != $data['email']){
    						$this->dbw->from('user_userlogin')->set('status',0)->where('uid',$uid)->update();
    						$this->logout();
    						if($this->sendEmail($data['email']))
    							$result = array('error' => 0,'msg' => 'email');
    						else
    							$result = array('error'=>1,'msg'=>'确认邮件发送失败');
    					}
    				}
    				else
    					$result = array('error'=>1,'msg'=>'更新用户登录表失败');
    			    // 手机 验证  暂时未添加
    			}*/	
    		}
    	}
    	return $result;
    }
    
    
    
    public function changepwd($data = array()){
    	$result = array('error' => 1,'msg' => '修改密码失败');
    	$uid = $this->uid;
    	if(!empty($data)&&$uid != false){
    		$password = isset($data['new'])?$data['new']:'';
    		if(!empty($password)){
    			if($this->dbw->from('user_userlogin')->set('password',md5(trim($password)))->where('uid',$uid)->update())
    				$result = array('error' => 0,'msg' => '修改密码成功');
    			else
    				array('error' => 1,'msg' => '修改密码失败');
    		}
    		else
    			$result = array('error' => 1,'msg' => '密码参数错误');
    	}
    	return $result;
    }
    
 
     
    
	//*获取用户头像
	public function getheadpic($headpic = ''){
		$arr = @unserialize($headpic);
		$result['small'] = isset($arr['_s'])?$arr['_s']:STATIC_DOMAIN."www/images/touxiang.gif";
		$result['big'] = isset($arr['_b'])?$arr['_b']:STATIC_DOMAIN."www/images/touxiang.gif";
		if(isset($arr['_s'])){
			$ext = strrchr($arr['_s'],'.');
			$arr1 = explode('_',$arr['_s']);
			$a1 = isset($arr1[0])?$arr1[0]:base_url()."static/images/touxiang";
			$result['ori'] = $a1.$ext;
			
			if(!empty($arr['_b'])){
				$result['mid'] = $a1.'_mid'.$ext;
			}
			else
				$result['mid'] = $result['big'];
		}
		return $result;
	}
	

	private function subtable($uid = 0){
		$t = substr(md5($uid),0,1);
		if(empty($t))
			$t = 1;
		return '_'.$t;
	}
	
	
	
	public function getUserInfo_format($userinfo = array()){
		$result = array();
		$userinfo['headpic'] = $this->getheadpic($userinfo['headpic']);
		$type = $userinfo['type'];
		foreach($this->common_conf['usertype'] as $k => $v){
			if($v['v'] == $type)
				$userinfo['type_str'] = $v['s'];
		}
		
		$result = $userinfo;
		return $result;
	}
	
	
	
	public function flut_mem(){
		Mem::flut();
	}
	
	//**获取用户列表
	public function getuserlist($data = array()){
		$page = isset($data['page'])&&!empty($data['page'])?intval($data['page']):1;
		$pagesize = isset($data['pagesize'])&&!empty($data['pagesize'])?intval($data['pagesize']):20;
		$type = isset($data['type'])&&!empty($data['type'])?intval($data['type']):0;  //**用类型 默认为全部用户
		
		$result = array(
							'list' => array(),
							'total' => 0
					   );
		
		if(!empty($page) && !empty($pagesize)){
			//**获取全部记录数
			$str = " from user_userlogin  as a left join user_userinfo as b on a.id = b.uid ";
			$where = '';
			$order = " order by a.regtime desc ";
			$limit = '';
			$where .= " where 1 and a.status <> 0 and a.status <> 2 ";
			if(!empty($type))
				$where .= " and a.type = ".$type;
			
			if(!empty($data['keyword']))
				$where .= " and (b.realname like '%".$data['keyword']."%' or a.loginname like '%".$data['keyword']."%')";
			
			$sqlstr = "select a.id ".$str.$where.$order.$limit;
			$re = $this->dbr->query($sqlstr)->result_array();
			$result['total'] = count($re);
			
			$start = ($page-1)*$pagesize;
			$limit = " limit ".$start.",".$pagesize;
			$sqlstr = 'select * '.$str.$where.$order.$limit;
			$re = $this->dbr->query($sqlstr)->result_array();
			
			foreach($re as $k => &$v){
				$v = $this->getUserInfo_format($v);
			}
			$result['list'] = $re;
					
		}
		return $result;
	}
	
	/*
	 * change user status
	 * @data 
	 */
	public function changeuser($data = array()){
		$uid = $data['id'];
		$type = $data['type'];
		$result = array('error' => 0,'msg' => '更新失败');
		$sqlstr = 'update user_userlogin ';
		$set = " ";
		$where = ' where 1 and id = '.$uid;
		switch($type){
			case 'freeze':
				$set = " set status =  ".$this->common_conf['userstatu']['freeze']['v'];
				break;
			case 'unfreeze':
				$set = " set status =  ".$this->common_conf['userstatu']['normal']['v'];
				break;
			case 'delete':
				$set = " set status =  ".$this->common_conf['userstatu']['delete']['v'];
				break;
			case 'teacher':
				$set = " set type =  ".$this->common_conf['usertype']['teacher']['v'];
				break;
			case 'unteacher':
				$set = " set type =  ".$this->common_conf['usertype']['student']['v'];
				break;
			case 'master':
				$set = " set type =  ".$this->common_conf['usertype']['master']['v'];
				break;
			case 'unmaster':
				$set = " set type =  ".$this->common_conf['usertype']['teacher']['v'];
				break;				
			default :
				$set = " set type = ".$this->common_conf['usertype'][$type]['v'];
				break;
			
		}
		$sqlstr .= $set.$where;
		if($this->dbr->query($sqlstr)){
			$result = array('error' => 0,'msg' => '更新成功');
		}
		return $result;
	}
	
	
	
	


 
    
    
}
?>