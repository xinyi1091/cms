<?php

if (!defined('IN_BIANMPS'))
{
    die('Access Denied');
}

//定义错误级别
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* 取得根目录 */
define('BIANMPS_ROOT', str_replace('wap/include/common.inc.php', '', str_replace('\\', '/', __FILE__)));
require BIANMPS_ROOT . 'data/config.php';
require BIANMPS_ROOT . 'include/com.fun.php';
require BIANMPS_ROOT . 'include/global.fun.php';
require BIANMPS_ROOT . 'wap/include/wap.fun.php';

@set_magic_quotes_runtime(0);
@ini_set('session.auto_start', 0);
session_start();

//转义处理客户端提交的数据
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
//初始化数据库类
	
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
require BIANMPS_ROOT . 'include/mysql.class.php';
$db = new mysql($db_host, $db_user, $db_pass, $db_name, '1');
$db_host = $db_user = $db_pass = $db_name = NULL;

$CFG = get_config();//取得系统信息
if($CFG['closesystem'])die($CFG['close_tips']);
if(!$CFG['wap'])die('系统未开启wap功能！');

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
define('PHPMPS_PATH', $CFG['weburl'].'/');//网址
echo "<?xml version='1.0' encoding='gb2312'?>";

?>