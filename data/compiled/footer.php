<?php if(!defined('IN_BIANMPS'))die('Access Denied'); ?><!-- 页脚 --><div id="clear" style="display:none;"></div> <div id="footer" class="clearfix"><div class="foot_info"><div class="foot_line"><p class="foot_nav"><?php if(is_array($about)) foreach($about AS $key => $val) { ?><a href=<?php echo $val['url'];?> target=_blank><?php echo $val['title'];?></a><?php if($key<(count($about)-1)) { ?> | <?php } ?><?php } ?>| <a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?=$CFG[weburl]?>');return(false);" style="cursor:pointer;">设为首页</a><a href=javascript:window.external.AddFavorite('<?php echo $CFG['weburl'];?>','<?php echo $CFG['webname'];?>')>加为收藏</a> |  <a href="./wap">手机版</a></p></div><div style="margin-top:20px; line-height:170%;"><a href="<?php echo $CFG['weburl'];?>" target=_blank><strong><?php echo $CFG['webname'];?></strong></a>&copy; 2014-<?php echo date("Y")?>  Inc. <?php if(!empty($CFG['qq'])) { ?><!-- QQ:	 --><?php if(is_array($CFG['qq'])) foreach($CFG['qq'] AS $qq) { ?><!-- <a href="http://wpa.qq.com/msgrd?V=1&amp;Uin=<?php echo $qq;?>&amp;Site=<?php echo $CFG['webname'];?>&amp;Menu=yes" target="_blank"><img style="display:inline" src="http://wpa.qq.com/pa?p=1:<?php echo $qq;?>:4" height="16" border="0" alt="QQ" /><?php echo $qq;?></a> --><?php } ?><?php } ?><br /> <?php echo $CFG['copyright'];?><Br /> ICP备案号：<a href=http://www.miibeian.gov.cn target=_blank><?php echo $CFG['icp'];?></a>&nbsp;&nbsp; <?php if($CFG['count']) { ?><?php echo $CFG['count'];?>  <a href="rss.php">RSS地图</a>  <a href="sitemap.xml">站点地图</a><?php } ?></div></div><div id="clear"></div><script type="text/javascript" src="js/navtop.js"></script><div id="clear"></div><!-- <div id="code"></div> --><div id="code_img" style="display: none;"></div><a id="gotop" style="display: block;" href="javascript:void(0)"></a></div><!-- 页脚 结束 -->