<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/reset.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/post.css" type="text/css" rel="stylesheet" />
<script src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<body class="home-page">

<?php include template(header); ?>
<div class="wrapper">
<!-- ���� -->
<div id="content" class="content">

<div class="step">
<ul>
                <li id="onestep" class="active">
                <span class="l_bg"></span>
                <span class="r_bg"></span>
                <span class="text">1.ѡ�����</span>
                </li>
                <li id="twostep">
                <span class="l_bg"></span>
                <span class="r_bg"></span>
                <span class="text">2.ѡ��С��</span>
                </li>
                <li id="threestep">
                <span class="l_bg"></span>
                <span class="r_bg"></span>
                <span class="text">3.��д��Ϣ</span>
                </li>
</ul>
</div>


<div class="tips">
1��ֻ��������<b class="red_skin"><?php echo $city;?></b>���������Ϣ<br />
2���벻Ҫ���ⷢ��������Ϣ�������Ϣ���ظ���Ϣ<br />
3��������Ϣ���������ϸ������л����񹲺͹����з��ɷ��漰���ء�����ҵ��ع涨���Ͻ����������κ�Υ����Υ��ɫ�ʵ���Ϣ<br />
4����Ϣ�����߱������ж���Ϣ����Ч�ԡ���ʵ�Գе�һ������
</div>

<div class="cate-bd">
<div id="father">
<div class="title">ѡ�����</div>
<ul class="cats">
<?$i=0?>
<?php if(is_array($cats)) foreach($cats AS $cat) { ?>
<li><a id="<?=$i?>" href="" data-name="<?php echo $cat['catname'];?>"><?php echo $cat['catname'];?></a></li>
<?$i=$i+1;?>

<?php } ?>

</ul>
</div>	

<?$n=0?>
<?php if(is_array($cats)) foreach($cats AS $cat) { ?>
            <div id="child<?=$n?>" class="chi-hid">
<div class="title">ѡ��<?php echo $cat['catname'];?>С��</div>
<ul class="cats">
<?php if(is_array($cat[children])) foreach($cat[children] AS $chi) { ?>
<li><a href="<?php echo $CFG['postfile'];?>?act=post&id=<?=$chi['id']?>" ><?php echo $chi['name'];?></a></li>

<?php } ?>

</ul>
            </div>
<?$n=$n+1;?>

<?php } ?>

</div>


<script type="text/javascript">

$(".cate-bd #father .cats a").hover(function(){var a=".cate-bd #child"+$(this).attr("id");return $(".step #onestep").removeClass("active").find("span.text").html("1."+$(this).attr("data-name")),$(".step #twostep").addClass("active"),$(".cate-bd #father li.select").removeClass("select"),$(this).parent().addClass("select"),$(".cate-bd .chi-show").removeClass("chi-show").addClass("chi-hid"),$(a).removeClass("chi-hid").addClass("chi-show"),!1});

</script>

</div>

</div><div id="mask" style="display:none"></div>

<?php include template(footer); ?>

</body>
</html>