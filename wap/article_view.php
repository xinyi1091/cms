<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
$cats  = get_cat_list();//所有分类
	
if(isset($_REQUEST['id']))$id = intval($_REQUEST['id']);
if(empty($id)) {
	header("Location: ./\n");
	exit;
}

$id = intval($_REQUEST['id']);
	if(empty($id))  showmsg('缺少参数！');
	$article = $db->getRow("SELECT * FROM {$table}article WHERE id='$id'");
	if(empty($article))showmsg('不存在此文章', 'index.php');
	extract($article);
	$addtime = date('Y-m-d H:i', $addtime);
	/* 取得关连文章 */
	/* 上一条，下一条 */
	$sql = "select id,title from {$table}article where id>$id and typeid=$typeid ";
	$next = $db->getRow($sql);
	if(!empty($next))$next[url] = url_rewrite('article', array('act'=>'view','aid'=>$next['id']));
	$pid = $db->getOne("select max(id) from {$table}article where id<$id and typeid=$typeid limit 1");
	if(!empty($pid)) {
		$sql = "select id,title from {$table}article where id = '$pid' ";
		$pre = $db->getRow($sql);
		if(!empty($pre))$pre[url] = url_rewrite('article_view.php', array('aid'=>$pre['id'],'act'=>'view'));
	}

	$row = $db->getRow("SELECT * FROM {$table}type WHERE typeid = '$typeid'");
	
	$seo['keywords']  = !empty($keywords) ? $keywords : cut_str($title,'15');
	$seo['description'] = $description;

$seo['title'] = $CFG['webname'].'手机版';
include tpl('article_view');
?>