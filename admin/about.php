<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';


chkadmin('about');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$res = $db->query("SELECT * FROM {$table}about");
		$about = array();
		while($row = $db->fetchRow($res)) {
			$row['postdate'] = date('Y-m-d',$row['postdate']);
			$row['is_show'] = ($row['is_show']=='1') ? '��' : '��';
			$about[] = $row;
		}
	    include tpl('list_about');
	break;

	case 'add':
		$maxorder = $db->getOne("SELECT MAX(aboutorder) FROM {$table}about");
		$maxorder = $maxorder + 1;
		$content = kind_editor('content','');
		$here = "��ӵ�ҳ";
		$action = array('name'=>'��ҳ�б�', 'href'=>'about.php?act=list');
		include tpl('add_about');
	break;

	case 'insert':
		if(empty($_POST['title']))show("�������");
		if(empty($_POST['content']))show("����д��ϸ����");
		
		$title       = htmlspecialchars(trim($_POST['title']));
		$url         = trim($_POST['url']);
		$aboutorder  = intval($_POST['aboutorder']);
		$content     = trim($_POST['content']);
		$keywords    = htmlspecialchars(trim($_POST['keywords']));
		$description = htmlspecialchars(trim($_POST['description']));
		$is_show     = trim($_POST['is_show']);
		$postdate    = time();

		if(empty($aboutorder)) {
			$sql = "SELECT MAX(aboutorder) FROM {$table}about";
			$maxorder  = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}
		$sql = "INSERT INTO {$table}about (title,url,keywords,description,content,postdate,aboutorder,is_show) VALUES ('$title','$url','$keywords','$description','$content','$postdate','$listorder','$is_show')";
		$res = $db->query($sql);

		admin_log("��ӵ�ҳ $title �ɹ�");//��Ӳ�����¼
		$link = 'about.php?act=add';
		show('��ӵ�ҳ�ɹ�', $link);
	break;
	
	case 'edit':
		$id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}about WHERE id = '$id'";
		$about = $db->getRow($sql);
		$content = kind_editor('content',$about['content']);
		include tpl('edit_about');
	break;

	case 'update':
		if(empty($_POST['title']))show("�������");
		if(empty($_POST['content']))show("����д��ϸ����");
		
		$id          = htmlspecialchars(intval($_POST['id']));
		$title       = trim($_POST['title']);
		$url         = trim($_POST['url']);
		$aboutorder  = intval($_POST['aboutorder']);
		$keywords    = htmlspecialchars(trim($_POST['keywords']));
		$description = htmlspecialchars(trim($_POST['description']));
		$content     = trim($_POST['content']);
		$is_show     = trim($_POST['is_show']);

		if(empty($aboutorder)) {
			$sql = "SELECT MAX(aboutorder) FROM {$table}about";
			$maxorder  = $db->getOne($sql);
			$aboutorder = $maxorder + 1;
		}

		$sql = "UPDATE {$table}about SET title='$title',url='$url',keywords='$keywords',description='$description',content='$content',aboutorder='$aboutorder',is_show='$is_show' WHERE id = '$id' ";
		$res = $db->query($sql);

		admin_log("�޸ĵ�ҳ $title �ɹ�");
		$link = 'about.php?act=list';
		show('�޸ĵ�ҳ�ɹ�', $link);
	break;

	case 'batch':
		$id = is_array($_REQUEST['id']) ? join(',', $_REQUEST['id']) : intval($_REQUEST['id']);
		if(empty($id))show('û��ѡ���¼');
		$sql = "delete from {$table}about where id in ($id)";
        $re = $db->query($sql);
		admin_log("ɾ����ҳ $id �ɹ�");
		$link = 'about.php?act=list';
		show('ɾ����ҳ�ɹ�', $link);
	break;

}
?>