<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
$cats  = get_cat_list();//所有分类
	
if(isset($_REQUEST['id']))$id = intval($_REQUEST['id']);
if(empty($id)) {
	header("Location: ./\n");
	exit;
}

/*取信息*/
$sql = "SELECT a.*,c.catname,r.areaname FROM {$table}info AS a LEFT JOIN {$table}category AS c ON c.catid=a.catid LEFT JOIN {$table}area AS r ON r.areaid = a.areaid WHERE id='$id'";
$info = $db->getrow($sql);
if(empty($info)){die('信息不存在');}
$info['content'] = strip_tags($info['content']);
extract($info);
if(!$is_check)die('信息尚未审核，审核后可浏览！');
//$content = str_replace("\n","<br />", htmlspecialchars($content));

if(!$CFG['expired_view']) {
	if($enddate<time() && $enddate>0) {
		$phone=empty($phone) ? '' : '已过期';
		$email=empty($email) ? '' : '已过期';
		$qq=empty($qq) ? '' : '已过期';
	}
}
$images = $db->getAll("SELECT * FROM {$table}info_image WHERE infoid = '$id' ");
$postdate = date('Y年m月d日', $postdate);
$lastdate = enddate($enddate);
$custom = get_info_custom($id);
$db->query("UPDATE {$table}info SET click=click+1 WHERE id='$id'");
$seo['title'] = $CFG['webname'].'手机版';
$cats44  = get_cat_list();//所有分类
include tpl('view');
?>