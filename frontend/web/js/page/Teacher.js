//点击语音改变图片 文字颜色
$(".click_voice").click(function() {
	$('.tape').show();
	$('.voice_image_display').hide();
	$('.voice_image_none').show();
	$('.font_voice').css({
		color: '#28CA78'
	});
	//点击语音体消失 状态显示初始状态
	$('.Input_text').hide();
	$('.text_image_display').show();
	$('.text_image_none').hide();
	$('.font_color').css({
		color: '#808080'
	});
	//点击语音媒体库消失
	$('.media_library').hide()
	$('.click_display3').show()
	$('.click_media_none').hide()
	$('.font_media').css({
		color: '#808080'
	})

})
//点击文字改变图片颜色,字体 颜色
$('.click_text').click(function() {
	$('.Input_text').show();
	$('.text_image_display').hide();
	$('.text_image_none').show();
	$('.font_color').css({
		color: '#28CA78'
	});
	//点击文字语音消失 字体改变成初始状态的字体颜色
	$('.tape').hide()
	$('.voice_image_display').show();
	$('.voice_image_none').hide();
	$('.font_voice').css({
		color: '#808080'
	});
	//点击文字媒体库消失
	$('.media_library').hide()
	$('.click_display3').show()
	$('.click_media_none').hide()
	$('.font_media').css({
		color: '#808080'
	})

});
//点击媒体库
$('.click_media_library').click(function() {
	$('.click_display3').hide()
	$('.click_media_none').show()
	$('.font_media').css({
		color: '#28CA78'
	})
	$('.media_library').show()
	//点击文字语音消失 字体改变成初始状态的字体颜色
	$('.tape').hide()
	$('.voice_image_display').show();
	$('.voice_image_none').hide();
	$('.font_voice').css({
		color: '#808080'
	});
	//点击媒体库字体消失 状态显示初始状态
	$('.Input_text').hide();
	$('.text_image_display').show();
	$('.text_image_none').hide();
	$('.font_color').css({
		color: '#808080'
	});
})
//点击重录弹框     
$('.rerecord ').click(function() {
	//	console.log($)
	$('.award_pop3').show()
})
$('.btn_left1').click(function() {
	$('.elastic').hide()
})
$('.btn_right1').click(function() {
	$('.elastic').hide()
})

//点击操作显示弹框
$('.operation').click(function() {
	$('.award_pop1').show()
	//点击文字语音消失 字体改变成初始状态的字体颜色
	//	$('.sound2').hide()
	//	$('.sound').hide()
	//	$('.sound1').hide()
	$('.voice_image_display').show()
	$('.voice_image_none').hide()
	$('.font_voice').css({
		color: '#808080'
	})
	//点击更多操作字体消失 状态显示初始状态
	$('.Input_text').hide();
	$('.text_image_display').show();
	$('.text_image_none').hide();
	$('.font_color').css({
		color: '#808080'
	});
	//点击更多操作媒体库消失
	$('.media_library').hide()
	$('.click_display3').show()
	$('.click_media_none').hide()
	$('.font_media').css({
		color: '#808080'
	})
})
//讨论区里的只查看提问的小黑点一开始是隐藏状态
$('.circles').hide()
//点击input显示小黑点
$('.p3').click(function() {
	$('.circles').show()
})
$('.discuss').click(function() {
	$('.award_pop').show()
})
$('.p4').click(function() {
	$('.award_pop').hide()
})
//讨论区删除弹框
//$(".delete").on("click", function() {
//		$(this).parents("ul").remove()
//})
$('.delete').click(function() {
	$('.award_pop4').show()
})
//点击更多操作的按钮弹框消失
$('.img_btn').click(function() {
	$('.award_pop1').hide()
})
//更多操作里的图片
$('.images_right').click(function() {
	if($(this).find('.image_right2').css("display") == "block") {
		$(this).find('.image_right2').css("display", "none");
		$(this).find('.image_right1').css("display", "block");
	} else {
		$(this).find('.image_right2').css("display", "block");
		$(this).find('.image_right1').css("display", "none");
	}

})
//点击回复显示输入框
$('.Reply').click(function() {
	$('.Input_text').show();
	$('footer').hide();
})

$('.shouqi').click(function() {
	$('.award_pop2').hide()
})

//讨论区上墙弹框
$('.Upper_wall').click(function() {
	$('.award_pop5').show()
})
//弹幕
$('.teacher_block').click(function() {
	$('.barrage_content').hide()
	$('.teacher_none').show()
	$(this).hide()
})
$('.teacher_none').click(function() {
	$('.barrage_content').show()
	$('.teacher_block').show()
	$(this).hide()
})
//禁言状态
$('.excuse').on('click', function() {
	var _this = $(this);
	if($(this).hasClass('btn')) {
		_this.removeClass('btn');
		$('.An_excuse').text('nino已被禁言').show();
		_this.text('禁言');
		setTimeout(function() {
			$('.An_excuse').text('nino已被禁言').hide();
		}, 3000)
		// $(this).removeClass('btn');
		// $('.An_excuse').text('nino已被禁言').show(300)
		// $(this).text('禁言');
	} else {
		_this.addClass('btn')
		$('.An_excuse').text('nino已被您解除禁言').show();
		_this.text('解禁');
		setTimeout(function() {
			$('.An_excuse').text('nino已被您解除禁言').hide();
		}, 3000)
		// $(this).addClass('btn')
		// $('.An_excuse').text('nino已被您解除禁言').show(300)
		// $(this).text('解禁');

	}
})
//点击回到顶部
$('.Top').click(function() {
	//	$('section').animate({
	//		scrollTop: 0
	//	}, 300);
	$('section').scrollTop(0);
	$('.award_pop1').hide();
})
$('.bottom').click(function() {
	//	$('section').animate({
	//		scrollTop: 100000
	//	}, 300);
	$('section').scrollTop(1000000);
	$('.award_pop1').hide();
})