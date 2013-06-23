<?php
if (!defined('DEBUG')) {
    define('DEBUG', false);
}

/**
 * 调试类
 * @author zhangwy
 * $d  = new debug();
 * $d->add_item('var',array('a'=>'1','b'=>'2'));
 * echo $d->display();
 */
class debug {
	private $items = array();
	private $start_time;
	private $start_men;
	
	public function __construct(){
		$this->start_time	=	$this->microtime_float();
		$this->start_men		= 	memory_get_usage();
	}

	private function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	private function etime($tt){
		return number_format(($this->microtime_float() - $tt) * 1000,0)." ms";
	}

	private function ememory($tt){
		return (memory_get_usage() - $tt).'Byte;';
	}
	
	private function summary_info(){
		$this->add_item('Summary', array('Time of Request'=>$this->etime($this->start_time),'memory used'=>$this->ememory($this->start_men)));
		$this->add_item('Server', $_SERVER);
		$this->add_item('Cookie', $_COOKIE);
		
		if(isset($_SESSION))
			$this->add_item('SESSION',$_SESSION);
		else 
			$this->add_item('SESSION',array());
		$this->add_item('GET', $_GET);
		$this->add_item('POST', $_POST);
		$last_error = error_get_last();
		if($last_error)
			$this->add_item('last_error', $last_error);		
		$this->add_item('backtrace', $this->get_backtrace());
	}
	
	private function get_backtrace(){
		$data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$data = array_slice($data, 3);
		$ret = array();
		foreach($data as $k=>$v){
			$ret[] = 'line:'.$v['line'].', function:'.$v['function'].', file:'.$v['file'].', type:'.$v['type'];
		}
		return $ret;
	}
	
	/**
	 * 添加数据
	 * @param string $title
	 * @param array $data array('key'=>'value');
	 */
	public function add_item($title, $data){
		if(FALSE == DEBUG){
			return false;
		}
		
		$html = empty($data) ? '<div class="box cgray"><h4>'.$title.'</h4><div class="content"><ul>':
								'<div class="box"><h4>'.$title.'</h4><div class="content"><ul>';
		foreach($data as $k=>$v){
			$html .= '<li><em>'.$k.'</em><span>= '.$v.'</span></li>';
		}
    	$html .= '</ul></div></div>';
    	$this->items[$title] = $html;
	}
	
	public function display(){
		if(FALSE == DEBUG){
			return false;
		}
		
		$this->summary_info();
		sort($this->items);
		
		$data = '';
		foreach($this->items as $v){
			$data .= $v;
		}
		
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>debug信息</title>
<style>
* { margin:0px; padding:0px; }
body{ font-family:arial;}
li { list-style:none; }
.head { background: #005A9E; color: white; padding: 4px; margin:4px; font-size: 12pt; font-weight: bold; }
.box { font-weight: bold;  background: #7EA5D7; color: white; padding-left: 4px; padding-right: 4px; padding-bottom: 2px; margin:4px; }
.box h4{cursor: pointer;}
.content { background: #fff; color:#666; font-size:13px; }
.content li { overflow:hidden; width:99%; line-height:1.8em; padding:0 4px; border:1px dashed #ccc; }
.content li em { display:block; float:left; min-width:200px; font-style:normal;}
.content li span { display:block; float:left; }
.cgray{ color:gray;} 
</style>
<script src="http://code.jquery.com/jquery-1.7.1.min.js" type="text/javascript"></script>
<script>
$(function(){
	$(".box .content").hide();
	$(".box h4").click(function(){
		$(this).next().toggle();
	});
});
</script>
</head>
<body>
<div class="head">
  <h3>debug信息：</h3>
</div>'.$data.'</body></html>';
		unset($data);
		echo $html;
		exit;
	}
}
//end debug.lib.php 2012-03-14