<?php
if (!defined('IN_BIANMPS'))
{
    die('Access Denied');
}

//������󼶱�
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/* ȡ�ø�Ŀ¼ */
/*
 * str_replace(Ҫ���ҵ�ֵ,�滻��ֵ,�涨���������ַ���,����[��ѡ])
 *
 * __FILE__ :PHPħ������ ,���ص�ǰִ��PHP�ű�������·�����ļ���,����:D:\work\www\cms\install\common.php
 * dirname(__FILE__) �������ص��ǽű������ڵ�·��,��:D:\work\www\cms\install
 * substr(string,start,length),length�Ǹ���ʱ�����ĩ�˿�ʱ���أ�����substr(dirname(__FILE__), 0, -7)���ص���:D:\work\www\cms\,��������վ��Ŀ¼
 * */
/*
 * ���ʱ���á�\��Ϊ·���ָ���
 * ��windowsϵͳ��·����ʹ�á�\����ͬʱ��Ҫ�ټ�һ��ת��ġ�\�������γ����������µ�·����
 * ��path\\fileName��
 * ����·����windowsϵͳûʲô���ԣ����ǵ���linuxϵͳ��������⣬��linuxϵͳ��������Ϊ��path\����һ���ļ��У�
 * ��������Ҫ�Դ������ļ�����ʱ���ͻ��Ҳ����ļ���
����*/
echo __FILE__;
define('BIANMPS_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));

set_magic_quotes_runtime(0);

@set_time_limit(360);//�ű����ִ��ʱ��

require_once BIANMPS_ROOT . 'install/global.fun.php';
require_once BIANMPS_ROOT . 'include/version.inc.php';
require_once BIANMPS_ROOT . 'include/json.class.php';

//ת�崦��ͻ����ύ������
if(!get_magic_quotes_gpc())
{
	$_POST   = addslashes_deep($_POST);
	$_GET    = addslashes_deep($_GET);
	$_COOKIE = addslashes_deep($_COOKIE);
}

header('Content-type: text/html; charset='.$charset);
?>