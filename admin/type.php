<?php

define('IN_PHPMPS', true);
require_once dirname(__FILE__) . '/include/common.php';


chkadmin('type');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;
$module = $_REQUEST['module'];

switch ($_REQUEST['act'])
{
	case 'list':
		$sql = "select * from {$table}type where module='$module' ";
		$type = $db->getAll($sql);
		$here = "�����б�";
		$action = array('name'=>'��ӷ���', 'href'=>"type.php?act=add&module=$module ");
	    include tpl('list_type');
	break;

	case 'add':
		$maxorder = $db->getOne("SELECT MAX(listorder) FROM {$table}type where module='$module'");
		$listorder = $maxorder + 1;
		$here = "��ӷ���";
		$action = array('name'=>'�����б�', 'href'=>'type.php?act=list&module=$module');
	    include tpl('add_type');
	break;

	case 'insert':
		if(empty($_REQUEST['typename']))show("����д��������");
		$len = strlen($_REQUEST['typename']);
		if($len<2 || $len>30)show("������������2����30���ַ�֮��");

		$typename    = trim($_REQUEST['typename']);
		$listorder   = intval($_REQUEST['listorder']);
		$keywords    = trim($_REQUEST['keywords']);
		$description = trim($_REQUEST['description']);

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}type";
			$maxorder = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}
		$sql = "INSERT INTO {$table}type (typename,listorder,keywords,description,module) VALUES ('$typename','$listorder','$keywords','$description','$module')";
		$res = $db->query($sql);
		
		admin_log("������� $typeaname �ɹ�");
		show('��ӷ���ɹ�',"type.php?act=add&module=$module");
	break;

	case 'edit':
	    $typeid = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}type WHERE typeid = '$typeid'";
		$type = $db->getRow($sql);
		
		$here = "�༭����";
		$action = array('name'=>'�����б�', 'href'=>"type.php?act=list&module=$type[module]");
		include tpl('edit_type');
	break;

	case 'update':
		if(empty($_REQUEST['typename']))show("����д��������");
		$len = strlen($_REQUEST['typename']);
		if($len<2 || $len>30)show("������������2����30���ַ�֮��");
		
		$typeid      = intval($_REQUEST['typeid']);
		$typename    = trim($_REQUEST['typename']);
		$listorder   = intval($_REQUEST['listorder']);
		$keywords    = trim($_REQUEST['keywords']);
		$description = trim($_REQUEST['description']);

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}type where module='$module'";
			$maxorder = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}
		$sql = "UPDATE {$table}type SET typename='$typename',listorder='$listorder',keywords='$keywords',description='$description' WHERE typeid = '$typeid'";
		$res = $db->query($sql);

		admin_log("�༭���� $typename �ɹ�");
		$link = "type.php?act=list&module=$module";
		show('�༭����ɹ�', $link);
	break;

	case 'delete':
		$typeid = intval($_REQUEST['id']);
		if(empty($typeid))show('û��ѡ���¼');

		$sql = "SELECT COUNT(*) FROM {$table}help WHERE typeid = '$typeid' ";
		if($db->getOne($sql)>0)show('�÷���������Ϣ���޷�ɾ��');

	    $db->query("DELETE FROM {$table}type WHERE typeid='$typeid'");

		admin_log("ɾ������ $typeid �ɹ�");
		$link = "type.php?act=list";
		show('ɾ������ɹ�', $link);
	break;
}
?>