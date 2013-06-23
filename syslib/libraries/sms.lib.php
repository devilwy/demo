<?php
/*~ class.smtp.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer - PHP email class                                    |
|   Version: 2.1                                                            |
|   Contact: via sourceforge.net support pages (also www.codeworxtech.com)  |
|      Info: http://phpmailer.sourceforge.net                               |
|   Support: http://sourceforge.net/projects/phpmailer/                     |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost (project admininistrator)                         |
|    Author: Brent R. Matzelle (original founder)                           |
| Copyright (c) 2004-2007, Andy Prevost. All Rights Reserved.               |
| Copyright (c) 2001-2003, Brent R. Matzelle                                |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| We offer a number of paid services (www.codeworxtech.com):                |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'

/**
 * SMTP is rfc 821 compliant and implements all the rfc 821 SMTP
 * commands except TURN which will always return a not implemented
 * error. SMTP also provides some utility methods for sending mail
 * to an SMTP server.
 * @package PHPMailer
 * @author Chris Ryan
 */
class SendSMS { // 13918611592
  private $spid = '';
  private $spsc = '';
  private $sppassword = '';
  private $sa = '';
  private $dc = '';
  private $host = '';
  private $port = 80;
  private $request = "/sms/mt";
  /**
   * Initialize the class so that the data is in a known state.
   * @access public
   * @return void
   */
  public function __construct(){
  	$config = include SYSLIB.'/config/sms.php';
	$this->spid = $config['spid'];
	$this->spsc = $config['spsc'];
	$this->sa   = $config['sa'];
	$this->dc   = $config['dc'];  
	$this->host = $config['host'];
	$this->port = $config['port'];
	$this->sppassword = $config['sppassword'];
  }
  /**
   * @param $da 手机号码
   * @param $sm 发送短信的内容
   * @param 
   * **/
  function singleMt($da , $sm) {
  	    $sm = iconv("UTF-8" , "GBK" , $sm);
		$request  = $this->request;
		$request .= "?command=MT_REQUEST&spid=".$this->spid."&spsc=".$this->spsc."&sppassword=".$this->sppassword;
		$request .= "&sa=".$this->sa."&da=".$da."&dc=".$this->dc."&sm=";
		$request .= $this->encodeHexStr($this->dc,$sm);			//下发内容转换HEX编码
		$content  = $this->doGetRequest($this->host,$this->port,$request);		//调用发送方法发送
		return $content;
	}
	
	/**
	 * 相同内容群发示例
	 * @param $das 8613472504787,8613472504787 ： 手机号码
	 * @param $sm  短信内容
	 * @return String
	 */
	function multiMt($das , $sm) {
		//拼接URI
		$sm = iconv("UTF-8" , "GBK" , $sm);
		$request  = $this->request;
		$request .= "?command=MULTI_MT_REQUEST&spid=".$this->spid."&spsc=".$this->spsc."&sppassword=".$this->sppassword;
		$request .= "&sa=".$this->sa."&das=".$das."&dc=".$this->dc."&sm=";
		$request .= $this->encodeHexStr($this->dc,$sm);				   	//下发内容转换HEX编码
		$content  = $this->doGetRequest($this->host,$this->port,$request);	//调用发送方法发送
		return $content;
	}
	
	/**
	 * 不同内容群发示例
	 * @param string $dasm  : 8613472504787/不同内容群发测试1,8613472504787/不同内容群发测试2,8613472504787/不同内容群发测试3
	 * @return String
	 */
	function multiXMt($dasm){
		if($dasm){ 
			//拼接URI
			$request = $this->request;
			$request.="?command=MULTIX_MT_REQUEST&spid=".$this->spid."&spsc=".$this->spsc."&sppassword=".$this->sppassword;
			$request.="&sa=".$this->sa."&dc=".$this->dc."&dasm=";
			$instances= explode(",",$dasm);//拆分下发号码与内容
			$i=0;
			foreach ($instances as $value) {
				$i++;
				if($i > 100){
					break;
				}
				list($da , $sm)=explode("/",$value,2);
				$sm = iconv("UTF-8" , "GBK" , $sm);
				$sm = $this->encodeHexStr($this->dc,$sm);//下发内容转换HEX编码
				$request .= $da."/".$sm.",";
			}
			$content = $this->doPostRequest($this->host,$this->port,$request);//调用发送方法发送,只能使用POST方式
			return $content;
		}else{ 
			return "";
		}
	}
  
  function doGetRequest($host,$port,$request) {
		$method="GET";
		return $this->httpSend($host,$port,$method,$request);
	}
	function doPostRequest($host,$port,$request) {
		$method="POST";
		return $this->httpSend($host,$port,$method,$request);
	}
	/**
	 * 使用http协议发送消息
	 *
	 * @param string $host
	 * @param int $port
	 * @param string $method
	 * @param string $request
	 * @return string
	 */
	function httpSend($host,$port,$method,$request) {
		$httpHeader  = $method." ". $request. " HTTP/1.1\r\n";
		$httpHeader .= "Host: $host\r\n";
		$httpHeader .= "Connection: Close\r\n";
		//	$httpHeader .= "User-Agent: Mozilla/4.0(compatible;MSIE 7.0;Windows NT 5.1)\r\n";
		$httpHeader .= "Content-type: text/plain\r\n";
		$httpHeader .= "Content-length: " . strlen($request) . "\r\n";
		$httpHeader .= "\r\n";
		$httpHeader .= $request;
		$httpHeader .= "\r\n\r\n";
		$fp = @fsockopen($host, $port,$errno,$errstr,5);
		$result = "";
		if ( $fp ) {
			fwrite($fp, $httpHeader);
			while(! feof($fp)) { //读取get的结果
				$result .= fread($fp, 1024);
			}
			fclose($fp);
		}else{
			return "连接短信网关超时！";//超时标志
		}
		list($header, $foo)  = explode("\r\n\r\n", $result);
		list($foo, $content) = explode($header, $result);
		$content=str_replace("\r\n","",$content);
		//返回调用结果
		return $content;
	}
	/**
	 *  decode Hex String
	 *
	 * @param string $dataCoding       charset
	 * @param string $hexStr      convert a hex string to binary string
	 * @return string binary string
	 */
	function decodeHexStr($dataCoding, $hexStr)
	{
		$hexLenght = strlen($hexStr);
		// only hex numbers is allowed
		if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexStr)) return FALSE;
	
		unset($binString);
		for ($x = 1; $x <= $hexLenght/2; $x++)
		{
			$binString .= chr(hexdec(substr($hexStr,2 * $x - 2,2)));
		}
	
		return $binString;
	}
	
	/**
	 * encode Hex String
	 *
	 * @param string $dataCoding
	 * @param string $realStr
	 * @return string hex string
	 */
	function encodeHexStr($dataCoding, $realStr) {
		return bin2hex($realStr);
	}
	
	function mobile_validate($mobile){
		//手机
		if(!preg_match('/^[1][3458][0-9]{9}$/', $mobile)){
			return false;
		}else{
			return true;
		}
	}
	
}


?>
