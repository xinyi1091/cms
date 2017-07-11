<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/reset.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/view.css" type="text/css" rel="stylesheet" />
<script src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<!-- phpmps -->
<script>
var lng = '<?php echo $mappoint['0'];?>';
var lat = '<?php echo $mappoint['1'];?>';
var address = '信息所在地';
function checkcomment()
{
if(document.comment.content.value==""){
alert('请输入评论内容！');
document.comment.content.focus();
return false;
}
if(document.comment.checkcode.value==""){
alert('请输入验证码！');
document.comment.checkcode.focus();
return false;
}
}
function chkreport()
{
var radios = document.getElementsByName("types"); 
var resualt = false;
for(i=0;i<radios.length;i++)
{
if(radios[i].checked)
{
    resualt = true;
}
}
if(!resualt)
{   
alert("请选择错误类型");
return false;
}
}
function chktype()
{
if(document.form3.password.value==""){
alert('请输入密码！');
document.form3.password.focus();
return false;
}
if(document.form3.act.value=="delinfo"){
return confirm('确认要删除吗？此操作不可恢复！')
}
}
</script>
</head>
<body class="home-page">

<?php include template(header); ?>

<div class="wrapper">
<div id="content">
<div class="m_title_h"><div class="dh_list"><b>信息详情</b><span>
<?php include template(here); ?>
</span></div></div>
<table width="97%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td  width="65%" valign="middle" height="70"><div class="h_title"><?php echo $title;?></div></td><td width="35%" valign="middle" align="right"><div class="bshare-custom icon-medium"><div class="bsPromo bsPromo2"></div><a title="分享到" href="http://www.bShare.cn/" id="bshare-shareto" class="bshare-more">分享到</a><a title="分享到QQ空间" class="bshare-qzone"></a><a title="分享到新浪微博" class="bshare-sinaminiblog"></a><a title="分享到人人网" class="bshare-renren"></a><a title="分享到腾讯微博" class="bshare-qqmb"></a><a title="分享到网易微博" class="bshare-neteasemb"></a><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a><span class="BSHARE_COUNT bshare-share-count" style="float: none;">0</span></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script></td>
  </tr>
 <tr><td colspan="2">
<div class="h_title_bottom">
<ul class="menu clearfix">
<li>信息编号：<span>INFO_2014_<?php echo $id;?></span></li>
<li>发布时间：<span><?php echo $postdate;?></span></li>
<li>有效期：<span><?php echo $lastdate;?></span></li>
<li>点击：<span><?php echo $click;?></span>次</li>
<li style="float:right;"><a href="#replay" class="reply">留言点评</a></li>
</ul>
</div></td></tr>
</table>
<div class="info_content">
<div class="clearfix">
<div class="col_main">
<div class="page_title">
<div class="clearfix">					
<table border="0" cellspacing="0" cellpadding="0" class="telbox">
  <tbody><tr>
    <td class="telpic">&nbsp;</td>
    <td class="dianhuabox" width="200"><span class="dianhua"><?php echo $phone;?></span></td>
    <td valign="middle" align="left"><?php if($phone) { ?><input type="button" onclick="window.open('http://www.ip138.com:8080/search.asp?action=mobile&mobile=<?php echo $phone_c;?>')" value="查看归属地" class="guishubut"><?php } ?></td>
    <td class="lianxiren">
<b>联系人：</b><?php echo $linkman;?>
</td>
    <td width="100"><div class="diqubox"><?php echo $areaname;?> </div></td><td width="10"></td>
  </tr>
</tbody></table>
</div>
                    <div class="top_table">
<table width="100%" class="table_1" border="0" cellspacing="0">
  <?php if(is_array($custom)) foreach($custom AS $val) { ?>
  <tr>
<td align="right" bgcolor="#eff1f7" width="100px"><b><?php echo $val['name'];?>：</b></td>
<td align="left"><?php echo $val['value'];?>&nbsp;&nbsp;<?php echo $val['unit'];?></td>
  </tr>
  
<?php } ?>

</table>
</div>
                    
                   <div class="top_table_3">
<span class="dico"><b>&nbsp;&nbsp;详情&nbsp;&nbsp;</b></span>&nbsp;&nbsp;<?php echo $content;?><br />


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="jubaoline">
                
           <form name="report" method="post" action="member.php" onsubmit="return chkreport()">
 


    
                
  <tr>
    <td width="270">  如果您发现这条信息有问题，请务必及时举报。</td>
    <td><input type="radio" name="types" value="1">&nbsp;非法信息&nbsp;&nbsp;
<input type="radio" name="types" value="2">&nbsp;分类错误&nbsp;&nbsp;
<input type="radio" name="types" value="3">&nbsp;中介信息&nbsp;&nbsp;
<input type="radio" name="types" value="4">&nbsp;信息失效&nbsp;&nbsp;</td>
    <td width="60"><input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="act" value="report">
<input type="submit" name="submit" value="提交" class="jubaobut"></td>
  </tr>  </form>  
</table>
<?php if($images) { ?> 
<div id="Gallery">
<div class="gallery-row">
<?php if(is_array($images)) foreach($images AS $val) { ?>
<div class="gallery-item"><a href=<?php echo $val['path'];?> target="_blank" ><img src=<?php echo $val['path'];?> class="postinfoimg" alt="" border=0 /></a></div>

<?php } ?>

</div></div>
<?php } ?>        
</div>  
<div class="content_box">
<table width="100%" class="table_2" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" height="30" colspan="3"><span class="dico"><b>&nbsp;&nbsp;其他联系信息&nbsp;&nbsp;</b></span></td>
  </tr>
  <tr>
<td height="36"  width="29%"><b>QQ：</b><?php echo $qq;?>
<?php if($qq) { ?><?php if($CFG['visitor_view']) { ?><script language=javascript src="js.php?act=qq&qq=<?php echo $js_qq;?>"></script>
<?php } ?><?php } ?></td>
<td width="29%"><b>邮箱：</b><?php echo $email;?></td><td height="36" width="42%"><b>联系地址：</b><?php echo $address;?></td>
  </tr>

</table>
</div>
</div>
<!---->
<div class="page_cont">

 

<!-- 联系方式 -->

<!-- 评论 -->
<div class="comment_box">
<div class="hd"><span class="title">网友回复</span></div>
<div class="bd">
<div class="comment_zone" id="showcomment">
正在加载数据，请稍等......
</div>
<div class="comment_write" style=" margin-top:15px;">
<form name="comment" action="member.php?act=comment" method="post" onsubmit="return checkcomment();" style="margin:0" >
<a name="replay"></a>
<textarea name="content" cols="" class="comment_input" rows=""></textarea>
<div class="clearfix comment_login"><span class="left">验证码：<input name=checkcode  type=text id=checkcode size="10" maxlength="5" onfocus='get_code();this.onfocus=null;' style="height:17px; line-height:17px; padding:5px;" />
&nbsp;<span id=imgid></span></span>
<span class="left"><input name="" value="提 交" type="submit" class="pingbut" />
<input type=hidden name=id value=<?=$id?> ><span id="checkshop"> (注意：仅限300汉字)</span></span>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
<div class="col_sub">
<div class="category_menu">
<div class="hd">信息管理</div>
<div class="bd">
<?php if($infouserid>0 && $infouserid==$_userid) { ?>

      <div class="postLinks12"><a href="member.php?act=editinfo&id=<?=$id?>" class="postBtn2">修改信息</a></div>
      <div class="postLinks12"><a href="member.php?act=delinfo&id=<?=$id?>" onClick="if(!confirm('确定要删除吗？\n\n此操作不可以恢复！'))return false;" class="postBtn2">删除信息</a></div>
      <div class="postLinks12"><a href="member.php?act=refer&id=<?=$id?>" class="postBtn2">刷新信息</a></div>
      <div class="postLinks12"><a href="member.php?act=top&id=<?=$id?>" class="postBtn2">置顶信息</a></div>
      <div style="clear:both"></div>
      </div>

<?php } else { ?>
                    
                    
                    <table width="210" border="0" cellspacing="0" cellpadding="0" align="center">
                    <form name=form3 action="member.php?act=editinfo" method=post>
  <tr>
    <td width="60" align="left" height="40">管理密码: </td>
    <td width="70" align="left"><input name=password type=password id=delpass size="10" maxLength=20 style="padding:4px; border:#cce3f1 1px solid"></td>
    <td width="60" align="right"><select name="act" id="act" style="padding:4px; border:#cce3f1 1px solid">
  <option value="delinfo">删除</option>
  <option value="editinfo">修改</option>
</select></td>
  </tr>
 
  <tr>
    <td colspan="3"  height="40"><input onClick="return chktype();" type=submit value="提交" name=submit class="xiugaibut">
<input type=hidden name=id value=<?php echo $id;?> /></td>

  </tr>	</form>
</table>
<?php } ?>
</div>
</div>
<!-- 信息地图 -->
<?php if($mappoint) { ?>
<div class="searchz_box">
<div class="hd">信息地图</div>
<div class="bd">
<iframe id="map_iframe" src="do.php?act=small_map&show=1&p=<?php echo $CFG['map'];?>&width=215&height=212" frameborder="0" scrolling="no" width="215" height="212"></iframe>
</div>
</div>
<?php } ?>
<!-- 相关信息 -->
<?php if($match_info) { ?>
<div class="searchz_box">
<div class="hd">相关信息</div>
<div class="bd">
<ul>
<?php if(is_array($match_info)) foreach($match_info AS $val) { ?>
<li><a href="<?php echo $val['url'];?>" target=_blank><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
</div>
</div>
<?php } ?>
<!-- 发送邮件 -->
<?php if($CFG['sendmailtype'] && $crypt_email) { ?>
<div class="searchz_box">
<div class="hd">发送邮件</div>
<div class="bd">
<form name="send" method="post" action="member.php?act=send_info_mail" >
标题：<br><input type="text" name="title" size="25" /><br>
内容：<br><textarea name="content" cols='30' rows="5"></textarea><br>
<input type="hidden" name="email" value="<?php echo $crypt_email;?>" />
<input type="submit" name="submit" value="发送" class="btn"/>
</form>
</div>
</div>
<?php } ?>
<!-- 举报 -->

</div>
</div>
</div></div>
<!-- 主体 结束 -->

</div>
<div class="floatBar"><a href="#"><img src="templates/<?php echo $CFG['tplname'];?>/images/top_go.png" alt="返回顶部"></a></div>
<!-- 评论 -->
<iframe id="icomment" name="icomment" src="comment.php?infoid=<?=$id?>" frameborder="0" scrolling="no" width="0" height=0></iframe>
<?php include template(footer); ?>


</body>
</html>
