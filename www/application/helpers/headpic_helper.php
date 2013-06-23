<?php

	class ImgUpload{
		
		public $web_root_path;
		public $root_path;
		
		public $web_path;
		public $path;
		public $date_uid;
		
		public function __construct(){
			$this->web_root_path =  'http://'.$_SERVER['HTTP_HOST'].'/upload/';
			$this->root_path = $_SERVER['DOCUMENT_ROOT']."/upload/";
		}
		
		//**获取绝对路径和相对路径
		public function getPath($userid,$web = 0) {
			$md5userid = md5($userid);
			$dir1 = $this->root_path.substr($md5userid,0,2).'/';
			$dir2 = $dir1.substr($md5userid,2,2).'/';
			$dir3 = $dir2.$userid.'/';
			$this->path = $dir2;
			
			if ($web) {
				$dir_web = $this->web_root_path.substr($md5userid,0,2).'/';
			}
			else
				$dir_web = $this->path;
			
			$this->web_path = $dir_web;
		}
		
		//**上传文件
		public function upload_image($tmp,$config,$uid,$img){
			$this->createFolder($this->path);
			$filename = $this->createFileName($tmp,$uid);
			$return_result = array();
	    	if(!empty($tmp['error'])){
	    		switch($tmp['error']){
	    			case '1':
	    				$return_result['msg'] = '上传的文件太大';
	    				$return_result['error'] = 1;
	    				break;
	    			case '2':
	    				$return_result['msg'] = '上传的文件太大';
	    				$return_result['error'] = 2;
	    				break;
	    			case '3':
	    				$return_result['msg'] = '上传的文件只有部分被上传';
	    				$return_result['error'] = 3;
	    				break;
	    			case '4':
	    				$return_result['msg'] = '没有文件被上传。';
	    				$return_result['error'] = 4;
	    				break;
	    			case '6':
	    				$return_result['msg'] = '缺少一个临时文件夹';
	    				$return_result['error'] = 6;
	    				break;
	    			case '7':
	    				$return_result['msg'] = '文件写入磁盘失败';
	    				$return_result['error'] = 7;
	    				break;
	    			case '8':
	    				$return_result['msg'] = '用户停止文件上传';
	    				$return_result['error'] = 8;
	    				break;
	    			case '999':
	    			default:
	    				$return_result['msg'] = '未知错误';
	    				$return_result['error'] = 999;
	    		}
	    	}
	    	else if(empty($tmp['tmp_name']) || $tmp['tmp_name'] == 'none'){
	    		$return_result['msg'] = '没有文件被上传';
	    		$return_result['error'] = 44;
	    	}
			else if($tmp['size']>$config['size']){ 
				$return_result = array('error' => -2,'msg' => $config['size_intro']);
			}
			else{
				$ext = strtolower(strrchr($tmp['name'],'.'));
				if(!in_array($ext, $config['file_ext']))
					$return_result = array('error' => 9,'msg' => '上传格式不正确');
				if(move_uploaded_file($tmp['tmp_name'],$this->path.$filename)){
					if($img == 1)
						$return_result =  array('error' => 0,'msg' => $this->resizeImg($this->path,$filename,$config,$this->web_path));
					else
						$return_result =  array('error' => 0,'msg' => $this->web_path.$filename);
					@unlink($tmp['tmp_name']);
				}
				else
					$return_result = array('error' => 3,'msg' => '上传文件失败');				
			}
			return $return_result;
		}
	
		//**创建文件夹
		private function createFolder($path)
		{
		    if (!file_exists($path))
		    {
			     $this->createFolder(dirname($path));
			     mkdir($path, 0777);
		    }
		}

		//**随机生成文件名
		private function createFileName($tmp,$uid){
			$this->date_uid = $uid;
			$filename = isset($tmp['name'])?$tmp['name']:'';
			$ext = strrchr($filename,'.');
			return $this->date_uid.$ext;
		}
		
			//**压缩图片
		public function resizeImg($path,$filename,$config,$web_path){
	    	$file_path = $path.$filename;
	    	$imginfo = getimagesize($file_path);
			$ext = strrchr($filename,'.');
			$normal_filename = $path.$filename;
			$tofilename = substr($filename,0,strlen($filename)-strlen($ext));
			$result = array('_n' => $web_path.$filename);
			foreach($config['resize'] as $k=>$v){
				if($imginfo[0]>$v[0] || $imginfo[1]>$v[1]){
					$small_filename = $path.$tofilename.$k.$ext;
					$dest_height = intval($imginfo[1] * $v[0] / $imginfo[0]);
					$img_dest = imageCreateTrueColor($v[0], $dest_height);
					if ($imginfo[2] == 1){
						$img_source = @imagecreatefromgif($file_path);
					}
					else if ($imginfo[2] == 2){
						$img_source = @imagecreatefromjpeg($file_path);
					}
					else if($imginfo[2] == 3){
						 $img_source = @imagecreatefrompng($file_path);
						 imagesavealpha($img_source, true);
						 imagealphablending($img_dest, false);
						 imagesavealpha($img_dest, true);
					}
	
					imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $v[0] ,$dest_height, $imginfo[0], $imginfo[1]);
					if ($imginfo[2] == 1) {
						imagegif($img_dest, $small_filename); 
					}
					else if($imginfo[2] == 2) {
						imagejpeg($img_dest, $small_filename, $config["JPEG_Quality"]);
					}
					else if($imginfo[2] == 3) {
						imagepng($img_dest, $small_filename);
					}
					$result[$k] = $web_path.$tofilename.$k.$ext;
				}
//				else{
//					@copy($filename, $small_filename);
//				}
			}
			return $result;
		}		
		
		
		
	}



?>