$(function(){
	/* 展开与隐藏 开始 */
	function updownFunc(){
		$('.updown').each(function(){
			var updown = $(this);
			var updownCont = updown.children('.cont');
			if(updownCont.css('max-height')!='none'){
				//不等于 none 代表的是折叠状态
				updownCont.css({'max-height':'none','height':'5em'});
			}
			var updownContHeightA = updownCont.css('height').replace('px',' ');
			updownCont.css('height','auto').removeClass('three_lines');
			var updownContHeightB = updownCont.height();
			if(updownContHeightA >= updownContHeightB){
				updownCont.css({'max-height':'5em','height':'auto'});
				/*已经处于展开状态,将底部按钮隐藏掉*/
				updown.find('.btn_func').hide();
			} else {
				updownCont.css({'max-height':'none','height':'5em'});
				updown.find('.btn_func').show();
			}
		    updownCont.addClass('three_lines');
		});
	}
	
	updownFunc();
	$(window).resize(function(){
		updownFunc();
	});
	
	$('.updown .cont_funcs .btn_func').click(function(){
	    var cont = $(this).parent('.cont_funcs').prev('.cont');
		var contHeight = cont.height();
		var hasBtnPackup = $(this).hasClass('btn_packup');
		var hasBtnUnfold = $(this).hasClass('btn_unfold');
		if(hasBtnUnfold){
			/*button 点击之前处于收起状态*/
			cont.removeClass('three_lines').css('height','auto');
			var contAutoHeight = cont.height();
			cont.css('height',contHeight+'px');
			cont.animate({height:contAutoHeight+'px'},200);
			$(this).removeClass('btn_unfold').addClass('btn_packup');
	    } else if(hasBtnPackup) {
	    	/*点击之前处于展开状态*/
			cont.animate({height:'5em'},200).addClass('three_lines');
			$(this).removeClass('btn_packup').addClass('btn_unfold');
	    }
	});
	/*展开与隐藏 结束*/

	
});


