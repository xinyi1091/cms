<?php
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', $CFG['uc_dbhost']);
define('UC_DBUSER', $CFG['uc_dbuser']);
define('UC_DBPW', $CFG['uc_dbpwd']);
define('UC_DBNAME', $CFG['uc_dbname']);
define('UC_DBCHARSET', $CFG['uc_charset']);
define('UC_DBTABLEPRE', $CFG['uc_dbname'].'.'.$CFG['uc_dbpre']);
define('UC_KEY', $CFG['uc_key']);
define('UC_API', $CFG['uc_api']);
define('UC_IP', $CFG['uc_ip']);
define('UC_APPID', $CFG['uc_appid']);
define('API_UPDATECREDIT', 0); //�����û����ֿ���
define('API_GETCREDITSETTINGS', 0);	//��UCenter�ṩ�������ÿ���
define('API_UPDATECREDITSETTINGS', 0); //����Ӧ�û������ÿ���
?>