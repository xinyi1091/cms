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
��<?php echo $pager['count'];?>������&nbsp;&nbsp;��ǰ<?php echo $pager['page'];?>/<?php echo $pager['page_count'];?>ҳ&nbsp;&nbsp;<?php echo $pager['size'];?>��/ҳ  &nbsp;&nbsp;

<?php if($pager[first]) { ?>
<a href="<?php echo $pager['first'];?>" target='icomment'>��һҳ </a>
<?php } else { ?>��һҳ <?php } ?>
<?php if($pager[prev]) { ?>
<a href="<?php echo $pager['prev'];?>" target='icomment'>��һҳ </a>
<?php } else { ?>��һҳ <?php } ?>
<?php if($pager[next]) { ?>
<a href="<?php echo $pager['next'];?>" target='icomment'>��һҳ 
<?php } else { ?>��һҳ <?php } ?>
<?php if($pager[last]) { ?>
<a href="<?php echo $pager['last'];?>" target='icomment'>���ҳ</a>
<?php } else { ?>���ҳ<?php } ?>

</div>

<?php } else { ?>
��������
<?php } ?>
