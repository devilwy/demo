<?php
	/**
	 * 获取客户端 IP
	 * ***/
	function publicfunction_getuserip(){
		global $_SERVER;
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
			
		} else if (getenv('HTTP_X_FORWARDED_FOR')) {
			
			$ip = getenv('HTTP_X_FORWARDED_FOR');
			
		} else if (getenv('REMOTE_ADDR')) {
			
			$ip = getenv('REMOTE_ADDR');
			
		} else {
			
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}


//邮件发送
function publicfunction_sendmail($sendtoemail = '' , $title = '' , $emailbody = '')
{
	if($sendtoemail == '' || $title == '' || $emailbody == '')
	{
		return false;
	}
	else
	{
		//发送邮件给
	  $phpmailerfile         = dirname(dirname(dirname(__FILE__))) . '/libraries/phpmailer.class.php';
	  if(is_file($phpmailerfile))
	  {
	  	include_once($phpmailerfile);
	  }
	  $mail       		       = new PHPMailer;
	  $mail->CharSet         = "UTF-8";
	  $mail->IsSMTP();
 	  $mail->Host            = "smtp.163.com";          // SMTP服务器地址
 	  $mail->Username        = "devilwy2009@163.com";      // 登录用户名
 	  $mail->Password        = "wyshj11202315";               // 登录密码
 	  $mail->From 		     = "devilwy2009@163.com";                 // 发件人地址(username@163.com)
	  $mail->SMTPAuth        = true;                       // SMTP是否需要验证，现在STMP服务器基本上都需要验证
	  $mail->WordWrap  	     = 10;
	  $mail->IsHTML(true);                                 //是否支持html邮件，true 或false
		//$mail->FromName        = $fromemail;                 //发件人名称
	  $mail->FromName        = "长江商学院";
	  $mail->Subject         = $title;                     //邮件标题
	  $mail->Body            = $emailbody;
	  $mail->AddAddress($sendtoemail);                     //这里是收件人地址(test@hnce.net)
		$result = $mail->Send();
	  return $result;
	}
}


function pagestring($ajaxmethod , $total_rows , $cur_page , $per_page = 10){
	$allpagenum = ceil($total_rows / $per_page);
	$vp = '<span class="disabled">&lt; </span>';
	if($cur_page >1){
		$vp = '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . ($cur_page - 1) . ');">上一页</a> ';
	}else{
		$vp = '<a class="blue" href="javascript:void(0);">上一页</a> ';
	}
	if($allpagenum <= 5){
		for($i = 1 ; $i <= $allpagenum ; $i++){
			if($i == $cur_page){
				$vp .= '<span class="current">'.$i.'</span>';
			}else{
				$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
			}
		}
	} else {
		if($cur_page == 1 || $cur_page == 2){
			for($i = 1 ; $i < 4 ; $i++){
				if($i == $cur_page){
					$vp .= '<span class="current">'.$i.'</span>';
				}
				else{
					$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
				}
			}
			$vp .= '... ';
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
		}else if($cur_page >= $allpagenum || $cur_page == ($allpagenum-1)){
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
			$vp .= '... ';
			for($i = $allpagenum - 2 ; $i <= $allpagenum ; $i++){
				if($i == $cur_page){
					$vp .= '<span class="current">'.$i.'</span>';
				}
				else{
					$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
				}
			}
		}else if($cur_page == 3){
			for($i = 1 ; $i <= 4 ; $i++){
				if($i == $cur_page){
					$vp .= '<span class="current">'.$i.'</span>';
				}else{
					$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
				}
			}
			$vp .= '... ';
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
		}else if($cur_page == ($allpagenum-2)){
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
			$vp .= '... ';
			for($i = $allpagenum - 3 ; $i <= $allpagenum ; $i++){
				if($i == $cur_page){
					$vp .= '<span class="current">'.$i.'</span>';
				}else{
					$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
				}
			}
		}else{
			$from = $cur_page - 1;
			$get  = $cur_page + 1;
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
			$vp .= '... ';
			for($i = $from ; $i <= $get ; $i++){
				if($i == $cur_page){
					$vp .= '<span class="current">'.$i.'</span>';
				}else{
					$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
				}
			}
			$vp .= '... ';
			$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
		}
	}
	if($cur_page<$allpagenum){
		$vp .= '<a class="blue" href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . ($cur_page + 1) . ');">下一页</a> ';
	}else{
		$vp .= '<a class="blue" href="javascript:void(0);" >下一页</a> ';
	}
	return $vp;
}


//**字符转义
function escapestring($arr = array())
{
	if(is_array($arr)&&!empty($arr)){
		foreach($arr as $k=>&$v){
			if(is_array($v)&&!empty($v)){
				$arr[$k] = escapestring($v);
			}
			else
				$arr[$k] = addslashes($v);
		}
	}
	return $arr;
}


/*
 * 数据过滤
 */
function htmlSpec($arr = array()){
	if(is_array($arr)&&!empty($arr)){
		foreach($arr as $k=>&$v){
			if(is_array($v)&&!empty($v)){
				$arr[$k] = htmlSpec($v);
			}
			else
				$arr[$k] = htmlspecialchars($v);
		}
	}
	return $arr;
}

/*
 * 判断参数
 * @parme $key 键值 $data 数据数组 $default 默认值
 * @return 返回默认值
 */ 
function defaultdata($key = '',$data = array(),$default = ''){
	$key = (string)$key;
	$data = is_array($data)?$data:array();
	$default = (string)$default;
	$re = isset($data[$key])?$data[$key]:$default;
	return $re;
}


?>