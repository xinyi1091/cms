<?php

define('IN_PHPMPS', true);
require dirname(__FILE__) . '/include/common.php';

$sql = "select * from {$table}facilitate order by listorder asc,id desc";
$res = $db->query($sql);
$fac_list = array();
while($row = $db->fetchRow($res)) {
	$fac_list[] = $row;
}
$seo['title'] = '����绰�б� - ' . $CFG['webname'];
$seo['keywords'] = '����绰�б�' . $CFG['webname'];;
include template('bianmin');
?>