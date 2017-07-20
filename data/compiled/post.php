<?php if(!defined('IN_BIANMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/reset.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/post.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/validator/validator.min.js"></script>
<link href="js/validator/validator.css" type="text/css" rel="stylesheet" />
</head>
<?php echo splitword();?>
<body class="home-page">

<?php include template(header); ?>
<div class="wrapper">
<!-- 主体 -->
<form name="post" action="<?php echo $CFG['postfile'];?>" method="post" enctype="multipart/form-data">
<input type=hidden name=postfrom value=<?=$_SERVER['SERVER_NAME']?>>
<div id="content" >
<div class="thd clearfix"><b>发布步骤：</b><span>1.选择分类</span><span class="current">2.填写内容</span><span>3.发布完成</span></div>
<div class="fbd">
<div class="edit_box">
<ul>
<li><span class="span_1">&nbsp;</span><span class="span_2" style="font-size:12px;">您的ip地址是：<span class="red_skin"><?php echo $ip;?></span>，请真实填写信息内容</span></li>
<li><span class="span_1">所选类别：</span><span class="span_2"><b><?php echo $catinfo['catname'];?></b>—> <a href="<?php echo $CFG['postfile'];?>"> 返回重选类别</a></span></li>
<li><span class="span_1">*选择地区：</span><span class="span_2">
<select name=areaid id="areaid" class="fabuselect">
<option value=''>请选择地区</option>
<?php echo $area_option;?>
</select>
</span><span id="c_area"></span></li>
<li><span class="span_1">*信息标题：</span><span class="span_2" style="font-size:12px; color:#888;"><input id="title" name="title"  type="text" size="58" class="fabuinput"></span><span id="c_title"></span></li>

<?php if(is_array($custom)) foreach($custom AS $item) { ?>
<li><span class="span_1"><?php echo $item['cusname'];?>：</span><span class="span_2"><?php echo $item['html'];?></span></li>

<?php } ?>


<li><span class="span_1">信息内容：</span><span class="span_2" style="font-size:12px; color:#888;"><textarea cols="115" rows="8" name="content"  class="fabutxt"></textarea>
<p></span><span id="c_content"></span></li>

<li><span class="span_1">上传图片：</span>
<span class="span_2" style="width:800px">
<input type="file" class="textInt23" size="20" name="file1" />&nbsp;
<input type="file" class="textInt23" size="20" name="file2" />&nbsp;
<input type="file" class="textInt23" size="20" name="file3" />&nbsp;
<br><font color="#999999">最多可上传3张图片，不超过500K，图片支持jpg 、gif 、png格式！</font>
</span>
</li>
<?php if($_userid) { ?>
<li><span class="span_1">置顶：</span><span class="span_2">
<input type="radio" id="is_top" name="is_top" value="0" checked  onclick="check_info_gold()"/>&nbsp;不置顶 
<input type="radio" id="is_top" name="is_top" value="1" onclick="check_info_gold()"/>&nbsp;大类置顶 
<input type="radio" id="is_top" name="is_top" value="2" onclick="check_info_gold()"  style="display:none;"/>
<input id="number" name="number"  type="text" size="6" value='' onblur="check_info_gold()" onKeyUp="value=value.replace(/\D+/g,'')" /> 天
&nbsp;&nbsp;【每天<font color="red"><?php echo $CFG['info_top_gold'];?></font>信息币】&nbsp;&nbsp;&nbsp;&nbsp;<span id="c_top"></span></span>
</li>
<script type="text/javascript">
function check_info_gold()
{
var temp=document.getElementsByName("is_top");
for (i=0;i<temp.length;i++) {
if(temp[i].checked)is_top = temp[i].value;
}
if(is_top == 0) {
$("#c_top").html("");
return false;
}
if($("#number").val()>0 && is_top>0) {
$.post(
'member.php?act=check_info_gold',
{is_top:$("#is_top").val(),number:$("#number").val()},
function (data) {
eval('arrstr='+data+';');
if(arrstr.gold<0) {
document.getElementById('is_top').checked='0';
$("#c_top").html("<font color=red>金额不足</font>");
} else {
$("#c_top").html("共需<font color='red'>"+arrstr.kou+"</font>信息币");
}
}
)
}
}
</script>
<?php } ?>
                    
                    <li style="display:none"><span class="span_1">邮箱：</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="email" value=""/></span><span id="c_linkman"></span></li>
                     <li style="display:none"><span class="span_1">地址：</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="address" value=""/></span><span id="c_linkman"></span></li>
                    
                    
                    
<li><span class="span_1">联系人：</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="linkman" value=""/></span><span id="c_linkman"></span></li>
                    
                    <li><span class="span_1">联系电话：</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="phone" id="phone" value="<?php echo $member['phone'];?>"/></span> <span id="c_phone"></span></li>
<li style="display:none;"><span class="span_1">联系QQ：</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="qq" id="qq" value="<?php echo $member['qq'];?>"/></span> <span id="c_qq"></span></li>
<li><span class="span_1">有效期：</span><span class="span_2"><input name=enddate type=text id=enddate value="" size="8" class="sfabuinput"> 
天 (不填则为永久有效)</span></li>
<li><span class="span_1">删除密码：</span><span class="span_2"><input type="password"  class="sfabuinput" size="30" id="password" name="password" /></span><span id="c_password"></span></li>

<?php if($CFG[map]) { ?>
<script type="text/javascript" src="js/msgbox/msgbox.js"></script>
<link href="js/msgbox/msgbox.css" type="text/css" rel="stylesheet" />
<li><span class="span_1">地图：</span><span class="span_2"><input id='mappoint' class="fabuinput"  name='mappoint' type=text value="<?php echo $member['mappoint'];?>" size="30" /> <input name="markmap" type="button" value="标注地图" class="pingbut" onclick="Yubox.win('do.php?act=small_map&mark=1&width=500&height=250&p=<?php echo $CFG['map'];?>',500,340,'标注地图',null,null,null,true);"></span></li>
<?php } ?>
<li><span class="span_1">*问题验证：</span>
<span class="span_2">
<input type="text" name="answer" id="answer" size="30"  class="sfabuinput"/>问题：<?php echo $verf['question'];?>？
<input type="hidden" id="vid" name="vid" value=<?php echo $verf['vid'];?> /></span>
</li>
<li><span class="span_1">&nbsp;</span>
<span class="span_2">
<input type="submit" value="立即发布" class="okbut"/>
<input type="hidden" name="catid" value="<?php echo $catid;?>" />
<input type="hidden" name="act" value="postok" />
</span></li>
</ul>
</div>		
</div>
</div>
<!-- 主体 结束 -->
</form>

</div>
<?php include template(footer); ?>

<script type="text/javascript">
$.validator("title")
.setTipSpanId("tip_span_title")
.setFocusMsg("4～38个字，不能填写电话、特殊符号")
.setRequired("请填写标题")
.setServerCharset("UTF-8")
.setStrlenType("symbol")
.setMinLength(4, "标题不足4个字")
.setMaxLength(38, "标题名称字数多于38个字");

$.validator("areaid")
.setTipSpanId("tip_span_areaid")
.setFocusMsg("请选择地区")
.setRequired("请选择地区")
.setServerCharset("UTF-8")
.setStrlenType("symbol");

$.validator("phone")
.setTipSpanId("tip_span_phone")
.setFocusMsg("请正确填写电话，分机用“-”分开")
.setRegexp(/^1[3458]\d{9}$|^(0\d{2,4}-)?[2-9]\d{6,7}(-\d{2,5})?$|^(?!\d+(-\d+){3,})[48]00(-?\d){7,10}$/, "电话格式错误，如87654321-001或400-1234-5678或138********", false)
.setCallback(chklink, "qq，电话必须填写一项", '1');

$.validator("qq")
.setTipSpanId("tip_span_qq")
.setCallback(chklink, "qq,电话必须填写一项", '1');

$.validator("answer")
.setRequired("请填写问题验证。")
.setAjax("do.php?act=ver&vid="+$('#vid').val(), "回答不正确。");

function chklink() {
if($('#phone').val()=='' && $('#email').val()=='' && $('#qq').val()=='') {
return false;
} else {
return true;
}
}
</script>
</body>
</html>