<?php
define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';
if($CFG['uc'])require BIANMPS_ROOT . 'include/uc.inc.php';
chkadmin('member');
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$here = "��Ա�б�";
		$page = empty($_REQUEST['page'])? 1 : intval($_REQUEST['page']);
		$sql = "SELECT COUNT(*) FROM {$table}member";
		$count = $db->getOne($sql);
		$pager = get_pager('member.php',array('act'=>'list'),$count,$page,'20');
		
		$sql = "SELECT * FROM {$table}member ORDER BY lastlogintime DESC LIMIT $pager[start],$pager[size]"; 
		$res = $db->query($sql);
		$userinfo = array();
		while($row=$db->fetchRow($res)) {
			$row['username'] = cut_str($row['username'],20);
			$row['registertime']  = date('Y-m-d H:i:s',$row['registertime']);
			$row['lastlogintime'] = date('Y-m-d H:i:s',$row['lastlogintime']);
			$userinfo[] = $row;
		}
	    include tpl('list_member');
	break;

	case 'edit':
	    $userid = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}member WHERE userid = '$userid'";
		$userinfo = $db->getRow($sql);
		$here = "�༭��Ա��Ϣ";
		include tpl('edit_member');
	break;

	case 'update':
		$userid = $_REQUEST['id'] ? intval($_REQUEST['id']) : '';
		$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
		$password = $_REQUEST['password'] ? trim($_REQUEST['password']) : '';
		$repassword = $_REQUEST['repassword'] ? trim($_REQUEST['repassword']) : '';
		$email = $_REQUEST['email'] ? trim($_REQUEST['email']) : '';
		$status = $_REQUEST['status'] ? trim($_REQUEST['status']) : '0';

		if($password != $repassword)show('�����������벻��ͬ');
		if($password && (strlen($password) < 5 || strlen($password) > 30))show("���������5����30���ַ�֮��");
		if(empty($email))show('���䲻��Ϊ��');
		if(!preg_match("/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+\.[0-9a-zA-Z_-]+$/",$email))show('�����ʽ����');
		
		/* ��֤�ʼ��Ƿ���� */
		$sql = "select count(*) from {$table}member where email='$email' and userid<>'$userid' ";
		if($db->getOne($sql)>0)show('������д���ʼ��Ѿ�����');
		
		if($password)$set[] = " password = '".MD5($password)."' ";
		$set[] = " email = '$email' ";
		$set[] = " status = '$status' ";
		if(!empty($set)) $set = join(',', $set);

		$res = $db->query("UPDATE {$table}member SET $set WHERE userid = '$userid'");
		
		if($CFG['uc']) {
			uc_call("uc_user_edit", array($username, '', $password, $email, '1'));
		}
		admin_log("�޸Ļ�Ա $username ��Ϣ�ɹ�");
		$link = "member.php?act=list";
		show("�޸Ļ�Ա $username ��Ϣ�ɹ�", $link);
	break;

	case 'batch':
		$userid = is_array($_REQUEST['id']) ? join(',',$_REQUEST['id']) : intval($_REQUEST['id']);
		if(empty($userid))show('û��ѡ���¼');
		
		/* 
		 ���ϵͳ����ɾ����Ա��ͬʱɾ������������Ϣ����ɾ����Ա������������Ϣ��������Ϣ��ͼƬ����Ϣ�����ۺ���Ϣ�ľٱ���
		 ��������ϵͳ����"ɾ����Ա�Ƿ�ͬʱɾ����Ա��������Ϣ"��
		 */
		if($CFG['del_m_info'])
		{
			$sql = "SELECT id FROM {$table}info WHERE userid in ($userid) ";
			$infos = $db->getAll($sql);

			foreach($infos as $info)
			{
				//ɾ������
				$db->query("DELETE FROM {$table}comment WHERE infoid = '$info[id]'");

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
				$sql = "DELETE from {$table}cus_value WHERE infoid = $info[id]";
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
		if($CFG['del_m_comment']) $db->query("delete from {$table}comment where userid in ($userid) ");
		
		/* ɾ����Ա�����ݿ���Ϣ */
	    $db->query("DELETE FROM {$table}member WHERE userid in ($userid) ");

		admin_log("ɾ����Ա $userid �ɹ�");
		$link = 'member.php?act=list';
		show("ɾ����Ա $userid �ɹ�", $link);
	break;
}
?>