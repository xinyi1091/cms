<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('link');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch($_REQUEST['act'])
{
	case 'list':
		$sql = "SELECT * FROM {$table}link ORDER BY id";
		$links = $db->getAll($sql);
		$here = "�����б�";
		$action = array('name'=>'�������', 'href'=>'link.php?act=add');
	    include tpl('list_link');
	break;

	case 'add':
		$maxorder = $db->getOne("SELECT MAX(linkorder) FROM {$table}link");
		$maxorder = $maxorder + 1;
		$here = "�������";
		$action = array('name'=>'�����б�', 'href'=>'link.php?act=list');
	    include tpl('add_link');
	break;

	case 'insert':
		if(empty($_POST['webname']))show("����д��������");
		if(empty($_POST['url']))show("����д���ӵ�ַ");

		$webname   = trim($_POST['webname']);
		$url       = trim($_POST['url']);
		$linkorder = intval($_POST['order']);
		$logo      = trim($_POST['logo']);
		
		if(empty($linkorder)) {
			$sql = "SELECT MAX(catorder) FROM {$table}category";
			$maxorder  = $db->getOne($sql);
			$linkorder = $maxorder + 1;
		}

		$sql = "INSERT INTO {$table}link (webname,url,linkorder,logo) VALUES ('$webname','$url','$linkorder','$logo')";
		$res = $db->query($sql);
		clear_caches('phpcache', 'link');
		admin_log("������� $webname �ɹ�");
		show('������ӳɹ�','link.php?act=add');
	break;

	case 'edit':
	    $linkid = intval($_REQUEST['linkid']);
		$sql = "SELECT * FROM {$table}link WHERE id = '$linkid'";
		$link = $db->getRow($sql);
		$here = "�༭����";
		$action = array('name'=>'�����б�', 'href'=>'link.php?act=list');
		include tpl('edit_link');
	break;

	case 'update':
		if(empty($_POST['webname']))show("����д��������");
		if(empty($_POST['url']))show("����д���ӵ�ַ");

        $linkid    = intval($_POST['linkid']);
		$webname   = trim($_POST['webname']);
		$url       = trim($_POST['url']);
		$linkorder = intval($_POST['order']);
		$logo      = trim($_POST['logo']);
		
		if(empty($linkorder)) {
			$sql = "SELECT MAX(catorder) FROM {$table}category";
			$maxorder  = $db->getOne($sql);
			$linkorder = $maxorder + 1;
		}
		$sql = "UPDATE {$table}link SET webname='$webname',url='$url',linkorder='$linkorder',logo='$logo' WHERE id = '$linkid'";
		$res = $db->query($sql);
		clear_caches('phpcache', 'link');
		admin_log("�༭���� $webname �ɹ�");
		$link = "link.php?act=list";
		show('�༭���ӳɹ�', $link);
	break;

	case 'delete':
		$id = intval($_REQUEST['linkid']);
		if(empty($id))show('û��ѡ���¼');
		$sql = "DELETE FROM {$table}link WHERE id='$id'";
	    $res = $db->query($sql);
		clear_caches('phpcache', 'link');
		admin_log("ɾ������ $id �ɹ�");
		show('ɾ�����ӳɹ�', 'link.php?act=list');
	break;

	case 'batch':
		$id = !empty($_POST['id']) ? join(',', $_POST['id']) : 0;
		if(empty($id))show('û��ѡ���¼');
		$sql = "DELETE FROM {$table}link WHERE id IN ($id)";
        $re = $db->query($sql);

		clear_caches('phpcache', 'link');
		admin_log("ɾ������ $id �ɹ�");
		show('ɾ�����ӳɹ�', 'link.php?act=list');
	break;
}
?>