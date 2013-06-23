<?php
require_once SYSLIB.'libraries/base.lib.php';
/**
 * controller
 */
class Controller extends baseController {
	public function __construct() {
		parent :: __construct();
		$this->load->model('opmodel','op');
		$uid = $this->getSession('uid');
		if(false != $uid && false != $this->getSession('is_logged_in')){
			$r = $this->op->getAclUserGroup(array('`user_id`'=>$uid));
			if($r){
				$this->menu = $this->op->makeUserMenu($r['group_id']);
			}else{
				$this->menu = false;
			}
			$this->manager_info = $this->op->getManagerMem($uid);
		}else{
			$this->menu = false;
		}
	}

	/**
	 * login validate
	 */
	protected function _validate(){
		return (bool)$this->user_info;
	}
	
	
	protected function getDefaultData($flag,$nav){
		$data['managerInfo'] = $this->manager_info;
		$data['flag'] 		 = $flag;
		$data['menu'] 		 = $this->menu;
		//$data['submenu'] 	 = $this->submenu;
		$data['nav'] 		 = $nav;
		return $data;
	}
	
	
	/**
	 * 修改 分页方法
	 * @param  $ajaxmethod -- ajax方法时，传的是function名 url时传的是分页前的部分url  如 http://abc.com/s.php?page=1&id=6则传值"http://abc.com/s.php?page=" 若带/的，把/也需传入，如http://abc.com/p/1/abc则传值为"http://abc.com/p/"  后半部分用$lasturl传入
	 * @param  $total_rows -- 总记录的条数
	 * @param  $cur_page   -- 当前的页码
	 * @param  $per_page   -- 每页的条数
	 * @param  $ftype-0-ajax分页,1-URL分页
	 * @param  $lasturl url链接时用到（分页的后半部分）
	 * 
	 * <div class="page"><span class="disabled">上一页</span><span class="current">1</span><a href="/system/index/2">2</a><a href="/system/index/3">3</a><a href="/system/index/2">下一页</a></div>
	 * 
	 * **/
	public function returnpagenum($ajaxmethod , $total_rows , $cur_page , $per_page = 10 , $ftype = 0 , $lasturl = ''){
		$allpagenum = ceil($total_rows / $per_page);	
		$vp = '<div class="page">';	
		if($cur_page > 1){
		  if($ftype == 0)
		  {
				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . ($cur_page - 1) . ');">上一页</a> ';
			}
			else
			{
				$vp .= '<a href="'.$ajaxmethod . ($cur_page - 1) . $lasturl . '">上一页</a> ';
			}
		}else{
			$vp .= '<span class="disabled">上一页</span> ';
		}
		if($allpagenum <= 5){
			for($i = 1 ; $i <= $allpagenum ; $i++){
				if($i == $cur_page){
					 $vp .= '<span class="current">'.$i.'</span>';
				}else{
					if($ftype == 0)
		 		  {
						$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
					}
					else
					{
						$vp .= '<a href="'.$ajaxmethod . $i . $lasturl . '">' . $i . '</a> ';
					}
				}
			}
		} else {
		    if($cur_page == 1 || $cur_page == 2){
				for($i = 1 ; $i < 4 ; $i++){
					if($i == $cur_page){
		    			$vp .= '<span class="current">'.$i.'</span>';
		    		}
		    		else{
		    			if($ftype == 0)
		 		  		{
		    				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
		    			}
		    			else
		    			{
		    				$vp .= '<a href="'.$ajaxmethod . $i . $lasturl . '">' . $i . '</a> ';
		    			}
		    		}
				}
				$vp .= '<span>...</span> ';
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . $allpagenum  . $lasturl . '">' .$allpagenum . '</a> ';
				}
			}else if($cur_page >= $allpagenum || $cur_page == ($allpagenum-1)){
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . '1'  . $lasturl . '">1</a> ';
				}
				$vp .= '<span>...</span> ';
				for($i = $allpagenum - 2 ; $i <= $allpagenum ; $i++){
					if($i == $cur_page){
		    			$vp .= '<span class="current">'.$i.'</span>';
		    		}
		    		else{
		    			if($ftype == 0)
		 					{
		    				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
		    			}
		    			else
		    			{
		    				$vp .= '<a href="'.$ajaxmethod . $i . $lasturl . '">' . $i . '</a> ';
		    			}
		    		}
				}
			}else if($cur_page == 3){
				for($i = 1 ; $i <= 4 ; $i++){
					if($i == $cur_page){
		    			$vp .= '<a class="now" style="cursor:pointer;">'.$i.'</a>';
		    		}else{
		    			if($ftype == 0)
		 					{
		    				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
		    			}
		    			else
		    			{
		    				$vp .= '<a href="'.$ajaxmethod . $i . $lasturl . '">' . $i . '</a> ';
		    			}
		    		}
				}
				$vp .= '<span>...</span> ';
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . $allpagenum . $lasturl . '">' .$allpagenum . '</a> ';
				}
			}else if($cur_page == ($allpagenum-2)){
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . '1'  . $lasturl . '">1</a> ';
				}
				$vp .= '<span>...</span> ';
				for($i = $allpagenum - 3 ; $i <= $allpagenum ; $i++){
					if($i == $cur_page){
		    			$vp .= '<span class="current">'.$i.'</span>';
		    		}else{
		    			if($ftype == 0)
		 					{
		    				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
		    			}
		    			else
		    			{
		    				$vp .= '<a href="' . $ajaxmethod . $i  . $lasturl . '">' . $i . '</a> ';
		    			}
		    		}
				}
			}else{
				$from = $cur_page - 1;
				$get  = $cur_page + 1;
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(1);">1</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . '1'  . $lasturl . '">1</a> ';
				}
				$vp .= '<span>...</span> ';
				for($i = $from ; $i <= $get ; $i++){
					if($i == $cur_page){
		    			$vp .= '<span class="current">'.$i.'</span>';
		    		}else{
		    			if($ftype == 0)
		 					{
		    				$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $i . ');">' . $i . '</a> ';
		    			}
		    			else
		    			{
		    				$vp .= '<a href="' . $ajaxmethod . $i  . $lasturl . '">' . $i . '</a> ';
		    			}
		    		}
				}
				$vp .= '<span>...</span> ';
				if($ftype == 0)
		 		{
					$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . $allpagenum . ');">' .$allpagenum . '</a> ';
				}
				else
				{
					$vp .= '<a href="' . $ajaxmethod . $allpagenum . $lasturl . '">' .$allpagenum . '</a> ';
				}
			}
		}
		if($cur_page<$allpagenum){
		 	if($ftype == 0)
		 	{
		 		$vp .= '<a href="javascript:void(0);" onclick="'.$ajaxmethod.'(' . ($cur_page + 1) . ');">下一页</a> ';
		 	}
		 	else
		 	{
		 		$vp .= '<a href="' . $ajaxmethod . ($cur_page + 1) . $lasturl . '">下一页</a> ';
		 	}
		}else{
			$vp .= '<a href="javascript:void(0);" >下一页</a> ';
		}
//		$vp .= "</div>";
		$vp .= '&nbsp;共'.$allpagenum.'页&nbsp;共'.$total_rows.'条&nbsp;</div>';
		return $vp;
	}
	
}