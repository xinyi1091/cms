 $(document).ready(function(){
    var topMain=$("#webhead").height()+70//是头部的高度加头部与nav导航之间的距离
    var nav=$(".nav");
    $(window).scroll(function(){
    if ($(window).scrollTop()>topMain){//如果滚动条顶部的距离大于topMain则就nav导航就添加类.nav_scroll，否则就移除
    nav.addClass("nav_scroll");
    }else{
    nav.removeClass("nav_scroll");
    }
    });
    })
	
	
	     //返回顶部js
function b(){
	h = $(window).height();
	t = $(document).scrollTop();
	if(t > h){
		$('#gotop').show();
	}else{
		$('#gotop').hide();
	}
}
$(document).ready(function(e) {
	b();
	$('#gotop').click(function(){
		$(document).scrollTop(0);	
	})
	$('#code').hover(function(){
			$(this).attr('id','code_hover');
			$('#code_img').show();
		},function(){
			$(this).attr('id','code');
			$('#code_img').hide();
	})
	
});

$(window).scroll(function(e){
	b();		
})


