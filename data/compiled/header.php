<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><script src="http://siteapp.baidu.com/static/webappservice/uaredirect.js" type="text/javascript"></script>
<script type="text/javascript">uaredirect("/wap/");</script>
<div class="topall">
<div id="top_bar"  class="clearfix">
<div class="change_city">
         <script type="text/javascript" src="js/date.js"></script>
</div>
<div class="site_service">
<?php if($_userid) { ?>
&nbsp;&nbsp;��ӭ�㣺<?php echo $_username;?>
&nbsp;&nbsp;<a href="member.php">[��Ա��������]</a>
<?php if($_status<=0) { ?>&nbsp;&nbsp;<a href="member.php?act=send_check_email"><font color='red'>[��֤�ʼ�]</font></a><?php } ?>
&nbsp;&nbsp;<a href="member.php?act=logout&mid=<?php echo $_userid;?>">[�˳�]</a>
<?php } else { ?>
<font color="red"><a href="member.php?act=login&refer=<?php echo $PHP_URL;?>">��Ա��¼</a></font>&nbsp;
<font color="red"><a href="member.php?act=register">��Աע��</a></font>&nbsp;
<?php } ?>
</div>
</div>
<!-- topBar ���� -->
<!-- ͷ�� -->
<div id="header" class="clearfix">
<div class="logo"><a href="./"><img src="templates/<?php echo $CFG['tplname'];?>/images/logo.png" /></a></div>
        <div class="post">
        <div class="postLinks1">
        <a href="<?php echo $CFG['postfile'];?>" class="postBtn1">������Ϣ</a><!--<a href="postcom.php" class="postBtn1">�̼���פ</a>--></div></div>
<div class="quick_menu"><div class="search_s"><form name="form" action="search.php" method="post"><input type="text" name="keywords" id="keywords"  class="inputtop" maxlength="40" ><input type="submit" name="search"  value="&nbsp;&nbsp;"  class="btn-s"></form></div></div>
   </div>
<!-- ͷ�� ���� -->
<!-- ������ -->
    
    
    
    
    <div class="nav">
    <div class="mainnav_box">
<ul id="mainnav_box_ul">
<li><a href="/" class="hover">��ҳ</a></li>
<?php if(is_array($cats_list)) foreach($cats_list AS $cat) { ?>
<li><a href="<?php echo $cat['caturl'];?>"><?php echo $cat['catname'];?></a></li>

<?php } ?>
<!--<li style="float:right"><a href="com.php" class="hover2">��ҵ��ҳ</a></li>-->
<li style="float:right"><a href="article.php" class="hover">վ�񹫸�</a></li>
</ul>
</div></div>
<!-- ���������� -->
<div class="sub_nav">
<div class="inner">
<ul class="clearfix">
<li class="jlebm">��������</li>
<?php if(is_array($areas_list)) foreach($areas_list AS $val) { ?>
<li class="jlebm"><a href="<?php echo $val['url'];?>"><?php echo $val['areaname'];?></a></li>

<?php } ?>

</ul>
</div>
</div>


</div>	


