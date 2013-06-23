<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class Common extends CI_Controller {

	public function index(){
		//$this->load->view('welcome_message');
	}
	
	/*
	 * 生成验证码
	* 2012 03 24
	* wenyong
	*/
	public function seccode()
	{
		$data = $this->input->post();
		if(empty($data)){
			Header("Content-type: image/gif");
			/*
			 * 初始化
			*/
			$border = 0; //是否要边框 1要:0不要
			$how = 4; //验证码位数
			$w = $how*18; //图片宽度
			$h = 20; //图片高度
			$fontsize = 20; //字体大小
			$alpha = "abcdefghijkmnpqrstuvwxyz"; //验证码内容1:字母
			$number = "23456789"; //验证码内容2:数字
			$randcode = ""; //验证码字符串初始化
			srand((double)microtime()*1000000); //初始化随机数种子
			$im = ImageCreate($w, $h); //创建验证图片
			/*
			 * 绘制基本框架
			*/
			$bgcolor = ImageColorAllocate($im, 255, 255, 255); //设置背景颜色
			ImageFill($im, 0, 0, $bgcolor); //填充背景色
			if($border)
			{
				$black = ImageColorAllocate($im, 0, 0, 0); //设置边框颜色
				ImageRectangle($im, 0, 0, $w-1, $h-1, $black);//绘制边框
			}
			/*
			 * 逐位产生随机字符
			*/
			for($i=0; $i<$how; $i++)
			{
			$alpha_or_number = mt_rand(0, 1); //字母还是数字
			$str = $alpha_or_number ? $alpha : $number;
			$which = mt_rand(0, strlen($str)-1); //取哪个字符
			$code = substr($str, $which, 1); //取字符
			$j = !$i ? 4 : $j+15; //绘字符位置
			$color3 = ImageColorAllocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100)); //字符随即颜色
			ImageChar($im, $fontsize, $j, 3, $code, $color3); //绘字符
			$randcode .= $code; //逐位加入验证码字符串
			}
			/*
			* 添加干扰
			*/
			 for($i=0; $i<$how*40; $i++)//绘背景干扰点
			{
			$color2 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰点颜色
			ImageSetPixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2); //干扰点
			}
			/*绘图结束*/
			Imagegif($im);
			ImageDestroy($im);
			$_SESSION['seccode'] = $randcode;
			//$this->load->library('Session');
			//$this->session->set_userdata('seccode', $randcode);
			/*绘图结束*/
		}
		else{
		//验证验证码
		//$seccode = isset($data['seccode'])?trim($data['seccode']):'';
			if(!($_SESSION['seccode'] == $data['seccode']))
				echo 'false';
			else
				echo 'true';
		}
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */