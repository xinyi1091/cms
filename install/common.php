<?php
if (!defined('IN_BIANMPS'))
{
    die('Access Denied');
}

//定义错误级别
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* 取得根目录 */
/*
 * str_replace(要查找的值,替换的值,规定被搜索的字符串,计数[可选])
 *
 * __FILE__ :PHP魔术常量 ,返回当前执行PHP脚本的完整路径和文件名,比如:D:\work\www\cms\install\common.php
 * dirname(__FILE__) 函数返回的是脚本所在在的路径,即:D:\work\www\cms\install
 * substr(string,start,length),length是负数时，则从末端开时返回，所以substr(dirname(__FILE__), 0, -7)返回的是:D:\work\www\cms\,正好是网站根目录
 * */
/*
 * 编程时慎用“\”为路径分隔符
 * 在windows系统中路径中使用“\”，同时需要再加一个转义的“\”，即形成了类似如下的路径：
 * “path\\fileName”
 * 此种路径在windows系统没什么不对，但是到了linux系统会出现问题，在linux系统会生成名为“path\”的一个文件夹，
 * 当你再需要对创建的文件操作时，就会找不到文件。
　　*/
echo __FILE__;
define('BIANMPS_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));

set_magic_quotes_runtime(0);

@set_time_limit(360);//脚本最大执行时间

require_once BIANMPS_ROOT . 'install/global.fun.php';
require_once BIANMPS_ROOT . 'include/version.inc.php';
require_once BIANMPS_ROOT . 'include/json.class.php';

//转义处理客户端提交的数据
if(!get_magic_quotes_gpc())
{
	$_POST   = addslashes_deep($_POST);
	$_GET    = addslashes_deep($_GET);
	$_COOKIE = addslashes_deep($_COOKIE);
}

header('Content-type: text/html; charset='.$charset);
?>