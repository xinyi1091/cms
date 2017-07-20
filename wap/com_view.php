<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
$cats  = get_cat_list();//所有分类	
$comid = intval($_REQUEST['id']);
	if(empty($comid)) showmsg('缺少参数！');
	$com_info = $db->getRow("select * from {$table}com where comid='$comid' ");
	if(empty($com_info)) showmsg('信息不存在','index.php');
	unset($com_info['userid']);
	extract($com_info);
	if(!$is_check)showmsg('黄页尚未审核，审核后可浏览！');
	$mappoint = $mappoint ? explode(',', $mappoint) : '';
	$thumb = PHPMPS_PATH.$thumb;
	
	$res = $db->query("select * from {$table}com_image where comid='$comid' ");
	$com_images = array();
	while($row=$db->fetchRow($res)) {
		$row['path'] = PHPMPS_PATH . $row['path'];
		$com_images[] = $row;
	}
	$db->query("UPDATE {$table}com SET click=click+1 WHERE comid='$comid'");
	
	$res = $db->query("select * from {$table}com order by comid desc,click desc limit 10");
	$match_com = array();
	while($row=$db->fetchrow($res)) {
		$row['sname'] = cut_str($row['comname'],14);
		$row['postdate'] = date('y-m-d', $row['postdate']);
		$row['url'] = url_rewrite('com', array('act'=>'view', 'comid'=>$row['comid']));
		$match_com[] = $row;
	}
	
	$cat_info = get_com_cat_info($catid);
	$here_arr[] = array('name'=>$cat_info['catname'],'url'=>url_rewrite('com',array('act'=>'list','catid'=>$catid)));
	$here = get_here($here_arr);

$seo['title'] = $CFG['webname'].'手机版';
include tpl('com_view');
?>