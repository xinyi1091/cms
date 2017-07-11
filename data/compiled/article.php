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
 <div class="m_title_h"><div class="dh_list"><b>新闻中心</b><span>
<?php include template(here); ?>
</span></div></div>
<div class="col_main">
<div class="newsShow">

<h2><?php echo $title;?></h2>
<div class="news_info">发布时间: <?php echo $addtime;?></div>
<div class="news_content" style="font-size:16px;">
<?php echo $content;?>
</div>
                <div class="top_menu2 clearfix">
<ul>
<li><a href="javascript:copyToClipBoard();" class="send">转 发</a></li>
<li><a href="javascript:window.external.AddFavorite(window.location.href,document.title);" class="fav">收 藏</a></li>
<li><a href="javascript:window.print();" class="print">打 印</a></li>
</ul>
</div>
<div class="page_get clearfix">
<div class="left">上一条：<a href="<?php echo $pre['url'];?>"><?php echo $pre['title'];?></a></div>
<div class="right">下一条：<a href="<?php echo $next['url'];?>"><?php echo $next['title'];?></a></div>
</div>
</div>
</div>
<div class="col_sub">
<!-- 推荐阅读排行 -->
       

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
           相关新闻
            </div>
            <div class="hd">
           <ul>
<?php if(is_array($match_article)) foreach($match_article AS $row) { ?>
<li><a href="<?php echo $row['url'];?>"><?php echo $row['title'];?></a></li>

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


</div>
</div>
<!-- 主体 结束 -->

</div>

<?php include template(footer); ?>

</body>
</html>