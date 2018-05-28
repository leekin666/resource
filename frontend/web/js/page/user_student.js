
//头部点击阴影然后消失
$(".head_shadow").click(function(){
	$(this).hide();
})
/*点击按钮控制弹框的显示隐藏*/
$(".js-open-tanmu").click(function(){
	$(".js-tan-pop").hide();
	$(this).hide();
	$(".js-close-tanmu").show();
})
$(".js-close-tanmu").click(function(){
	$(".js-tan-pop").show();
	$(this).hide();
	$(".js-open-tanmu").show();
})

/*更多操作*/
$(".more_operate .js-close-operate").click(function(){
	$(".more_operate").hide();
	$(".show_collect_clas").hide();
})
$(".js-open-operate").click(function(){
	$(".tape").removeClass("active");
	$(".more_operate").show();
})
/*收藏课程*/
$(".operta_cont .collect_class").click(function(){
	$(".show_collect_clas").show();
})
/*回顶部*/
$(".operta_cont .to_top").click(function(){
	$(".more_operate").hide();
	$(".js-main").scrollTop(0);
})
/*回底部*/
$(".operta_cont .to_bottom").click(function(){
	$(".more_operate").hide();
	$(".js-main").scrollTop(100000000);
})
/*控制讨论区*/
$(".foot_menu .li4").click(function(){
	$(".award_pop").show();
	$(".tape").removeClass("active");
})
$(".award_pop .shouqi").click(function(){
	$(".award_pop").hide();
})
$(".award_pop2 .shouqi").click(function(){
	$(".award_pop2").hide();
})
/*讨论区取消删除*/
$(".award_pop4 .btn_left1").click(function(){
	$(".award_pop4").hide();
})
$(".list_li1 .delete").click(function(){
	$(".award_pop4").show();
})
/*底部输入框的焦点获取*/
$(".js-ques-txt").on("focus",function(){
	/*让语音的东西消失*/
	$(".tape").removeClass("active");
	$(".footer").addClass("js-is-input");
	var isIOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //判断是iOS
    if(isIOS) {
    	$(".pop_shadow_single").show();
    	$(".footer").css("z-index","11");
     	$(".footer").css("top","0");
     	$(".footer").css("bottom","auto");
        $(".footer").css("padding-top","0.4rem");
       
    }
})

/*点击发送和下面的阴影 让输入框归为*/
$(".user_sent,.pop_shadow_single,.header,.head_teacher,.show_p_main").on("mousedown",function(){
	/*进行判断 防止多余操作浪费内存*/
	if($(".footer").hasClass("js-is-input")){
		$(".footer").removeClass("js-is-input");
		var isIOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //判断是iOS
	    if(isIOS) {
	    	$(".pop_shadow_single").hide();
	    	$(".footer").css("z-index","1");
	    	$(".footer").css("top","auto");
	    	$(".footer").css("bottom","0");
	        $(".footer").css("padding-top",0);
	    }
	}
		
})





/*点击音频图标控制录音的显示隐藏*/
$(".foot_menu .li1 ").click(function(){
	if($(".tape").hasClass("active")) {
		$(".tape").removeClass("active");
	}else{
		$(".tape").addClass("active");
	}
})







/*控制中间区域的高度*/
var js_main_height =$(window).height()-$(".header").height()-$(".head_teacher").height()-$(".footer").height();
$(".js-main").height(js_main_height)




//录音功能








/*音频的控制*/
$(".audio_box .js-a-open").click(function(){
	
})