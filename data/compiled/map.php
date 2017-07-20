<?php if(!defined('IN_BIANMPS'))die('Access Denied'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<script language="javascript">
function besuremark() {
var point1 = document.getElementById('point1').value;
var point2 = document.getElementById('point2').value;
if(point1 == '' || point2 == ''){
if(!confirm('您尚未标注地图\n\n确定提交吗?')) return false;
} else {
parent.document.post.mappoint.value = point1 + "," + point2;
}
parent.Yubox.close();
}
</script>
<body style="margin:0;">
<div id="mymap" style="width:<?php echo $width;?>px; height:<?php echo $height;?>px;"></div>
<?php if($mark=='1') { ?>
<div style="padding-top:10px; padding-bottom:10px; text-align:center; border-top:1px #666 solid">
<input type="button" onclick="markmap();" id="markbtn" value="标注" class="gray"/>
<input type="button" value="确定" onclick="besuremark();" class="gray">
</div>
<?php } ?>
<input type="hidden" id="point1" value='' />
<input type="hidden" id="point2" value='' />
<script type="text/javascript">
var map_id = 'mymap';
var p1 = '<?php echo $p1;?>';
var p2 = '<?php echo $p2;?>';
var mark = 'mark';
var show = '<?php echo $show;?>';
var title = '<?php echo $title;?>';
var content = '<?php echo $content;?>';
var width = <?php echo $width;?>;
var height = <?php echo $height;?>;
var view_level = <?php echo $level;?>;
</script>
<script type="text/javascript" language="javascript" src="<?php echo $CFG['mapapi'];?>" charset="utf8" ></script>
<script type="text/javascript" language="javascript" src="js/map/<?php echo $CFG['mapflag'];?>.js?v=1.1"></script>
</body>
</html>
