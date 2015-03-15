// Stylio JS Functions


//SLOWING VIDEO PLAYBACK RATE
var video = document.getElementById("video");
if (!Modernizr.touch) {
		if (video) {video.playbackRate = 0.70;}
	} else {
		if (video) {jQuery("#video-container,.video-overlay").remove()}
}

// THE FANCYBOX VIDEO INITALISATION
jQuery(document).ready(function() {
	"use strict";

	// $(".fancybox").fancybox({'hideOnContentClick': true, 'helpers' : {
	// 				'overlay' : { 'locked' : false }
	// 			}});
	// jQuery(".header-video-link.video").click(function() {
	// 		$.fancybox({
	// 			'padding'		: 0,
	// 			'autoScale'		: false,
	// 			'transitionIn'	: 'none',
	// 			'transitionOut'	: 'none',
	// 			'titleShow'     : false,
	// 			'helpers' : {
	// 				'overlay' : { 'locked' : false }
	// 			},
	// 			'width'			: 720,
	// 			'height'		: 520,
	// 			'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/') + '&autoplay=1',
	// 			//'href'			: this.href.replace(new RegExp("([0-9])","i"),'moogaloop.swf?clip_id=$1'), //for vimeo, don't forget to change the video link to Vimeo too!
	// 			'type'			: 'swf',
	// 			'swf'			: {
	// 			'wmode'				: 'transparent',
	// 			'allowfullscreen'	: 'true'
	// 			}
	// 		});

	// 	return false;
	// });

	//OWL CAROUSEL

	// $("#owl-demo").owlCarousel({
	// 	autoPlay: 3000, //Set AutoPlay to 3 seconds
	// 	items : 3,
	// 	pagination: true,
	// 	itemsDesktop : [1199,3],
	// 	itemsDesktopSmall : [979,3]
	// });

	//PRICE HOVERS
	 $("#pricing .package")
	  .mouseenter(function() {
		$('#pricing .package').removeClass('inverted');
	  $(this).addClass('inverted');
	  })
	  .mouseleave(function() {
		 $('#pricing .package').removeClass('inverted');
	  });

	//PHONE SLIDER
	//var slideDefaultOffset = $('.slide-default-offset').css('height').replace('px', '');
	var slideDefaultOffset = 0;
	//var slideOffset = $('.slide-offset').css('height').replace('px', '');
	var slideOffset = 0;
	var levels = Array();
	var margins = Array();
	var i = 0;

	margins.push('N/A');

		$('.phone-menu a.level').each( function() {
			if ($(this).data('level') != 0){
				margins.push(i);   //finding all available screenshot "levels" for further manipulation
			}
			levels.push($(this).data('level'));   //finding all available screenshot "levels" for further manipulation
			i += parseFloat(slideDefaultOffset);
		});
		$(margins).each(function(index, value) {
			var defaultMargin = Math.abs(value) * -1;
			$(".left-col .level" + index).css('margin-top', defaultMargin);
		});

	$( ".phone-menu .level" ).click(function() {

	  // VISUAL EFFECTS
	  $('.phone-menu .dotted').removeClass('resp-show');

	  if ($(window).width() < 640){
		$(this).parent().find('.text').fadeIn( 200, function() {});
		$('.phone-menu .active .text').fadeOut( 200, function() { });

	  } else {
		$('.phone-menu .active .text').slideUp( 200, function() { });
		$(this).parent().find('.text').slideDown( 500, function() {});
	  }

	  $('.phone-menu li').removeClass('active');
	  $(this).parent().find('.dotted').addClass('resp-show');
	  $(this).parent().addClass('active');

		// MARGIN/HEIGHT CALCULATIONS
		var level = $(this).parent().find('a').data('level');
		//RESTORE DEFAULT STATE
		$(margins).each(function(index, value) {
			var defaultMargin = Math.abs(value) * -1;
			$(".left-col .level" + index).css('margin-top', defaultMargin);
		});

		$(levels).each(function(index, value) {
			if (index > level) {
				var curMargin = Math.abs(margins[index]) * -1;
				var newMargin = parseFloat(curMargin) - slideOffset;
				$(".left-col .level" + index).css('margin-top', newMargin);
			}
		});

		return false;
	});
});

	//INIT LOCAL SCROLL
	jQuery('.navbar').localScroll({hash:true, offset: {top: -100},duration: 800});

	//INIT SKROLLR
	//DISABLE PARALLAX FOR HANDHELDS
	if (!Modernizr.touch) {
		var s = skrollr.init({
			mobileDeceleration: 1,
			edgeStrategy: 'set',
			forceHeight: false,
			smoothScrolling: true,
			smoothScrollingDuration: 300,
				easing: {
					WTF: Math.random,
					inverted: function(p) {
						return 1-p;
					}
				}
			});
	}

	//QUICK ARRAY SORTER
	jQuery.fn.reverse = [].reverse;

	//HIGHLIGHT MENU ITEMS & ANIMATIONS ON SCROLL
	//ENABLED ONLY FOR DESKTOPS
	if (!Modernizr.touch){
		jQuery('.appear').appear();
		jQuery('.animate').appear({force_process: true});
		jQuery('.animate_phone').appear();

		jQuery(".appear").on("appear", function(data) {
				var id = $(this).attr("id");
				jQuery('.navbar .nav li').removeClass('active');
				jQuery(".navbar .nav a[href='#" + id + "']").parent().addClass("active");
			});

		jQuery(".animate").on("appear", function(data) {
			jQuery(this).addClass("animate_start");
		});

		var delayed = 800;
		jQuery(".animate_phone").on("appear", function(data) {
			var item = jQuery(this);

			jQuery(item).reverse().each(function( index ) {
					  setTimeout(function() {
						item.addClass('animate_start');
					  }, delayed);

					  delayed += 200;
			});
		});
	} else {
		//REMOVING ANIMATIONS FOR MOBILE DEVICES
		jQuery('.animate').removeClass('animate animate_aft animate_afr animate_afl animate_afb animate_afc animate_phone animate_aftp');
		jQuery('.animate_phone').removeClass('animate_phone animate_aftp');
	}

	//COUNT NUMBERS ON APPEAR, RUNS ONCE ONLOAD
	var runOnce = true;
		jQuery(".stats").on("appear", function(data) {
			var counters = {};
			var i = 0;
			if (runOnce){
				jQuery('.number').each(function(){
					counters[this.id] = $(this).html();
					i++;
				});
				jQuery.each( counters, function( i, val ) {
					//console.log(i + ' - ' +val);
					jQuery({countNum: 0}).animate({countNum: val}, {
					  duration: 3000,
					  easing:'linear',
					  step: function() {
						jQuery('#'+i).text(Math.ceil(this.countNum));
					  }
					});
				});
				runOnce = false;
			}
	});

$('.nav-tabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

// $('.ios-app-btn').hover(
// 	function() {
// 		var $this = $(this); // caching $(this)
// 		$this.data('<i class="fa fa-apple"></i> App Store', $this.text());
// 		$this.text(appstore);
// 	},
// 	function() {
// 		var $this = $(this); // caching $(this)
// 		$this.html("<i class='fa fa-apple'></i> App Store");
// 	}
// );