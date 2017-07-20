<?php

define('IN_BIANMPS', true);
require_once dirname(__FILE__) . '/include/common.php';

chkadmin('ads');

//��ʼ��act����
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list' ;

$type = array('1'=>'���ֹ��','ͼƬ���','flash���','������');

$sql = "SELECT * FROM {$table}ads_place";
$ads_place = $db->getAll($sql);

switch ($_REQUEST['act'])
{
	case 'list':
		$res = $db->query("SELECT a.*,p.placename FROM {$table}ads AS a LEFT JOIN {$table}ads_place AS p ON p.placeid = a.placeid ");
		$ads = array();
		while($row = $db->fetchRow($res)) {
			$row['adstype']  = $type[$row['adstype']];
			$row['addtime']  = date('Y-m-d',$row['addtime']);
			$ads[] = $row;
		}
		//$here = "����б�";
		$action = array('name'=>'��ӹ��', 'href'=>'ads.php?act=add');
	    include tpl('list_ads');
	break;

	case 'add':
		$here = "��ӹ��";
		$action = array('name'=>'����б�', 'href'=>'ads.php?act=list');
		include tpl('add_ads');
	break;

	case 'insert':
		if(empty($_POST['placeid']))show('��ѡ����λ');
		if(empty($_POST['adstype']))show('��ѡ��������');
		if(empty($_POST['adsname']))show('����д�������');

		$placeid = intval($_POST['placeid']);
		$adstype = intval($_POST['adstype']);
		$adsname = trim($_POST['adsname']);

		if($adstype != '3' || $adstype!='4')$adsurl = !empty($_POST['adsurl']) ? trim($_POST['adsurl']) : '';

		/* �鿴��������Ƿ����ظ� */
		$sql = "SELECT COUNT(*) FROM {$table}ads WHERE adsname = '$adsname'";
		if( $db->getOne($sql))show('�Դ��ڴ˹������');

		if($adstype == '1')//�������Ϊ�ı����
		{
			if (!empty($_POST['adscontent']))
			{
				$adscode = trim($_POST['adscontent']);
			}
			else
			{
				show('�ı�Ϊ��');
			}
		}
		elseif($adstype == '2')/* ���ͼƬ���͵Ĺ�� */
		{
			if((isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) || (!isset($_FILES['image']['error']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != 'none'))
			{
				$name = date('ymdhis');
				for($i = 0;$i < 6;$i++) 
				{
					$name .= chr(mt_rand(97, 122));
				}
				$name .= '.' . end(explode('.', $_FILES['image']['name']));
				$to = BIANMPS_ROOT . 'data/ads/' . $name;
				if(move_uploaded_file($_FILES['image']['tmp_name'], $to))
				{
					$adscode = "data/ads/" . $name;
				}
				else
				{
					show('ͼƬ�ϴ�ʧ��');
				}
			}
			if(!empty($_POST['imageurl']))
			{
				$adscode = $_POST['imageurl'];
			}
			if((isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] == 'none') && empty($_POST['imageurl']))
			{
				show('ͼƬ��ͼƬ��ַΪ��');
			}
		}
		elseif ($adstype == '3')/* �����ӵĹ����Flash��� */
		{
			if ((isset($_FILES['flash']['error']) && $_FILES['flash']['error'] == 0) || (!isset($_FILES['flash']['error']) && isset($_FILES['image']['tmp_name']) && $_FILES['flash']['tmp_name'] != 'none'))
			{
				/* ����ļ����� */
				if ($_FILES['flash']['type'] != "application/x-shockwave-flash")
				{
					show('�ϴ�flash���ʹ���');
				}

				/* �����ļ��� */
				$urlstr = date('ymdhis');
				for($i = 0; $i < 6; $i++)
				{
					$urlstr .= chr(mt_rand(97, 122));
				}

				$source_file = $_FILES['flash']['tmp_name'];
				$target = BIANMPS_ROOT . 'data/ads/';
				$file_name = $urlstr .'.swf';

				if(move_uploaded_file($source_file, $target.$file_name))
				{
					$adscode = "data/ads/" . $file_name;
				}
				else
				{
					show('�ϴ�flashʧ��');
				}
			}
			elseif(!empty($_POST['flashurl']))
			{
				if (substr(strtolower($_POST['flashurl']), strlen($_POST['flashurl']) - 4) != '.swf')
				{
					show('����flash���ʹ���');
				}
				$adscode = trim($_POST['flashurl']);
			}

			if (((isset($_FILES['flash']['error']) && $_FILES['flash']['error'] > 0) || (!isset($_FILES['flash']['error']) && isset($_FILES['flash']['tmp_name']) && $_FILES['flash']['tmp_name'] == 'none')) && empty($_POST['flashurl']))
			{
				show('�ϴ�flash��flash��ַΪ��');
			}
		}
		elseif ($adstype== '4')/* ����������Ϊ������ */
		{
			if (!empty($_POST['adscode']))
			{
				$adscode = $_POST['adscode'];
			}
			else
			{
				show('����Ϊ��');
			}
		}
		
		$addtime = time();
		/* �������� */
		$sql = "INSERT INTO {$table}ads (placeid,adstype,adsname,adsurl,adscode,addtime,linkman)
		VALUES ('$placeid','$adstype','$adsname','$adsurl','$adscode','$addtime','$_POST[postman]')";
		$db->query($sql);

		admin_log("��ӹ�� $adsname �ɹ�");//��Ӳ�����¼
		$link = 'ads.php?act=add';
		show('��ӹ��ɹ�', $link);
	break;
	
	case 'edit':
		$id = intval($_REQUEST['id']);
		$sql = "SELECT * FROM {$table}ads WHERE adsid = '$id'"; 
		$ads = $db->getRow($sql);
		include tpl('edit_ads');
	break;

	case 'update':
		if(empty($_POST['placeid']))show('��ѡ����λ');
		if(empty($_POST['adsname']))show('����д�������');
		
		$adsid   = intval($_REQUEST['adsid']);
		$placeid = intval($_REQUEST['placeid']);
		$adstype = intval($_REQUEST['adstype']);
		$adsname = trim($_REQUEST['adsname']);

		if($adstype != '4')$adsurl = !empty($_POST['adsurl']) ? trim($_POST['adsurl']) : '';
		
		$sql = "select adscode from {$table}ads where adsid='$adsid' ";
		$adscode = $db->getOne($sql);

		if($adstype == '1')//�������Ϊ�ı����
		{
			if (!empty($_POST['adscontent']))
			{
				$adscode = trim($_POST['adscontent']);
			}
			else
			{
				show('�ı�Ϊ��');
			}
		}
		elseif($adstype == '2')/* ���ͼƬ���͵Ĺ�� */
		{
			if((isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) || (!isset($_FILES['image']['error']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != 'none'))
			{
				$name = date('ymdhis');
				for($i = 0;$i < 6;$i++) 
				{
					$name .= chr(mt_rand(97, 122));
				}
				$name .= '.' . end(explode('.', $_FILES['image']['name']));
				$to = BIANMPS_ROOT . 'data/ads/' . $name;
				if(move_uploaded_file($_FILES['image']['tmp_name'], $to))
				{
					if((strpos($adscode, 'http://') === false) && (strpos($adscode, 'https://') === false))
					{
						$img_name = basename($img);
						@unlink(BIANMPS_ROOT.'data/ads/'.$img_name);
					}
					$adscode = "data/ads/" . $name;
				}
				else
				{
					show('ͼƬ�ϴ�ʧ��');
				}
			}
			if(!empty($_POST['imageurl']))
			{
				$adscode = $_POST['imageurl'];
			}
			if((isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] == 'none') && empty($_POST['imageurl']))
			{
				show('ͼƬ��ͼƬ��ַΪ��');
			}
		}
		elseif ($adstype == '3')/* �����ӵĹ����Flash��� */
		{
			if ((isset($_FILES['flash']['error']) && $_FILES['flash']['error'] == 0) || (!isset($_FILES['flash']['error']) && isset($_FILES['image']['tmp_name']) && $_FILES['flash']['tmp_name'] != 'none'))
			{
				/* ����ļ����� */
				if ($_FILES['flash']['type'] != "application/x-shockwave-flash")
				{
					show('�ϴ�flash���ʹ���');
				}

				/* �����ļ��� */
				$urlstr = date('ymdhis');
				for($i = 0; $i < 6; $i++)
				{
					$urlstr .= chr(mt_rand(97, 122));
				}

				$source_file = $_FILES['flash']['tmp_name'];
				$target = BIANMPS_ROOT . 'data/ads/';
				$file_name = $urlstr .'.swf';

				if(move_uploaded_file($source_file, $target.$file_name))
				{
					if((strpos($adscode, 'http://') === false) && (strpos($adscode, 'https://') === false))
					{
						$img_name = basename($img);
						@unlink(BIANMPS_ROOT.'data/ads/'.$img_name);
					}
					$adscode = $file_name;
				}
				else
				{
					show('�ϴ�flashʧ��');
				}
			}
			elseif(!empty($_POST['flashurl']))
			{
				if (substr(strtolower($_POST['flashurl']), strlen($_POST['flashurl']) - 4) != '.swf')
				{
					show('����flash���ʹ���');
				}
				$adscode = trim($_POST['flashurl']);
			}

			if (((isset($_FILES['flash']['error']) && $_FILES['flash']['error'] > 0) || (!isset($_FILES['flash']['error']) && isset($_FILES['flash']['tmp_name']) && $_FILES['flash']['tmp_name'] == 'none')) && empty($_POST['flashurl']))
			{
				show('�ϴ�flash��flash��ַΪ��');
			}
		}
		elseif ($adstype== '4')/* ����������Ϊ������ */
		{
			if (!empty($_POST['adscode']))
			{
				$adscode = $_POST['adscode'];
			}
			else
			{
				show('����Ϊ��');
			}
		}
		$updatetime = time();
		
		$sql = "UPDATE {$table}ads SET placeid = '$placeid', adsname = '$adsname', adsurl = '$adsurl',adscode='$adscode',updatetime='$updatetime',linkman='$_POST[linkman]' WHERE adsid = '$adsid'";
		$db->query($sql);
		
		admin_log("�޸Ĺ�� $adsname �ɹ�");
		$link = 'ads.php?act=list';
		show('�޸Ĺ��ɹ�', $link);
	break;

	case 'batch':
		$id = is_array($_REQUEST['id']) ? join(',',$_REQUEST['id']) : intval($_REQUEST['id']);
		if(empty($id))show('û��ѡ���¼');
		$sql = "SELECT * FROM {$table}ads WHERE adsid in ($id)";
		$ads = $db->getAll($sql);
		
		foreach((array)$ads as $ad) {
			if($ads[adstype]==2 && $ads['adscode']!='' && is_file(BIANMPS_ROOT.'/data/ads/'.$ads['adscode'])) {
				@unlink('../'.$ads['adscode']);
			}
		}
		$sql = "DELETE FROM {$table}ads WHERE adsid in ($id)";
	    $res = $db->query($sql);

		admin_log("ɾ����� $id �ɹ�");
		$link = 'ads.php?act=list';
		show('ɾ�����ɹ�', $link);
	break;
}
?>