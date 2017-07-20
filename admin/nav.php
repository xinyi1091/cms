<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('nav');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch($_REQUEST['act'])
{
	case 'list':
		$sql = "SELECT * FROM {$table}nav ORDER BY id";
		$nav = $db->getAll($sql);
		$here = "�����б�";
		$action = array('name'=>'��ӵ���', 'href'=>'nav.php?act=add');
	    include tpl('list_nav');
	break;

	case 'add':
		$maxorder = $db->getOne("SELECT MAX(navorder) FROM {$table}nav");
		$maxorder = $maxorder + 1;
	    include tpl('add_nav');
	break;

	case 'insert':
		if(empty($_POST['navname']))show("����д��������");
		if(empty($_POST['url']))show("����д��ת��ַ");

		$navname   = trim($_POST['navname']);
		$url       = trim($_POST['url']);
		$navorder  = intval($_POST['order']);
		$target    = trim($_POST['target']);
		
		if(empty($navorder)) {
			$sql = "SELECT MAX(navorder) FROM {$table}nav";
			$maxorder  = $db->getOne($sql);
			$navorder = $maxorder + 1;
		}
		$res = $db->query("INSERT INTO {$table}nav (navname,url,target,navorder) VALUES ('$navname','$url','$target','$navorder')");

		clear_caches('phpcache', 'nav');
		admin_log("��ӵ��� $navname �ɹ�");
		show('��ӵ����ɹ�','nav.php?act=add');
	break;

	case 'edit':
	    $id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}nav WHERE id = '$id'";
		$nav = $db->getRow($sql);
		$action = array('name'=>'�����б�', 'href'=>'nav.php?act=list');
		include tpl('edit_nav');
	break;

	case 'update':
		if(empty($_POST['navname']))show("����д��������");
		if(empty($_POST['url']))show("����д��ת��ַ");

		$id        = intval($_POST['id']);
		$navname   = trim($_POST['navname']);
		$url       = trim($_POST['url']);
		$navorder  = intval($_POST['order']);
		$target    = trim($_POST['target']);

		if(empty($navorder)) {
			$sql = "SELECT MAX(navorder) FROM {$table}nav";
			$maxorder  = $db->getOne($sql);
			$navorder = $maxorder + 1;
		}

		$res = $db->query("UPDATE {$table}nav SET navname='$navname',url='$url',target='$target',navorder='$navorder' WHERE id = '$id'");
		clear_caches('phpcache', 'nav');
		admin_log("�༭���� $navname �ɹ�");
		$link = "nav.php?act=list";
		show('�༭�����ɹ�', $link);
	break;

	case 'batch':
		$id = is_array($_REQUEST['id']) ? join(',', $_REQUEST['id']) : intval($_REQUEST['id']);
		if(empty($id))show('û��ѡ���¼');
		$sql = "DELETE FROM {$table}nav WHERE id IN ($id)";
        $re = $db->query($sql);
		clear_caches('phpcache', 'nav');
		admin_log("ɾ������ $id �ɹ�");
		show('ɾ�������ɹ�', 'nav.php?act=list');
	break;
}
?>