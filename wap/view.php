<?php

define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
$cats  = get_cat_list();//���з���
	
if(isset($_REQUEST['id']))$id = intval($_REQUEST['id']);
if(empty($id)) {
	header("Location: ./\n");
	exit;
}

/*ȡ��Ϣ*/
$sql = "SELECT a.*,c.catname,r.areaname FROM {$table}info AS a LEFT JOIN {$table}category AS c ON c.catid=a.catid LEFT JOIN {$table}area AS r ON r.areaid = a.areaid WHERE id='$id'";
$info = $db->getrow($sql);
if(empty($info)){die('��Ϣ������');}
$info['content'] = strip_tags($info['content']);
extract($info);
if(!$is_check)die('��Ϣ��δ��ˣ���˺�������');
//$content = str_replace("\n","<br />", htmlspecialchars($content));

if(!$CFG['expired_view']) {
	if($enddate<time() && $enddate>0) {
		$phone=empty($phone) ? '' : '�ѹ���';
		$email=empty($email) ? '' : '�ѹ���';
		$qq=empty($qq) ? '' : '�ѹ���';
	}
}
$images = $db->getAll("SELECT * FROM {$table}info_image WHERE infoid = '$id' ");
$postdate = date('Y��m��d��', $postdate);
$lastdate = enddate($enddate);
$custom = get_info_custom($id);
$db->query("UPDATE {$table}info SET click=click+1 WHERE id='$id'");
$seo['title'] = $CFG['webname'].'�ֻ���';
$cats44  = get_cat_list();//���з���
include tpl('view');
?>