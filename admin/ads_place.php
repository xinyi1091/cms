<?php

define('IN_PHPMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('ads_place');

//��ʼ��act����
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$sql = "SELECT * FROM {$table}ads_place";
		$place = $db->getAll($sql);
		$here = "���λ�б�";
		$action = array('name'=>'��ӹ��λ', 'href'=>'ads_place.php?act=add');
	    include tpl('list_ads_place');
	break;

	case 'add':
		$action = array('name'=>'���λ�б�', 'href'=>'ads_place.php?act=list');
		include tpl('add_ads_place');
	break;

	case 'insert':
		if(empty($_POST['placename']))show("������λ����");
		if(empty($_POST['width']))show("����д���");
		if(empty($_POST['height']))show("����д�߶�");
		
		$placename = trim($_POST['placename']);
		$width     = intval($_POST['width']);
		$height    = intval($_POST['height']);
		$introduce = trim($_POST['introduce']);

		$sql = "INSERT INTO {$table}ads_place (placename,width,height,introduce) VALUES ('$placename','$width','$height','$introduce')";
		$res = $db->query($sql);

		admin_log("��ӹ��λ $title �ɹ�");
		$link = 'ads_place.php?act=add';
		show('��ӹ��λ�ɹ�', $link);
	break;
	
	case 'edit':
		$id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}ads_place WHERE placeid = '$id'"; 
		$place = $db->getRow($sql);
		include tpl('edit_ads_place');
	break;

	case 'update':
		if(empty($_POST['placename']))show("������λ����");
		if(empty($_POST['width']))show("����д���");
		if(empty($_POST['height']))show("����д�߶�");
		
		$id        = intval($_POST['id']);
		$placename = trim($_POST['placename']);
		$width     = trim($_POST['width']);
		$height    = intval($_POST['height']);
		$introduce = trim($_POST['introduce']);

		$sql = "UPDATE {$table}ads_place SET 
		placename='$placename',
		width='$width',
		height='$height',
		introduce='$introduce' 
		WHERE placeid = '$id' ";
		$res = $db->query($sql);

		admin_log("�޸Ĺ��λ $placename �ɹ�");
		$link = 'ads_place.php?act=list';
		show('�޸Ĺ��λ�ɹ�', $link);
	break;

	case 'delete':
		$id = intval($_REQUEST['id']);
		if(empty($id))show('û��ѡ���¼');

		//��֤�Ƿ��й��
		$sql = "select count(*) from {$table}ads where placeid='$id'";
		$count = $db->getOne($sql);
		if($count>0)show('�˹��λ���й�棬����ɾ��');

		$sql = "DELETE FROM {$table}ads_place WHERE placeid='$id'";
	    $res = $db->query($sql);
		admin_log("ɾ�����λ $id �ɹ�");
		$link = 'ads_place.php?act=list';
		show('ɾ�����λ�ɹ�', $link);
	break;
}
?>