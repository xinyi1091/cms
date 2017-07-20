<?php

if (!defined('IN_BIANMPS')) {
    die('Access Denied');
}

define('IN_ADMIN', true);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('BIANMPS_ROOT', str_replace('admin/include/common.php', '', str_replace('\\', '/', __FILE__)));
set_magic_quotes_runtime(0);
session_start();

require BIANMPS_ROOT . 'data/config.php';
require BIANMPS_ROOT . 'admin/include/global.fun.php';
require BIANMPS_ROOT . 'include/global.fun.php';
header('Content-type: text/html; charset='.$charset);
unset($_REQUEST['table']);
if(!get_magic_quotes_gpc()) {
	$_POST   = addslashes_deep($_POST);
	$_GET    = addslashes_deep($_GET);
	$_COOKIE = addslashes_deep($_COOKIE);
}
if (!empty($_REQUEST))$_REQUEST  = sql_replace($_REQUEST);
if (!empty($_POST))$_POST  = sql_replace($_POST);
if (!empty($_GET))$_GET  = sql_replace($_GET);

require BIANMPS_ROOT . 'include/mysql.class.php';
$db = new mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;

if(empty($_SESSION['adminid']) &&  !strstr($_SERVER['SCRIPT_FILENAME'],'login.php')){
    header("Location: ./login.php?act=login");
	exit;
}
$CFG = get_config();
define('PHPMPS_PATH', $CFG['weburl'].'/');
?>