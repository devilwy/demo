<?php
require_once SYSLIB.'libraries/dbmodel.lib.php';

class OpModel extends Dbmodel {
	
	private $commonConf;
	public $adminId;
	public $adminName;
	public $isLogin;
	public $rem;
	public $remName;
	
	static  private $remKey = "adminRememver";
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('cookie');
		$this->checkLogin();
	}

    /**
     * 运营登录
     * @return boolen -1 账号不存在
     *                -2 密码错误
     *                -3 状态未开启
     */
    public function login($data = array()){
    	$data = escapestring($data);
    	$username = defaultdata('username',$data,'');
    	$password = defaultdata('password',$data,'');
    	$remember = defaultdata('remember',$data,'off');
    	if($remember === 'on'){
    		$adminCookie = array(
    				'name'   => self::$remKey,
    				'value'  => "1|".$username,
    				'expire' => time()+86400,
    				'domain' => '.bdfun.cn',
    				'path'   => '/',
    		);    		
    	}
    	else{
    		$adminCookie = array(
    				'name'   => self::$remKey,
    				'value'  => "0|",
    				'expire' => -1,
    				'domain' => '.bdfun.cn',
    				'path'   => '/',
    		);   		
    	}
    	$this->input->set_cookie($adminCookie);
    	$_COOKIE[self::$remKey] = $adminCookie['value'];
    	var_dump($this->input->cookie(self::$remKey,TRUE));exit;
        $user_info = $this->getOne(array('`name`' => $username) , 'admin_user');
        if (!$user_info) {
            $result = array('error' => 1,'msg' => '管理员不存在');
        }
        else{
	        $time = time();
	        if ($user_info['password'] != md5($password)) 
	            $result = array('error' => 1,'msg' => '密码错误');
	        else {
	            if ($user_info['status'] != 1) {
	                $result = array('error' => 1,'msg' => '管理员异常');
	            }else {
	                $userRole = $this->getUserGroupInfo($user_info['id']);
	                $this->makeUserMenu($user_info['id']);
	                $data = array(
	                    'id' 			=> $user_info['id'],
	                    'name' 			=> $username,
	                    'status' 		=> $user_info['status'],
	                    'lastLoginTime' => $user_info['lastLoginTime'],
	                    'createTime' 	=> $user_info['createTime'],
	                    'loginTimes' 	=> $user_info['loginTimes'] + 1,
	                    'realname' 		=> $user_info['realname'],
	                    'uid' 			=> $user_info['id'],
	                    'group' 		=> $userRole[0],
	                    'group_id' 		=> $userRole[0]['group_id'],
	                    'is_logged_in' 	=> true
	                );
					foreach($data as $k=>$v){
						$_SESSION[$k] = $v;
					}
	                //更新登录信息
	                $this->updateManagerInfo(
	                	array(
		                    '`lastLoginTime`' => $time,
		                    '`loginTimes`' => ($user_info['loginTimes'] + 1)
	                    ), $user_info['id']);
	                
	                $result = array('error' => 0,'msg' => '登录成功');
	            }
	        }
        

	        $content = $result['msg'];
	        if($result['error'] == 1)
	        	$status = '失败';
	        else
	        	$status = '成功';
	        
	        //写登录日志
	        $ip		  = $this->getIP();
	        $location = $this->convertip($ip);
	        $address  = $this->ipToArea($location);
	        if (false == $address['province'] && false == $address['city']) {
	            $addr = $location;
	        } else {
	            $addr = $address['province'] . " " . $address['city'];
	        }
	        $this->insertData(
	        	array(
			        'empId'    	=> $user_info['id'],
			        'user_ip' 	=> $ip,
			        'address' 	=> $addr,
			        'loginTime' => $time,
			        'content' 	=> $content,
			        'status' 	=> $status
			    ) , 'admin_login_record');
        }
        return $result;
    }
	
	/**
	 * 判断用户操作权限
	 */
	public function checkUserAuthority($name,$uid){
		$actionlist = $this->getUserAction($uid);
		$list 		= $this->getAclAction(array('`name`'=>$name));
		foreach($actionlist AS $key=>$value){
			if(isset($list[0]['id']) && $value['action_id'] == $list[0]['id']){
				$flag = intval($value['visible']) + intval($value['editable']);
				return $flag;
			}
		}
		return 0;
	}
	
	
	/*
	 * 验证用户登录
	 */
	private function checkLogin(){
		$this->adminId = defaultdata('id',$_SESSION,0);
		$this->adminName = defaultdata('name',$_SESSION,'');
		$this->rem = $this->input->cookie($this->remKey);
	}
	
	/**
	 * 用户权限组
	 */
	public function getUserGroupInfo($uid){
		$sql = "SELECT admin_group.*, admin_user_group.group_id FROM `admin_user_group` LEFT JOIN `admin_group`" .
				" ON admin_user_group.group_id = admin_group.id" .
				" WHERE admin_user_group.user_id = $uid";
		$r = $this->querySQL($sql);
		return $r;
	}
	
	/**
	 * 用户权限-功能
	 */
	public function getUserAction($uid){
		$sql = "SELECT admin_group_action.* FROM `admin_user_group` LEFT JOIN `admin_group_action`" .
				" ON admin_user_group.group_id = admin_group_action.group_id" .
				" WHERE admin_user_group.user_id = $uid";
		$r = $this->querySQL($sql);
		return $r;
	}
	
	public function userInsert($data){
		return $this->insertData($data , 'admin_user');
	}
	
	public function aclUserGroupInsert($data){
		return $this->insertData($data , 'admin_user_group');
	}
	
	public function aclUserGroupDelete($where){
		return $this->deleteData($where , 'admin_user_group');
	}
	
	public function aclGroupInsert($data){
		return $this->insertData($data , 'admin_group');
	}
	
	/**
	 * 获取用户导航条
	 * @param bool $status true:获取用户权限菜单
	 * 					   false：更新用户权限菜单
	 */
	public function makeUserMenu($group_id, $status = false){
		if($status){
			$menu = Mem::get('op_user_menu_'.$group_id);
		}else{
			$menu = false;
		}
		$marray = array();
		if(false == $menu){     //缓存中无菜单列表，调取数据库
			$menu = array();
			$sql = "SELECT admin_group_action.* FROM `admin_user_group` LEFT JOIN `admin_group_action`" .
				" ON admin_user_group.group_id = admin_group_action.group_id" .
				" WHERE admin_user_group.group_id = $group_id ORDER BY admin_group_action.action_id ASC";
			$r 	  = $this->querySQL($sql);
			$list = $this->getList(array('`status`'=>1) , '' , '' , 'admin_action');
			foreach($list AS $key=>$value){
				$actionlist[$value['id']] = $value;
			}
			if(empty($r)){
				return false;
			}else{
				foreach($r AS $key=>$value){
					if(isset($actionlist[$value['action_id']]['group_name']) && !in_array($actionlist[$value['action_id']]['group_name'], $marray)){
						$marray[] 		= $actionlist[$value['action_id']]['group_name'];     
						$ary['name'] 	= $actionlist[$value['action_id']]['group_name'];    //导航名
						$ary['submenu'] = $this->getSubMenu($value['group_id'],$actionlist[$value['action_id']]['group_name']);    //获取用户左边菜单
						$ary['url'] 	= $ary['submenu'][0]['url'];         //导航链接
						$menu[] 		= $ary;
					}
				}
				Mem::set('op_user_menu_'.$group_id, $menu);      //缓存菜单
			}
		}
		
		return $menu;
	}
	
	/**
	 * 获取action操作地址
	 */
	public function getActionUrl($action_id){
		$r = $this->getOne(array('`id`'=>$action_id) , 'admin_action');
		return $r['url'];
	}
	
	/**
	 * 获取用户左边子菜单
	 */
	public function getSubMenu($group_id, $name){
		$sql = "SELECT admin_action.name, admin_action.url FROM `admin_action`" .
				" LEFT JOIN `admin_group_action` ON admin_action.id = admin_group_action.action_id" .
				" WHERE admin_group_action.group_id = $group_id AND admin_action.group_name = '$name'";
		$r = $this->querySql($sql);
		return $r;
	}
	
	/**
	 * 更新后台管理员信息
	 */
	public function updateManagerInfo($data, $uid){
		$r = $this->updateData($data, array('`id`'=>$uid) , 'admin_user');
		Mem::delete('manager_'.$uid);
		return $r;
	}
	
	/**
	 * 登录操作记录
	 */
	public function getLoginRecord($where = Null, $order = Null, $limit = Null){
		return $this->getList($where, $order, $limit,'admin_login_record');
	}
	
	public function getManagerAccount($where){
		return $this->getOne($where , 'admin_user');
	}
	
	/**
	 * 权限管理组列表
	 */
	public function getGroupList($type = 'list'){
		$data = array();
		$sql = "SELECT inner_group FROM admin_group GROUP BY inner_group ORDER BY id ASC";
		$group = $this->querySQL($sql);
		if($type == 'select'){
			return $group;
		}else{
			foreach($group AS $key=>$value){
				$list = $this->getList(array('`inner_group`'=>$value['inner_group']),'id ASC' , '' , 'admin_group');
				foreach($list AS $k=>$v){
					$sql = "SELECT admin_user_group.*, admin_user.realname FROM admin_user_group" .
							" LEFT JOIN admin_user ON admin_user_group.user_id = admin_user.id" .
							" WHERE admin_user_group.group_id = ".$v['id'];
					$v['member'] = $this->querySQL($sql);
					$value['list'][] = $v;
				}
				$data[] = $value;
			}
			return $data;
		}
	}
	
	/**
	 * 根据分组获取岗位列表
	 */
	public function getPostList($group){
		$this->selectField('`name`');
		return $this->getList(array('`inner_group`'=>$group), 'id ASC','','admin_group');
	}
	
	public function getManagerInfo($where){
		return $this->getOne($where , 'admin_user');
	}
	
	public function getManagerMem($uid){
		$user = Mem::get('manager_'.$uid);
		if(false == $user){
			$r = $this->getOne(array('`id`'=>$uid) , 'admin_user');
			$sql = "SELECT admin_group.name,admin_group.inner_group FROM admin_user_group" .
					" LEFT JOIN admin_group ON admin_user_group.group_id = admin_group.id" .
					" WHERE admin_user_group.user_id = $uid";
			$res = $this->querySQL($sql);
			$r['post'] = $res[0]['name'];
			$r['inner_group'] = $res[0]['inner_group'];
			Mem::set('manager_'.$uid, $r);
			$user = $r;
		}
		return $user;
	}
	
	public function getGroupInfo($where){
		return $this->getOne($where , 'admin_group');
	}
	
	/**
	 * 管理员列表
	 */
	public function getManagerList($order = '', $limit = ''){
		$this->selectField('`id`,`name`,`realname`,createTime');
		$list = $this->getList('', $order, $limit , 'admin_user');
		$data = array();
		if(!empty($list)){
			foreach($list AS $key=>$value){
				$r = $this->getUserGroupInfo($value['id']);
                                $value['inner_group'] = isset($r[0]['inner_group'])?$r[0]['inner_group']:'';
                                $value['inner_group_id'] = isset($r[0]['id'])?$r[0]['id']:'';
                                $value['post'] = isset($r[0]['name'])?$r[0]['name']:'';
				$data[] = $value;
			}
		}
		return $data;
	}
	
	/**
	 * 获取组权限
	 */
	public function getActionList($group_id){
		//$list = Mem::get('action_list');
		$list = false;
		if(false == $list){
			$list = array();
			$sql = "SELECT group_name FROM admin_action GROUP BY group_name ORDER BY id ASC";
			$group_name = $this->querySQL($sql);
			foreach($group_name AS $key=>$value){
				$r = $this->getList(array('`group_name`'=>$value['group_name']),'id ASC' , '' , 'admin_action');
				$value['list'] = $r;
				$list[] = $value;
			}
			Mem::set('action_list',$list);
		}
		return $list;
	}
	
	public function getActionByGroup($group_id){
		return $this->getList(array('`group_id`'=>$group_id) , '' , '' , 'admin_group_action');
	}
	
    public function getAclActionOne($where){
		return $this->getOne($where , 'admin_action');
	}
        
	public function getAclAction($where = Null, $order = Null, $limit = Null){
		return $this->getList($where, $order, $limit ,'admin_action');
	}
        
    public function delAclAction($where){
        return $this->deleteData($where , 'admin_action');
    }
        
	public function getAclActionGroupName(){
        $list = array();
        $sql = "SELECT group_name FROM admin_action GROUP BY group_name ORDER BY id ASC";
        $group_name = $this->querySQL($sql);
        foreach($group_name AS $value){
            $list[] = $value['group_name'];
        }
        return $list;
	}
        
    public function insertAclAction($data){
        $this->insertData($data , 'admin_action');
    }
    
    public function updateAclAction($data, $where){
        $this->updateData($data, $where , 'admin_action');
    }
	
	public function AclGroupActionInsert($data){
		return $this->insertData($data , 'admin_group_action');
	}
	
	public function AclGroupActionDelete($where){
		return $this->deleteData($where , 'admin_group_action');
	}
	
	public function getAclUserGroup($where){
		return $this->getOne($where , 'admin_user_group');
	}
	
	public function getAclUserGroupList($where = Null, $order = Null, $limit = Null ){
		return $this->getList($where, $order, $limit , 'admin_user_group');
	}
	
	/**
	 * 用户组基本设置更新
	 */
	public function aclGroupUpdate($data,$group_id){
		$r 	  = $this->AclGroup->update($data, array('`id`'=>$group_id));
		$list = $this->getAclUserGroupList(array('`group_id`'=>$group_id));
		foreach($list AS $key=>$value){
			Mem::delete('manager_'.$value['user_id']);
		}
		return $r;
	}
	
	/**
	 * 删除分组
	 */
	public function aclGroupDelete($data){
		return $this->deleteData($data , 'admin_group');
	}
	
	public function userDelete($where){
		return $this->deleteData($where , 'admin_user');
	}
	
	public function userUpdate($data,$where){
		return $this->updateData($data,$where , 'admin_user');
	}
	
	/**
	 * 根据用户ip获取用户地址
	 */
	public function convertip($ip) {
		//IP数据文件路径，请根据情况自行修改
		$dat_path = SYSLIB.'tools/qqwry.dat';
		//检查IP地址
		if(!preg_match("/^([0-9]{1,3}.){3}[0-9]{1,3}$/", $ip)){
			return 'IP Address Error';
		}
		//打开IP数据文件
		if(!$fd = @fopen($dat_path, 'rb')){
			return 'IP date file not exists or access denied';
		}
		//分解IP进行运算，得出整形数
		$ip = explode('.', $ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
		//获取IP数据索引开始和结束位置
		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		$BeginNum = 0;
		$EndNum = $ipAllNum;
		$ipAddr2 = $ipAddr1 = '';
		//使用二分查找法从索引记录中搜索匹配的IP记录
		do {
			$Middle= intval(($EndNum + $BeginNum) / 2);
			//偏移指针到索引位置读取4个字节
			fseek($fd, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fd, 4);
			if(strlen($ipData1) < 4) {
				fclose($fd);
				return 'System Error';
			}
			//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);
			//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
			//取完上一个索引后取下一个索引
			$DataSeek = fread($fd, 3);
			if(strlen($DataSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($fd, $DataSeek);
			$ipData2 = fread($fd, 4);
			if(strlen($ipData2) < 4) {
				fclose($fd);
				return 'System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if($ip2num < 0) $ip2num += pow(2, 32);
			//没找到提示未知
			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					fclose($fd);
					return 'Unknown';
				}
				$BeginNum = $Middle;
			}
		}while($ip1num>$ipNum || $ip2num<$ipNum);
		//下面的代码读晕了，没读明白，有兴趣的慢慢读
		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($fd, 3);
			if(strlen($ipSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($fd, $ipSeek);
			$ipFlag = fread($fd, 1);
		}
		if($ipFlag == chr(2)) {
			$AddrSeek = fread($fd, 3);
			if(strlen($AddrSeek) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while(($char = fread($fd, 1)) != chr(0))
			$ipAddr2 .= $char;
			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($fd, $AddrSeek);
			while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;
		} else {
			fseek($fd, -1, SEEK_CUR);
			while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return 'System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while(($char = fread($fd, 1)) != chr(0)){
				$ipAddr2 .= $char;
			}
		}
		fclose($fd);
		//最后做相应的替换操作后返回结果
		if(preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
		$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = 'Unknown';
		}
		$ipaddr = iconv('GB2312','UTF-8',$ipaddr);
		return $ipaddr;
	}

	/**
	 * 截取省市信息
	 */
	public function ipToArea($string) {
		$data = array(
		'province' => '',
		'city' => ''
		);

		$province_pos = strpos($string, '省');
		if($province_pos){
			$data['province'] = substr($string, 0, $province_pos).'省';
		}
		$city_pos = strpos($string, '市');
		if($city_pos){
			if($province_pos){
				$data['city'] = substr($string, ($province_pos+3), ($city_pos-$province_pos-3)).'市';
			}else{
				$data['city'] = substr($string, 0, $city_pos).'市';
			}
		}
		return $data;
	}
	
	private function getIP(){		
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		        $onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		        $onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		        $onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip;
	}
}
