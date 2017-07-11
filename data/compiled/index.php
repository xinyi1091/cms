<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/index.css" type="text/css" rel="stylesheet" />
<script src="js/common.js"></script>
<script src="js/hdpic.js"></script>
<script src="js/jquey.js"></script>
</head>
<body class="home-page">

<?php include template(header); ?>

<!-- 主体 -->
<div class="adbox_1"> <?php echo ads_list('1');?></div>

<div class="layer_box">
<div class="left1">
<div class="nr">
<div class="slide">
<script type=text/javascript>
<!--
var focus_width=400
var focus_height=310
var text_height=0
var swf_height = focus_height+text_height
var pics='<?=$flash['image']?>'
var links='<?=$flash['url']?>'
var texts='||||'	
document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="images/flashplay.swf"><param name="quality" value="high"><param name="bgcolor" value="#ffffff">');
document.write('<param name="menu" value="false"><param name="wmode" value="opaque">');
document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">');
document.write('<embed src="images/flashplay.swf" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" menu="false" bgcolor="#ffffff" quality="high" width="'+ focus_width +'" height="'+ focus_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');		
document.write('</object>');
//-->
</script>
</div>
<div class="news">

<!-- 置顶信息 -->
<ul class="news_top">
<?php if(is_array($top_info)) foreach($top_info AS $val) { ?>
<li><a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="blue"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
<!-- 最新信息 -->
<ul class="news_new">
<?php if(is_array($articles)) foreach($articles AS $val) { ?>
<li>
<span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['pdate'];?></span>
<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>"><?php echo $val['title'];?></a>
</li>

<?php } ?>

</ul>

</div>
</div>
</div>
<div class="right1">
<div class="nr">
<div class="tianqi">
<?php echo ads_list('5');?>
</div>
<div class="tongji">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td height=28 width=135>共有信息<span><?=$info_num?></span>条</td>
<!-- <td height=28 width=135>商家信息<span><?=$com_num?></span>条</td> --><td height=28 width=135>注册会员<span><?=$member_num?></span>位</td>
</tr>
<tr>
<!-- <td height=28 width=135>新闻资讯<span><?=$article_num?></span>条</td>
<td height=28 width=135>注册会员<span><?=$member_num?></span>位</td> -->
</tr>
</table>

</div>
<div class="hotline">
热线电话：<?php echo $CFG['phone'];?>
<br>QQ：<?php if(is_array($CFG['qq'])) foreach($CFG['qq'] AS $qq) { ?><a href="http://wpa.qq.com/msgrd?V=1&amp;Uin=<?php echo $qq;?>&amp;Site=<?php echo $CFG['webname'];?>&amp;Menu=yes" target="_blank"><img style="display:inline" src="http://wpa.qq.com/pa?p=1:<?php echo $qq;?>:4" height="16" border="0" alt="QQ" /><?php echo $qq;?></a>
<?php } ?>

</div>
</div>
</div>
</div>

<!-- 商家 --><!--
<div class="layer_box md10">
<div class="tt9">
<h6>
<span>共有&nbsp;<font color="#ee3300"><?=$com_num?></font>&nbsp;家商家进驻&nbsp;&nbsp;&nbsp;&nbsp;<a href="com.php">更多&gt;&gt;</a></span>
<a href="com.php">最新入驻商家</a>
</h6>
</div>
<div class="comlist">
<div class="tu">
<ul id="tu">

<?php $comi=1?>

<?php if(is_array($coms)) foreach($coms AS $val) { ?>
<li>
<div class="com_tu"><a href="<?php echo $val['url'];?>" class="img_wrap"><img src="<?php echo $val['thumb'];?>" border="0" alt="<?php echo $val['comname'];?>"></a>
</div>
<div class="text-area"><p><?php echo $val['sname'];?></p><p>Tel：<span class="p-num"><?php echo $val['phone'];?></span></p></div>
</li>

<?php $comi++?>


<?php } ?>


</ul>
<ul id=text>
<?php if(is_array($coms2)) foreach($coms2 AS $val) { ?>

<li>
<span><a href="com.php">[商家]</a></span>&nbsp;<a href="<?php echo $val['url'];?>" target=_blank><?php echo $val['sname'];?></a>
</li>


<?php } ?>

</ul>
</div>
</div>
</div>-->


<div class="adbox_1"> <?php echo ads_list('2');?></div><div style="margin:10px auto; width:100%;">
<div class="layer_box2">


<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=96">更多最新</a></span><a href="category.php?id=96">便民服务</a></h6>
<ul>
<?php if(is_array($new_info_7)) foreach($new_info_7 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=2">更多最新</a></span><a href="category.php?id=2">房屋租售</a></h6><ul>
<?php if(is_array($new_info_2)) foreach($new_info_2 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99">
<h6><span><a href="category.php?id=1">更多最新</a></span><a href="category.php?id=1">创业</a></h6><ul>
<?php if(is_array($new_info_1)) foreach($new_info_1 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>
</div>

<div class="adbox_1"> <?php echo ads_list('3');?></div>
<div class="layer_box2" style="margin-top:10px;">

<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=4">更多最新</a></span><a href="category.php?id=4">生活服务</a></h6><ul>
<?php if(is_array($new_info_4)) foreach($new_info_4 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=3">更多最新</a></span><a href="category.php?id=3">二手买卖</a></h6><ul>
<?php if(is_array($new_info_3)) foreach($new_info_3 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99">
<h6><span><a href="category.php?id=5">更多最新</a></span><a href="category.php?id=5">健康生活</a></h6><ul>
<?php if(is_array($new_info_5)) foreach($new_info_5 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>
</div>

<div class="layer_box2" style="margin-top:10px;">

<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=7">更多最新</a></span><a href="category.php?id=7">交友征婚</a></h6><ul>
<?php if(is_array($new_info_9)) foreach($new_info_9 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99" style="border-right:#e8e8e8 1px solid">
<h6><span><a href="category.php?id=6">更多最新</a></span><a href="category.php?id=6">车辆信息</a></h6><ul>
<?php if(is_array($new_info_6)) foreach($new_info_6 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>

<div id="ilist99">
<h6><span><a href="category.php?id=119">更多最新</a></span><a href="category.php?id=119">宠物信息</a></h6><ul>
<?php if(is_array($new_info_8)) foreach($new_info_8 AS $val) { ?>
<li><span style="float:right; font-size:11px;font-family:Arial; padding-left:10px;"><?php echo $val['postdate'];?></span><font color="#ff6600">[<?php echo $val['areaname'];?>]</font>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" class="red"><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>
</div>





</div>



<div class="ggbox2"> <?php echo ads_list('4');?></div>

<div class="mod_3 mar10">
<div class="hd clearfix">
<div class="dh_bm"><b class="left">便民电话114 · 同城服务</b><span class="yellow" style="color:#f50;">想让您的电话显示在这里吗？请联系客服&nbsp;<?php if(is_array($CFG['qq'])) foreach($CFG['qq'] AS $qq) { ?><a href="http://wpa.qq.com/msgrd?V=1&amp;Uin=<?php echo $qq;?>&amp;Site=<?php echo $CFG['webname'];?>&amp;Menu=yes" target="_blank"><img style="display:inline" src="http://wpa.qq.com/pa?p=1:<?php echo $qq;?>:4" height="16" border="0" alt="QQ" /><?php echo $qq;?></a>

<?php } ?>
&nbsp; &nbsp;<a href="bianmin.php">更多电话&gt;&gt;</a></span></div>
</div>
<div class="bd">
<div id="bmdh" class="area">
<ul class="bianmin clearfix">

<?php $t1=1?>

<?php if(is_array($fac)) foreach($fac AS $fac) { ?>
<li class="bg<?php echo $t1;?>"><i><?php echo $fac['title'];?><br><?php echo $fac['phone'];?></i></li> 

<?php $t1++?>


<?php } ?>

</ul>
</div>
</div>
</div>

<?php if(!empty($links[txt])) { ?>
<div class="friendLink mar10">
<div class="bd">

<div class="text">
友情连接：
<?php if(is_array($links[txt])) foreach($links[txt] AS $link) { ?>
<a href="<?php echo $link['url'];?>" target=_blank title="<?php echo $link['webname'];?>"><?php echo $link['webname'];?></a>&nbsp;&nbsp;

<?php } ?>
<?php if(is_array($links[image])) foreach($links[image] AS $link) { ?><a href="<?php echo $link['url'];?>" target=_blank title="<?php echo $link['webname'];?>"><?php echo $link['webname'];?></a>&nbsp;&nbsp;
<?php } ?>

</div>
  
</div>
</div>
<?php } ?>

<!-- 主体 结束 -->

<?php include template(footer); ?>

</div>
</body>
</html>