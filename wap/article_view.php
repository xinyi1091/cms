<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
$cats  = get_cat_list();//���з���
	
if(isset($_REQUEST['id']))$id = intval($_REQUEST['id']);
if(empty($id)) {
	header("Location: ./\n");
	exit;
}

$id = intval($_REQUEST['id']);
	if(empty($id))  showmsg('ȱ�ٲ�����');
	$article = $db->getRow("SELECT * FROM {$table}article WHERE id='$id'");
	if(empty($article))showmsg('�����ڴ�����', 'index.php');
	extract($article);
	$addtime = date('Y-m-d H:i', $addtime);
	/* ȡ�ù������� */
	/* ��һ������һ�� */
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

$seo['title'] = $CFG['webname'].'�ֻ���';
include tpl('article_view');
?>