<?php if(!defined('IN_PHPMPS'))die('Access Denied'); ?><script type="text/javascript">
function comment(){
parent.document.getElementById("showcomment").innerHTML=document.getElementById("comment").innerHTML;
}
</script>
<body onload="comment()">
<div id="comment">
<?php if($comment) { ?>
<?php if(is_array($comment)) foreach($comment AS $comment) { ?><div class="comments">
  <div class="comment_hd clearfix"><span class="jubao"><span class="name"><?php echo $comment['username'];?></span> <?php echo $comment['postdate'];?></div>
  <div class="comment_bd"><?php echo $comment['content'];?></div>
</div>

<?php } ?>
共<?php echo $pager['count'];?>条评论&nbsp;&nbsp;当前<?php echo $pager['page'];?>/<?php echo $pager['page_count'];?>页&nbsp;&nbsp;<?php echo $pager['size'];?>条/页  &nbsp;&nbsp;

<?php if($pager[first]) { ?>
<a href="<?php echo $pager['first'];?>" target='icomment'>第一页 </a>
<?php } else { ?>第一页 <?php } ?>
<?php if($pager[prev]) { ?>
<a href="<?php echo $pager['prev'];?>" target='icomment'>上一页 </a>
<?php } else { ?>上一页 <?php } ?>
<?php if($pager[next]) { ?>
<a href="<?php echo $pager['next'];?>" target='icomment'>下一页 
<?php } else { ?>下一页 <?php } ?>
<?php if($pager[last]) { ?>
<a href="<?php echo $pager['last'];?>" target='icomment'>最后页</a>
<?php } else { ?>最后页<?php } ?>

</div>

<?php } else { ?>
暂无评论
<?php } ?>
