<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="wb:webmaster" content="e87b4a31495ff2e7" />
<title>用户设置</title>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jsconf.js"></script>
<!–[if IE 6]>
<script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/DD_belatedPNG_0.0.8a-min.js"></script>
<!–[if IE]>
<script src="<?php echo STATIC_DOMAIN;?>www/js/html5.js"></script>
<![endif]–>
<script>
	DD_belatedPNG.fix('img,.header-content,.pwd-btn,.reg,.regbody');
</script>
<![endif]–>
</head>
<body>
<div>
	<table>
	    <form enctype="multipart/form-data" method="post" name="upform" target="upload_target" action="<?php echo site_url('user/uploadHeadPic'); ?>">
		<tr>
			<td>头像:</td>
			<td>
                <dt><img src="<?php echo $userinfo[''];?>"  height=80px width=80px id="head_image_pic"></dt>
                <dd> 
                    <span>
                    	<input type="file" name='Filedata' id='Filedata'>
						<input style="margin-right:20px;" type="submit" name="" value="上传形象照" onclick="return checkFile();" /><span style="visibility:hidden;" id="loading_gif"><img src="<?php echo STATIC_DOMAIN;?>www/avatar2/loading.gif" align="absmiddle" />上传中，请稍侯......</span>                   		
                    </span>
                    <p>支持JPEG、PNG、GIF文件，最大500KB</p>
                    <iframe src="about:blank" name="upload_target" style="display:none;"></iframe>
				    <div id="avatar_editor"></div>
                </dd>			
			</td>
		</tr>
		</form>
		<form id="info_form" name="info_form" method="post">
		<tr>
			<td>性别:</td>
			<td>
			<input type="radio" name="sex"  value="<?php echo $common_conf['sex']['man'];?>" />&nbsp;男&nbsp;&nbsp;
			<input type="radio" name="sex"  value="<?php echo $common_conf['sex']['woman'];?>" />&nbsp;女
			</td>
		</tr>
		<tr>
			<td>手机:</td>
			<td>
				<input type="text" value="" name="mobile"  id="mobile" />
			</td>
		</tr>
		<tr>
			<td>个性签名:</td>
			<td>
				<textarea name="sign" id="sign"></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<a style="cursor:pointer;" id="info_submit">提交</a>&nbsp;&nbsp;<a href="<?php echo site_url('user');?>">个人中心</a>
			    <input type="hidden" value="" id='head_image' name="head_image">
              	<input type="hidden"  id='head_image_path' name="head_image_path" value='<?php echo isset($this->userinfo['headpic']['ori'])?$this->userinfo['headpic']['ori']:''; ?>' />    
			</td>
		</tr>	
		</form>
	</table>
</div>


  <!--========== footer ===============-->
  <footer>
    
  </footer>
  <!--========== footer end ===============--> 
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/jquery.validate.js"></script>
  <script type="text/javascript" src="<?php echo STATIC_DOMAIN;?>www/js/userinfo.js"></script>

</body>
</html>
<script>


var base_url = WWWBASEURL;
//允许上传的图片类型
var extensions = 'jpg,jpeg,gif,png';
//保存缩略图的地址.
var saveUrl = base_url+'/avatar2/save_avatar.php';
//保存摄象头白摄图片的地址.
var cameraPostUrl = base_url+'/avatar2/camera.php';
//头像编辑器flash的地址.
var editorFlaPath = base_url+'/avatar2/AvatarEditor.swf?2';
//Download by http://www.codefans.net
function useCamera()
{
	var content = '<embed height="464" width="514" ';
	content +='flashvars="type=camera';
	content +='&postUrl='+cameraPostUrl+'?&radom=1';
	content += '&saveUrl='+saveUrl+'?radom=1" ';
	content +='pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" ';
	content +='allowscriptaccess="always" quality="high" ';
	content +='src="'+editorFlaPath+'"/>';
	document.getElementById('avatar_editor').innerHTML = content;
}

function buildAvatarEditor(pic_id,pic_path,post_type)
{
	$('#head_image').val(pic_id);
	$('#head_image_path').val(pic_path);
	$('#avatar_editor').show();
	var content = '<embed height="464" width="514"'; 
	content+='flashvars="type='+post_type;
	content+='&photoUrl='+pic_path;
	content+='&photoId='+pic_id;
	content+='&postUrl='+cameraPostUrl+'?&radom=1';
	content+='&saveUrl='+saveUrl+'?radom=1"';
	content+=' pluginspage="http://www.macromedia.com/go/getflashplayer"';
	content+=' type="application/x-shockwave-flash"';
	content+=' allowscriptaccess="always" quality="high" src="'+editorFlaPath+'"/>';
	document.getElementById('avatar_editor').innerHTML = content;
}
	/**
	  * 提供给FLASH的接口 ： 没有摄像头时的回调方法
	  */
function noCamera(){
	 //alert("俺是小狗, 俺没有camare ：）");
}
			
/**
 * 提供给FLASH的接口：编辑头像保存成功后的回调方法
 */
function avatarSaved(){
	//alert('保存成功，哈哈');
	//window.location.href = '/profile.do';
	var id = $('#head_image').val();
	var path = $('#head_image_path').val();
	var arr = path.split(id);
	var small_path = arr[0]+id+"_small"+arr[1];
	$('#head_image_pic').attr('src',small_path);
	hideLoading();
	$('#avatar_editor').hide();
}
	
 /**
  * 提供给FLASH的接口：编辑头像保存失败的回调方法, msg 是失败信息，可以不返回给用户, 仅作调试使用.
  */
 function avatarError(msg){
	 alert("上传失败了呀，哈哈");
	 $('#avatar_editor').hide();
 }

 function checkFile()
 {
	 var path = document.getElementById('Filedata').value;
	 var ext = getExt(path);
	 var re = new RegExp("(^|\\s|,)" + ext + "($|\\s|,)", "ig");
	  if(extensions != '' && (re.exec(extensions) == null || ext == '')) {
	 alert('对不起，只能上传jpg, gif, png类型的图片');
	 return false;
	 }
	 showLoading();
	 return true;
 }

 function getExt(path) {
	return path.lastIndexOf('.') == -1 ? '' : path.substr(path.lastIndexOf('.') + 1, path.length).toLowerCase();
}
      function	showLoading()
	  {
		  document.getElementById('loading_gif').style.visibility = 'visible';
	  }
	  function hideLoading()
	  {
		document.getElementById('loading_gif').style.visibility = 'hidden';
	  }
</script>