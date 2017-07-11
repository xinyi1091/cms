<?php
define('IN_PHPMPS', true);
ob_start();
header("Cache-control: private");
require dirname(__FILE__) . '/include/common.inc.php';
$ip = get_ip();
$cats44  = get_cat_list();//所有分类
$area_option1 = area_options2();
$act = $_REQUEST['act'] ? htmlspecialchars(trim($_REQUEST['act'])) : 'select' ;
if(empty($CFG['visitor_post']) && empty($_userid)) {
	show_m('游客不允许发布信息，请登陆后发布');
}
$CFG['posttimelimit'] = 30;
if((time() - $_COOKIE['lastposttime']) < $CFG['posttimelimit']) {
	show_m('您发布的太快了，休息一下吧！');
}
if($_userid){
	$member = member_info($_userid);
	if($member['status']!=1) showmsg('您尚未通过审核或被锁定！');
	if((time() - $_lastposttime) < $CFG['posttimelimit']) {
		show_m('您发布的太快了，休息一下吧！');
	}
}
if($act == 'select')
{
	$id=$_REQUEST['id'] ? intval($_REQUEST['id']) : 0;
	$cat_info=get_cat_info($id);
	$here='<a href="." title="首页">首页</a>';
	if (!empty($cat_info)&&!$cat_info['parentid']){
		$here.=' > <a href="post.php" title="发布信息">发布信息</a> > ' . $cat_info['catname'];
		$cat_children=get_cat_children($id,'array');
	}else{
		$here.=' > 发布信息';
		$cats = get_cat_list();
	}
	$seo['title'] = '选择分类' . ' - ' . $CFG['webname'];
	$seo['keywords'] = $CFG['keywords'];
	$seo['description'] = $CFG['description'];
	include tpl('select');
}elseif($act == 'post'){
	$catid = intval($_REQUEST['id']);
	if(empty($catid)) {
		show_m('没有选择分类');
	}
	$catinfo = get_cat_info($catid);
	if(empty($catinfo)) show_m('不存在此分类');
	$verf = get_one_ver();
	$seo['title'] = '发布信息'.' - '.$CFG['webname'];
	$seo['keywords'] = $CFG['keywords'];
	$seo['description'] = $CFG['description'];
	$cats = get_cat_list();
	
	$here='<a href="." title="首页">首页</a> > <a href="post.php">发布信息</a> > 填写信息';
	include tpl('post');
}elseif($act=='postok'){
	$catid     = $_POST['catid'] ? intval($_POST['catid']) : '';
	$cat_info=get_cat_info($catid);
	$title     = $_POST['title'];
	$keywords=cut_str($title,10,0,0).','.$cat_info['keywords'];
	$areaid    = $_POST['areaid'] ? intval($_POST['areaid']) : '';
	$postdate  = time();
	$enddate   = $_POST['enddate']>0 ? (intval($_POST['enddate']*3600*24)) + time() : (30*3600*24) + time();
	$content= $_POST['content'];
	$description = !empty($content) ? cut_str($content,100) : $cat_info['description'];
	$linkman   = $_POST['linkman'];
	$phone     = $_POST['phone'] ? trim($_POST['phone']) : '';
	$password  = $_POST['password'] ? trim($_POST['password']) : '';
	$is_check  = $CFG['post_check'] == '1' ?  '0' : '1';
	$title = censor($title);
	$content = censor($content);
	//来自手机
	$from_mobile=1;
	$pregstr = "/[\x{4e00}-\x{9fa5}]+/u";
	
		
	$so = " ip = '$ip' ";
		
	if(!empty($phone))  $so .= " or phone = '$phone' ";
	if(!empty($qq))     $so .= " or qq = '$qq' ";
	if(!empty($email))  $so .= " or email = '$email' ";
	if(!empty($linkman))$so .= " or linkman = '$linkman' ";

	//是否超出每天发布数量
	if(!empty($CFG['maxpost'])) {
		if($_userid) {
			$sql = "select count(*) from {$table}info where userid='$_userid' and postdate > " .mktime(0,0,0);
		} else {
			$sql = "select count(*) from {$table}info where postdate > " .mktime(0,0,0)." and ($so)";
		}
		if($db->getOne($sql) >= $CFG['maxpost']) show_m("每天最多发布 $CFG[maxpost] 条信息");
	}
		
	//判断是否重复发布信息
	if($_userid) {
		$sql = "select count(*) from {$table}info where title='$title' and userid='$_userid' and postdate > " .mktime(0,0,0);
	} else {
		$sql = "select count(*) from {$table}info where title='$title' and ($so) and postdate > " .mktime(0,0,0);
	}
	if($db->getOne($sql) > 0) show_m('请不要重复发布信息');
		
	$items = array(
		'userid' => $_userid,
		'catid'  => $catid,
		'areaid' => $areaid,
		'title'  => $title,
		'keywords' => $keywords,
		'description' => $description,
		'content' => $content,
		'linkman' => $linkman,
		'email' => $email,
		'qq' => $qq,
		'phone' => $phone,		
		'postarea' => $postarea,
		'postdate' => $postdate,
		'address' => $address,
		'enddate' => $enddate,
		'ip' => $ip,
		'is_check' => $is_check,
		'is_top' => $is_top,
		'top_type' => $top_type,
		
	);
	$id = addInfo($items, $_POST['cus_value']);
	
	//设置最后发布时间的cookie
	setcookie('lastposttime', time(), time()+86400*24);
	
	$url = 'view.php?id='.$id;
	$seo['title']='发布成功'.' - '.$CFG['webname'];
	$here='<a href="." title="首页">首页</a> > <a href="post.php">发布信息</a> > 发布成功';
	//ob_end_flush();
	include tpl('post_ok');
}
?>