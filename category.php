<?php

/*
	$s_cat    : �����÷���������
	$cats     : �����ӷ�����
	$cat_arr  : ���ർ��
	$s_area   : �����÷���������
	$areas    : �����ӷ�����
	$area_arr : ���ർ��
	$top_type : �ö�����
	$here_arr : ���м����
	$top_info : �ö���Ϣ
	$articles : �б������Ϣ
	$cat_custom : ���฽������
*/

define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.php';

$catid = $_REQUEST['id'] ? intval($_REQUEST['id']) : '';
$areaid = $_REQUEST['area'] ? intval($_REQUEST['area']) : '';

if(empty($catid) && empty($areaid)) {
	header("Location: ./");
	exit;
}

if($catid) {
	$cat_info = get_cat_info($catid);
	if(empty($cat_info)) showmsg('�����ڴ˷���', 'index.php');
	$here_arr[] = array('name'=>$cat_info['catname']);
	$cat_parent = $cat_info['parentid'];

	if(empty($cat_parent)) {
		//�鿴��û�и����࣬û�в�����û���ӷ���
		$cat_row = get_cat_children($catid, 'array');
		//������ӷ���
		if(!empty($cat_row)) {
			//��������
			$s_cat .= '<select name="id" id="id"><option value="0">��ѡ��</option>';
			foreach($cat_row as $cat) {
				$s_cat .= "<option value=$cat[id]>$cat[name]</option>";
			}
			$s_cat .= '</select>';
			//ȡ�������ӷ���ID���ö������ӣ�����ȡ����Ϣ
			$cats = get_cat_children($catid);
			if($cats) $cats .= ','.$catid;

			/* ���ɵ��� */
			$cat_arr = array();
			foreach($cat_row as $val) {
				$val['catname'] = $val['name'];
				$val['url'] = url_rewrite('category', array('cid'=>$val['id'],'eid'=>$areaid));
				$cat_arr[] = $val;
			}
		} else {
			//���û�У���ֻ����һ�����࣬����ѡ��
			$s_cat .= '<select name="id" id="id" disabled>';
			$s_cat .= "<option value=$catid selected>".$cat_info['catname']."</option>";
			$s_cat .= '</select>';
			$cats = $catid;
		}
		$top_type = '1';//�ö�����Ϊ������ö�
		
	} else {
		//����и����࣬ȡ�������е��ӷ���
		$cat_row = get_cat_children($cat_parent, 'array');
		/* ���ɵ��� */
		if(!empty($cat_row))
		{
			foreach($cat_row as $val) {
				$val['catnid'] = $val['id'];
				$val['catname'] = $val['name'];
				$val['url'] = url_rewrite('category',array('cid'=>$val['id'],'eid'=>$areaid));
				$cat_arr[] = $val;
			}
			/* �������� */
			$s_cat .= '<select name="id" id="id" disabled>';
			foreach($cat_row as $cat) {
				$select = $cat['id'] == $catid ? 'selected' : '';
				$s_cat .= "<option value='$cat[id]' $select>$cat[name]</option>";
			}
			$s_cat .= '</select>';
		}
		$top_type = '2'; //�ö�����ΪС�����ö�
		$cats = $catid;
	}
	$cat_sql = " and catid in ($cats) ";
	$cat_custom = cat_search_custom($catid);
	$top_info = get_top_info($cats, $top_type);
	if(!empty($top_info)) {
		foreach((array)$top_info as $val) {
			$ids[] = $val['id'];
		}
		$top_info_ids = join(',', $ids);
		$top_info_sql = " and id not in ($top_info_ids)";
	}
} else {
	//�����ڷ��࣬��ȡ�����д����
	$cat_row = get_parent_cat();
	//���ɵ���
	if(!empty($cat_row)) {
		foreach($cat_row as $val) {
			$val['url'] = url_rewrite('category', array('cid'=>$val['catid'],'eid'=>$areaid));
			$cat_arr[] = $val;
		}
	}
	//��������
	$s_cat = '<select name="id" id="id"><option value="0">��ѡ��</option>';
	foreach($cat_row as $cat) {
		$s_cat .= "<option value=$cat[catid]>$cat[catname]</option>";
	}
	$s_cat .= '</select>';
}

if($areaid) {
	$area_info = get_area_info($areaid);
	if(empty($area_info)) showmsg('�����ڴ˵���');
	$here_arr[] = array('name'=>$area_info['areaname']);
	$area_parent = $area_info['parentid'];
	if(empty($area_parent)) {
		//�����
		$area_row = get_area_children($areaid,'array');
		if($area_row) {
			//������
			$area_arr = array();
			foreach($area_row as $val) {
				$val['areaname'] = $val['name'];
				$val['url'] = url_rewrite('category',array('cid'=>$catid,'eid'=>$val['id']));
				$area_arr[] = $val;
			}
			//������
			$s_area .= '<select name="area" id="area"><option value="0">��ѡ��</option>';
			foreach($area_row as $cat) {
				$s_area .= "<option value=$cat[id]>$cat[name]</option>";
			}
			$s_area .= '</select>';
		} else {
			$s_area .= '<select name="area" id="area" disabled>';
			$s_area .= "<option value=$areaid selected>".$area_info['areaname']."</option>";
			$s_area .= '</select>';
		}
		$areas = get_area_children($areaid);
		if(!empty($areas)) $areas .= ','.$areaid;
	} else {
		//����и����࣬ȡ�������е��ӷ���
		$area_row = get_area_children($area_parent, 'array');
		/* ���ɵ��� */
		foreach($area_row as $val) {
			$val['areaid'] = $val['id'];
			$val['areaname'] = $val['name'];
			$val['url'] = url_rewrite('category',array('eid'=>$val['id'],'cid'=>$catid));
			$area_arr[] = $val;
		}
		/* �������� */
		$s_area .= '<select name="area" id="area">';
		foreach($area_row as $cat) {
			$select = $cat['id'] == $areaid ? 'selected' : '';
			$s_area .= "<option value='$cat[id]' $select>$cat[name]</option>";
		}
		$s_area .= '</select>';
		$areas = $areaid;
	}
	$area_sql = " and areaid in ($areas) ";
} else {
	//û�е�����ȡ���и�����
	$area_row = get_parent_area();
	if($area_row) {
		$area_arr = array();
		foreach($area_row as $val) {
			$val['areaname'] = $val['areaname'];
			$val['url'] = url_rewrite('category', array('cid'=>$catid, 'eid'=>$val['areaid']));
			$area_arr[] = $val;
		}

		$s_area .= '<select name="area" id="area"><option value="0">��ѡ��</option>';
		foreach($area_row as $area) {
			$s_area .= "<option value=$area[areaid]>$area[areaname]</option>";
		}
		$s_area .= '</select>';
	}
}

$area_array = get_area_array();
$cat_array = get_cat_array();
$page = empty($_REQUEST['page']) ? '1' : intval($_REQUEST['page']);
$sql = "SELECT COUNT(*) FROM {$table}info as i WHERE is_check=1 $cat_sql $area_sql";
$count = $db->getOne($sql);
$size = '10';
$pager = page('category', $catid, $areaid, $count, $size, $page);
$sql = "SELECT id,title,postdate,enddate,catid,areaid,thumb,description,click FROM {$table}info WHERE is_check=1 $cat_sql $area_sql $top_info_sql ORDER BY postdate DESC limit $pager[start], $pager[size]";
$res = $db->query($sql);
$info = array();
while($row=$db->fetchRow($res)) {
	$row['url']      = url_rewrite('view',array('vid'=>$row['id']));
	$row['postdate'] = date('y��m��d��', $row['postdate']);
	$row['lastdate'] = enddate($row['enddate']);
	$row['intro']    = cut_str($row['description'], 83);
	$row['areaname'] = $area_array[$row['areaid']];
	$row['catname']  = $cat_array[$row['catid']];
	$info[$row['id']] = $row;
}

if($info) {
	foreach($info as $val) {
		$infoid .= $val['id'].',';
	}
	$infoid = substr($infoid,0,-1);
	$info_custom = get_infos_custom($infoid);
	foreach($info as $key=>$val) {
		$info[$key]['custom'] = is_array($info_custom[$key]) ? $info_custom[$key] : array();
	}
}

$cat_pro = get_info($cats, $areas, '8', 'pro','','10'); //�Ƽ���Ϣ
$cat_hot = get_info($cats, $areas, '8', '','click','10'); //������Ϣ


$here = get_here($here_arr);
$seo['title'] = $area_info['areaname'] . $cat_info['catname'] .'��'. '��Ϣ�б�'.' - '.$CFG['webname'];
$seo['keywords'] = $area_info['areaname'].$cat_info['keywords'];
$seo['description'] = $cat_info['description'];

$template = $cat_info['cattplname'] ? $cat_info['cattplname'] : 'category';
include template($template);
?>