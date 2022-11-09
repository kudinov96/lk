/* common */
$(document).ready(function() {
	if($('.mask').length>0) {
		$(".mask").mask("+7 (999) 999-99-99");
	}
	//$('input, select').styler();



	function ress() {
		minHeight('.tool-sheet1','.tool-sheet1__clm',0,1);
		minHeight('.list-semminars1','.list-semminars1__over',0,0);
	}
	ress();
	$(window).resize(function() {
		ress();
	});
	$(window).load(function() {
		ress();
	});
	function minHeight(e1,e2,e3,e4) {
		var y1=$(e1);
		var y2;
		y1.each(function() {
			y2=$(this).find($(e2));
			y2.css({'min-height':'0px'});
			if(e3==1) {
				y2.css({'height':'0px'});
			}
			var heightAll=0;
			y2.each(function() {
				if($(this).innerHeight()>heightAll) {
					heightAll=$(this).innerHeight()
				}
			});
			if(e4) {
				if(e4>heightAll) {
					heightAll=e4;
				}
			}
			if(e3==1) {
				y2.css({'height':heightAll});
			}
			else {
				y2.css({'min-height':heightAll});
			}
		});
	}


	$('.sertificate-list1-js').html($('.sertificate-list1').html());
	$('.sertificate-list1-js').slick({
	    prevArrow:'<div class="prev"></div>',
	    nextArrow:'<div class="next"></div>',
	    adaptiveHeight: true,
	    slidesToShow: 2
	});

	$('.list-video1-js').html($('.list-video1').html());
	$('.list-video1-js').slick({
	    prevArrow:'<div class="prev"></div>',
	    nextArrow:'<div class="next"></div>',
	    adaptiveHeight: true,
	    slidesToShow: 2,
		responsive: [
		{
			breakpoint: 761,
			settings: {
				slidesToShow: 1,
				dots: true
			}
		}
		]
	});

	$('.carousel-review1').each(function() {
		$(this).find('.carousel-review1__over').each(function() {
			$('.carousel-review1-js').append('<div class="carousel-review1-js__item"></div>');
			$('.carousel-review1-js__item:last-child').append($(this).clone());
		});
	});
	$('.carousel-review1-js').slick({
	    prevArrow:'<div class="prev"></div>',
	    nextArrow:'<div class="next"></div>',
	    adaptiveHeight: true,
	    slidesToShow: 2,
		responsive: [
		{
			breakpoint: 761,
			settings: {
				slidesToShow: 1
			}
		}
		]
	});
	$('.carousel-review1').slick({
	    prevArrow:'<div class="prev"></div>',
	    nextArrow:'<div class="next"></div>',
	    adaptiveHeight: true,
	    slidesToShow: 2
	});

	$('.block3__current[data-dt=1]').addClass('active').next().show();
	$('.block3__current').click(function(e) {
		e.preventDefault();
		if($(this).attr('data-dt')=='0') {
			$(this).attr('data-dt','1').addClass('active').next().slideDown(200);
		}
		else {
			$(this).attr('data-dt','0').removeClass('active').next().slideUp(200);
		}
	});

	var scrl1;
	var modalUp=0;
	function scrolljs(e) {
		if(e==0) {
			scrl1=$(window).scrollTop();
			if($(window).width()<1001) {
				$('body').css({'position':'fixed','top':-scrl1});
			}
			modalUp=1;
		}
		if(e==1) {
			$('body').css({'position':'static','top':'0px'});
			if($(window).width()<1001) {
				$(window).scrollTop(scrl1);
			}
			$('.modal-up').fadeOut(300);
			modalUp=0;
		}
	}
	$('*[data-modal]').click(function(e) {
		e.preventDefault();
		scrolljs(0);
		$('.'+$(this).attr('data-modal')).fadeIn(300);
	});
	$('.modal-up__close').click(function() {
		scrolljs(1);
	});
	$(document).click(function(e){
	    if ($(e.target).closest(".modal-up__content,*[data-modal],.mobile-menu1,.menu-button2").length) return;
	    if(modalUp==1) {
	    	scrolljs(1);
	    }
	    e.stopPropagation();
	});

	$('.menu-button1').click(function() {
		$('.drop-menu1').toggleClass('active');
	});
	$('.drop-menu1__close').click(function() {
		$('.drop-menu1').removeClass('active');
	});
	$('.menu-button2').click(function() {
		$(window).scrollTop(0);
		scrolljs(0);
		$('.page-wrapper,.mobile-menu1').addClass('active');
	});
	$('.mobile-menu1__black').click(function() {
		scrolljs(1);
		$('.page-wrapper,.mobile-menu1').removeClass('active');
	});

	$('.select-price1__current').click(function() {
		$(this).parent().toggleClass('active');
	});
	$(document).click(function(e){
	    if ($(e.target).closest(".select-price1").length) return;
		$('.select-price1').removeClass('active');
	    e.stopPropagation();
	});
	$('.select-price1__drop-item').click(function() {
		$(this).parent().prev().find('div').html($(this).html());
		$(this).parent().parent().removeClass('active');
	});

	$('[data-parallax]').parallax();
});







