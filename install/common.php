<?php


if (!defined('IN_PHPMPS'))
{
    die('Access Denied');
}

//������󼶱�
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* ȡ�ø�Ŀ¼ */
define('PHPMPS_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));

set_magic_quotes_runtime(0);

@set_time_limit(360);

require_once PHPMPS_ROOT . 'install/global.fun.php';
require_once PHPMPS_ROOT . 'include/version.inc.php';
require_once PHPMPS_ROOT . 'include/json.class.php';

//ת�崦��ͻ����ύ������
if(!get_magic_quotes_gpc())
{
	$_POST   = addslashes_deep($_POST);
	$_GET    = addslashes_deep($_GET);
	$_COOKIE = addslashes_deep($_COOKIE);
}

header('Content-type: text/html; charset='.$charset);
?>