var swfu;
window.onload = function () {
	swfu = new SWFUpload({
		upload_url: upload_url,
		post_params: {},
		// 文件上传设置
		file_size_limit : "2 MB",													// 2MB	最大2G
		file_types : "*.jpg;*.JPG;",			//设置选择文件的类型
		file_types_description : "JPG Images",				//这里是描述文件类型
		file_upload_limit : "0",													//0代表不受上传个数的限制
		file_queue_error_handler : fileQueueError,									//选择过文件后,把文件放入队列后,所触发的事件
		file_dialog_complete_handler : fileDialogComplete,							//这个和上面的查不多,当关闭选择框后,做触发的事件.
		upload_progress_handler : uploadProgress,									//处理上传进度条
		upload_error_handler : uploadError,											//错误事件处理
		upload_success_handler : uploadSuccess,										//上传成功后事件处理
		upload_complete_handler : uploadComplete,									//上传结束后事件处理
		//上传按钮设置
		button_image_url : "http://static.179.com/www/images/uploadbutton.png",
		button_placeholder_id : "uploadButton",
		button_width: 110,
		button_height: 27,
		button_text : '',
		button_text_style : '',
		button_text_top_padding: 0,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		// Flash文件路径配置
		flash_url : "http://www.179.com/upload/swfupload.swf",
		prevent_swf_caching : false,
        preserve_relative_urls : false,
//		custom_settings : {upload_target : "upload_target"},
		debug: false
	});
};