<?php 
/**
 * QQ 账号登录
 * **/
class QQapi extends Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url','client'));
	}
	// ===================================== 彩虹ID
	public function caihong_callback(){ 
		header("Content-Type:text/html;charset=utf-8");
		$code = isset($_GET['code']) ? intval($_GET['code']) : 0;
		$error = 0; $vdcID = '' ; $pwd = '';
		if($code){
			// 查询是否已经授权
			$account_info = $this->user->getAccount(array('`ExtAccount`'=>$code));
			if(!empty($account_info)){
				$a    = token::get_user_by_vdc($account_info['179ID']);
				if($a){ 
					$vdcID = $account_info['179ID'] ; $pwd = $a['password'];
				}else{ 
					$error = 8;
				}
			}else{
				$vdcID = $this->user->getVdcID(2);
				$pwd   = md5($this->generate_sid(16));
				$regIp = $this->getIP(); // 注册 IP
				$regsource = $this->getCookie('regsource_spread_cookie');
				$r = $this->user->insert(2, $code, $vdcID, '', '', $pwd, '', $ip = $regIp, $regfrom = '51' , '' , '' , '' , '' , $regIp , $regsource);
				if(!$r){
					$error = 7;
				}
			}
		}else{
			$error = 2;
		}
		if($error == 0){
			echo json_encode(array('vdcID' => $vdcID , "password" => $pwd , "error" => $error , 'error' => $error));	
		}else{
			echo json_encode(array('vdcID' => $vdcID , "password" => $pwd , "error" => $error , "code" => $code , 'error'=>$error));
		}
	}
	
	// ========================================== QQ 
	public function qq_echo_open(){ 
		header("Content-Type:text/html;charset=utf-8");
		$vdcID 		  = $_GET['vdcID'];
		$password     = $_GET['password'];
		$error        = $_GET['error'];
		$data['QQ_vdcID'] = $vdcID;
		$data['QQ_password'] = $password;
		$data['QQ_error'] = $error;
		$this->load->view('/qqapi/v_qq_echo.php' , $data);
	}
	
	public function qq_echo_error(){
		header("Content-Type:text/html;charset=utf-8");
		$error = isset($_GET['error']) ? $_GET['error'] : '';
		$this->load->view('/qqapi/v_qq_error.php');
	}
	
	public function qq_callback(){
		header("Content-Type:text/html;charset=utf-8");
		$code = isset($_GET['code']) ? $_GET['code'] : '';
		$data['code'] = $code;
		$this->load->view('/qqapi/v_qq_loading.php' , $data);
	}
	
	// 客户端 ajax 
	public function ajax_qq_callback(){
		header("Content-Type:text/html;charset=utf-8");
		$code = isset($_POST['code']) ? $_POST['code'] : '';
		$error = 0; $vdcID = '' ; $pwd = '';$openid='';
		if($code){
			$config = include SYSLIB.'/config/apikey.php';
			$sUrl   = "https://graph.qq.com/oauth2.0/token";
			$aGetParam = array(
				"grant_type"     => "authorization_code",
				"client_id"      => $config['qqkey']['api_qq_appid'],
				"client_secret"  => $config['qqkey']['api_qq_appsecret'],
				"code"           => $code,
				"redirect_uri"   => 'http://www.179.com/qqapi/qq_callback'
			);
			$sContent = $this->get($sUrl,$aGetParam);
			file_put_contents(ROOTPATH . '/upload/qq_client_request.log' , "sContent:".$sContent . "\n" , FILE_APPEND);
			if($sContent!==FALSE){
				$aTemp  = explode("&", $sContent);
				if(is_array($aTemp) && isset($aTemp[0]) && isset($aTemp[1])){
					$aParam = array();
					foreach($aTemp as $val){
						$aTemp2 = explode("=", $val);
						$aParam[$aTemp2[0]] = $aTemp2[1];
					}
					$_SESSION["access_token"] = $aParam["access_token"];
					$sUrl = "https://graph.qq.com/oauth2.0/me";
					$aGetParam = array(
						"access_token" => $aParam["access_token"]
					);
					$sContent = $this->get($sUrl, $aGetParam);
					if($sContent!==FALSE){
						$aTemp = array();
						preg_match('/callback\(\s+(.*?)\s+\)/i', $sContent,$aTemp);
						$aResult = json_decode($aTemp[1],true);
						$openid  = $aResult["openid"];
						if($openid){
							// 查询是否已经授权
							$account_info = $this->user->getAccount(array('`ExtAccount`'=>$openid));
							
							if(!empty($account_info)){
							$a    = token::get_user_by_vdc($account_info['179ID']);
								if($a){ 
									$vdcID = $account_info['179ID'] ; $pwd = $a['password'];
								}else{ 
									$error = 8;
								}
							}else{
								$vdcID = $this->user->getVdcID(2);
								$pwd   = md5($this->generate_sid(16));
								$regIp = $this->getIP(); // 注册 IP
								$regsource = $this->getCookie('regsource_spread_cookie');
								$r = $this->user->insert(3, $openid, $vdcID, '', '', $pwd, '', $ip = $regIp, $regfrom = 'qq' ,  '' , '' , '' , '' , $regIp , $regsource);
								if(!$r){
									$error = 7;
								}
							}
						}else{
							$error = 6;
						}
					}else{
						$error = 5;
					}
				}else{
					$error = 4;
				}
			}else{
				$error = 3;
			}
		}else{
			$error = 2;
		}
		if($error == 0){
			echo json_encode(array('url'=>'http://www.179.com/qqapi/qq_echo_open/?vdcID='.$vdcID . "&password=".$pwd."&error=".$error , 'error'=>$error));	
		}else{
			echo json_encode(array('url'=>'http://www.179.com/qqapi/qq_echo_error/?vdcID='.$vdcID . "&password=".$pwd."&error=".$error."&openid=".$openid , 'error'=>$error));
		}
	}
	
	public function qq_login_api(){
		$config = include SYSLIB.'/config/apikey.php';
		$aParam = array(
		    "response_type" =>  "code",
		    "client_id"     =>  $config['qqkey']['api_qq_appid'],
		    "redirect_uri"  =>  'http://www.179.com/qqapi/qq_callback',
		    "scope"         =>  'get_user_info',
		);
		$aGet = array();
		foreach($aParam as $key=>$val){
		    $aGet[] = $key."=".urlencode($val);
		}
		$sUrl = "https://graph.qq.com/oauth2.0/authorize?";
		$sUrl .= join("&",$aGet);
		header("location:".$sUrl);
	}
	
	public function qq_www_callback(){
		$code = isset($_GET['code']) ? $_GET['code'] : ''; $vdcID = '' ; $pwd = ''; $loginflag = false; $bbs_url = ""; $openid = ''; $RoleID = '';
		$error = 0;
		if($code){
			$config = include SYSLIB.'/config/apikey.php';
			$sUrl   = "https://graph.qq.com/oauth2.0/token";
			$aGetParam = array(
				"grant_type"     => "authorization_code",
				"client_id"      => $config['qqkey']['api_qq_appid'],
				"client_secret"  => $config['qqkey']['api_qq_appsecret'],
				"code"           => $code,
				"redirect_uri"   => 'http://www.179.com/qqapi/qq_www_callback'
			);
			$sContent = $this->get($sUrl,$aGetParam);
			file_put_contents(ROOTPATH . '/upload/qq_www_request.log' , "sContent:".$sContent . "\n" , FILE_APPEND);
			if($sContent!==FALSE){
				$aTemp  = explode("&", $sContent);
				if(is_array($aTemp) && isset($aTemp[0]) && isset($aTemp[1])){
					$aParam = array();
					foreach($aTemp as $val){
						$aTemp2 = explode("=", $val);
						$aParam[$aTemp2[0]] = $aTemp2[1];
					}
					$_SESSION["access_token"] = $aParam["access_token"];
					$sUrl = "https://graph.qq.com/oauth2.0/me";
					$aGetParam = array(
						"access_token" => $aParam["access_token"]
					);
					$sContent = $this->get($sUrl, $aGetParam);
					if($sContent!==FALSE){
						$aTemp = array();
						preg_match('/callback\(\s+(.*?)\s+\)/i', $sContent,$aTemp);
						$aResult = json_decode($aTemp[1],true);
						$openid  = $aResult["openid"];
						if($openid){
							// 查询是否已经授权
							$account_info = $this->user->getAccount(array('`ExtAccount`'=>$openid));
							if(!empty($account_info)){
								$a 	  = token::get_user_by_vdc($account_info['179ID']);
								if(isset($a['uin']) && $a['uin']){ 
									$RoleID = $a['uin'];
								}else{
									$error = 8;
								}
							}else{
								$vdcID 	   = $this->user->getVdcID(2);
								$pwd   	   = md5($this->generate_sid(16));
								$regIp 	   = $this->getIP(); // 注册 IP
								$regsource = $this->getCookie('regsource_spread_cookie');
								$r = $this->user->insert(3, $openid, $vdcID, '', '', $pwd, '', $ip = $regIp, $regfrom = 'qq' ,  '' , '' , '' , '' , $regIp , $regsource);
								if(!$r){
									$error = 7;
								}else{
									$RoleID = $r;
								}
							}
						}else{
							$error = 6;
						}
					}else{
						$error = 5;
					}
				}else{
					$error = 4;
				}
			}else{
				$error = 3;
			}
		}else{
			$error = 2;
		}

		if($error == 0){
			$this->load->model('areamodel','area');
			$ip 	  = $this->getIP();
			$location = convertip($ip);
			$data = $this->area->ipToArea($location);
			if(!$data['province']){
				$data['province'] = '上海市';
			}
			if($RoleID){
				$this->user_info = $this->user->getRoleID($RoleID);	
				if(isset($this->user_info['RoleName']) && $this->user_info['RoleName']){
					$loginflag = $this->user->login('qq', $openid, $pwd);
					redirect('http://www.179.com');
				}
				$this->setCookie('RoleID' , $RoleID);
			}else{
				$error = 8;
			}
			$data['openid']  = $openid;
			$data['error']   = $error;
			$this->load->view('v_perfect.php' , $data);
		}else{
			file_put_contents(ROOTPATH . '/upload/qq_error_reg.log' , "error:".$error. '--code:'.$code."---RoleID:" . $RoleID . '---openid:'.$openid ."\n" , FILE_APPEND);
			redirect('http://www.179.com/qqapi/qq_www_login_api');
		}
	}
	
	/**
	 * 
	 * */
	public function qq_perfect(){
		$error = 0;
		$openid = $this->input->post("openid");
		$loginflag = $this->user->login('qq', $openid, '');
		if($loginflag){
			$this->user_info = $this->user->getRoleID($this->getCookie('RoleID'));	
			$rolename = $this->input->post('RoleName',true);
			$rolename = word_filter($rolename);
			$error = 0;
			if(FALSE == $this->user->checkRoleName($rolename , $this->user_info['RoleID'])){
				$error = 2;
			}
			if(FALSE === $this->input->post('Gender')){
				$error = 3;
			}
			$gender = $this->input->post('Gender');
			$year 	= $this->input->post('year');
			$month  = $this->input->post('month');
			$day    = $this->input->post('day');

			$province = $this->input->post('province');
			$city 	  = $this->input->post('city');
			$country  = $this->input->post('country');
			
			$this->load->model('usermodel','user');
		//	$uin = $this->getCookie('RoleID');
			$uin = $this->user_info['RoleID'];	
			$updateData = array(
				'RoleName'				=> $rolename,
				'Gender'				=> $gender,
				'Birthday'				=> "$year$month$day",
				'CurLocateAddrProvince' => $province,
				'CurLocateAddrCity'		=> $city,
				'CurLocateAddrCounty'	=> $country
			);
			$r = $this->user->updateUserInfo($updateData,$uin);
			$accountInfo = $this->user->getAccount(array('`179Uin`'=>$uin));
			file_put_contents(ROOTPATH . '/upload/qq_error_perfect.log' , "uin:".$uin . "---rolename:".$rolename."\n" , FILE_APPEND);
			$platform    = 'QQ';
			$sess_ary    = array (
				'platform' 		=> $platform,
				'AccountFrom'	=> $accountInfo['ExtAccountType'],
				'RoleID' 		=> $uin,
				'RoleName'		=> $rolename,
				'Gender' 		=> $gender,
				'is_logged_in' 	=> true);
			foreach ($sess_ary as $key=>$value){
				$this->setCookie($key , $value);
			}
//			if($r){
				$loginflag = $this->user->login('qq', $openid, '');
				if($loginflag){
					$a 	  = token::get_user_by_uin($uin);
					$pwd  = $a['password'];	
					$r = uc_user_register($rolename, $pwd, '');
					
				}
//			}
		}else{
			$error = 1;
		}
		echo json_encode(array('error'=>$error));
	}
	
	public function qq_perfect_ok(){
		if($this->getCookie('RoleID')){
			$a 	     = token::get_user_by_uin($this->getCookie('RoleID'));
			$res     = uc_user_login($this->getCookie('RoleName'), $a['password']);
			$bbs_url = uc_user_synlogin($res[0]);
			$data['bbs_url'] = $bbs_url;
			$this->load->view('v_perfect_ok.php' , $data);
		}else{
			redirect('http://www.179.com/' , 'location');
			return ;
		}
	}
	
	public function qq_www_login_api(){ 
		$config = include SYSLIB.'/config/apikey.php';
		$aParam = array(
		    "response_type" =>  "code",
		    "client_id"     =>  $config['qqkey']['api_qq_appid'],
		    "redirect_uri"  =>  'http://www.179.com/qqapi/qq_www_callback',
		    "scope"         =>  'get_user_info'
		);
		$aGet = array();
		foreach($aParam as $key=>$val){
		    $aGet[] = $key."=".urlencode($val);
		}
		$sUrl = "https://graph.qq.com/oauth2.0/authorize?";
		$sUrl .= join("&",$aGet);
		header("location:".$sUrl);
	}
	
	/*
	 * GET请求
	 */
	function get($sUrl,$aGetParam){
	    global $aConfig;
	    $oCurl = curl_init();
	    if(stripos($sUrl,"https://")!==FALSE){
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    }
	    $aGet = array();
	    foreach($aGetParam as $key=>$val){
	        $aGet[] = $key."=".urlencode($val);
	    }
	    curl_setopt($oCurl, CURLOPT_URL, $sUrl."?".join("&",$aGet));
	    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	    $sContent = curl_exec($oCurl);
	    $aStatus = curl_getinfo($oCurl);
	    curl_close($oCurl);
	    if(intval($aConfig["debug"])===1){
	        echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>".$sUrl."</pre></td></tr>";
	        echo "<tr><td class='narrow-label'>GET参数:</td><td><pre>".var_export($aGetParam,true)."</pre></td></tr>";
	        echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>".var_export($aStatus,true)."</pre></td></tr>";
	        if(intval($aStatus["http_code"])==200){
	            echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>".$sContent."</pre></td></tr>";
	            if((@$aResult = json_decode($sContent,true))){
	                echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>".var_export($aResult,true)."</pre></td></tr>";
	            }
	        }
	    }
	    if(intval($aStatus["http_code"])==200){
	        return $sContent;
	    }else{
	        echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>".$aStatus["http_code"].",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
	        return FALSE;
	    }
	}
	
	/*
	 * POST 请求
	 */
	function post($sUrl,$aPOSTParam){
	    global $aConfig;
	    $oCurl = curl_init();
	    if(stripos($sUrl,"https://")!==FALSE){
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $aPOST = array();
	    foreach($aPOSTParam as $key=>$val){
	        $aPOST[] = $key."=".urlencode($val);
	    }
	    curl_setopt($oCurl, CURLOPT_URL, $sUrl);
	    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt($oCurl, CURLOPT_POST,true);
	    curl_setopt($oCurl, CURLOPT_POSTFIELDS, join("&", $aPOST));
	    $sContent = curl_exec($oCurl);
	    $aStatus = curl_getinfo($oCurl);
	    curl_close($oCurl);
	    if(intval($aConfig["debug"])===1){
	        echo "<tr><td class='narrow-label'>请求地址:</td><td><pre>".$sUrl."</pre></td></tr>";
	        echo "<tr><td class='narrow-label'>POST参数:</td><td><pre>".var_export($aPOSTParam,true)."</pre></td></tr>";
	        echo "<tr><td class='narrow-label'>请求信息:</td><td><pre>".var_export($aStatus,true)."</pre></td></tr>";
	        if(intval($aStatus["http_code"])==200){
	            echo "<tr><td class='narrow-label'>返回结果:</td><td><pre>".$sContent."</pre></td></tr>";
	            if((@$aResult = json_decode($sContent,true))){
	                echo "<tr><td class='narrow-label'>结果集合解析:</td><td><pre>".var_export($aResult,true)."</pre></td></tr>";
	            }
	        }
	    }
	    if(intval($aStatus["http_code"])==200){
	        return $sContent;
	    }else{
	        echo "<tr><td class='narrow-label'>返回出错:</td><td><pre>".$aStatus["http_code"].",请检查参数或者确实是腾讯服务器出错咯。</pre></td></tr>";
	        return FALSE;
	    }
	}
}
?>
