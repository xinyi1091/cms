<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

//��ʼ��act����
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

if($_REQUEST['act'] != 'modify' && $_REQUEST['act'] != 'repass')chkadmin('admin');

switch ($_REQUEST['act'])
{
	case 'list':
		$sql = "SELECT * FROM {$table}admin ORDER BY userid";
		$res = $db->query($sql);
		$admin = array();
		while($row = $db->fetchRow($res)){
			$admin[] = $row;
		}
		include tpl('list_admin');
	break;

	case 'add':
		include tpl('add_admin');
	break;

	case 'insert':
		$username = trim($_POST['username']);
		$email    = trim($_POST['email']);
		$purview  = is_array($_POST['purview']) ? implode(",", $_POST['purview']) : '';

		if(empty($username))show('�������û���');
		if(!empty($username)) {
			$sql = "select count(*) from {$table}admin where username = '$username'";
			if($db->getOne($sql))show('�Ѿ����ڴ��û��������������룡');
		}
		if(empty($_POST['password']))show('����������');
		if(empty($_POST['repass']))show('�������ظ�����');
		if($_POST['password'] <> $_POST['repass'])show('������������벻һ��');
		
		if(empty($email))show('����������');
		if(!empty($email)) {
			if(!is_email($email))show('�ʼ���ʽ����ȷ');
			$sql = "select count(*) from {$table}admin where email = '$email'";
			if($db->getOne($sql))show('�Ѿ����ڴ��ʼ������������룡');
		}
		
		$password = MD5($_POST['password']);
		$sql = "INSERT INTO {$table}admin (username,password,email,purview) VALUES ('$username','$password','$email','$purview')";
		$res = $db->query($sql);

		$msg = $res ? '��ӹ���Ա�ɹ�' : '��ӹ���Աʧ��';
		admin_log('��ӹ���Ա $username �ɹ�');
		$link = 'admin.php?act=add';
		show('��ӹ���Ա�ɹ�', $link);
	break;

	case 'edit':
		$userid = intval($_REQUEST['id']);
		$sql = "select * from {$table}admin where userid = '$userid'";
		$admin = $db->getRow($sql);

		$purview = explode(',',$admin['purview']);
		include tpl('edit_admin');
	break;

	case 'update':
		$userid  = trim($_GET['id']);
		$email   = trim($_POST['email']);
		$purview = is_array($_POST['purview']) ? implode(",", $_POST['purview']) : '';

		if(!empty($_POST['password']) && !empty($_POST['repass'])){	
			if($_POST['password'] <> $_POST['repass'])show('������������벻һ��');
			$password = MD5($_POST['password']);
			$pass = "password = '$password',";
		}
		
		if(empty($email))show('���䲻��Ϊ��');
		if(!empty($_POST['email'])){
			if(!is_email($email))show('�ʼ���ʽ����ȷ');
			$sql = "select count(*) from {$table}admin where email = '$email' and userid <> '$_GET[id]' ";
			if($db->getOne($sql))show('�Ѿ����ڴ�email�����������룡');
		}
		
		$sql = "update {$table}admin set 
		".$pass."
		email = '$email',
		purview = '$purview'
		where userid = '$userid' ";
		$res = $db->query($sql);

		$msg = $res ? '�༭����Ա�ɹ�' : '�༭����Աʧ��';
		admin_log('�༭����Ա $username �ɹ�');

		$link = 'admin.php?act=list';
		show("�༭����Ա�ɹ�", $link);
	break;

	case 'modify':
		include tpl('modify');
	break;

	case 'repass':
		if(empty($_REQUEST[password]))show("����������");
		if(empty($_REQUEST[repassword]))$msg .= "�������ظ�����\n";
		if($_REQUEST[password] <> $_REQUEST[repassword])show("������������벻һ��");
		
		$password = md5($_REQUEST[password]);
		$sql = "UPDATE {$table}admin SET password = '$password' WHERE userid = '$_SESSION[adminid]'";
		$res = $db->query($sql);
		admin_log("$_SESSION[adminid]�޸�����ɹ�");
		show('�����޸ĳɹ�', 'admin.php');
	break;

	case 'delete':
		$userid = intval($_GET[id]);
		//check is_admin
		$sql = "select is_admin from {$table}admin where userid = '$userid' ";
		$is_admin = $db->getOne($sql);
		if($is_admin>0)show('��ʼ����Ա���ܱ�ɾ��');
		//get username
		$username = $db->getOne("select username from {$table}admin where userid = '$userid' ");
		//delete user
		$sql = "delete from {$table}admin where userid = '$userid' ";
		$res = $db->query($sql);
		$msg = $res ? 'ɾ������Ա $username �ɹ�' : 'ɾ������Ա $username �ɹ�';
		admin_log("ɾ������Ա$username�ɹ�");
		$link = "admin.php?act=list";
		show("ɾ������Ա$username�ɹ�", $link);
	break;
}
?>