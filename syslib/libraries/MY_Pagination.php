<?php

class MY_Pagination extends CI_Pagination{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_page($params = array()){
		$config = array();

		$config['base_url'] = '';
		$config['total_rows'] = 100;
		$config['per_page'] = 12;
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;

		$config['full_tag_open'] = '<div class="paging">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = '首页';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = '末页';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<a href="#" class="current">';
		$config['cur_tag_close'] = '</a>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function get_page_1($params = array()){
		$config = array();

		$config['base_url'] = '';
		$config['total_rows'] = 100;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;

		$config['full_tag_open'] = '<div class="grayr">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = '首页';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = '末页';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页&raquo;';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '&laquo;上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<a href="#" class="cur">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['disable_tag_open'] = '<span class="disabled">';
		$config['disable_tag_close'] = '</span>';

		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function client(){
		
	}
	
	public function www(){
		
	}
	
	public function ucenter($params = array()){
		$config = array();

		$config['base_url'] = '/ucenter/index/';
		$config['per_page'] = 5;
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;

		$config['full_tag_open'] = '<li class="page">';
		$config['full_tag_close'] = '</li>';

		$config['first_link'] = '首页';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = '末页';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['prev_class'] = 'fl';
		$config['next_class'] = 'fr';

		$config['cur_tag_open'] = '<a href="#" class="cur">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['disable_tag_open'] = '<span class="disabled">';
		$config['disable_tag_close'] = '</span>';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['display_pages'] = FALSE;
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function artist_record($params = array()){
		$config = array();

		$config['base_url'] = '/artist/record';
		$config['per_page'] = 5;
		$config['uri_segment'] = 3;      //设置链接带的参数总数
		$config['num_links'] = 5;

		$config['full_tag_open'] = '<div class="page">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = false;
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = false;
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['prev_class'] = 'prev';
		$config['next_class'] = 'next';

		$config['cur_tag_open'] = '<a href="#" class="cur">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['disable_tag_open'] = '<span class="disabled">';
		$config['disable_tag_close'] = '</span>';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function artist_search($params = array()){
		$config = array();

		$config['base_url'] = "";
		$config['per_page'] = 5;
		$config['uri_segment'] = 5;     //设置链接带的参数总数
		$config['num_links'] = 5;

		$config['full_tag_open'] = '<div class="page">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = false;
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = false;
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['prev_class'] = 'prev';
		$config['next_class'] = 'next';

		$config['cur_tag_open'] = '<a href="#" class="cur">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['disable_tag_open'] = '<span class="disabled">';
		$config['disable_tag_close'] = '</span>';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function login_record($params = array()){
		$config = array();

		$config['base_url'] = '/system/index';
		$config['total_rows'] = 100;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;

		$config['full_tag_open'] = '<div class="page">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = false;
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = false;
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<span class="current">';
		$config['cur_tag_close'] = '</span>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['disable_tag_open'] = '<span class="disabled">';
		$config['disable_tag_close'] = '</span>';
		
		$config['prev_no_link'] = '<span class="disabled">上一页</span>';
		$config['next_no_link'] = '<span class="disabled">下一页</span>';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}
	
	public function rl_record($params = array()){
		$config = array();

		$config['base_url'] = '';
		$config['total_rows'] = 100;
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;

		$config['full_tag_open'] = '<div class="page fl">';
		$config['full_tag_close'] = '</div>';

		$config['first_link'] = false;
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = false;
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '下一页';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '上一页';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<a class="cur" href="#">';
		$config['cur_tag_close'] = '</a>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$config['page_query_string'] = FALSE;
		$config['enable_query_strings'] = FALSE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		$this->initialize(array_merge($config, $params));
		return $this->create_links();
	}

}