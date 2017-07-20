<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('fac');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch($_REQUEST['act'])
{
	case 'list':
		$sql = "SELECT * FROM {$table}facilitate  ORDER BY id";
		$fac = array();
		$fac = $db->getAll($sql);
	    include tpl('list_fac');
	break;

	case 'add':
		$maxorder  = $db->getOne("SELECT MAX(listorder) FROM {$table}facilitate ");
		$listorder = $maxorder + 1;
		$here = "��ӱ�����Ϣ";
		$action = array('name'=>'������Ϣ�б�', 'href'=>'bm.php?act=list');
	    include tpl('add_fac');
	break;

	case 'insert':
		if(empty($_POST['title']))show("����д����");
		if(empty($_POST['phone']))show("����д�绰");

		$title      = trim($_POST['title']);
		$phone      = trim($_POST['phone']);
		$introduce  = trim($_POST['introduce']);
		$listorder  = intval($_POST['listorder']);
		$updatetime = time();

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}facilitate";
			$maxorder  = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}

		$sql = "INSERT INTO {$table}facilitate (title,phone,introduce,listorder,updatetime) VALUES ('$title','$phone','$introduce','$listorder','$updatetime')";
		$res = $db->query($sql);
		clear_caches('phpcache', 'fac');
		admin_log("��ӱ�����Ϣ $title �ɹ�");
		show('��ӱ�����Ϣ�ɹ�','fac.php?act=add');
	break;

	case 'edit':
	    $id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}facilitate WHERE id = '$id'";
		$fac = $db->getRow($sql);
		$here = "�༭������Ϣ";
		$action = array('name'=>'������Ϣ�б�', 'href'=>'fac.php?act=list');
		include tpl('edit_fac');
	break;

	case 'update':
		if(empty($_POST['title']))show("����д����");
		if(empty($_POST['phone']))show("����д�绰");
		
		$id        = intval($_POST['id']);
		$title     = trim($_POST['title']);
		$phone     = trim($_POST['phone']);
		$introduce = trim($_POST['introduce']);
		$listorder = intval($_POST['listorder']);
		$updatetime = time();

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}facilitate";
			$maxorder  = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}

		$sql = "UPDATE {$table}facilitate SET 
				title='$title',
				phone='$phone',
				introduce='$introduce',
				listorder='$listorder',
				updatetime='$updatetime'
				WHERE id = '$id'";
		$res = $db->query($sql);
		clear_caches('phpcache','fac');

		admin_log("�༭������Ϣ $title �ɹ�");
		$link = "fac.php?act=list";
		show('�༭������Ϣ�ɹ�', $link);
	break;

	case 'batch':
		$id = !empty($_POST['id']) ? join(',', $_POST['id']) : 0;
		if(empty($id))show('û��ѡ���¼');

		$sql = "DELETE FROM {$table}facilitate WHERE id IN ($id)";
        $re = $db->query($sql);
		clear_caches('phpcache', 'fac');
		admin_log("ɾ��������Ϣ $id �ɹ�");
		show('ɾ��������Ϣ�ɹ�', 'fac.php?act=list');
	break;
}
?>