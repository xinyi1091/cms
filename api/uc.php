<?php

define('IN_BIANMPS', true);
define('UC_CLIENT_VERSION', '1.5.0');	//note UCenter �汾��ʶ
define('UC_CLIENT_RELEASE', '20081031');
define('API_DELETEUSER', 1);		//note �û�ɾ�� API �ӿڿ���
define('API_RENAMEUSER', 1);		//note �û����� API �ӿڿ���
define('API_GETTAG', 1);		    //note ��ȡ��ǩ API �ӿڿ���
define('API_SYNLOGIN', 1);		    //note ͬ����¼ API �ӿڿ���
define('API_SYNLOGOUT', 1);		    //note ͬ���ǳ� API �ӿڿ���
define('API_UPDATEPW', 1);		    //note �����û����� ����
define('API_UPDATEBADWORDS', 1);	//note ���¹ؼ����б� ����
define('API_UPDATEHOSTS', 1);		//note ���������������� ����
define('API_UPDATEAPPS', 1);		//note ����Ӧ���б� ����
define('API_UPDATECLIENT', 1);		//note ���¿ͻ��˻��� ����
define('API_UPDATECREDIT', 0);		//note �����û����� ����
define('API_GETCREDITSETTINGS', 0);	//note �� UCenter �ṩ�������� ����
define('API_GETCREDIT', 0);		    //note ��ȡ�û���ĳ����� ����
define('API_UPDATECREDITSETTINGS', 0);	//note ����Ӧ�û������� ����
define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

//note ��ͨ�� http ֪ͨ��ʽ
if(!defined('IN_UC')) 
{
	error_reporting(0);
	set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	include '../include/common.php';
	include '../include/uc.inc.php';

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);

	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}
	$action = $get['action'];

	require_once '../uc_client/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));
	
	if(in_array($get['action'], array('test', 'deleteuser', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient'))) 
	{
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} 
	else
	{
		exit(API_RETURN_FAILED);
	}
}

class uc_note 
{
	var $db = '';      //���ݿ����
	var $table = '';   //��ǰ׺
	var $appdir = '';  //��վ��Ŀ¼

	function _serialize($arr, $htmlon = 0) 
	{
		if(!function_exists('xml_serialize')) 
		{
			include_once '../uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() 
	{
		$this->appdir = substr(dirname(__FILE__), 0, -3);
		$this->db = $db;
		$this->table = $table;
	}

	function test($get, $post) 
	{
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) 
	{
		global $db,$table,$CFG;
		$uids = $get['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);

		//note �û�ɾ�� API �ӿ�
		$sql = "select userid from {$table}member where uid in ($uids)";
		$userids = $db->getRow($sql);
		$userid = is_array($userids) ? join(',',$userids) : '';

		if($CFG['del_m_info'])
		{
			$sql = "SELECT id FROM {$table}info WHERE userid in ($userid) ";
			$infos = $db->getAll($sql);

			foreach($infos as $info)
			{
				//ɾ������
				$sql = "DELETE FROM {$table}comment WHERE infoid = '$info[id]'";
				$db->query($sql);
				
				$sql = "SELECT thumb FROM {$table}info WHERE infoid = '$info[id]' ";
				$thumb = $db->getOne($sql);
				@unlink(BIANMPS_ROOT . $thumb);

				//ɾ������ͼƬ
				$sql = "select * from {$table}info_image where infoid='$info[id]'";
				$res = $db->query($sql);
				while($row=$db->fetchrow($res)) {
					if($row['path'] != '' && is_file(BIANMPS_ROOT.$row['path']))
					@unlink(BIANMPS_ROOT.$row['path']);
				}

				//ɾ��ͼƬ���ݿ��¼
				$sql = "DELETE FROM {$table}info_image WHERE infoid = $info[id]";
				$db->query($sql);

				//ɾ����������
				$sql = "delete from {$table}cus_value where infoid = $info[id]";
				$sql = $db->query($sql);
				
				//ɾ������Ϣ
				$sql = "DELETE FROM {$table}info WHERE id = '$info[id]'";
				$db->query($sql);
			}
		}
		/*
		 * ɾ���û����������ۡ�
		 * ϵͳ����ɾ����Աʱɾ���˻�Ա��������۵Ļ�����ɾ�����ۡ�
		 */
		if($CFG['del_m_comment'])$db->query("delete from {$table}comment where userid in ($userid) ");

		/* ɾ����Ա�����ݿ���Ϣ */
		$sql = "DELETE FROM {$table}member WHERE userid in ($userid) ";	
	    $db->query($sql);

		return API_RETURN_SUCCEED;
	}

	function synlogin($get, $post) 
	{
		global $db, $table;

		$uid = $get['uid'];
		$username = $get['username'];
		
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

		set_session($username);
	}

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}
		//note ͬ���ǳ� API �ӿ�
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		set_session();
	}

	function updatepw($get, $post) {
		global $db,$table;
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
		$username = $get['username'];
		$password = $get['password'];

		$newpw = md5($password);
		$db->query("UPDATE {$table}member SET password='$newpw' WHERE username='$username'");
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		global $db,$table;
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		//note д app �����ļ�
		$cachefile = $this->appdir.'uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		//note д�����ļ�//
		$UC_API = htmlspecialchars_deep($UC_API);
		$sql = "update {$table}config set value='".$UC_API."' where setname='uc_api' ";
		$db->query($sql);
		
		clear_caches('phpcache', 'webconfig');
	
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}
}

//note ʹ�øú���ǰ��Ҫ require_once $this->appdir.'./config.inc.php';
function _setcookie($var, $value, $life = 0, $prefix = 1) 
{
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
{
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) 
{
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}