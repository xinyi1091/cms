<?php

define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.php';
if($CFG['uc'])require PHPMPS_ROOT . 'include/uc.inc.php';
require PHPMPS_ROOT . 'include/json.class.php';
require PHPMPS_ROOT . 'include/pay.fun.php';
$act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'index';

$not_login = array('login','act_login','register','act_register','logout',  'ajax', 'get_password', 'reset_password', 'send_pwd_email', 'email_edit_password','credit_rule','receive', 'check_info_gold', 'delinfo', 'editinfo', 'updateinfo', 'report', 'comment');

$must_login = array('index','modify','act_modify', 'edit_password', 'act_edit_password', 'info','info_comment', 'payonline', 'confirm', 'send', 'exchange', 'gold', 'act_gold', 'com_comment', 'com_list', 'editcom', 'updatecom', 'delcom','refer', 'top', 'send_info_mail' );

if(empty($_userid)) {
    if (!in_array($act, $not_login)) {
        if (in_array($act, $must_login)) {
            showmsg('���ȵ�¼', 'member.php?act=login&refer='.$PHP_URL);
        } else {
			showmsg('�벻Ҫ�ύ�Ƿ�����');
		}
    }
}

switch($act)
{
	case 'index':
		$seo['title'] = "��Ա����".' - '.$CFG['webname'];
		$userinfo = member_info($_userid);
		extract($userinfo);
		/*
		 * extract() �����������н��������뵽��ǰ�ķ��ű�
                �ú���ʹ�����������Ϊ��������ʹ�������ֵ��Ϊ����ֵ����������е�ÿ��Ԫ�أ����ڵ�ǰ���ű��д�����Ӧ��һ��������
                �ڶ������� type ����ָ����ĳ�������Ѿ����ڣ�������������ͬ��Ԫ��ʱ��extract() ������ζԴ������ĳ�ͻ��
		����:<?php
                $a = "Original";
                $my_array = array("a" => "Cat","b" => "Dog", "c" => "Horse");
                extract($my_array);
                echo "\$a = $a; \$b = $b; \$c = $c";
            ?>
		����:$a = Cat; $b = Dog; $c = Horse
		����:�õ������Array
            (
                [userid] => 7559
                [uid] => 0
                [username] => xinyi0713
                [email] => hdkah@hkh.com
                [password] => 482c811da5d5b4bc6d497ffa98491e38
                [registertime] => 1499932750
                [registerip] => 127.0.0.1
                [lastlogintime] => 1499999485
                [lastloginip] => 127.0.0.1
                [sendmailtime] => 0
                [qq] =>
                [phone] =>
                [address] =>
                [mappoint] =>
                [money] => 8
                [gold] => 3
                [credit] => 7
                [lastposttime] => 1499935947
                [status] => 1
            )
		����extract($userinfo);����ֱ���б���$registertime
		*/
		$registertime = date('Y��m��d��', $registertime);
		if(empty($email) || empty($qq) || empty($phone))$notice=1;
		include template('member');
	break;

	case 'login':
		if(!empty($_userid)) {
			showmsg("���Ѿ���¼��", "member.php");
		}
		$verf = get_one_ver();
		$refer = trim(htmlspecialchars_deep($_SERVER['HTTP_REFERER']));
		$seo['title'] = "�û���¼";
		include template('login');
	break;

	case 'act_login':
		if(!submitcheck('submit')) showmsg('�ύ����');
		$username = $_POST['username'] ? htmlspecialchars_deep(trim($_POST['username'])) : '';
		$password = $_POST['password'] ? trim($_POST['password']) : '';
		$checkcode = $_POST['checkcode'] ? trim($_POST['checkcode']) : '';
		$md5_password = MD5($password);
		if(empty($username))showmsg("�û�������Ϊ��");
		if(empty($password))showmsg("���벻��Ϊ��");
		//check_code($checkcode);
		check_ver(intval($_REQUEST['vid']), trim($_REQUEST['answer']));
		/* 
		 *	����UCenter��¼��˼·
		 * 
		 *	һ. ���Ȳ�ѯUCenter��û���û���Ϊ$username���û������������������
		 * 
		 *	  1.���û�У���ѯ���أ���������У���ע�ᵽUCenter��ͬʱ��½UCenter����¼ʧ������ʾ������ɹ����򵽵ڶ���
		 * 
		 * 	  2.����У��жϱ�����û�д��û�
		 * 		(1).�����У�����±����û���UCenter���
		 * 		(2).����û�У�����뱾��
		 * 
		 * 	��. ���ⲽ��UCenter�϶��ǵ�½�ɹ��ˣ�Ȼ��ͬ����½UCenter
		 */
		if($CFG['uc']) {
			/* UCenter��û���û���Ϊ$username���û� */
			list($uid, $uc_username, $uc_password, $email) =  uc_call("uc_user_login", array($username, $password));
			
			if($uid==-2) {
				showmsg('�������');
			}
			/* ���UCenter�����ڴ��û� */
			if($uid==-1) {
				/* ��ѯ���� */
				$sql = "SELECT * FROM {$table}member WHERE username='$username' and password='$md5_password'";
				$user = $db->getRow($sql);
				
				/* ��������� */
				if($user['userid']>0) {
					/* ע�ᵽUCenter */
					$uid = uc_call("uc_user_register", array($user['username'], $password, $user['email']));
			
					/* ���ע��ʧ�ܣ�����ʾ */
					if($uid<=0){showmsg('ע�ᵽucenterʧ��');}
					
					/* ���ע��ɹ���������û�UCenter��� */
					if($uid>0) {
						$db->query("UPDATE {$table}member SET uid='$uid' WHERE userid='$user[userid]' ");
					}

					/* ��¼UCenter */
					list($uid, $uc_username, $uc_password, $email) =  uc_call("uc_user_login", array($username, $password));

					if($uid<0) {
						showmsg('ͬ����½��UCenterʧ��');
					}
				}
			}

			if($uid>0) {
				/* ��ѯ�����Ƿ��д��û� */
				$userinfo = $db->getOne("select * from {$table}member where username='$username' and password='$md5_password' ");
				
				/* ���غ�UCenter���д��û������±����û���UCenter��� */
				if($userinfo && $userinfo['uid']!=$uid) {
					$sql = "update {$table}member set uid='$uid' where userid='$userinfo[userid]'";
					$db->query($sql);
				}

				/* �������û�У����뱾�� */
				if($userinfo['userid']<=0) {
					$ip = get_ip();
					$regtime = $lastlogintime = time();
					
					$sql = "insert into {$table}member (uid,username,email,password,registertime,registerip,lastlogintime) values ('$uid','$username','$email','$md5_password','$regtime','$ip','$lastlogintime')";
					$db->query($sql);
				}
			}
			$ucsynlogin = uc_call('uc_user_synlogin', array($uid));
			echo $ucsynlogin;
		}
		
		if(login($username,$md5_password)) {
			$credit_count = $db->getOne("select count(*) from {$table}pay_exchange where username='$username' and addtime>".mktime(0,0,0)." and note='login' ");
			if($credit_count < $CFG['max_login_credit']) {
				if(!empty($CFG['login_credit'])) credit_add($username, $CFG['login_credit'],'login');
			}
			$url= $_REQUEST['refer'] ? rawurldecode($_REQUEST['refer']) : 'member.php';
			showmsg("��½�ɹ�", $url);
		} else {
			showmsg("��¼ʧ��",'member.php?act=login');
		}
	break;
	
	case 'logout':
		if($CFG['uc']) {
			$ucsynlogout = uc_call('uc_user_synlogout', array());
			echo $ucsynlogout;
		}
		logout();
		$link = "index.php";
		showmsg("�˳��ɹ�",$link);
	break;

	case 'register':
		if($CFG['close_register'] == '1') showmsg('��վ�ѹر��û�ע�ᡣ');
		if(!empty($_userid)) {
			$link = "member.php?act=index";
			showmsg("���˳����ٽ��иò���", $link);
		}
		$ip = get_ip();
		$postarea = getPostArea($ip);
		onlyarea($postarea);//�����������Ϣ(ע��)�ĵ���

		$verf = get_one_ver();//���ȡ����֤����
		$mappoint = explode(',',$CFG[map]);//��ͼ���õ�Ĭ�ϵ���

		$seo['title'] = "��Աע��";
		include template('register');
	break;

	case 'act_register':
		$ip = get_ip();
		$postarea = getPostArea($ip);
		onlyarea($postarea);
		
		if(!submitcheck('submit')) showmsg('�ύ����');

		$username   = $_POST['username'] ? htmlspecialchars_deep(trim($_POST['username'])) : '';
		$password   = $_POST['password'] ? trim($_POST['password']) : '';
		$repassword = $_POST['repassword'] ? trim($_POST['repassword']) : '';
		$email      = $_POST['email']?trim($_POST['email']):'';
		$checkcode  = $_POST['checkcode']?trim($_POST['checkcode']):'';
		$md5_password = MD5($password);

		if(empty($username))showmsg("�û�������Ϊ��");
		if(empty($password))showmsg("���벻��Ϊ��");
		if(empty($repassword))showmsg("�ظ����벻��Ϊ��");
		if(empty($email))showmsg("���䲻��Ϊ��");
		if($password != $repassword)showmsg("������������벻��ͬ");
		if(!is_email($email))showmsg("����ĸ�ʽ����ȷ");
		//check_code($checkcode);
		check_ver(intval($_REQUEST['vid']), trim($_REQUEST['answer']));

		if($CFG['uc']){
			/* 
			��ѯ�����Ƿ��д��û�������У���Ӧ�õ���½��ʱ������¼��ʱ����������У�UCenterû�У�����UCenter�� 
			*/
			$userid = $db->getOne("select userid from {$table}member where username='$username' ");
			if($userid>0){
				showmsg('�Ѿ����ڴ��û���');
			}

			//����û�У���ֱ���뵽Ucenter��Phpmps
			$uid = uc_call("uc_user_register", array($username, $password, $email));

			if($uid == -1) {
				showmsg('�û������Ϸ�');
			} elseif($uid == -2) {
				showmsg('����Ҫ����ע��Ĵ���');
			} elseif($uid == -3) {
				showmsg('�û����Ѿ�����');
			} elseif($uid == -4) {
				showmsg('Email ��ʽ����');
			} elseif($uid == -5) {
				showmsg('Email ������ע��');
			} elseif($uid == -6) {
				showmsg('�� Email �Ѿ���ע��');
			}
			$regtime = $lastlogintime = time();
			$sql = "insert into {$table}member (uid,username,email,password,registertime,registerip,lastlogintime) values ('$uid','$username','$email','$md5_password','$regtime','$ip','$lastlogintime')";
			$res = $db->query($sql);
		}
		
		if(empty($res)) {
			if(register($username,$md5_password,$email)) {
			    //register_credit ע���ͻ����ֶ�
				if(!empty($CFG['register_credit'])){
                    credit_add($_SESSION['username'], $CFG['register_credit'],'register');
                    /*�û���,����ֵ,��־˵��*/
                }

				$link='member.php';
				showmsg("ע��ɹ�",$link);
			} else {
				$link = "member.php?act=register";
				showmsg("ע��ʧ��",$link);
			}
		}
		login($username, $md5_password);//ֱ�ӵ�¼
		showmsg('ע��ɹ�', 'member.php');
	break;

		case 'modify':
		$mappoint = explode(',',$CFG['map']);
		$userinfo = member_info($_userid);
		$seo['title'] = "�޸Ļ�Ա����";
		include template('modify');
	break;

	case 'act_modify':
		$phone    = $_POST['phone'];
		$qq       = $_POST['qq'];
		$address  = $_POST['address'];
		$mappoint = $_POST['mappoint'];
		$userid   = $_SESSION['userid'];
		$username = $_SESSION['username'];
		$email    = htmlspecialchars_deep($_POST['email']);
		if(!is_email($email))showmsg('Email ��ʽ����');

		if($CFG['uc']) {
			$result = uc_call("uc_user_edit", array($username, '', '', $email, '1'));

			if($result == -4) {
				showmsg('Email ��ʽ����');
			} elseif($result == -5) {
				showmsg('Email ������ע��');
			} elseif($result == -6) {
				showmsg('�� Email �Ѿ���ע��');
			}
		}

		$sql = "update {$table}member set 
				phone = '$phone',
				qq = '$qq',
				address = '$address',
				mappoint = '$mappoint',
				email = '$email'
				where userid='$_userid' and username='$username' ";
		$res = $db->query($sql);

		showmsg('�޸����ϳɹ�', 'member.php?act=modify');
	break;

	case 'edit_password':
		$seo['title'] = '�޸�����';
		include template('edit_password');
	break;

	case 'act_edit_password':
		$oldpassword = $_POST['oldpassword'] ? trim($_POST['oldpassword']) : '';
		$password = $_POST['password'] ? trim($_POST['password']) : '';
		$repassword = $_POST['repassword'] ? trim($_POST['repassword']) : '';
		
		if(empty($oldpassword) && !empty($password))showmsg('����������룡');
		if($password && $repassword && $password!=$repassword)showmsg('������������벻һ�£�');

		$sql = "SELECT password FROM {$table}member WHERE userid='$_userid' LIMIT 1";
		if(MD5($oldpassword) != $db->getOne($sql))showmsg('�������������');

		$password = MD5($password);
		$query = $db->query("UPDATE {$table}member SET password='$password' WHERE userid='$_userid' ");

		if($CFG['uc']) {
			$username = $_username;
			$old_password = $oldpassword;
			$new_password = $password;
			
			$result = uc_call("uc_user_edit", array($username, $old_password, $new_password, '1'));
			if($result == -1) {
				showmsg('�����벻��ȷ');
			}
		}
		showmsg('�����޸ĳɹ�!','member.php?act=edit_password');
	break;

	case 'ajax':
		$json = new Services_JSON;
		switch($_REQUEST['type'])
		{
			case 'username':
				$username = trim($_REQUEST['username']);
				$sql = "SELECT count(*) FROM {$table}member WHERE username='$username'";
				$count = $db->getOne($sql);
				if($CFG['uc'])$uc_count = uc_call("uc_user_checkname", $username);

				if($count>0 || $uc_count<0) {
					echo $json->encode(false);
					exit;
				} else {
					echo $json->encode(true);
					exit;
				}
			break;

			case 'email':
				$count = $uc_count = 0;
				$email = trim($_REQUEST['email']);
				$sql = "SELECT userid FROM {$table}member WHERE email='$email'";
				$count = $db->getOne($sql);

				if($CFG['uc'])$uc_count = uc_call("uc_user_checkemail", $email);

				if($count>0 || $uc_count<0) {
					echo $json->encode(false);
					exit;
				} else {
					echo $json->encode(true);
					exit;
				}
			break;
		}
	break;


	case 'payonline':
		$payonline_setting = get_pay_setting();
		$paycenter = $_COOKIE['paycenter'];
		if($paycenter) $selected[$paycenter] = 'selected';
		if(!isset($amount)) $amount = '';

		$memberinfo = member_info($_userid);
		$telephone = $memberinfo['telephone'];
		$email = $memberinfo['email'];
		$seo['title'] = '����֧��-ѡ��֧����ʽ';
		include template('payonline');
	break;

	case 'confirm':
		$payonline_setting = get_pay_setting();
		$paycenter = trim($_POST['paycenter']);
		$contactname = trim($_POST['contactname']);
		$telephone = trim($_POST['telephone']);
		$email = trim($_POST['email']);
		
		$amount = round(floatval($_POST['amount']), 2);
		if($amount < 0.01) showmsg('����С��0.01Ԫ');
		if(empty($contactname) || empty($telephone) || empty($email)) showmsg('����д������Ϣ');

		array_key_exists($paycenter, $payonline_setting) or showmsg('�����ڴ�֧����ʽ');
		@extract($payonline_setting[$paycenter]);

		if($percent) {
			$percent = round(floatval($percent), 2);
			$trade_fee = round($amount*$percent/100, 2);
			if($trade_fee < 0.01) $trade_fee = 0.01;
		} else {
			$trade_fee = 0;
		}
		$total_amount = $amount + $trade_fee;
		require PHPMPS_ROOT.'include/payonline/'.$paycenter.'/confirm.php';

		$seo['title'] = '����֧��-ȷ�϶���';
		include template('payconfirm');
	break;

	case 'send':
		$paycenter = trim($_POST['paycenter']);
		$contactname = trim($_POST['contactname']);
		$telephone = trim($_POST['telephone']);
		$email = trim($_POST['email']);
		$username = trim($_POST['username']);
		$orderid = trim($_POST['orderid']);
		$time = time();
		$ip = get_ip();
		$payonline_setting = get_pay_setting();
		array_key_exists($paycenter, $payonline_setting) or showmsg('�����ڴ�֧����ʽ');
		@extract($payonline_setting[$paycenter]);
		setcookie('paycenter', $paycenter, time() + 3600*24*365);

		$r = $db->getOne("SELECT payid FROM {$table}pay_online WHERE `orderid`='$orderid'");
		if($r) showmsg('��Ҫˢ��');
		$moneytype = 'CNY';
		$amount = floatval($_POST['amount']);
		$trade_fee = floatval($_POST['trade_fee']);
		
		$db->query("INSERT INTO {$table}pay_online (`paycenter`,`username`,`orderid`,`moneytype`,`amount`,`trade_fee`,`contactname`,`telephone`,`email`,`sendtime`,`ip`) VALUES('$paycenter','$_username','$orderid','$moneytype','$amount','$trade_fee','$contactname','$telephone','$email','$time','$ip')");

		$amount = $amount + $trade_fee;
		require PHPMPS_ROOT.'include/payonline/'.$paycenter.'/send.php';
	break;

	case 'receive':
		extract($_REQUEST);
		$payonline_setting = get_pay_setting();
		array_key_exists($paycenter, $payonline_setting) or showmsg('֧������');
		@extract($payonline_setting[$paycenter]);
		require PHPMPS_ROOT.'include/payonline/'.$paycenter.'/receive.php';
		
		$total_amount = $amount + $trade_fee;
		$seo['title'] = '֧��������Ϣ';
		include template('payreceive');
	break;

	case 'exchange':
		$units = array('gold'=>'ö', 'money'=>'Ԫ', 'credit'=>'��');
		$types = array('money'=>'�ʽ�', 'gold'=>'��Ϣ��', 'credit'=>'����');
		$notes = array('login'=>'��½����', 'post_info_credit'=>'������Ϣ����' ,'post_comment_credit'=>'�������ۻ���' ,'info_refer'=>'һ��������Ϣ' ,'info_top'=>'��Ϣ�ö�' , 'credit2gold'=>'���ֶһ���Ϣ��', 'money2gold'=>'�ʽ�����Ϣ��');
		extract($_REQUEST);

		$page = isset($page) ? intval($page) : 1;
		$pagesize = 20;

		$sql = '';
		if($type) $sql .= " AND type='$type' ";
		if($begindate) {
			$begintime = strtotime($begindate.' 00:00:00');
			$sql .= " AND addtime>=$begintime ";
		}
		if($enddate) {
			$endtime = strtotime($enddate.' 23:59:59');
			$sql .= " AND addtime<=$endtime";
		}
		$_username = addslashes(stripslashes($_username));
		$r = $db->getOne("SELECT count(*) as number FROM {$table}pay_exchange WHERE username='$_username' $sql");
		$pager['search'] = array('act' => 'exchange');
		$pager = get_pager('member.php', $pager['search'], $r, $page, $pagesize);

		$exchanges = array();
		$result = $db->query("SELECT * FROM {$table}pay_exchange WHERE username='$_username' $sql ORDER BY exchangeid DESC LIMIT $pager[start],$pager[size]");
		while($r = $db->fetchrow($result)) {
			$r['unit'] = $units[$r['type']];
			$r['type'] = $types[$r['type']];
			$r['note'] = !empty($notes[$r['note']]) ? $notes[$r['note']] : $r['note'];
			$r['addtime'] = date('Y-m-d H:i:s', $r['addtime']);
			$exchanges[] = $r;
		}
		$seo['title'] = '��������';
		include template('member_exchange');
	break;

	case 'get_password':
		if(!$CFG['sendmailtype'])showmsg('��δ�����ʼ����������������Ա��ϵ��');
		if (isset($_GET['code']) && isset($_GET['userid'])) {
			$code = trim($_GET['code']);
			$userid  = intval($_GET['userid']);
			/* �ж����ӵĺϷ��� */
			$user_info = member_info($userid);
			if (empty($user_info) || ($user_info && md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']) != $code)) {
				showmsg('��������');
			}
			$seo['title'] = '��������';
			include template('reset_password');
		} else {
			$seo['title'] = '�һ�����';
			include template('get_password');
		}
	break;

	case 'send_pwd_email':
		$username = !empty($_POST['username']) ? trim($_POST['username']) : '';
		$email     = !empty($_POST['email'])     ? trim($_POST['email'])     : '';
		$user_info = member_info($username,'2');

		if ($user_info && $user_info['email'] == $email) {
			$code = md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']);
			include PHPMPS_ROOT.'include/mail.inc.php';
			if (send_pwd_email($user_info['userid'], $username, $email, $code)) {
				showmsg('���ͳɹ�' , 'index.php');
			} else {
				showmsg('����ʧ��' , 'index.php');
			}
		} else {
			showmsg('�û������ʼ���ַ��ƥ��');
		}
	break;

	case 'email_edit_password':
		$new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
		$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
		$userid  = isset($_POST['userid']) ? intval($_POST['userid']) : $userid;
		$code = isset($_POST['code']) ? trim($_POST['code'])  : '';

		if (strlen($new_password) < 6)showmsg('���벻������6λ');
		if($new_password != $confirm_password)showmsg('������������벻һ��');
		$user_info = member_info($userid);

		if (($user_info && (!empty($code) && md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']) == $code))) {
			$password = MD5($new_password);
			$sql = "UPDATE {$table}member SET password='$password' WHERE userid='$userid' ";
			$query = $db->query($sql);

			if($CFG['uc']) {
				$result = uc_call("uc_user_edit", array($username, $old_password, $new_password, ''));
				if($result == -1)showmsg('�����벻��ȷ');
			}
			showmsg('�����޸ĳɹ�', 'index.php');
		} else {
			showmsg('�����޸�ʧ��', 'index.php');
		}
	break;

	case 'gold':
		$userinfo = member_info($_userid);
		$seo['title'] = "������Ϣ��";
		include template('gold');
	break;

	case 'act_gold':
		$type = $_POST['type'];
		$number = $type == 'money2gold' ? intval($_POST['m_number']) : intval($_POST['c_number']);

		if($number <= 1)showmsg('�����������1');
		$userinfo = member_info($_userid);
		$_credit = $number/2 * $CFG['credit2gold'];
		$_money = $number/2 * $CFG['money2gold'];

		if($type == 'money2gold') {
			if($_money > $userinfo['money']) showmsg('�����ʽ�����֧���˴ι���');
			money_diff($_username, $_money, $type);
		} else {
			if($_credit > $userinfo['credit']) showmsg('���Ļ��ֲ�����֧���˴ι���');
			credit_diff($_username, $_credit, $type);
		}
		gold_add($_username, $number, $type);

		showmsg('������Ϣ�ҳɹ�' , 'member.php?act=gold');
	break;

	case 'credit_rule':
		$user_info = member_info($_userid);
		$seo['title'] = '���ֹ���';
		include template('credit_rule');
	break;

	case 'check_credit2gold':
		$json = new Services_JSON;
		$number = intval($_REQUEST['number']);
		$sql = "select credit from {$table}member where userid='$_userid'";
		$user_credit = $db->getOne($sql);
		$pay_credit = $number * $CFG['credit2gold'];
		$data = $pay_credit > $user_credit ? '0' : '1';
		echo $json->encode($data);
	break;

	case 'check_money2gold':
		$json = new Services_JSON;
		$number = intval($_REQUEST['number']);
		$sql = "select money from {$table}member where userid='$_userid'";
		$user_money = $db->getOne($sql);
		$pay_money = $number * $CFG['money2gold'];
		$data = $pay_money > $user_money ? '0' : '1';
		echo $json->encode($data);
	break;

	case 'check_info_gold':
		$json = new Services_JSON;
		//extract($_REQUEST);
		$number = intval($_REQUEST[number]);
		$m_gold = $db->getOne("select gold from {$table}member where userid='$_userid' ");
		$data['kou'] = $CFG['info_top_gold'] * intval($number);
		$data['gold'] = $m_gold - $data['kou'];
		$data=$json->encode($data);
		echo $data;
	break;
	
	case 'info':
		$page = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
		$size = !empty($CFG['pagesize']) && intval($CFG['pagesize']) > 0 ? intval($CFG['pagesize']) : 20;
		$sql = "SELECT COUNT(*) FROM {$table}info WHERE userid='$_userid'";
		$count = $db->getOne($sql);
		$max_page = ($count> 0) ? ceil($count / $size) : 1;
		if($page>$max_page)$page = $max_page;
		$pager['search'] = array('act' => 'info');
		$pager = get_pager('member.php', $pager['search'], $count, $page, $size);
		$sql = "SELECT i.*,a.areaname FROM {$table}info as i left join {$table}area as a on a.areaid=i.areaid WHERE userid='$_userid' ORDER BY id desc LIMIT $pager[start],$pager[size]";
		$res = $db->query($sql);
		$infos = array();
		while($row = $db->fetchRow($res)) {
			$row['title']    = cut_str($row['title'],'18');
			$row['postdate'] = date('y��m��d��', $row['postdate']);
			$row['lastdate'] = $row['enddate'];
			$row['is_pro']   = $row['is_pro']>=time() ? '��' : '��';
			$row['is_top']   = $row['is_top']>=time() ? '��' : '��';
			$row['is_check'] = $row['is_check']=='1' ? '��' : '��';
			$row['url']      = url_rewrite('view',array('vid'=>$row['id']));
			$infos[] = $row;
		}
		$seo['title'] = "�ҷ�������Ϣ";
		include template('member_info');
	break;

	case 'delinfo':
		$id = intval($_REQUEST['id']);
		if(empty($id)) showmsg('ȱ�ٲ�����');
		$info = getInfo($id);
		if(empty($info)){showmsg('��Ϣ������','index.php');}
		checkInfoUser($id, trim($_REQUEST['password']));
		delInfo($id);

		showmsg('ɾ����Ϣ�ɹ�', $_SERVER['HTTP_REFERER']);
	break;

	case 'editinfo':
		$id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}info WHERE id = '$id'";
		$info = $db->getRow($sql);
		if(empty($info)){showmsg('��Ϣ������','index.php');}
		checkInfoUser($id, trim($_REQUEST['password']));
		extract($info);
		$postdate = date('Y��m��d��', $postdate);
		$lastdate = round(($enddate-time())/(3600*24));
		$lastdate = $lastdate ? $lastdate : '30';
		
		if(!empty($mappoint)) {
			$mappoints = explode(',', $mappoint);
		} elseif(!empty($CFG['map'])) {
			$mappoints = explode(',', $CFG['map']);
		}
		$custom = cat_post_custom($catid,$id);
		$info_area = area_options($areaid);

		$seo['title'] = '�޸���Ϣ - '.$CFG['webname'];
		include template('edit_info');
	break;

	case 'updateinfo':
		$id       = intval($_POST['id']);
		

		$title    = $_POST['title'] ? htmlspecialchars_deep(trim($_POST['title'])) : '';
		$areaid   = $_POST['areaid'] ? intval($_POST['areaid']) : '';
		$enddate  = !empty($_POST['enddate']) ? (intval($_POST['enddate']*3600*24)) + time() : '0';
		$content  = $_POST['content'] ? htmlspecialchars_deep(trim($_POST['content'])) : '';
		$linkman  = $_POST['linkman'] ? htmlspecialchars_deep(trim($_POST['linkman'])) : '';
		$phone    = $_POST['phone'] ? trim($_POST['phone']) : '';
		$qq       = $_POST['qq'];
		$email    = $_POST['email'] ? htmlspecialchars_deep(trim($_POST['email'])) : '';
		$address  = $_POST['address'] ? trim($_POST['address']) : '';
		$mappoint = $_POST['mappoint'] ? trim($_POST['mappoint']) : '';

		if(empty($title))showmsg("���ⲻ��Ϊ��");
		if(empty($phone) && empty($qq) && empty($email))showmsg("�绰��qq��email��������дһ��");
		check_words(array($title,$content));

		$items = array(
			'areaid' => $areaid,
			'title' => $title,
			'content' => $content,
			'linkman' => $linkman,
			'email' => $email,
			'qq' => $qq,
			'phone' => $phone,
			'mappoint' => $mappoint,
			'address' => $address,
			'enddate' => $enddate
		);
		$res = editInfo($items, $_POST['cus_value'], $id);

		$res ? $msg="��ϲ�����޸ĳɹ���" : $msg="��Ǹ�޸�ʧ�ܣ�����ͷ���ϵ��";
		$link = "view.php?id=$id";
		showmsg($msg, $link);
	break;

	case 'report':
		$info     = intval($_REQUEST['id']);
		$type     = intval($_REQUEST['types']);
		$ip       = get_ip();
		$postdate = time();
		
		$yes = $db->getOne("SELECT COUNT(*) FROM {$table}report WHERE info='$info' AND ip='$ip'");
		if($yes) showmsg('���Ѿ��ٱ�����');

		$db->query("INSERT INTO {$table}report (info,type,ip,postdate) VALUES ('$info','$type','$ip','$postdate')");
		showmsg('�ٱ��ɹ�,лл���Ĳ��룡', trim(htmlspecialchars_deep($_SERVER['HTTP_REFERER'])));
	break;

	case 'comment':
		if(!$CFG['visitor_comment'] && empty($_userid)) showmsg('�οͲ����������ۣ����½���ٷ�����');
		$infoid = intval($_POST['id']);
		$userid = $_userid;
		$username = $_username;
		$content = htmlspecialchars_deep(trim($_POST['content']));
		$checkcode = trim($_POST['checkcode']);
		if(empty($infoid)) {
			showmsg('ȱ����Ϣ���');
		}
		$ip = get_ip();
		$postarea = getPostArea($ip);
		onlyarea($postarea);
		if(empty($content))showmsg('����д��������');
		if(empty($checkcode))showmsg('����д��֤��');
		check_code($checkcode);
		check_words(array($content));

		$postdate = time();
		$check = $CFG['comment_check'] == '0' ? '1' : '0' ;
		$sql = "INSERT INTO {$table}comment (infoid,userid,username,content,postdate,is_check,ip) VALUES ('$infoid','$userid','$username','$content','$postdate','$check','$ip')";
		$res = $db->query($sql);

		if($_username) {
			$credit_count = $db->getOne("select count(*) from {$table}pay_exchange where username='$_username' and addtime>".mktime(0,0,0)." and note='post_comment_credit' ");
			if($credit_count < $CFG['max_comment_credit']) {
				if(!empty($CFG['post_comment_credit']))credit_add($_username, $CFG['post_comment_credit'],'post_comment_credit');
			}
		}
		if($CFG['comment_check'] == '1')$msg = "<br>������˺������ʾ";
		$link = "view.php?id=$infoid";
		showmsg("�������۳ɹ� $msg", $link);
	break;

	case 'info_comment':
		$page = empty($_REQUEST['page'])? 1 : intval($_REQUEST['page']);
		$count = $db->getOne("SELECT COUNT(*) FROM {$table}comment where userid='$_userid'");
		$pager = get_pager('member.php',array('act'=>'info_comment'),$count,$page,'20');
		$sql = "SELECT * FROM {$table}comment where userid='$_userid' ORDER BY id DESC LIMIT $pager[start],$pager[size]";
		$res = $db->query($sql);
		$comments = array();
		while($row=$db->fetchRow($res)) {
			$row['postdate'] = date('Y-m-d', $row['postdate']);
			$row['is_check'] = $row['is_check'] == '1' ? '��' : '��' ;
			$row['title']    = cut_str($row['content'], 15);
			$comments[] = $row;
		}
		$seo['title'] = "�ҷ������Ϣ�����б�";
	    include template('member_info_comment');
	break;

	case 'delete':
		$id = is_array($_REQUEST['id']) ? join(',', array_map('intval',$_REQUEST['id'])) : intval($_REQUEST['id']);
		if(empty($id))showmsg('û��ѡ���¼');
		$db->query("DELETE FROM {$table}comment WHERE id IN ($id)");
		showmsg('ɾ���ɹ�', 'member.php?act=info_comment');
	break;

	case 'refer':
		$id = intval($_REQUEST['id']);
		$infouser = $db->getOne("select userid from {$table}info where id='$id' ");
		if($infouser != $_userid)showmsg('����Ϣ���������˺ŷ����ģ��޷�����');

		if(!empty($_POST['submit'])) {
		    /*
		     * �û�������Ϣ����ֵ����־��Ϣ*/
			gold_diff($_username, $CFG['info_refer_gold'], 'info_refer');
			$db->query("update {$table}info set postdate=".time()." where id='$id' ");
			$url = url_rewrite('category', array('cid'=> $info['catid']));
			showmsg('��Ϣ���³ɹ�', $url);
		} else {
			$seo['title'] = 'һ��ˢ����Ϣ';
			$user_info = member_info($_userid);
			$info = $db->getRow("select * from {$table}info where id='$id'");
			include template('member_info_refer');
		}
	break;

	case 'top':
		$id = intval($_REQUEST['id']);
		$infouser = $db->getOne("select userid from {$table}info where id='$id' ");
		if($infouser != $_userid)showmsg('����Ϣ���������˺ŷ����ģ��޷�����');

		if(!empty($_POST['submit'])) {
			gold_diff($_username, $CFG['info_top_gold'], 'info_top');
			$is_top = intval($_POST['number'])*3600*24+time();
			$db->query("update {$table}info set is_top='$is_top',top_type='$_POST[is_top]' where id='$id' ");

			if($_POST['is_top']=='1') {
				$catinfo = get_cat_info($info['catid']);
				$catid = $catinfo['parentid'];
			} else {
				$catid = $info['catid'];
			}
			$url = url_rewrite('category', array('cid'=> $catid));
			showmsg('��Ϣ�ö��ɹ�', $url);
		} else {
			$seo['title'] = '��Ϣ�ö�';
			$user_info = member_info($_userid);
			$info = $db->getRow("select * from {$table}info where id='$id'");
			$is_top = $info['is_top'];
			if($is_top>time())showmsg('������Ϣ���ö�');
			include template('member_info_top');
		}
	break;
	
	case 'send_info_mail':
		include PHPMPS_ROOT.'include/mail.inc.php';
		extract($_REQUEST);
		$email = decrypt($email, $CFG['crypt']);
		$content = $CFG['webname'].'����,����ظ���<br />'.$content;
		if (sendmail($email, $title, $content)) {
			showmsg('���ͳɹ�', $_SERVER['HTTP_REFERER']);
		} else {
			showmsg('����ʧ��', $_SERVER['HTTP_REFERER']);
		}
	break;

	case 'com_comment':
		$comid  = intval($_POST['comid']);
		$username = $_SESSION['username'];
		$content = htmlspecialchars_deep(trim($_POST['content']));
		$checkcode = trim($_POST['checkcode']);
		if(empty($comid)) {
			header("Location: ./\n");
			exit;
		}
		require_once PHPMPS_ROOT . 'include/ip.class.php';
		$ip = get_ip();
		$cha = new ip();
		$address = $cha->getaddress($ip);
		$postarea = $address["area1"].$address["area2"];
		onlyarea($postarea);
		if(empty($content))showmsg('����д��������');
		if(empty($checkcode))showmsg('����д��֤��');

		check_code($checkcode);
		check_words($who=array('content'));

		$postdate = time();
		$ip = get_ip();
		$check = $CFG['comment_check'] == '0' ? '1' : '0' ;
		$sql = "INSERT INTO {$table}com_comment (comid,userid,username,content,postdate,is_check,ip) VALUES ('$comid','$userid','$username','$content','$postdate','$check','$ip')";
		$res = $db->query($sql);
		if($CFG['comment_check'] == '1')$msg = "<br>������˺������ʾ";

		showmsg("�������۳ɹ�", $_SERVER['HTTP_REFERER']);
	break;

	case 'com':
		$page = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
		$size=20;
		$sql = "SELECT COUNT(*) FROM {$table}com WHERE userid='$_userid'";
		$count = $db->getOne($sql);
		$max_page = ($count> 0) ? ceil($count / $size) : 1;
		if($page>$max_page)$page = $max_page;
		$pager['search'] = array('act' => 'com');
		$pager = get_pager('member.php', $pager['search'], $count, $page, $size);
		$sql = "SELECT i.*,a.areaname FROM {$table}com as i left join {$table}area as a on a.areaid=i.areaid WHERE userid='$_userid' ORDER BY comid desc,postdate desc LIMIT $pager[start],$pager[size]";
		$res = $db->query($sql);
		$coms = array();
		while($row = $db->fetchRow($res)) {
			$row['comname']  = cut_str($row['comname'],'18');
			$row['postdate'] = date('y-m-d', $row['postdate']);
			$row['is_check'] = $row['is_check']=='1' ? '��' : '��';
			$row['url']      = url_rewrite('com',array('act'=>'view','comid'=>$row['comid']));
			$coms[] = $row;
		}
		$seo['title'] = "�ҷ�������ҵ��ҳ��Ϣ";
		include template('member_com','com');
	break;

	case 'editcom':
		$comid = intval($_GET['id']);
		$sql = "SELECT c.*,cc.catname FROM {$table}com as c left join {$table}com_cat as cc on c.catid=cc.catid WHERE comid = '$comid'";
		$com = $db->getRow($sql);
		$com['comuid'] = $com['userid'];
		unset($com['userid']);
		if(empty($com))showmsg('��Ϣ������','index.php');
		if($com['comuid']!=$_userid)showmsg('��Ϣ�����������ģ������޸�');
		extract($com);
		$postdate = date('Y-m-d', $postdate);
		$thumb    = PHPMPS_PATH.$thumb;

		if(!empty($mappoint)) {
			$mappoints = explode(',', $mappoint);
		}
		$com_area = area_options($areaid);
		$seo['title'] = '�޸���ҵ��ҳ��Ϣ';
		include template('member_editcom','com');
	break;

	case 'updatecom':
		$comid    = intval($_POST['id']);
		$comname  = $_POST['comname'] ? htmlspecialchars_deep(trim($_POST['comname'])) : '';
		$areaid   = $_POST['areaid'] ? intval($_POST['areaid']) : '';
		$introduce  = $_POST['introduce'] ? htmlspecialchars_deep(trim($_POST['introduce'])) : '';
		$description = cut_str($introduce,30);
		$linkman  = $_POST['linkman'] ? htmlspecialchars_deep(trim($_POST['linkman'])) : '';
		$phone    = $_POST['phone'] ? trim($_POST['phone']) : '';
		$qq       = $_POST['qq'];
		$email    = $_POST['email'] ? htmlspecialchars_deep(trim($_POST['email'])) : '';
		$address  = $_POST['address'] ? trim($_POST['address']) : '';
		$mappoint = $_POST['mappoint'] ? trim($_POST['mappoint']) : '';
		$hours     = $_POST['hours'] ? htmlspecialchars_deep(trim($_POST['hours'])) : '';
		$fax       = trim($_POST['fax']);
		
		$sql = "SELECT * FROM {$table}com WHERE comid = '$comid'";
		$com = $db->getRow($sql);
		$com['comuid'] = $com['userid'];
		unset($com['userid']);
		if(empty($com))showmsg('��Ϣ������','index.php');
		if($com['comuid']!=$_userid)showmsg('��Ϣ�����������ģ������޸�');

		if(empty($comname))showmsg("���ⲻ��Ϊ��");
		if(empty($introduce))showmsg("���ݲ���Ϊ��");
		if(empty($phone) && empty($qq) && empty($email))showmsg("�绰��qq��email��������дһ��");
		
		check_words($who=array('comname','content'));

		if(!empty($_FILES['thumb']['name']))
		{
			
			$alled = array('png','jpg','gif','jpeg');
			$exname = strtolower(trim(substr(strrchr($_FILES['thumb']['name'], '.'), 1)));
			if(checkupfile($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['tmp_name'] != 'none' && $_FILES['thumb']['tmp_name'] && $_FILES['thumb']['name'] && $_FILES['thumb']['size']<'523298' && in_array($exname,$alled))
			{
				$sql = "SELECT thumb FROM {$table}com WHERE comid IN ($comid)";
				$image = $db->getAll($sql);
				foreach((array)$image AS $val) {
					if($val['thumb'] != '' && is_file(PHPMPS_ROOT.$val['thumb'])) {
						@unlink(PHPMPS_ROOT . $val['thumb']);
					}
				}

				$thumb_name = $comid.'_thumb'. '.' . end(explode('.', $_FILES['thumb']['name']));
				$dir = PHPMPS_ROOT . 'data/com/thumb/';
				if(!is_dir($dir)) {
					if(@mkdir(rtrim($dir,'/'), 0777))@chmod($dir, 0777);
				}
				$to = $dir.'/'. $thumb_name;
				CreateSmallImage( $_FILES['thumb']['tmp_name'], $to, 200, 150);
				$image = 'data/com/thumb/'. $thumb_name;
				$sql = "update {$table}com set thumb='$image' where comid='$comid' ";
				$db->query($sql);
			}
		}
		$sql = "UPDATE {$table}com SET
				areaid='$areaid',
				comname='$comname',
				introduce='$introduce',
				description='$description',
				linkman='$linkman',
				email='$email',
				qq='$qq',
				phone='$phone',
				mappoint='$mappoint',
				address='$address',
				fax='$fax',
				hours='$hours'
				where comid = '$comid' ";
		$res = $db->query($sql);
		
		$msg="��ϲ�����޸ĳɹ���";
		$link = url_rewrite('com',array('act'=>'view', 'comid'=>$comid));
		showmsg($msg, $link);
	break;

	case 'delcom':
		$comid = intval($_REQUEST['id']);
		$sql = "select userid from {$table}com where comid='$comid' ";
		$comuserid = $db->getOne($sql);
		if($comuserid!=$_userid)showmsg('����Ϣ�����������ģ��������޸�');
		
		$sql = "SELECT thumb FROM {$table}com WHERE comid IN ($comid)";
		$image = $db->getOne($sql);
		if($image != '' && is_file(PHPMPS_ROOT.$image)) {
			@unlink(PHPMPS_ROOT.$image);
		}

		$sql = "SELECT path FROM {$table}com_image WHERE comid IN ($comid)";
		$image = $db->getAll($sql);
		foreach((array)$image AS $val) {
			if($val[path] != '' && is_file(PHPMPS_ROOT.$val[path])) {
				@unlink(PHPMPS_ROOT.$val[path]);
			}
		}

		$db->query("DELETE FROM {$table}com_image WHERE comid IN ($comid)");
		$db->query("DELETE FROM {$table}com WHERE comid IN ($comid)");

		showmsg('ɾ����Ϣ�ɹ�',$_SERVER['HTTP_REFERER']);
	break;

	case 'avatar':
		if(!$CFG['uc']) showmsg('ϵͳû������Ucenter������ʹ�ô˹���');
		include PHPMPS_ROOT.'include/uc.inc.php';
		if(!$_userid) showmsg('���ȵ�¼', 'member.php?act=login');

		$uid = $db->getone("select uid from {$table}member where userid='$_userid' ");
		$uc_html = uc_call("uc_avatar",  array($uid));
		$seo['title'] = '�޸�ͷ��';
		include template('member_uc_avatar');
	break;
	
	case 'send_check_email':
		if($_POST) 
		{
			$email = trim($_POST['email']);
			$user_info = member_info($_userid);
			$code = md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']);
			$reset_email = $CFG['weburl'].'/member.php?act=check_email&userid='.$_userid.'&code=' . $code;
			
			$send_date = date('Y-m-d', time());
			$content = "{$username}���ã�<br><br>������������(���߸��Ƶ����������):<br><br><a href=".$reset_email." target=\"_blank\">".$reset_email."</a><br><br>�Խ��������ʼ���֤��<br><br>".$send_date;

			$code = md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']);
			include PHPMPS_ROOT.'include/mail.inc.php';
			if (sendmail($email, $CFG['webname'].'-�ʼ���֤', $content)) {
				showmsg('���ͳɹ�,���¼���������֤' , 'member.php?act=send_check_email');
			} else {
				showmsg('����ʧ��' , 'member.php?act=send_check_email');
			}
		} else {
			$userinfo = member_info($_userid);
			$seo['title'] = '��֤�ʼ�';
			include template('member_check_email');
		}
	break;

	case 'check_email':
		$code = isset($_GET['code']) ? trim($_GET['code'])  : '';
		$user_info = member_info(intval($_REQUEST['userid']));

		if ($user_info && (!empty($code) && md5($user_info['userid'] . $CFG['crypt'] . $user_info['registertime']) == $code)) {
			$sql = "update {$table}member set status='1' where userid='$_userid' ";
			$query = $db->query($sql);
			showmsg('�ʼ���֤�ɹ�', 'member.php');
		} else {
			showmsg('�ʼ���֤ʧ��', '?send_check_email');
		}
	break;
}
?>