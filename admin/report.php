<?php

define('IN_PHPMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('report');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$report = array('1'=>'�Ƿ���Ϣ','2'=>'�������','3'=>'�н���Ϣ','4'=>'��Ϣ��ʧЧ');

		$page = empty($_REQUEST[page])? 1 : intval($_REQUEST['page']);
		$count = $db->getOne("SELECT COUNT(*) FROM {$table}report");
		$pager = get_pager('report.php',array('act'=>'list'),$count,$page,'20');
		
		$sql = "SELECT * FROM {$table}report ORDER BY id DESC LIMIT $pager[start],$pager[size]";
		$res = $db->query($sql);
		$reports = array();
		while($row=$db->fetchRow($res)) {
			$row['postdate'] = date('Y-m-d', $row['postdate']);
			$row['type']     = $report[$row['type']];
			$reports[]       = $row;
		}
	
		$here = "�ٱ������б�";
		$action = array('name'=>'', 'href'=>'');
	    include tpl('list_report');
	break;

	case 'delete':
		$id = intval($_REQUEST['id']);
		if(empty($id))show('û��ѡ���¼');
	    $res = $db->query("DELETE FROM {$table}report WHERE id='$id'");
		admin_log("ɾ���ٱ� $id �ɹ�");
		show('ɾ���ɹ�', 'report.php?act=list');
	break;

	case 'batch':
		$id = !empty($_POST['id']) ? join(',', $_POST['id']) : 0;
		if(empty($id))show('û��ѡ���¼');
        $re = $db->query("DELETE FROM {$table}report WHERE id IN ($id)");
		admin_log("ɾ���ٱ� $id �ɹ�");
		show('ɾ���ɹ�', 'report.php?act=list');
	break;

}
?>