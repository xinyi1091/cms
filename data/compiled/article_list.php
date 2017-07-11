<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
<title><?php echo $seo['title'];?></title>
<meta name="Keywords" content="<?php echo $seo['keywords'];?>">
<meta name="Description" content="<?php echo $seo['description'];?>">
<link href="templates/<?php echo $CFG['tplname'];?>/style/reset.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/style.css" type="text/css" rel="stylesheet" />
<link href="templates/<?php echo $CFG['tplname'];?>/style/article.css" type="text/css" rel="stylesheet" />
<script src="js/common.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body class="home-page">

<?php include template(header); ?>

<div class="wrapper">
<!-- 主体 --> 
<div id="content" class="clearfix">
    <div class="m_title_h"><div class="dh_list"><b><?php echo $seo['title2'];?></b><span>
<?php include template(here); ?>
</span></div></div>
    <div style="width:100%; float:left;"> 
    <div class="col_main">
<!-- 新闻列表 -->
<div class="newsList_box">
<div class="bd">
<ul>
<?php if(is_array($articles)) foreach($articles AS $row) { ?>
<li><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['title'];?></a><span><?php echo $row['addtime'];?></span>
                        <p><?php echo $row['description'];?></p></li>

<?php } ?>

</ul>
<div class="manu" style="text-align:left;"><br>

<?php include template(page); ?>
</div>
</div>
</div>
</div>
<div class="col_sub">
<!-- 推荐阅读排行 -->
           <div class="html_box">
<ul>
<?php if(is_array($type)) foreach($type AS $row) { ?>
<li><a href="<?php echo $row['url'];?>"><?php echo $row['typename'];?></a></li>

<?php } ?>

</ul>
                
</div>
            <div style="width:100%; height:23px; clear:both"></div>
<div class="news_right_box">
            <div class="head_t">
            推荐资讯
            </div>
            <div class="hd">
            <ul>
<?php if(is_array($pro_articles)) foreach($pro_articles AS $article) { ?>
<li><a href="<?php echo $article['url'];?>"><?php echo $article['title'];?></a></li>

<?php } ?>

</ul>
                    </div>
            </div>
             <div style="width:100%; height:23px; clear:both"></div>
<div class="news_right_box">
            <div class="head_t">
            最新信息
            </div>
            <div class="hd">
            <ul>
 <?php if(is_array($new_info)) foreach($new_info AS $val) { ?>
<li>&nbsp;<a href="<?php echo $val['url'];?>" target="_blank" title="<?php echo $val['title'];?>" ><?php echo $val['title'];?></a></li>

<?php } ?>

</ul>
                    </div>
            </div>
            <div style="width:100%; height:23px; clear:both"></div>
            <div class="news_right_box">
            <div class="head_t">
            赞助商广告位
            </div></div>
<div class="news_ad1"><img src="images/ad1.jpg" /></div>

</div></div>

</div>
<!-- 主体 结束 -->

</div>

<?php include template(footer); ?>

</body>
</html>