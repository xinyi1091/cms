<?php

/*
	$s_cat    : 搜索用分类下拉框
	$cats     : 所有子分类编号
	$cat_arr  : 分类导航
	$s_area   : 搜索用分类下拉框
	$areas    : 所有子分类编号
	$area_arr : 分类导航
	$top_type : 置顶类型
	$here_arr : 面包屑导航
	$top_info : 置顶信息
	$articles : 列表分类信息
	$cat_custom : 分类附加属性
*/

define('IN_BIANMPS', true);
require dirname(__FILE__) . '/include/common.php';
//处理扣除信息币信息 added by bian
require BIANMPS_ROOT . 'include/pay.fun.php';
$act = $_REQUEST['act'] ? trim($_REQUEST['act']) : '';
if($act == "gold_diff"){
    $memberUserId = !empty($_REQUEST[userid])?intval($_REQUEST[userid]):0;
    $memberPostToken = !empty($_REQUEST[token])?trim($_REQUEST[token]):'';
    $memberInfoId = !empty($_REQUEST[infoid])?intval($_REQUEST[infoid]):0;
    if(empty($memberUserId) || empty($memberPostToken) || empty($memberInfoId)){
        $data = array(
            'error'=>1,
            'content'=>'The request parameters are incomplete',
        );
    }else{
        $sql = "select username,password from {$table}member where userid = $memberUserId";
        $res = $db->getRow($sql);
        if(!$res){
            $data = array(
                'error'=>2,
                'content'=>'You have a wrong userid',
            );
        }else{
            $memberUserName = $res['username'];
            $memberPass = $res['password'];
        }
        //计算token
        $memberCalculatedToken = md5(substr($memberPass, 1,10).$memberUserId);
        if($memberPostToken !== $memberCalculatedToken){
            $data = array(
                'error'=>3,
                'content'=>'Illegal request',
            );
        }
        $sql = "select id from {$table}info where id = $memberInfoId";
        $resInfoId = $db->getOne($sql);
        if(!$resInfoId){
            $data = array(
                'error'=>4,
                'content'=>'You have a wrong infoid',
            );
        }else{
            if(gold_diff($memberUserName,$CFG['info_gold_diff'], 'paymentInformation','',$resInfoId)){
                $data = array(
                    'error'=>200,
                    'content'=>'The success of the information coin is deducted ',//json_encode 只支持utf-8编码，所以content会输出null，可以用iconv处理下
                );
            }else{
                $data = array(
                    'error'=>201,
                    'content'=>'An error occurred. Please try again later ',
                );
            }
        }
    }
    die(json_encode($data));
}
$catid = $_REQUEST['id'] ? intval($_REQUEST['id']) : '';
$areaid = $_REQUEST['area'] ? intval($_REQUEST['area']) : '';

if(empty($catid) && empty($areaid)) {
	header("Location: ./");
	exit;
}

if($catid) {
	$cat_info = get_cat_info($catid);
	if(empty($cat_info)) showmsg('不存在此分类', 'index.php');
	$here_arr[] = array('name'=>$cat_info['catname']);
	$cat_parent = $cat_info['parentid'];

	if(empty($cat_parent)) {
		//查看有没有父分类，没有查找有没有子分类
		$cat_row = get_cat_children($catid, 'array');
		//如果有子分类
		if(!empty($cat_row)) {
			//生成搜索
			$s_cat .= '<select name="id" id="id"><option value="0">请选择</option>';
			foreach($cat_row as $cat) {
				$s_cat .= "<option value=$cat[id]>$cat[name]</option>";
			}
			$s_cat .= '</select>';
			//取得所有子分类ID，用逗号连接，用于取得信息
			$cats = get_cat_children($catid);
			if($cats) $cats .= ','.$catid;

			/* 生成导航 */
			$cat_arr = array();
			foreach($cat_row as $val) {
				$val['catname'] = $val['name'];
				$val['url'] = url_rewrite('category', array('cid'=>$val['id'],'eid'=>$areaid));
				$cat_arr[] = $val;
			}
		} else {
			//如果没有，就只有这一级分类，不可选择
			$s_cat .= '<select name="id" id="id" disabled>';
			$s_cat .= "<option value=$catid selected>".$cat_info['catname']."</option>";
			$s_cat .= '</select>';
			$cats = $catid;
		}
		$top_type = '1';//置顶类型为大分类置顶
		
	} else {
		//如果有父分类，取得他所有的子分类
		$cat_row = get_cat_children($cat_parent, 'array');
		/* 生成导航 */
		if(!empty($cat_row))
		{
			foreach($cat_row as $val) {
				$val['catnid'] = $val['id'];
				$val['catname'] = $val['name'];
				$val['url'] = url_rewrite('category',array('cid'=>$val['id'],'eid'=>$areaid));
				$cat_arr[] = $val;
			}
			/* 生成搜索 */
			$s_cat .= '<select name="id" id="id" disabled>';
			foreach($cat_row as $cat) {
				$select = $cat['id'] == $catid ? 'selected' : '';
				$s_cat .= "<option value='$cat[id]' $select>$cat[name]</option>";
			}
			$s_cat .= '</select>';
		}
		$top_type = '2'; //置顶类型为小分类置顶
		$cats = $catid;
	}
	$cat_sql = " and catid in ($cats) ";
	$cat_custom = cat_search_custom($catid);//该方法的sql有个where条件where is_top>=time() 所以一般是不会取到值的
	$top_info = get_top_info($cats, $top_type);
	if(!empty($top_info)) {
		foreach((array)$top_info as $val) {
			$ids[] = $val['id'];
		}
		$top_info_ids = join(',', $ids);
		$top_info_sql = " and id not in ($top_info_ids)";
	}
} else {
	//不存在分类，就取得所有大分类
	$cat_row = get_parent_cat();
	//生成导航
	if(!empty($cat_row)) {
		foreach($cat_row as $val) {
			$val['url'] = url_rewrite('category', array('cid'=>$val['catid'],'eid'=>$areaid));
			$cat_arr[] = $val;
		}
	}
	//生成搜索
	$s_cat = '<select name="id" id="id"><option value="0">请选择</option>';
	foreach($cat_row as $cat) {
		$s_cat .= "<option value=$cat[catid]>$cat[catname]</option>";
	}
	$s_cat .= '</select>';
}

if($areaid) {
	$area_info = get_area_info($areaid);
	if(empty($area_info)) showmsg('不存在此地区');
	$here_arr[] = array('name'=>$area_info['areaname']);
	$area_parent = $area_info['parentid'];
	if(empty($area_parent)) {
		//大分类
		$area_row = get_area_children($areaid,'array');
		if($area_row) {
			//导航条
			$area_arr = array();
			foreach($area_row as $val) {
				$val['areaname'] = $val['name'];
				$val['url'] = url_rewrite('category',array('cid'=>$catid,'eid'=>$val['id']));
				$area_arr[] = $val;
			}
			//下拉框
			$s_area .= '<select name="area" id="area"><option value="0">请选择</option>';
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
		//如果有父分类，取得他所有的子分类
		$area_row = get_area_children($area_parent, 'array');
		/* 生成导航 */
		foreach($area_row as $val) {
			$val['areaid'] = $val['id'];
			$val['areaname'] = $val['name'];
			$val['url'] = url_rewrite('category',array('eid'=>$val['id'],'cid'=>$catid));
			$area_arr[] = $val;
		}
		/* 生成搜索 */
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
	//没有地区，取所有根地区
	$area_row = get_parent_area();
	if($area_row) {
		$area_arr = array();
		foreach($area_row as $val) {
			$val['areaname'] = $val['areaname'];
			$val['url'] = url_rewrite('category', array('cid'=>$catid, 'eid'=>$val['areaid']));
			$area_arr[] = $val;
		}

		$s_area .= '<select name="area" id="area"><option value="0">请选择</option>';
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
//sql edited by bian 多取了一个userid
$sql = "SELECT id,userid,title,postdate,enddate,catid,areaid,thumb,description,click FROM {$table}info WHERE is_check=1 $cat_sql $area_sql $top_info_sql ORDER BY postdate DESC limit $pager[start], $pager[size]";
$res = $db->query($sql);
$info = array();
while($row=$db->fetchRow($res)) {
	$row['url']      = url_rewrite('view',array('vid'=>$row['id']));
	$row['postdate'] = date('y年m月d日', $row['postdate']);
	$row['lastdate'] = enddate($row['enddate']);
	$row['intro']    = cut_str($row['description'], 83);
	$row['areaname'] = $area_array[$row['areaid']];
	$row['catname']  = $cat_array[$row['catid']];
	$info[$row['id']] = $row;
}

if($info) {
	foreach($info as $val) {
		$infoid .= $val['id'].',';
		$infoCatId .= $val['catid'].',';//added by bian  新增 如:125,120,120,120,120,124,
	}
	$infoid = substr($infoid,0,-1);
	//added by bian s
    $infoCatId = substr($infoCatId,0,-1);//为了去掉最后一个逗号，变为125,120,120,120,120,124
	$sql = "SELECT {$table}info.id,{$table}category.needPay FROM {$table}category LEFT JOIN {$table}info ON {$table}category.catid ={$table}info.catid WHERE {$table}category.catid in ($infoCatId) ORDER BY {$table}info.postdate DESC";
	$res = $db->getAll($sql);
    $new_resarr = array();
    foreach( $res as $key1=>$val1) {
        $new_resarr[$val1['id']]=$val1['needPay'];
    }
    //added by bian e
	$info_custom = get_infos_custom($infoid);
    //获取会员信息 s
    $userinfo = member_info($_userid);
    $memberToken = md5(substr($userinfo['password'], 1,10).$_userid);//token，在调用接口时判断，避免非法调用
    $memberGold = empty($_userid)?0:$userinfo['gold'];
    //获取会员信息 e
	foreach($info as $key=>$val) {
        $info[$key]['memberInfo'] = $_userid;//存储是否登录信息，值为0则没有登录，否则传入用户的userid
        $info[$key]['isMemberGoldEnough'] =($memberGold>=$CFG['info_gold_diff'])?1:0 ;//会员的信息币信息是否充足，充足为1，否则为0
        //判断是否是本人发布的信息,是本人为1，不是本人为0
        $info[$key]['isMemberSelf']= ($info[$key]['userid']==$_userid)?1:0;
        $info[$key]['needPay'] = $new_resarr[$key];
        //e
		$info[$key]['custom'] = is_array($info_custom[$key]) ? $info_custom[$key] : array();
	}
}


$cat_pro = get_info($cats, $areas, '8', 'pro','','10','','',$_userid); //推荐信息
$cat_hot = get_info($cats, $areas, '8', '','click','10','','',$_userid); //热门信息


$here = get_here($here_arr);
$seo['title'] = $area_info['areaname'] . $cat_info['catname'] .'，'. '信息列表'.' - '.$CFG['webname'];
$seo['keywords'] = $area_info['areaname'].$cat_info['keywords'];
$seo['description'] = $cat_info['description'];
$template = $cat_info['cattplname'] ? $cat_info['cattplname'] : 'category';
include template($template);
?>