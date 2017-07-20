<?php
define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.inc.php';
    $catid = $_REQUEST['catid'] ? intval($_REQUEST['catid']) : '';
	$areaid = $_REQUEST['area'] ? intval($_REQUEST['area']) : '';
	$page = $_REQUEST['page'] ? intval($_REQUEST['page']) : '1';
   $com_cats = get_com_cat_list();/* 企业分类列表 */
	if($catid) {
		$com_cat_info = get_com_cat_info($catid);

		if(empty($com_cat_info['parentid'])) {
			$cats = get_com_cat_children($catid);
			if(empty($cats))$cats=$catid;
		} else {
			$cats = $catid;
		}
		$cat_sql = " and catid in ($cats) ";
	}
	if(!$areaid) {
		$area_row = get_parent_area();
		if(!empty($area_row)) {
			$area_arr = array();
			foreach($area_row as $val) {
				$val['areaname'] = $val['areaname'];
				$val['url'] = url_rewrite('com', array('act'=>'list', 'catid'=>$catid,'eid'=>$val['areaid']));
				$area_arr[] = $val;
			}
		}
	} else {
		$area_info = get_area_info($areaid);
		$area_parent = $area_info['parentid'];

		if(empty($area_parent)) {
			$area_row = get_area_children($areaid,'array');
			if(!empty($area_row)) {
				$area_arr = array();
				foreach($area_row as $val) {
					$val['areaname'] = $val['name'];
					$val['url'] = url_rewrite('com',array('act'=>'list', 'catid'=>$catid,'eid'=>$val['id']));
					$area_arr[] = $val;
				}
				$areas = get_cat_children($areaid);
			}
			if(empty($areas))$areas = $areaid;
		} else {
			$areas = $areaid;
		}
		$area_sql = " and areaid in ($areas) ";
	}
	$area_array = get_area_array();
	$cat_array = get_com_cat_array();
	$sql = "SELECT COUNT(*) FROM {$table}com as i WHERE is_check=1 $cat_sql $area_sql";
	$count = $db->getOne($sql);

$size = '20';
$pager['search'] = array('id'=>$catid);
$pager = get_pager('com.php',$pager['search'],$count,$page,$size);
	
	$sql = "SELECT * FROM {$table}com WHERE is_check=1 $cat_sql $area_sql ORDER BY postdate DESC limit $pager[start],$pager[size]";
	$res = $db->query($sql);
	$articles = array();
	while($row=$db->fetchRow($res)) {
		$row['sname']      = cut_str($row['comname'],50);
		$row['postdate']   = date('m月d日', $row['postdate']);
		$row['thumb']      = PHPMPS_PATH.$row['thumb'];
		$row['introduce']  = cut_str($row['introduce'],70);
		$row['areaname2']   = $area_array[$row['areaid']];
		$row['catname2']    = $cat_array[$row['catid']];
		$row['address']    = $row['address'];
		$row['url']        = url_rewrite('com',array('act'=>'view','comid'=>$row['comid']));
		$articles[] = $row;
	}

	if(empty($com_cat_info) && empty($area_info)) {
		$here_arr[] = array('name'=> '商家黄页');
	} elseif(empty($com_cat_info) && !empty($area_info)) {
		$here_arr[] = array('name'=> '商家黄页','url'=>url_rewrite('com', array('act'=>'list', 'catid'=>$catid)));
	} else {
		$here_arr[] = array('name'=>$com_cat_info['catname'],'url'=>url_rewrite('com', array('act'=>'list', 'catid'=>$catid)));
	}

	$here_arr[] = array('name'=>$area_info['areaname'],'url'=>url_rewrite('com', array('act'=>'list', 'eid'=>$areaid)));
	$here = get_here($here_arr);

$cats44  = get_cat_list();//所有分类
$seo['title'] = $CFG['webname'].'手机版';
include tpl('com_list');
?>