<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<div style="overflow:hidden;zoom:1">
<div style="float:left;">
<span class="nolk">��<?php echo $pager['count'];?>��¼&nbsp;&nbsp;</span><span class="nolk">��ǰ<?php echo $pager['page'];?>/<?php echo $pager['page_count'];?>ҳ</span>

</div>
<div style="float:right">
<?php if($pager[first]) { ?>
<a href="<?php echo $pager['first'];?>">��ҳ  </a>
<?php } ?>
<?php if($pager[prev]) { ?>
<a href="<?php echo $pager['prev'];?>">��һҳ</a>
<?php } ?>
<?
/*���ɷ�ҳ����*/
$s_f=ltrim($_SERVER['PHP_SELF'],'/');//ҳ���ļ�
$a_s=explode('.',$s_f);
$s_a=$a_s[0];//ҳ���ʶ
$a_p=array();//����
$url='';//���Ӹ�ʽ
switch ($s_a){
case 'category':
$cat=$catid;$area=$areaid;
$a_p = array('cid' => $cat, 'eid' => $area);
$url=url_rewrite($s_a,$a_p,1);
break;
case 'com';
$cat=$catid;$area=$areaid;
$a_p = array('catid' => $cat, 'eid' => $area, 'act'=>'list');
$url=url_rewrite($s_a,$a_p,1);
break;
case 'article';
$cat=$typeid;
$a_p = array('iid' => $cat, 'act'=>'list');
$url=url_rewrite($s_a,$a_p,1);
break;
case 'help';
$cat=$typeid;
$a_p = array('tid' => $cat, 'act'=>'list');
$url=url_rewrite($s_a,$a_p,1);
break;
default:
$flt='.page.cat.catid.keywords.keyword.area.areaid.type.typeid';
$_REQUEST['page']=intval($_REQUEST['page'])?intval($_REQUEST['page']):1;
foreach($_REQUEST as $k=>$v){
$v=trim($v);
if(!$v) continue;
if (strpos($flt,$k,0!==false)){
$url.=$k.'='.$v.'&';
}
}
$url=$s_f.'?'.rtrim($url,'&');
break;
}
/*��ȡ���ӽ���*/
$p_count=$pager['page_count'];//��ҳ����
$p_page=$pager['page'];//��ǰҳ
$p_s=($p_page-4)<=1?1:$p_page-4;$p_e=($p_page+5)>=$p_count?$p_count:$p_page+5;
if($p_count<=10){
$p_s=1;$p_e=$p_count;
}
if($p_count>=10){
if($p_e<10) $p_e=10;
if(($p_e-$p_s)<10) $p_s=$p_e-9;
}
for($i=$p_s;$i<=$p_e;$i++){
if($i==$p_page) {
echo '&nbsp;&nbsp;<span class=curt>'.$i.'</span>&nbsp;&nbsp;';
}else{
echo '&nbsp;<a href="'.preg_replace('/page(.)(\d+)/i','page${1}'.$i,$url).'"> '.$i.' </a>&nbsp;';
}
}

?>
<?php if($pager[next]) { ?>
<a href="<?php echo $pager['next'];?>">��һҳ</a>
<?php } ?>
<?php if($pager[last]) { ?>
<a href="<?php echo $pager['last'];?>">βҳ</a>  
<?php } ?><span class="nolk"><?php echo $pager['size'];?>/ҳ</span>
</div>
</div>