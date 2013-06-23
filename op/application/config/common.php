<?php
	/*
	 * 2012 10 18
	 * 长江商学院基本配置
	 * wenyong
	 */
	
	//**用户身份
	$config['common']['usertype'] = array(
	 										'guest' => array('v' => 1,'s' => '游客'),
											'member' => array('v' => 2,'s' => '会员'),				 
	 										'student' => array('v' => 3,'s' => '学生'),	  
	 										'teacher' => array('v' => 4,'s' => '教授'),	   
	 										'master' => array('v' => 5,'s' => '班主任'),	    
	 									   );
	
	//**用户状态
	$config['common']['userstatu'] = array(
											'unconfirm' => array('v' => 0,'s' => '邮箱未验证'),	 
											'normal' => array('v' => 1,'s' => '正常'),	
											'delete' => array('v' => 2,'s' => '删除'),
											'freeze' => array('v' => 3,'s' => '被冻结'),		
										  );
	
	//**用户性别
	$config['common']['sex'] = array(
									'man' => array('v' => 1,'s' => '男'),	
									'woman' => array('v' => 1,'s' => '女'),
									);
	
	//**用户权限
	$config['common']['competence'] = array(
												'normal' => array('v' => 1,'s' => '基本设置'),
											);
	
	$config['common']['meg_type'] = array(
											'news' => array('v' => 1,'s' => '长江新闻'),
											'notice' => array('v' => 2,'s' => '系统公告'),
											'act' => array('v' => 3,'s' => '长江活动'),
										 );
	
	
	
	
	
	
	 
	

	 

	 



?>