<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.php';
require BIANMPS_ROOT . 'include/json.class.php';
require BIANMPS_ROOT . 'include/pay.fun.php';
$act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'select' ;
$ip = get_ip();
$postarea = getPostArea($ip);
onlyarea($postarea);

if(empty($CFG['visitor_post']) && empty($_userid)) {
	showmsg('�οͲ���������Ϣ�����½�󷢲�','member.php?act=login&refer='.$PHP_URL);
}

$CFG['posttimelimit'] = 1;
if((time() - $_COOKIE['lastposttime']) < $CFG['posttimelimit']) {
	showmsg('��������̫���ˣ���Ϣһ�°ɣ�');
}

if($_userid){
	$member = member_info($_userid);
	if($member['status']!=1) showmsg('����δͨ����˻�������');
	if((time() - $_lastposttime) < $CFG['posttimelimit']) {
		showmsg('��������̫���ˣ���Ϣһ�°ɣ�');
	}
}

if($act == 'select')
{
	$cats = get_cat_list();
	$here = array('name'=>'ѡ�����', 'url'=>'post.php');
	
	$seo['title'] = 'ѡ�����' . ' - ';
	$seo['keywords'] = $CFG['keywords'];
	$seo['description'] = $CFG['description'];
	
	include template('select');
}
elseif($act == 'post')
{
	$catid = intval($_REQUEST['id']);
	

	$catinfo = get_cat_info($catid);
	
	
	$verf = get_one_ver();
	$member = member_info($_userid);
	$custom = cat_post_custom($catid);
	$mappoint = $CFG['map'] ? explode(',', $CFG['map']) : '';
	
	$seo['title'] = '������Ϣ  - '.$CFG['webname'];
	$seo['keywords'] = $CFG['keywords'];
	$seo['description'] = $CFG['description'];

	include template('post');
}
elseif($act == 'postok')
{
	$catid     = $_POST['catid'] ? intval($_POST['catid']) : '';
	$title     = $_POST['title'] ;
	$areaid    = $_POST['areaid'] ? intval($_POST['areaid']) : '';
	$postdate  = time();
	$enddate   = $_POST['enddate']>0 ? (intval($_POST['enddate']*3600*24)) + time() : '0';
	$content   = $_POST['content'];
	$keywords  = $_POST['keyword'];
	$description = cut_str($content,100);
	$linkman   = $_POST['linkman'];
	$phone     = $_POST['phone'];
	$qq        = $_POST['qq'] ? intval($_POST['qq']) : '';
	$email     = $_POST['email'];
	$password  = $_POST['password'];
	$address   = $_POST['address'];
	$mappoint  = $_POST['mappoint'];
	$checkcode = $_POST['checkcode'];
	$number    = $_POST['number'] ? intval($_POST['number']) : '';
	$top_type  = $_POST['top_type'] ? intval($_POST['top_type']) : '';
	$is_type   = $_POST['is_top'] ? intval($_POST['is_top']) : '';
	$is_check  = $CFG['post_check'] == '1' ?  '0' : '1';
	$title = censor($title);
	$content = censor($content);
	$postfrom = $_POST['postfrom'] ? trim($_POST['postfrom']) : '';
    if(empty($postfrom) /*|| $postfrom != 'http://bianjy.com/cms/'*/)showmsg('������վ���ύ');
	if(empty($title)) showmsg("���ⲻ��Ϊ��");
	if (strlen($content) < 20) showmsg("����������������20�ַ�!");
	if($areaid<=0) showmsg('��ѡ�����');
	if(empty($_userid)) {
		if(empty($password)) showmsg("����д����");
	}
    if(empty($phone) && empty($qq) && empty($email))showmsg("��ϵ��ʽ������дһ��");

	check_ver(intval($_REQUEST['vid']), htmlspecialchars($_REQUEST['answer']));
	
	$so = " ip = '$ip' ";
	if(!empty($phone))  $so .= " or phone = '$phone' ";
	if(!empty($qq))     $so .= " or qq = '$qq' ";
	if(!empty($email))  $so .= " or email = '$email' ";
	if(!empty($linkman))$so .= " or linkman = '$linkman' ";

	//�Ƿ񳬳�ÿ�췢������
	if(!empty($CFG['maxpost'])) {
		if($_userid) {
			$sql = "select count(*) from {$table}info where userid='$_userid' and postdate > " .mktime(0,0,0);
		} else {
			$sql = "select count(*) from {$table}info where postdate > " .mktime(0,0,0)." and ($so)";
		}
		if($db->getOne($sql) >= $CFG['maxpost']) showmsg("ÿ����෢�� $CFG[maxpost] ����Ϣ");
	}
	
	//�ж��Ƿ��ظ�������Ϣ
	if($_userid) {
		$sql = "select count(*) from {$table}info where title='$title' and userid='$_userid' and postdate > " .mktime(0,0,0);
	} else {
		$sql = "select count(*) from {$table}info where title='$title' and ($so) and postdate > " .mktime(0,0,0);
	}
	if($db->getOne($sql) > 0) showmsg('�벻Ҫ�ظ�������Ϣ');
	
	//�����ö�
	if(!empty($is_top) && !empty($number)) {

		$gold = getUserGlod($_userid);
		if($gold < $CFG['top_gold'] * $number) {
			$is_top = '';
			$top_type  = '';
		} else {
			gold_diff($_username, $CFG['info_top_gold']*$number, '�ö��۳���Ϣ��');
			$is_top = time() + $number*3600*24;
		}
	}
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
		'password' => $password,
		'postarea' => $postarea,
		'postdate' => $postdate,
		'mappoint' => $mappoint,
		'address' => $address,
		'enddate' => $enddate,
		'ip' => $ip,
		'is_check' => $is_check,
		'is_top' => $is_top,
		'top_type' => $top_type,
	);
	$id = addInfo($items, $_POST['cus_value']);

	foreach($_FILES as $key=>$val) {

		$alled = array('png','jpg','gif','jpeg');
		$exname = strtolower(trim(substr(strrchr($val['name'], '.'), 1)));
		if(!checkupfile($val['tmp_name']) || !($val['tmp_name'] != 'none' && $val['tmp_name'] && $val['name']) || $val['size']>'523298' || !in_array($exname,$alled)){
			continue;
		}
		$name = date('ymdhis');
		for($a = 0;$a < 6;$a++){ $name .= chr(mt_rand(97, 122));}
		$thumb_name = $name.'_thumb'. '.' . end(explode('.', $val['name']));
		$name .= '.' . end(explode('.', $val['name']));
		
		$dir = BIANMPS_ROOT . 'data/infoimage/' . date('ymd');
		if(!is_dir($dir)) {
			if(@mkdir(rtrim($dir,'/'), 0777))@chmod($dir, 0777);
		}
		$to = $dir.'/'. $name;

		if(move_uploaded_file($val['tmp_name'], $to)) {
			$image = 'data/infoimage/' . date('ymd').'/'. $name;
			@chmod(BIANMPS_ROOT.$image, 0777);
			$db->query("INSERT INTO {$table}info_image (infoid,path) VALUES ('$id','$image')");
		}
		if(!$do) {
			$thumbimg = 'data/infoimage/' . date('ymd').'/'.$thumb_name;
			$thumb = CreateSmallImage( $image, $thumbimg, 154, 134);
			@chmod(BIANMPS_ROOT.$thumbimg, 0777);
			$db->query("UPDATE {$table}info SET thumb='$thumbimg' WHERE id='$id' ");
			$do = true;
		}
	}
	
	//��������
	if($_username) {
		$credit_count = getCreditTimes($_username, 'post_info_credit');
		if($credit_count < $CFG['max_info_credit']) {
			if(!empty($CFG['post_info_credit'])) credit_add($_username, $CFG['post_info_credit'], 'post_info_credit');
		}
		$query = $db->query("UPDATE {$table}member SET lastposttime=".time()." WHERE userid='$_userid' ");
	}

	//������󷢲�ʱ���cookie
	setcookie('lastposttime', time(), time()+86400*24);

	$url = url_rewrite('view', array('vid'=>$id));
	include template('post_ok');
}
?>
