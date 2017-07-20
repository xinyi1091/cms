<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('category');

//��ʼ��act����
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

switch ($_REQUEST['act'])
{
	case 'list':
		$cat = get_cat_list();
		$here = "�����б�";
		$action = array('name'=>'��ӷ���', 'href'=>'category.php?act=add');
	    include tpl('list_category');
	break;

	case 'add':
        $cat = get_cat_list();
		$cats = $db->getAll("SELECT * from {$table}category WHERE parentid=0");
		$maxorder = $db->getOne("SELECT MAX(catorder) FROM {$table}category");
		$maxorder = $maxorder + 1;
		$cattplname = showtpl('category','cattplname');
		$viewtplname = showtpl('view','viewtplname');
		$here = "��ӷ���";
		$action = array('name'=>'�����б�', 'href'=>'category.php?act=list');
	    include tpl('add_category');
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
		$cattplname  = $_REQUEST['cattplname'];
		$viewtplname = $_REQUEST['viewtplname'];
		//added by bian 2017-07-12
		$needPay = intval($_REQUEST['needPay']);
		if(empty($catorder)) {
			$sql = "SELECT MAX(catorder) FROM {$table}category";
			$maxorder = $db->getOne($sql);
			$catorder = $maxorder + 1;
		}
		$sql = "INSERT INTO {$table}category (catname,keywords,description,parentid,catorder,cattplname,viewtplname,needPay) VALUES ('$catname','$keywords','$description','$parentid','$catorder','$cattplname','$viewtplname','$needPay')";
		$res = $db->query($sql);

		clear_caches('phpcache');
		admin_log("������� $cataname �ɹ�");
		show('��ӷ���ɹ�','category.php?act=add');
	break;

	case 'edit':
	    $catid = intval($_REQUEST['catid']);
		$sql = "SELECT * FROM {$table}category WHERE catid = '$catid'";
		$cat = $db->getRow($sql);
		$cattplname = showtpl('category','cattplname', $cat['cattplname']);
		$viewtplname = showtpl('view','viewtplname', $cat['viewtplname']);
		$sql  = "SELECT * FROM {$table}category WHERE parentid = '0'";
	    $cats = $db->getAll($sql);
		include tpl('edit_category');
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
		$cattplname  = $_REQUEST['cattplname'];
		$viewtplname = $_REQUEST['viewtplname'];
		//added by bian 2017-07-12
		$needPay = intval($_REQUEST['needPay']);

		$sql = "UPDATE {$table}category SET catname='$catname',keywords='$keywords',description='$description',parentid='$parentid',catorder='$catorder',cattplname='$cattplname',viewtplname='$viewtplname',needPay='$needPay' WHERE catid = '$catid'";
		$res = $db->query($sql);
		
		clear_caches('phpcache');
		admin_log("�༭���� $catname �ɹ�");
		$link = "category.php?act=list";
		show('�༭����ɹ�', $link);
	break;

	case 'delete':
		$catid = intval($_REQUEST['catid']);
		if(empty($catid))show('û��ѡ���¼');
		
		$sql = "SELECT COUNT(*) FROM {$table}category WHERE parentid = '$catid' ";
		if($db->getOne($sql)>0)show('�÷������з��࣬�޷�ɾ��');
		
		$sql = "SELECT COUNT(*) FROM {$table}info WHERE catid = '$catid' ";
		if($db->getOne($sql)>0)show('�÷���������Ϣ���޷�ɾ��');

		$sql = "DELETE FROM {$table}category WHERE catid='$catid'";
	    $db->query($sql);

		$sql = "select cusid from {$table}custom where catid='$catid' ";
		$cusids = $db->getRow($sql);
		$cusids = is_array($cusids) ? join(',',$cusids) : '';
		if($cusids) {
			$db->query("delete from {$table}cus_value where cusid in ($cusids)");
			$db->query("delete from {$table}custom where catid=$catid");
		}
		clear_caches('phpcache');
		admin_log("ɾ������ $catid �ɹ�");
		$link = 'category.php?act=list';
		show('ɾ������ɹ�', $link);
	break;
}


function showtpl($type = 'category', $name = 'tplname', $templateid = 0)
{
	global $CFG;

    $templatedir = BIANMPS_ROOT."/templates/".$CFG['tplname']."/";
    $content = "";
	$files = glob($templatedir."/*.htm");

	foreach($files as $tplfile) {
		$tplfile = basename($tplfile);
		$tpl = str_replace(".htm","",$tplfile);
		if($type==$tpl || preg_match("/^".$type."-(.*)/i",$tpl)) {
			$selected = ($templateid && $tpl==$templateid) ? 'selected' : '';
            $templatename = $tpl;
			$content .= "<option value='".$tpl."' ".$selected.">".$templatename."</option>\n";
		}
	}
	$content = "<select name='".$name."' ".$property."><option value='0'>Ĭ��ģ��</option>\n".$content."</select>";
	return $content;
}
?>