
// Instantiate editor
var um = UM.getEditor('cat1_editor');
var um = UM.getEditor('cat2_editor');
var um = UM.getEditor('cat3_editor');


(function($){
	// Tab
	var tabContainers = $('div.tabs > div');
	tabContainers.hide().filter(':first').show();

	$('div.tabs ul.tabNavigation a').click(function () {
		tabContainers.hide();
		tabContainers.filter(this.hash).show();
		$('div.tabs ul.tabNavigation a').removeClass('active');
		$(this).addClass('active');
		return false;
	}).filter(':first').click();

	// Open external links in new window
	var externalLinks = function(){
		var host = location.host;

		$('body').on('click', 'a', function(e){
			var href = this.href,
				link = href.replace(/https?:\/\/([^\/]+)(.*)/, '$1');

			if (link != '' && link != host && !$(this).hasClass('fancybox')){
				window.open(href);
				e.preventDefault();
			}
		});
	};

	// Append caption after pictures
	var appendCaption = function(){
		$('.bbs_main_boy p').each(function(i){
			var _i = i;
			$(this).find('img').each(function(){
				var alt = this.alt;

				if (alt != ''){
					$(this).after('<span class="caption">'+alt+'</span>');
				}

				$(this).wrap('<a href="'+this.src+'" title="'+alt+'" class="fancybox" rel="gallery'+_i+'" />');
			});
		});
	};

	externalLinks(); // Delete or comment this line to disable opening external links in new window
	appendCaption(); // Delete or comment this line to disable caption

	$('.fancybox').fancybox({
		arrows : false // Disable fancybox previous and next links showing up
	});
})(jQuery);