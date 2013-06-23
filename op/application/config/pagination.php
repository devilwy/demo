<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$pagination['base_url'] = '';
$pagination['total_rows'] = 100;
$pagination['per_page'] = 10;
$pagination['uri_segment'] = 3;
$pagination['num_links'] = 3;

$pagination['full_tag_open'] = '<div class="grayr">';
$pagination['full_tag_close'] = '</div>';

$pagination['first_link'] = '首页';
$pagination['first_tag_open'] = '';
$pagination['first_tag_close'] = '';

$pagination['last_link'] = '末页';
$pagination['last_tag_open'] = '';
$pagination['last_tag_close'] = '';

$pagination['next_link'] = '下一页&raquo;';
$pagination['next_tag_open'] = '';
$pagination['next_tag_close'] = '';

$pagination['prev_link'] = '&laquo;上一页';
$pagination['prev_tag_open'] = '';
$pagination['prev_tag_close'] = '';

$pagination['cur_tag_open'] = '<a href="#" class="cur">';
$pagination['cur_tag_close'] = '</span>';

$pagination['num_tag_open'] = '';
$pagination['num_tag_close'] = '';

$pagination['disable_tag_open'] = '<span class="disabled">';
$pagination['disable_tag_close'] = '</span>';

$pagination['page_query_string'] = TRUE;
$pagination['enable_query_strings'] = TRUE;
$pagination['query_string_segment'] = 'page';
$pagination['use_page_numbers'] = TRUE;


?>