<?php
	define('IN_PHPMPS',true);
	define('PHPMPS_ROOT', str_replace('wap/do.php', '', str_replace('\\', '/', __FILE__)));
	require PHPMPS_ROOT . 'data/config.php';
	require PHPMPS_ROOT . 'include/global.fun.php';
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