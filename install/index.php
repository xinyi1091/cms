<?php

//��װ�ļ�



define('IN_PHPMPS', true);

$charset = 'gb2312';

$dbcharset = 'gbk';

require_once 'common.php';



if(file_exists(PHPMPS_ROOT . 'data/install.lock')) {

	die('ϵͳ�Ѱ�װ�����Ҫ���°�װ����ɾ�� data/install.lock ����ļ���');

}



//��ʼ��step

$_REQUEST['step'] = $_REQUEST['step'] ? $_REQUEST['step'] : 'license' ;



switch($_REQUEST['step'])

{

	case 'license';

		include tpl('license');

	break;



	case '1':

		require_once 'dirs.php';

		foreach ($dirs AS $key=>$dir)

		{

			$message[$key]['name'] = $dir;

			if(!is_writable(PHPMPS_ROOT . $dir))

			{

				$next = 'no';

				$message[$key]['write'] = "<font color=#ff0000><b>Ȩ�޲���ȷ������д</b></font>";

			}

			else

			{

				$message[$key]['write'] = "<font color=#119911><b>Ȩ����ȷ����д</b></font>";

			}

		}

		include tpl('1');

	break;



	case '2':

		$code = MD5(random('6'));

		$path = get_url();

		include tpl('2');

	break;



	case '3':

		$db_host  = trim($_POST['db_host']);

		$db_user  = trim($_POST['db_user']);

		$db_pass  = trim($_POST['db_pass']);

		$db_name  = trim($_POST['db_name']);

		$db_table = trim($_POST['db_table']);



		$admin      = trim($_POST['admin']);

		$password   = trim($_POST['password']);

		$repassword = trim($_POST['repassword']);

		$email      = trim($_POST['email']);

		$crypt      = trim($_POST['crypt']);

		$path       = trim($_POST['path']);



		if(empty($db_host))die("δ��д���ݿ��������ַ��");

		if(empty($db_user))die("δ��д���ݿ�������û�����");

		if(empty($db_name))die("δ��д���ݿ����ơ�");

		if(empty($db_table))die("δ��д���ݿ��ǰ׺��");



		if(empty($admin))die("δ��д����Ա�ʺš�");

		if(empty($password))die("δ��д����Ա���롣");

		if(empty($repassword))die("δ��д�ظ����롣");

		if($password != $password)die("������������벻һ�¡�");

		if(empty($email))die("δ��д�����ʼ���");

		if(!is_email($email))die("�����ʼ���ʽ����ȷ��");

		if(empty($crypt))die("δ��д�����ַ�����");

		

		//������ݿ��Ƿ���ڣ��������򴴽�

		$conn = @mysql_connect($db_host, $db_user, $db_pass);

		if($conn===false)die("�޷��������ݿ�,������ز����Ƿ���ȷ��");

		mysql_connect($db_host, $db_user, $db_pass);

		$yes = mysql_select_db($db_name);

		$mysql_version = mysql_get_server_info();

		if($yes===false)

		{

			$sql = $mysql_version >= '4.1' ? "CREATE DATABASE $db_name DEFAULT CHARACTER SET $dbcharset" : "CREATE DATABASE $db_name";

			if (mysql_query($sql, $conn) === false)

			{

				die("�޷��������ݿ�,������ز����Ƿ���ȷ��");

			}

		}

		@mysql_close($conn);



		//д��config.inc.php�ļ�

		$files = '<?'."php\n";

		$files .= "\$db_host   = \"$db_host\";\n\n";

		$files .= "\$db_name   = \"$db_name\";\n\n";

		$files .= "\$db_user   = \"$db_user\";\n\n";

		$files .= "\$db_pass   = \"$db_pass\";\n\n";

		$files .= "\$table     = \"$db_table\";\n\n";

		$files .= "\$charset   = \"$charset\";\n\n";

		$files .= "\$dbcharset = \"$dbcharset\";\n";

		$files .= '?>';



		$file = @fopen(PHPMPS_ROOT . 'data/config.php', 'wb+');

		if(!$file)

		{

			die('�޷����ļ�');

		}

		if(!@fwrite($file, trim($files)))

		{

			die('�޷�д���ļ�');

		}

		@fclose($file);



		require PHPMPS_ROOT . 'data/config.php';

		require PHPMPS_ROOT . 'include/mysql.class.php';

		$db = new mysql($db_host, $db_user, $db_pass, $db_name, $dbcharset);

		$db_host = $db_user = $db_pass = $db_name = NULL;



		//����phpmps.sql

		import_sql('demosql.sql', $db_table);



		//���������ʻ�

		$password = MD5($password);

		$sql = "INSERT INTO {$table}admin (username,password,email,is_admin) VALUES ('$admin','$password','$email','1')";

		$db->query($sql);

		$sql = "UPDATE {$table}config SET value='$crypt' where setname='crypt' ";

		$db->query($sql);

		$sql = "UPDATE {$table}config SET value='$path' where setname='weburl' ";

		$db->query($sql);

		//���ɰ�װ�����ļ�

		write_lock();



		include tpl('3');

	break;



	case 'check_db':

		$db_host = trim($_REQUEST['db_host']);

		$db_user = trim($_REQUEST['db_user']);

		$db_pass = trim($_REQUEST['db_pass']);

		$db_name = trim($_REQUEST['db_name']);

		$conn = mysql_connect($db_host, $db_user, $db_pass);

		$yes = mysql_select_db($db_name);

		@mysql_close($conn);



		$json = new Services_JSON;

		$s = $json->encode($yes);

		exit($s);

	break;



}

?>
