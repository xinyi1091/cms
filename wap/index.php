<?php
define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';

$seo['title'] = $CFG['webname'] .' - '.$CFG['webname'];
$seo['keywords'] = $CFG['keywords'];
$seo['description'] = $CFG['description'];
$coms  = get_index_com('10');//��ҳ��Ҷ
$articles   = get_index_article('10');//����
$cats  = get_cat_list();//���з���
$new_info  = get_info('','','10','','date','40');//������Ϣ
if(!empty($new_info)) {
	foreach ($new_info as $val) {
		$val['title'] = encode_output($val['title']);
		$val['areaname'] = encode_output($val['areaname']);
		$val['phone'] = encode_output($val['phone']);
	}
}

$seo['title'] = $CFG['webname'].'�ֻ���';
include tpl('index');
?>