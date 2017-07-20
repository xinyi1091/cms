<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';
require BIANMPS_ROOT . "include/editor/editor.php";

chkadmin('article');

$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch($_REQUEST['act'])
{
	case 'list':
		$page = empty($_REQUEST[page])? 1 : intval($_REQUEST['page']);
		$sql = "SELECT COUNT(*) FROM {$table}article order by id desc";
		$count = $db->getOne($sql);
		$pager = get_pager('article.php',array('act'=>'list'),$count,$page,'20');

		$sql = "SELECT a.*,t.typename FROM {$table}article as a left join {$table}type as t on t.typeid=a.typeid ORDER BY id DESC LIMIT $pager[start],$pager[size]";
		$res = $db->query($sql);
		$data = array();
		while($row=$db->fetchRow($res)) {
			$row['addtime']  = date('Y��m��d��', $row['addtime']);
			$row['is_index'] = $row['is_index']=='1'?'��':'��';
			$row['is_pro']   = $row['is_pro']=='1'?'��':'��';
			$data[] = $row;
		}
		$here = "�����б�";
		$action = array('name'=>'�������', 'href'=>'article.php?act=add');
	    include tpl('list_article','article');
	break;

	case 'add':
		$maxorder = $db->getOne("SELECT MAX(listorder) FROM {$table}article");
		$maxorder = $maxorder + 1;
		
		$type_select = type_select('article');

		$content = kind_editor('content','');
		$here = "��ӵ�ҳ";
		$action = array('name'=>'��ҳ�б�', 'href'=>'article.php?act=list');
		include tpl('add_article','article');
	break;

	case 'insert':
		if(empty($_POST['title']))show("�������");
		if(empty($_POST['typeid']))show("�������");
		if(empty($_POST['content']))show("����д��ϸ����");
		
		$title       = trim($_POST['title']);
		$typeid      = intval($_POST['typeid']);
		$content     = trim($_POST['content']);
		$keywords    = trim($_POST['keyword']);
		$description = trim($_POST['description']);
		$listorder   = intval($_POST['listorder']);
		$is_index    = intval($_POST['is_index']);
		$is_pro    = intval($_POST['is_pro']);
		$addtime     = time();

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}article";
			$maxorder  = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}

		$sql = "INSERT INTO {$table}article (title,typeid,keywords,description,content,listorder,addtime,is_index,is_pro) VALUES ('$title','$typeid','$keywords','$description','$content','$listorder','$addtime','$is_index','$is_pro')";
		$res = $db->query($sql);

		admin_log("������� $title �ɹ�");
		$link = 'article.php?act=list';
		show('������ųɹ�', $link);
	break;
	
	case 'edit':
		$id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}article WHERE id = '$id'";
		$article = $db->getRow($sql);
		$type_select = type_select('article',$article['typeid']);
        $content = kind_editor('content',$article['content']);
		include tpl('edit_article','article');
	break;

	case 'update':
		if(empty($_POST['title']))show("�������");
		if(empty($_POST['content']))show("����д��ϸ����");
		
		$id          = intval($_POST['id']);
		$typeid      = intval($_POST['typeid']);
		$title       = trim($_POST['title']);
		$content     = trim($_POST['content']);
		$keywords    = trim($_POST['keyword']);
		$description = trim($_POST['description']);
		$listorder   = intval($_POST['listorder']);
		$is_index    = intval($_POST['is_index']);
		$is_pro    = intval($_POST['is_pro']);

		if(empty($listorder)) {
			$sql = "SELECT MAX(listorder) FROM {$table}article";
			$maxorder  = $db->getOne($sql);
			$listorder = $maxorder + 1;
		}

		$sql = "UPDATE {$table}article SET title='$title',typeid='$typeid',keywords='$keywords',description='$description',content='$content',listorder='$listorder',is_index='$is_index',is_pro='$is_pro' WHERE id='$id' ";
		$res = $db->query($sql);

		admin_log("�޸����� $title �ɹ�");
		$link = 'article.php?act=list';
		show('�޸����ųɹ�', $link);
	break;

	case 'batch':
		$id = !empty($_POST['id']) ? join(',', $_POST['id']) : 0;
		if(empty($id))show('û��ѡ���¼');
		$sql = "DELETE FROM {$table}article WHERE id IN ($id)";
        $re = $db->query($sql);
		admin_log("ɾ������ $id �ɹ�");
		$link = 'article.php?act=list';
		show('ɾ�����ųɹ�', $link);
	break;
}
?>