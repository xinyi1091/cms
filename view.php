<?php
define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.php';
$php_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
if(empty($php_referer)){
    showmsg('�Ƿ����ʣ�','index.php');
}
$id = $_REQUEST['id'] ? intval($_REQUEST['id']) : '';
if(empty($id)) showmsg('ȱ�ٲ�����');
$sql = "SELECT a.*,m.username FROM {$table}info AS a LEFT JOIN {$table}member AS m ON m.userid=a.userid WHERE a.id='$id'";
$info = $db->getRow($sql);
if(empty($info)) showmsg('��Ϣ������','index.php');
$info['content'] = strip_tags($info['content']);
$info['infouserid'] = $info['userid'];
unset($info['userid']);
extract($info);
$content = str_replace("\n","<br />", $content);
$cat_array = get_cat_array();
$area_array = get_area_array();
$catname = $cat_array[$catid];
$areaname = $area_array[$areaid];

$phone_c = $phone;
$email_c = $email;
$qq_c    = $qq;

if($email)$crypt_email = encrypt($email,$CFG['crypt']);
if($qq)$js_qq = encrypt($qq, $CFG['crypt']);

$link_image = 1;
if($link_image == '1') {
	
	$email = empty($email)? '' : '<img src="do.php?act=show&num='.encrypt($email,	$CFG['crypt']).'" align="absbottom">';
	$qq = empty($qq)? '' : '<img src="do.php?act=show&num='.encrypt($qq, $CFG['crypt']).'" align="absbottom">';
} else {
	$phone = $phone_c;
	$email = $email_c;
	$qq = $qq_c;
}
if(!$CFG['visitor_view']) {
	if(empty($_userid)) {
		$phone=empty($phone) ? '' : '��½����ʾ';
		$email=empty($email) ? '' : '��½����ʾ';
		$qq=empty($qq) ? '' : '��½����ʾ';
	}
}
if(!$CFG['expired_view']) {
	if($enddate<time() && $enddate>0) {
		$phone=empty($phone) ? '' : '�ѹ���';
		$email=empty($email) ? '' : '�ѹ���';
		$qq=empty($qq) ? '' : '�ѹ���';
	}
}
$thisYear = date("Y");
$postdate = date('Y��m��d��', $postdate);
$lastdate = enddate($enddate);
$mappoint = $mappoint ? explode(',', $mappoint) : '';
if(!$is_check)showmsg('��Ϣ��δ��ˣ���˺�������', 'index.php');
$custom = get_info_custom($id);
$images = $db->getAll("SELECT * FROM {$table}info_image WHERE infoid = '$id' ");
$db->query("UPDATE LOW_PRIORITY {$table}info SET click=click+1 WHERE id='$id'");/*���µ����*/

/*ȡ�ù�����Ϣ*/
$match_info = array();
$res = $db->query("SELECT id,title FROM {$table}info WHERE is_check=1 AND catid='$catid' ORDER BY id DESC LIMIT 0,5 ");
while($row = $db->fetchrow($res)) {
	if($row['id'] != $id) continue;
	$row['url'] = url_rewrite('view', array('vid'=>$row['id']));
	$match_info[] = $row;
}

$here_arr[] = array('name'=>$catname, 'url'=>url_rewrite('category',array('cid'=>$catid)));
$here_arr[] = array('name'=>$title);
$here = get_here($here_arr);

$seo['title']   = $title . ' - '. $areaname . ' - '. $catname . ' - ' . $CFG['webname'];
$seo['keywords']  = !empty($keywords) ? $keywords : cut_str($title,'15');
$seo['description'] = !empty($description) ? $description : cut_str(strip_tags($content),70);
$match_info= get_info($catid,'','10','','date','15');
$cat_info = get_cat_info($catid);
$template = $cat_info['viewtplname'] ? $cat_info['viewtplname'] : 'view';
include template($template);
?>