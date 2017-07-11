<?php

define('IN_PHPMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'login' ;

if($_REQUEST['act'] == 'login')
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
	include tpl('login');
}

if($_REQUEST['act'] == 'act_login')
{
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

    $sql = "SELECT userid,username,password FROM {$table}admin WHERE username='".$username."' AND password='".md5($password)."'";
	$row = $db->getRow($sql);

	if($row)
	{
		$_SESSION['adminid']  = $row['userid'];
		$_SESSION['adminname'] = $row['username'];
        
		//¸üÐÂµÇÂ½IP,Ê±¼ä
		$sql = "UPDATE {$table}admin SET lastip='$_SERVER[REMOTE_ADDR]',lastlogin='". time() ."' WHERE userid='$_SESSION[adminid]'";
		$db->query($sql);

		admin_log("$row[username] µÇÂ½³É¹¦");
		show('µÇÂ½³É¹¦', 'index.php');
	}
	else
	{
		admin_log("$row[username] µÇÂ½Ê§°Ü");
        show('µÇÂ½Ê§°Ü', 'index.php');
    }
}
?>

