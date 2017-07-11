<?php

define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.php';

$sql = "select * from {$table}facilitate order by listorder asc,id desc";
$res = $db->query($sql);
$fac_list = array();
while($row = $db->fetchRow($res)) {
	$fac_list[] = $row;
}
$seo['title'] = '便民电话列表 - ' . $CFG['webname'];
$seo['keywords'] = '便民电话列表' . $CFG['webname'];;
include template('bianmin');
?>