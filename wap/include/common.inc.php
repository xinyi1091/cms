<?php

if (!defined('IN_PHPMPS'))
{
    die('Access Denied');
}

//������󼶱�
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* ȡ�ø�Ŀ¼ */
define('PHPMPS_ROOT', str_replace('wap/include/common.inc.php', '', str_replace('\\', '/', __FILE__)));
require PHPMPS_ROOT . 'data/config.php';
require PHPMPS_ROOT . 'include/com.fun.php';
require PHPMPS_ROOT . 'include/global.fun.php';
require PHPMPS_ROOT . 'wap/include/wap.fun.php';

@set_magic_quotes_runtime(0);
@ini_set('session.auto_start', 0);
session_start();

//ת�崦��ͻ����ύ������
if(!get_magic_quotes_gpc())
{
	if (!empty($_GET))$_GET  = addslashes_deep($_GET);
    if (!empty($_POST))$_POST = addslashes_deep($_POST);
    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}
if(function_exists('date_default_timezone_set')){
    date_default_timezone_set('Asia/Shanghai');
}
header('Content-type: text/html; charset=gb2312');
//��ʼ�����ݿ���
	
	function area_options2($selectid='')
{
	$area = get_area_list();
	foreach($area as $area) {
		$option .= "<option value=$area[areaid] style='color:red;'";
		$option .= ($selectid == $area['areaid']) ? " selected='selected'" : '';
		$option .= ">$area[areaname]</option>";

		if(!empty($area['children'])) {
			foreach($area['children'] as $chi) {
				$option .= "<option value=$chi[id]";
				$option .= ($selectid == $chi['id']) ? " selected='selected'" : '';
				$option .= ">&nbsp;&nbsp;|--$chi[name]</option>";
			}
		}
	}
	return $option;
}
require PHPMPS_ROOT . 'include/mysql.class.php';
$db = new mysql($db_host, $db_user, $db_pass, $db_name, '1');
$db_host = $db_user = $db_pass = $db_name = NULL;

$CFG = get_config();//ȡ��ϵͳ��Ϣ
if($CFG['closesystem'])die($CFG['close_tips']);
if(!$CFG['wap'])die('ϵͳδ����wap���ܣ�');

$_userid = 0;
$_username = '';
if($_SESSION['userid'])
{
	$user_info = $db->getrow("select * from {$table}member where userid='$_SESSION[userid]' ");
	if($user_info) {
		$_userid = $user_info['userid'];
		$_username = $user_info['username'];
	}
}
define('PHPMPS_PATH', $CFG['weburl'].'/');//��ַ
echo "<?xml version='1.0' encoding='gb2312'?>";

?>