<?php
	define('IN_BIANMPS',true);
	define('BIANMPS_ROOT', str_replace('wap/do.php', '', str_replace('\\', '/', __FILE__)));
	require BIANMPS_ROOT . 'data/config.php';
	require BIANMPS_ROOT . 'include/global.fun.php';
	$act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'index';
	switch($act){
		case 'chkcode':
				session_start();
				$_SESSION["chkcode"] = "";
				$chkcode = chkcode();
				$_SESSION["chkcode"] = $chkcode;
		break;
	}
?>