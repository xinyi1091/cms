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
<!-- ���� -->
<form name="post" action="<?php echo $CFG['postfile'];?>" method="post" enctype="multipart/form-data">
<input type=hidden name=postfrom value=<?=$_SERVER['SERVER_NAME']?>>
<div id="content" >
<div class="thd clearfix"><b>�������裺</b><span>1.ѡ�����</span><span class="current">2.��д����</span><span>3.�������</span></div>
<div class="fbd">
<div class="edit_box">
<ul>
<li><span class="span_1">&nbsp;</span><span class="span_2" style="font-size:12px;">����ip��ַ�ǣ�<span class="red_skin"><?php echo $ip;?></span>������ʵ��д��Ϣ����</span></li>
<li><span class="span_1">��ѡ���</span><span class="span_2"><b><?php echo $catinfo['catname'];?></b>��> <a href="<?php echo $CFG['postfile'];?>"> ������ѡ���</a></span></li>
<li><span class="span_1">*ѡ�������</span><span class="span_2">
<select name=areaid id="areaid" class="fabuselect">
<option value=''>��ѡ�����</option>
<?php echo $area_option;?>
</select>
</span><span id="c_area"></span></li>
<li><span class="span_1">*��Ϣ���⣺</span><span class="span_2" style="font-size:12px; color:#888;"><input id="title" name="title"  type="text" size="58" class="fabuinput"></span><span id="c_title"></span></li>

<?php if(is_array($custom)) foreach($custom AS $item) { ?>
<li><span class="span_1"><?php echo $item['cusname'];?>��</span><span class="span_2"><?php echo $item['html'];?></span></li>

<?php } ?>


<li><span class="span_1">��Ϣ���ݣ�</span><span class="span_2" style="font-size:12px; color:#888;"><textarea cols="115" rows="8" name="content"  class="fabutxt"></textarea>
<p></span><span id="c_content"></span></li>

<li><span class="span_1">�ϴ�ͼƬ��</span>
<span class="span_2" style="width:800px">
<input type="file" class="textInt23" size="20" name="file1" />&nbsp;
<input type="file" class="textInt23" size="20" name="file2" />&nbsp;
<input type="file" class="textInt23" size="20" name="file3" />&nbsp;
<br><font color="#999999">�����ϴ�3��ͼƬ��������500K��ͼƬ֧��jpg ��gif ��png��ʽ��</font>
</span>
</li>
<?php if($_userid) { ?>
<li><span class="span_1">�ö���</span><span class="span_2">
<input type="radio" id="is_top" name="is_top" value="0" checked  onclick="check_info_gold()"/>&nbsp;���ö� 
<input type="radio" id="is_top" name="is_top" value="1" onclick="check_info_gold()"/>&nbsp;�����ö� 
<input type="radio" id="is_top" name="is_top" value="2" onclick="check_info_gold()"  style="display:none;"/>
<input id="number" name="number"  type="text" size="6" value='' onblur="check_info_gold()" onKeyUp="value=value.replace(/\D+/g,'')" /> ��
&nbsp;&nbsp;��ÿ��<font color="red"><?php echo $CFG['info_top_gold'];?></font>��Ϣ�ҡ�&nbsp;&nbsp;&nbsp;&nbsp;<span id="c_top"></span></span>
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
$("#c_top").html("<font color=red>����</font>");
} else {
$("#c_top").html("����<font color='red'>"+arrstr.kou+"</font>��Ϣ��");
}
}
)
}
}
</script>
<?php } ?>
                    
                    <li style="display:none"><span class="span_1">���䣺</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="email" value=""/></span><span id="c_linkman"></span></li>
                     <li style="display:none"><span class="span_1">��ַ��</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="address" value=""/></span><span id="c_linkman"></span></li>
                    
                    
                    
<li><span class="span_1">��ϵ�ˣ�</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="linkman" value=""/></span><span id="c_linkman"></span></li>
                    
                    <li><span class="span_1">��ϵ�绰��</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="phone" id="phone" value="<?php echo $member['phone'];?>"/></span> <span id="c_phone"></span></li>
<li style="display:none;"><span class="span_1">��ϵQQ��</span><span class="span_2"><input type="text" class="fabuinput" size="30" name="qq" id="qq" value="<?php echo $member['qq'];?>"/></span> <span id="c_qq"></span></li>
<li><span class="span_1">��Ч�ڣ�</span><span class="span_2"><input name=enddate type=text id=enddate value="" size="8" class="sfabuinput"> 
�� (������Ϊ������Ч)</span></li>
<li><span class="span_1">ɾ�����룺</span><span class="span_2"><input type="password"  class="sfabuinput" size="30" id="password" name="password" /></span><span id="c_password"></span></li>

<?php if($CFG[map]) { ?>
<script type="text/javascript" src="js/msgbox/msgbox.js"></script>
<link href="js/msgbox/msgbox.css" type="text/css" rel="stylesheet" />
<li><span class="span_1">��ͼ��</span><span class="span_2"><input id='mappoint' class="fabuinput"  name='mappoint' type=text value="<?php echo $member['mappoint'];?>" size="30" /> <input name="markmap" type="button" value="��ע��ͼ" class="pingbut" onclick="Yubox.win('do.php?act=small_map&mark=1&width=500&height=250&p=<?php echo $CFG['map'];?>',500,340,'��ע��ͼ',null,null,null,true);"></span></li>
<?php } ?>
<li><span class="span_1">*������֤��</span>
<span class="span_2">
<input type="text" name="answer" id="answer" size="30"  class="sfabuinput"/>���⣺<?php echo $verf['question'];?>��
<input type="hidden" id="vid" name="vid" value=<?php echo $verf['vid'];?> /></span>
</li>
<li><span class="span_1">&nbsp;</span>
<span class="span_2">
<input type="submit" value="��������" class="okbut"/>
<input type="hidden" name="catid" value="<?php echo $catid;?>" />
<input type="hidden" name="act" value="postok" />
</span></li>
</ul>
</div>		
</div>
</div>
<!-- ���� ���� -->
</form>

</div>
<?php include template(footer); ?>

<script type="text/javascript">
$.validator("title")
.setTipSpanId("tip_span_title")
.setFocusMsg("4��38���֣�������д�绰���������")
.setRequired("����д����")
.setServerCharset("UTF-8")
.setStrlenType("symbol")
.setMinLength(4, "���ⲻ��4����")
.setMaxLength(38, "����������������38����");

$.validator("areaid")
.setTipSpanId("tip_span_areaid")
.setFocusMsg("��ѡ�����")
.setRequired("��ѡ�����")
.setServerCharset("UTF-8")
.setStrlenType("symbol");

$.validator("phone")
.setTipSpanId("tip_span_phone")
.setFocusMsg("����ȷ��д�绰���ֻ��á�-���ֿ�")
.setRegexp(/^1[3458]\d{9}$|^(0\d{2,4}-)?[2-9]\d{6,7}(-\d{2,5})?$|^(?!\d+(-\d+){3,})[48]00(-?\d){7,10}$/, "�绰��ʽ������87654321-001��400-1234-5678��138********", false)
.setCallback(chklink, "qq���绰������дһ��", '1');

$.validator("qq")
.setTipSpanId("tip_span_qq")
.setCallback(chklink, "qq,�绰������дһ��", '1');

$.validator("answer")
.setRequired("����д������֤��")
.setAjax("do.php?act=ver&vid="+$('#vid').val(), "�ش���ȷ��");

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