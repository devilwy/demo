<?php
/**
 * base
 */
require_once SYSLIB.'libraries/memsession.lib.php';

class baseController extends CI_Controller{
	public function __construct(){
		parent :: __construct();
		
		if(!isset($_SESSION))
			session_start();
	}

	protected function isPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}
	
	protected function getHost() {
		return "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	}
	
	protected function getIP(){		
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

	protected function getUserAgent(){
		return $_SERVER['HTTP_USER_AGENT'];
	}

	protected function getSession($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		return '';
	}
	
	protected function setSession($key,$val = null){
		if(is_array($key)){
			foreach($key as $k=>$v){
				$_SESSION[$k] = $v;
			}
			return true;
		}
		$_SESSION[$key] = $val;		
	}
	
	protected function unsetSession($key){
		if(is_array($key)){
			foreach($key AS $k){
				if(isset($_SESSION[$k])){
					unset($_SESSION[$k]);
				}
			}			
		}else if(isset($_SESSION[$key])){
			unset($_SESSION[$key]);
		}
		return true;
	}
	
	protected function getCookie($name){
		if (isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} 
		return false;
	}

	protected function setCookie($name, $val, $expire = 0){
		include SYSLIB.'config/config.php';
		$path	= $config['cookie_path'];
		$domain = $config['cookie_domain'];		
		$expire = ($expire == 0)? $config['cookie_expire'] : time() + $expire;
		return setcookie($name, $val, $expire, $path, $domain);		
	}
	
	protected function unsetCookie($name){
		$this->setcookie($name, "", time()-24*60*60);
	}
	
	/**
	 * rand string
	 */
	protected function generate_sid($length, $numeric = 0) {
		$DOCUMENT_ROOT = "@#$9472sdkfhs2EE2042sj";
		$seed = base_convert(md5(microtime().$DOCUMENT_ROOT), 16, $numeric ? 10 : 35);
		$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
		$hash = '';
		$max = strlen($seed) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $seed{mt_rand(0, $max)};
		}
		return $hash;
	}
	
	/**
	 * 
	 * @param unknown_type $RoleID
	 * @param unknown_type $login_timeout default 8H
	 */
	protected function login_session_write($RoleID, $login_timeout = 28800){		
		$mid = $this->generate_sid(26);
		$this->setCookie("ckgsb_PASSPORT", $mid, $login_timeout);
		$fix_mid 		= md5($mid.md5($this->getUserAgent()));
		$session_time 	= $login_timeout ? $login_timeout : 8*3600;		
		$f 				= MemSession::set($fix_mid, $RoleID, $session_time);
		Mem::set('uid_' . $RoleID, $fix_mid);
		return $f;
	}
	
	protected function login_session_read($mid){
		$agent = $this->getUserAgent();
		$fix_mid = md5($mid.md5($agent));
		return MemSession::get($fix_mid);
	}
	
	protected function login_session_refresh($mid, $sess_uid, $login_timeout = 28800){		
		$this->setCookie("ckgsb_PASSPORT", $mid, $login_timeout);		
		$fix_mid 		= md5($mid.md5($this->getUserAgent()));
		$session_time 	= $login_timeout ? $login_timeout : 8*3600;	
		MemSession::set($fix_mid, $sess_uid, $session_time);
	}
	
	/**
	 * 获取验证码
	 * **/
	function get_verification_code($type = 'register'){ 
		$code = '';
		switch($type){
			case 'register':
				$code = $this->getCookie('userRegChk');
			break;
			case 'login':
				$code = $this->getCookie('userLogChk');
			break;
			case 'forget':
				$code = $this->getCookie('userForgetChk');
			break;
			case 'advisement':
				$code = $this->setCookie('userAdvisementChk');
			break;
			case 'tipOff':
				$code = $this->getCookie('userTipOffChk');
			break;
			case 'safety':
				$code = $this->getCookie('safetyChk');
			break;
		}
		return $code;
	}
	
	/**
	 * 
	 * $param $width   验证码区域宽度
	 * $param $height  验证码区域高度
	 * 
	 */	
	protected function verification_code($type = 'register', $width = 90, $height = 20) {
		
		$x_size=$width;
		$y_size=$height;
		if(function_exists("imagecreate"))
		{
			$aimg = imagecreate($x_size,$y_size);
			$back = imagecolorallocate($aimg, 255, 255, 255);
			$border = imagecolorallocate($aimg, 0, 0, 0);
			imagefilledrectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $back);
			$txt="123456789";
			$txtlen=strlen($txt);
	
			$thetxt="";
			for($i=0;$i<4;$i++)
			{
				$randnum=mt_rand(0,$txtlen-1);
				$randang=mt_rand(-20,20);	//文字旋转角度
				$rndtxt=substr($txt,$randnum,1);
				$thetxt.=$rndtxt;
				$rndx=mt_rand(2,7);
				$rndy=0;
				$colornum1=($rndx*$rndx*$randnum)%255;
				$colornum2=($rndy*$rndy*$randnum)%255;
				$colornum3=($rndx*$rndy*$randnum)%255;
				$newcolor=imagecolorallocate($aimg, $colornum1, $colornum2, $colornum3);
				imageString($aimg,5,$rndx+$i*21,5+$rndy,$rndtxt,$newcolor);
			}
			unset($txt);
			$thetxt = strtolower($thetxt);
			switch($type){
				case 'register':
					$this->setCookie('userRegChk',md5($thetxt));
					break;
				case 'login':
					$this->setCookie('userLogChk',md5($thetxt));
					break;
				case 'forget':
					$this->setCookie('userForgetChk',md5($thetxt));
					break;
				case 'advisement':
					$this->setCookie('userAdvisementChk',md5($thetxt));
					break;
				case 'tipOff':
					$this->setCookie('userTipOffChk',md5($thetxt));
					break;
				case 'safety':
					$this->setCookie('safetyChk',md5($thetxt));
				break;
			}
			
			imagerectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $border);
	
			$newcolor="";
			$newx="";
			$newy="";
			$pxsum=50;	//干扰像素个数
			for($i=0;$i<$pxsum;$i++)
			{
				$newcolor=imagecolorallocate($aimg, mt_rand(0,254), mt_rand(0,254), mt_rand(0,254));
				imagesetpixel($aimg,mt_rand(0,$x_size-1),mt_rand(0,$y_size-1),$newcolor);
			}
			header("Pragma:no-cache");
			header("Cache-control:no-cache");
			header("Content-type: image/png");
			imagepng($aimg);
			imagedestroy($aimg);
			exit;
		}
	}
	
	protected function getEmailFrom($email){
		$from = '';
		$email_ary = array(
			'qq.com' => 'http://mail.qq.com',
			'126.com' => 'http://mail.126.com',
			'sohu.com' => 'http://mail.sohu.com',
			'mail.51.com' => 'http://mail.51.com',
			'yahoo.cn' => 'http://mail.yahoo.cn',
			'gmail.com' => 'http://www.gmail.com',
			'139.com' => 'http://mail.139.com',
			'eyou.com' => 'http://mail.eyou.com',
			'sml.com' => 'http://mail.sml.com',
			'e165.com' => 'http://mail.e165.com',
			'263.com' => 'http://mail.263.com',
			'hotmail.com' => 'http://mail.msn.com',
			'17173.com' => 'http://mail.17173.com',
			'163.com' => 'http://mail.163.com',
			'yahoo.com.cn' => 'http://mail.yahoo.com.cn',
			'sina.com' => 'http://mail.sina.com',
			'sina.cn' => 'http://mail.sina.com',
			'live.com' => 'http://mail.live.com',
			'189.cn' => 'http://mail.189.cn',
			'tom.com' => 'http://mail.tom.com',
			'china.com' => 'http://mail.china.com',
			'yeah.net' => 'http://mail.yeah.net',
			'56.com' => 'http://mail.56.com',
			'sand.com.cn' => 'http://mail.sand.com.cn',
			'zhuaxia.com' => 'http://mail.zhaxia.com',
			'sogou.com' => 'http://mail.sogou.com'
		);
		
		$input_ary = array(
			'qq.com' => 'http://mail.qq.com',
			'163.com' => 'http://mail.163.com',
			'126.com' => 'http://mail.126.com',
			'yahoo.com.cn' => 'http://mail.yahoo.com.cn',
			'sohu.com' => 'http://mail.sohu.com',
			'sina.com' => 'http://mail.sina.com',
			'mail.51.com' => 'http://mail.51.com',
			'hotmail.com' => 'http://www.hotmail.com',
			'yahoo.cn' => 'http://mail.yahoo.cn',
			'live.com' => 'http://mail.live.com',
			'gmail.com' => 'http://www.gmail.com',
			'189.cn' => 'http://mail.189.cn',
			'tom.com' => 'http://mail.tom.com',
			'139.com' => 'http://mail.139.com',
			'china.com' => 'http://mail.china.com',
			'eyou.com' => 'http://mail.eyou.com',
			'yeah.net' => 'http://mail.yeah.net',
			'sml.com' => 'http://mail.sml.com',
			'56.com' => 'http://mail.56.com',
			'e165.com' => 'http://mail.e165.com',
			'sand.com.cn' => 'http://mail.sand.com.cn',
			'263.com' => 'http://mail.263.com',
			'zhuaxia.com' => 'http://mail.zhuaxia.com',
			'msn.com' => 'http://mail.msn.com',
			'sogou.com' => 'http://mail.sogou.com',
			'cyworld.com.cn' => 'http://mail.cyworld.com.cn',
			'17173.com' => 'http://mail.17173.com',
			'ku6.com' => 'http://mail.ku6.com',
			'91.com' => 'http://mail.91.com'
		);
		
		foreach($email_ary AS $key=>$value){
			if(!stristr($email, $key)===FALSE){
				$from = $value;
				break;
			}
		}
		return $from;
	}
	
	/**
	 * 错误信息提示
	 * @param string $url   提示错误后跳转地址、
	 * @param string $msg   提示信息
	 * @param int $time     页面停留时间
	 *
	 */
	protected function Error($msg, $url, $time = 2){
		$data = array(
			'msg' => $msg,
			'url' => $url,
			'time' => $time
		);
		$this->load->view('error_msg', $data);
	}
	

}