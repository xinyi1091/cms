<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><!-- ҳ�� -->
<div id="clear" style="display:none;"></div>
 
<div id="footer" class="clearfix">
<div class="foot_line">
<p class="foot_nav">

<?php if(is_array($about)) foreach($about AS $key => $val) { ?>
<a href=<?php echo $val['url'];?> target=_blank><?php echo $val['title'];?></a>
<?php if($key<(count($about)-1)) { ?> | <?php } ?>

<?php } ?>

| <a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?=$CFG[weburl]?>');return(false);" style="cursor:pointer;">��Ϊ��ҳ</a>
<a href=javascript:window.external.AddFavorite('<?php echo $CFG['weburl'];?>','<?php echo $CFG['webname'];?>')>��Ϊ�ղ�</a> |  <a href="./wap">�ֻ���</a>
</p>
<div style="margin-top:20px; line-height:170%;">
<a href="<?php echo $CFG['weburl'];?>" target=_blank><strong><?php echo $CFG['webname'];?></strong></a>&copy; 2014-<?php echo date("Y")?>  Inc. 
<?php if(!empty($CFG['qq'])) { ?>
<!-- QQ:	 -->
<?php if(is_array($CFG['qq'])) foreach($CFG['qq'] AS $qq) { ?>
<!-- <a href="http://wpa.qq.com/msgrd?V=1&amp;Uin=<?php echo $qq;?>&amp;Site=<?php echo $CFG['webname'];?>&amp;Menu=yes" target="_blank"><img style="display:inline" src="http://wpa.qq.com/pa?p=1:<?php echo $qq;?>:4" height="16" border="0" alt="QQ" /><?php echo $qq;?></a> -->

<?php } ?>

<?php } ?>
<?php echo $CFG['count'];?>  <a href="rss.php">RSS��ͼ</a>  <a href="sitemap.xml">վ���ͼ</a>
<?php } ?>
</div>
<script type="text/javascript" src="js/navtop.js"></script>
<!-- <div id="code"></div> -->
<!-- ҳ�� ���� -->