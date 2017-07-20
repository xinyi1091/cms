<?php

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.php';

chkadmin('area');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$area = get_area_list();
		$here = "�����б�";
		$action = array('name'=>'��ӵ���', 'href'=>'area.php?act=add');
	    include tpl('list_area');
	break;

	case 'add':
		$area = $db->getAll("SELECT * from {$table}area WHERE parentid=0");
		$maxorder = $db->getOne("SELECT MAX(areaorder) FROM {$table}area");
		$maxorder = $maxorder + 1;
		$here = "��ӵ���";
		$action = array('name'=>'�����б�', 'href'=>'area.php?act=list');
	    include tpl('add_area');
	break;

	case 'insert':
		if(empty($_REQUEST['areaname']))show("����д��������");
		$len = strlen($_REQUEST['areaname']);
		if($len < 2 || $len > 30)show("������������2����30���ַ�֮��");
		$areaname  = trim($_REQUEST['areaname']);
		$parentid  = intval($_REQUEST['parentid']);
		$areaorder = intval($_REQUEST['areaorder']);

		if(empty($areaorder)) {
			$sql = "SELECT MAX(areaorder) FROM {$table}area";
			$maxorder = $db->getOne($sql);
			$areaorder = $maxorder + 1;
		}
		$sql = "INSERT INTO {$table}area (areaname,parentid,areaorder) VALUES ('$areaname','$parentid','$areaorder')";
		$res = $db->query($sql);
		
		clear_caches('phpcache');
		admin_log("������� $areaname �ɹ�");
		show('��ӵ����ɹ�','area.php?act=add');
	break;

	case 'edit':
	    $areaid = intval($_REQUEST['areaid']);
		$sql = "SELECT * FROM {$table}area WHERE areaid = '$areaid'";
		$area = $db->getRow($sql);
		$sql  = "SELECT * FROM {$table}area WHERE parentid = '0'";
	    $areas = $db->getAll($sql);	
		$here = "�༭����";
		$action = array('name'=>'�����б�', 'href'=>'area.php?act=list');
		include tpl('edit_area');
	break;

	case 'update':
		if(empty($_REQUEST['areaname']))show("����д��������");
		$len = strlen($_REQUEST['areaname']);
		if($len < 2 || $len > 30)show("������������2����30���ַ�֮��");
        
		$areaid    = intval($_REQUEST['areaid']);
		$areaname  = trim($_REQUEST['areaname']);
		$parentid  = intval($_REQUEST['parentid']);
		$areaorder = intval($_REQUEST['areaorder']);

		$sql = "UPDATE {$table}area SET areaname='$areaname',
		parentid='$parentid',
		areaorder='$areaorder'
		WHERE areaid = '$areaid'";
		$res = $db->query($sql);
		clear_caches('phpcache');
		admin_log("�༭���� $areaname �ɹ�");        
		$link = "area.php?act=list";
		show('�༭�����ɹ�', $link);
	break;

	case 'delete':
		$areaid = intval($_REQUEST['areaid']);
		if(empty($areaid))show('û��ѡ���¼');
		$sql = "DELETE FROM {$table}area WHERE areaid='$areaid'";
	    $res = $db->query($sql);
		clear_caches('phpcache');
		admin_log("ɾ������ $areaid �ɹ�");
		$link = "area.php?act=list";
		show('ɾ�������ɹ�', $link);
	break;
}
?>