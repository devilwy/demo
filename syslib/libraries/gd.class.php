<?php
class GD
{
	
	/**
	 * js 裁剪 - 图片
	 * **/
	function create_thumb_js($image , $thumbw , $thumbh , $imagex , $imagey , $imagew , $imageh){ 
		$size	  = getimagesize($image); // 获取原图大小
		$new_name = $thumbw.'_'.$thumbh.'_'.end(explode('/',$image));
		$get_path = str_replace(end(explode('/',$image)),"",$image);
		$src_img  = $this->___get_img($image,$size[2]);
		if(function_exists("imagecreatetruecolor")){
			$dst_img = imagecreatetruecolor($thumbw, $thumbh);
			if($size[2]==3){
				imagealphablending($dst_img,false);		//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
				imagesavealpha($dst_img,true);			//这里很重要,意思是不要丢了$thumb图像的透明色;
			}
		}else{
			$dst_img = imagecreate($thumbw, $thumbh); // 创建目标图
		}
		
		$back = ImageColorAllocate($dst_img, 255,255,255); // 填充的背景色
		imagefill($dst_img,0,0,$back);
		if(function_exists("ImageCopyResampled")){
			imagecopyresampled($dst_img,$src_img,0,0,$imagex,$imagey,$thumbw,$thumbh,$imagew,$imageh);
		}else{
			ImageCopyResized($dst_img,$src_img,0,0,$imagex,$imagey,$thumbw,$thumbh,$imagew,$imageh);
		}
		if($size[2] == 1){
			$return = imagegif($dst_img,$get_path.$new_name);
		}elseif($size[2] == 2){
			$return = imagejpeg($dst_img,$get_path.$new_name);
		}elseif($size[2] == 3){
			imagesavealpha($dst_img,true);
			$return = imagepng($dst_img,$get_path.$new_name);
		}else{
			#[如果不存在这些条件，那将文件改为jpg的缩略图]
			$newfile = $get_path.$new_name.".jpg";
			if(file_exists($newfile)){
				unlink($newfile);
			}
			imagejpeg($dst_img,$newfile);
		}
		ImageDestroy($src_img);
		ImageDestroy($dst_img);
		if($return){
//			return end(explode('/',$get_path.$new_name));
			return $get_path.$new_name;
		}
	}
	
	/**
	 * 缩略图生成函数，代替GD库
	 * @author sz@gootop.net  
	 * @Date 2011-9-1
	 * @return 
	 */
	function create_thumb($image,$thumbw,$thumbh){
		$size = getimagesize($image); // 获取原图大小
		if($thumbw>0 && $thumbh>0){
			$scale = min($thumbw/$size[0], $thumbh/$size[1]); // 计算缩放比例
		}else{
			$scale = $thumbw/$size[0]>0 ? $thumbw/$size[0] : $thumbh/$size[1];
		}
		
		if($size[0] <= $thumbw){
			$width = $size[0];
			if($thumbh>0 && $size[1]>=$thumbh){
				$height = $thumbh;
			}else{
				$height = $size[1];
			}
		}else{
			$width = (int)($size[0]*$scale);
			$height = (int)($size[1]*$scale);
		}
		
		if($thumbw==0){
			$thumbw = $width;
		}
		if($thumbh==0){
			$thumbh = $height;
		}
		//echo '|<br/>'.$width.'<br/>'.$height;exit;
		$deltaw = (int)(($thumbw - $width)/2);
		$deltah = (int)(($thumbh - $height)/2);
		$new_name = $thumbw.'_'.$thumbh.'_'.end(explode('/',$image));
		$get_path = str_replace(end(explode('/',$image)),"",$image);
		$per1 = round($size[0]/$size[1],2);//计算原图长宽比
		$per2 = round($thumbw/$thumbh,2);//计算缩略图长宽比
		
		$src_img = $this->___get_img($image,$size[2]);
		
		if(function_exists("imagecreatetruecolor")){
			$dst_img = imagecreatetruecolor($thumbw, $thumbh); // 创建目标图
			if($size[2]==3){
				imagealphablending($dst_img,false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
				imagesavealpha($dst_img,true);//这里很重要,意思是不要丢了$thumb图像的透明色;
			}
		}else{
			$dst_img = imagecreate($thumbw, $thumbh); // 创建目标图
		}
		
		$back = ImageColorAllocate($dst_img, 255,255,255); // 填充的背景色
		imagefill($dst_img,0,0,$back);
		
		if(function_exists("ImageCopyResampled")){
			ImageCopyResampled($dst_img, $src_img, $deltaw, $deltah, 0, 0, $width, $height, ImageSX($src_img),ImageSY($src_img)); // 复制图片
		}else{
			ImageCopyResized($dst_img, $src_img, $deltaw, $deltah, 0, 0, $width, $height, ImageSX($src_img),ImageSY($src_img)); // 复制图片
		}
		
		if($size[2] == 1)
		{
			$return = imagegif($dst_img,$get_path.$new_name);
		}
		elseif($size[2] == 2)
		{
			$return = imagejpeg($dst_img,$get_path.$new_name);
		}
		elseif($size[2] == 3)
		{
			imagesavealpha($dst_img,true);
			$return = imagepng($dst_img,$get_path.$new_name);
		}else{
			#[如果不存在这些条件，那将文件改为jpg的缩略图]
			$newfile = $get_path.$new_name.".jpg";
			if(file_exists($newfile))
			{
				unlink($newfile);
			}
			imagejpeg($dst_img,$newfile);
		}
		ImageDestroy($src_img);
		ImageDestroy($dst_img);
		if($return){
			return end(explode('/',$get_path.$new_name));
		}
	}
	
	function ___get_img($pic,$type)
	{
		if($type == 1)
		{
			$img = imagecreatefromgif($pic);
		}
		elseif($type == 2)
		{
			$img = imagecreatefromjpeg($pic);
		}
		elseif($type == 3)
		{
			$img = imagecreatefrompng($pic);
		} else {
			$img = "";
		}
		unset($pic,$type);
		return $img;
	}
}
?>