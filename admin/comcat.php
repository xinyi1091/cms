<?php

define('IN_BIANMPS', true);
require_once 'include/common.php';

require '../include/com.fun.php';

chkadmin('category');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$cat = get_com_cat_list();
		$here = "�����б�";
		$action = array('name'=>'��ӷ���', 'href'=>'comcat.php?act=add');
	    include tpl('list_com_cat', 'com');
	break;

	case 'add':
        $cat = get_com_cat_list();
		$cats = $db->getAll("SELECT * from {$table}com_cat WHERE parentid=0");
		$maxorder = $db->getOne("SELECT MAX(catorder) FROM {$table}com_cat");
		$maxorder = $maxorder + 1;

		$here = "��ӷ���";
		$action = array('name'=>'�����б�', 'href'=>'comcat.php?act=list');
	    include tpl('add_com_cat', 'com');
	break;

	case 'insert':
		if(empty($_REQUEST[catname]))show("����д��������");
		$len = strlen($_REQUEST[catname]);
		if($len < 2 || $len > 30)show("������������2����30���ַ�֮��");

		$catname     = trim($_REQUEST['catname']);
	    $parentid    = intval($_REQUEST['parentid']);
		$catorder    = intval($_REQUEST['catorder']);
		$keywords    = trim($_REQUEST['keywords']);
		$description = trim($_REQUEST['desc']);

		if(empty($catorder)) {
			$sql = "SELECT MAX(catorder) FROM {$table}com_cat";
			$maxorder = $db->getOne($sql);
			$catorder = $maxorder + 1;
		}
		$sql = "INSERT INTO {$table}com_cat (catname,keywords,description,parentid,catorder) VALUES ('$catname','$keywords','$description','$parentid','$catorder')";
		$res = $db->query($sql);

		clear_caches('phpcache');
		admin_log("������� $cataname �ɹ�");
		show('��ӷ���ɹ�','comcat.php?act=add');
	break;

	case 'edit':
	    $catid = intval($_REQUEST['catid']);
		$sql = "SELECT * FROM {$table}com_cat WHERE catid = '$catid'";
		$cat = $db->getRow($sql);
		$sql  = "SELECT * FROM {$table}com_cat WHERE parentid = '0'";
	    $cats = $db->getAll($sql);

		include tpl('edit_com_cat', 'com');
	break;
	
	case 'update':
		if(empty($_REQUEST[catname]))show("����д��������");
		$len = strlen($_REQUEST[catname]);
		if($len < 2 || $len > 30)show("������������2����30���ַ�֮��");
        
		$catid       = intval($_REQUEST['catid']);
		$catname     = trim($_REQUEST['catname']);
	    $parentid    = intval($_REQUEST['parentid']);
		$catorder    = intval($_REQUEST['catorder']);
		$keywords    = trim($_REQUEST['keywords']);
		$description = trim($_REQUEST['desc']);

		$sql = "UPDATE {$table}com_cat SET catname='$catname',keywords='$keywords',description='$description',parentid='$parentid',catorder='$catorder' WHERE catid = '$catid'";
		$res = $db->query($sql);
		
		clear_caches('phpcache');
		admin_log("�༭���� $catname �ɹ�");
		$link = "comcat.php?act=list";
		show('�༭����ɹ�', $link);
	break;

	case 'delete':
		$catid = intval($_REQUEST['catid']);
		if(empty($catid))show('û��ѡ���¼');
		
		$sql = "SELECT COUNT(*) FROM {$table}com_cat WHERE parentid = '$catid' ";
		if($db->getOne($sql)>0)show('�÷������з��࣬�޷�ɾ��');
		
		$sql = "SELECT COUNT(*) FROM {$table}com WHERE catid = '$catid' ";
		if($db->getOne($sql)>0)show('�÷���������Ϣ���޷�ɾ��');

		$sql = "DELETE FROM {$table}com_cat WHERE catid='$catid'";
	    $db->query($sql);

		clear_caches('phpcache');
		admin_log("ɾ������ $catid �ɹ�");
		$link = 'comcat.php?act=list';
		show('ɾ������ɹ�', $link);
	break;
}
?>