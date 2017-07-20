<?php
define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
if(isset($_REQUEST['id']))$catid = intval($_REQUEST['id']);
$cats44  = get_cat_list();//所有分类
	$res = $db->query("select * from {$table}type where module='article'");
	$type = array();
	while($row = $db->fetchrow($res)) {
		$row['url'] = url_rewrite('article', array('iid'=>$row['typeid'],'act'=>'list'));
		$type[] = $row;
	}
	$typeid  = !empty($_REQUEST['typeid']) ? intval($_REQUEST['typeid']) : '';
	$typewhere = $typeid ? " AND typeid = '$typeid' " : '';

	$page = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
	$size = !empty($_CFG['pagesize']) && intval($_CFG['pagesize']) > 0 ? intval($_CFG['page_size']) : 20;

	$count = $db->getOne("SELECT COUNT(*) FROM {$table}article WHERE 1 ".$typewhere);
	$max_page = ($count> 0) ? ceil($count / $size) : 1;
	if($page>$max_page)$page = $max_page;

$pager['search'] = array('typeid'=>$typeid);
$pager = get_pager('article.php',$pager['search'],$count,$page,$size);
	$sql = "SELECT * FROM {$table}article WHERE 1 " .$typewhere. " ORDER BY listorder desc,id DESC LIMIT $pager[start],$pager[size]";
	$res = $db->query($sql);
	$articles = array();
	while($row = $db->fetchRow($res)) {
		$row['title']    = cut_str($row['title'],'48');
		$row['addtime']  = date('Y-m-d h:i', $row['addtime']);
		$row['url']      = url_rewrite('article',array('aid'=>$row['id'],'act'=>'view'));
		$row['description']    = cut_str($row['description'],'98');
		$articles[]      = $row;
	}
	

	/* 所处位置 */
	$cat_info = $db->getRow("SELECT * FROM {$table}type WHERE typeid = '$typeid'");
	if(empty($cat_info)) {
		$here_arr[] = array('name'=>'全部资讯');
	} else {
		$here_arr[] = array('name'=>$cat_info['typename']);
	}
	$here = get_here($here_arr);
	$seo['title'] = $cat_info['typename'] . ' - ' . $CFG['webname'];
	$seo['title2'] = $cat_info['typename'] . '  ';
	$seo['keywords'] = $cat_info['keywords'];
	$seo['description'] = $cat_info['description'];


$seo['title'] = $CFG['webname'].'手机版';
include tpl('article_list');
?>