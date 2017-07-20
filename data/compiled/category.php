<?php if(!defined('IN_BIANMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/reset.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/category.css" type="text/css" rel="stylesheet" />
<script src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/jquery.lazyload.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="js/layer/layer.js"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
$("img").lazyload({
effect : "fadeIn"
});
});
        function layerOpen(isMemberSelf,jumpUrl,isMemberGoldEnough,infoid) {
            var _userid = <?php echo $_userid;?>;
var _token= '<?php echo $memberToken;?>';
            if(_userid){
if(isMemberSelf){
                    window.open(jumpUrl);
}else{
//如果费用充足
if(isMemberGoldEnough){
                        layer.open({
                            icon: 0,
                            skin: 'layui-layer-lan',
                            title:'消息',
                            content: '<span style="color:red;" id="layerSpan">本类信息需付费，每条<i style="color:blue;"><?php echo $CFG["info_gold_diff"];?></i>信息币.继续浏览将付费(24小时内重复浏览不会重复计费)</span>',
                            btn: ['确认', '取消'],
                            yes: function(){
                                $.ajax({
                                        type:'post',
                                        url:'category.php',
                                        data:{act:"gold_diff",userid:_userid,token:_token,infoid:infoid},
datatype:"json",
                                        success:function (data) {
                                            var data = JSON.parse(data);//ajax解析json要用eval();（此方法不推荐）或JSON.parse();（推荐）
                                            if(data.error==200){
                                                location.href=jumpUrl;
}else{
                                                $("#layerSpan").text(data.content);
}
                                        }
});
                            },
                        });
}else{
                        layer.open({
                            icon: 0,
                            skin: 'layui-layer-lan',
                            title:'消息',
                            content: '<span style="color:red;">本类信息需付费，每条<i style="color:blue;"><?php echo $CFG["info_gold_diff"];?></i>信息币.但您的费用不足，确认将会跳转到购买信息币页面</span>',
                            btn: ['确认', '取消'],
                            yes: function(){
                                location.href='member.php?act=gold'
                            }
                        });
}
}
}else{
                layer.open({
                    icon: 0,
                    skin: 'layui-layer-lan',
                    title:'消息',
                    content: '<span style="color:red;">本类信息需付费，每条<i style="color:blue;"><?php echo $CFG["info_gold_diff"];?></i>信息币.请先登录</span>',
                    btn: ['登录', '取消'],
                    yes: function(){
                        location.href='member.php?act=login'
                    }
                });
}
        }
</script>
</head>
<body class="home-page">

<?php include template(header); ?>

<div class="wrapper">
<!-- 主体 -->
<div id="content">
<div class="m_title_h"><div class="dh_list"><b>最新信息</b><span>
<?php include template(here); ?>
</span></div></div>
 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="margin-top:10px;">
  <tbody><?php if($cat_arr || $area_arr) { ?>
                    <?php if($cat_arr) { ?>
                    <tr >
<td></td><td width="50" valign="top"><div class="city_dh_list2"><em>分类</em></div></td>
<td>
                    <div class="city_dh_list">
<ul><?php if(is_array($cat_arr)) foreach($cat_arr AS $val) { ?><li>
<a href=<?php echo $val['url'];?>> <?php echo $val['catname'];?></a>&nbsp;
</li>
<?php } ?>
</ul></div>
</td>
</tr>
                    <?php } ?>
                    
                    <?php if($area_arr) { ?>
                    <tr>
<td></td><td width="50" valign="top"><div class="city_dh_list2"><em>地区</em></div></td>
<td>
                    <div class="city_dh_list">
<ul><?php if(is_array($area_arr)) foreach($area_arr AS $val) { ?><li>
<a href=<?php echo $val['url'];?>> <?php echo $val['areaname'];?></a>&nbsp;
</li>
<?php } ?>
</ul></div>
</td>
</tr>
                    <?php } ?>
                    <?php } ?>

  	<tr><td></td><td width="50" valign="top"><div class="city_dh_list2"><em>搜索</em></div></td>
<td align="left"><div class="city_dh_list3" >
<form method="get" name="lbform" id="lbform" onsubmit="return searchCommunity(this);" style="padding-left:30px;">
<input type="text" class="ssinput" id="search_kw" value="请输入小区名搜索" name="q" onfocus="if(value=='请输入小区名搜索'){value='';style.color='#000';}this.select();" style="color:#999" autocomplete="off">
<input id="searchbtn" type="submit" class="redBtn" value="本类搜索">
</form></div></td>
</tr>
  
  
  
</tbody></table>
<div style=" clear:both"></div>
<div id="cat_content" class="clearfix">
<ul id="listul">  
<?php if(is_array($top_info)) foreach($top_info AS $article) { ?>   
<li class="jianbg3">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="9%" rowspan="2" height="65" align="center" valign="middle"><?php if($article[thumb]) { ?><img data-original="<?php echo $article['thumb'];?>" src="js/grey.gif" style="width:60px;height:60px; border-radius:5%; border:#fff 3px solid" alt="<?php echo $article['title'];?>">
<?php } else { ?><img  data-original="templates/<?php echo $CFG['tplname'];?>/images/nosmall.gif" src="js/grey.gif" style="width:60px;height:60px; border-radius:5%; border:#fff 3px solid" alt="<?php echo $article['title'];?>">
<?php } ?></td>
    <td width="72%" height="30"><font color="#ff0000">[置顶]&nbsp;&nbsp;</font><a href="<?php echo $article['url'];?>" target="_blank"><?php echo $article['title'];?></a>&nbsp;&nbsp;<font color="#ff5500" style="font-size:12px;"><?php echo $article['areaname'];?></font>&nbsp;&nbsp;<font color="#007700"><?php echo $article['catname'];?></font></td>
<td width="10%" align="center"><span style="color:#f80;font-size:12px;"><?php echo $article['postdate'];?></span></td>
<td width="9%" align="center"><span style="color:#888;font-size:12px; padding-right:12px;">点击：<?php echo $article['click'];?></span></td>
   
  </tr>
  <tr>
    <td colspan="3" valign="top" height="35"><div style=" line-height:140%; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif"><?php echo $article['description'];?>...</div></td>
    </tr>
</table>
</li>
<?php } ?>
 



<?php $t1=1?>

<?php if(is_array($info)) foreach($info AS $val) { ?>
<?php if($t1%2==0) { ?>
<li class="jianbg2"><?php } else { ?><li class="jianbg21">
<?php } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
<td width="9%" rowspan="2" height="65" align="center" valign="middle"><?php if($val[thumb]) { ?><img data-original="<?php echo $val['thumb'];?>" src="js/grey.gif" style="width:60px;height:60px; border-radius:5%; border:#fff 3px solid" alt="<?php echo $val['title'];?>">
<?php } else { ?><img  data-original="templates/<?php echo $CFG['tplname'];?>/images/nosmall.gif" src="js/grey.gif" style="width:60px;height:60px; border-radius:5%; border:#fff 3px solid" alt="<?php echo $val['title'];?>">
<?php } ?></td>
    <td width="72%" height="30"><?php if($val[needPay]) { ?><a href="javascript:void(0);"  onclick="layerOpen(<?php echo $val['isMemberSelf'];?>,'<?php echo $val['url'];?>',<?php echo $val['isMemberGoldEnough'];?>,<?php echo $val['id'];?>);"><?php echo $val['title'];?></a><?php } else { ?><a href="<?php echo $val['url'];?>" target="_blank"><?php echo $val['title'];?></a><?php } ?>&nbsp;&nbsp;<font color="#ff5500" style="font-size:12px;"><?php echo $val['areaname'];?></font>&nbsp;&nbsp;<font color="#007700"><?php echo $val['catname'];?></font></td>
<td width="10%" align="center"><span style="color:#f80;font-size:12px;"><?php echo $val['postdate'];?></span></td>
<td width="9%" align="center"><span style="color:#888;font-size:12px; padding-right:12px;">点击：<?php echo $val['click'];?></span></td>
    
  </tr>
  <tr>
    <td colspan="3" valign="top" height="35"><div style=" line-height:140%; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif"><?php echo $val['intro'];?>...</div></td>
    </tr>
</table>
</li>

<?php $t1++?>


<?php } ?>

                
</ul>
</div>
<div class="pagination_module clearfix" style="margin-top:7px;">
<span class="right2" style="float:right;"><a href="#top" style="border:0; color:#fff;">返回顶部 ↑</a></span>
<span class="right2" style="float:left;">
<?php include template(page); ?>
</span></span>	

</div>




 </div></div>
<!-- 主体 结束 -->

<?php include template(footer); ?>

</div>
</body>
</html>
