// By luxurioust use jQuery


// Ajax pagination

$(function() {
	$('#post-ajax').on('click', '.lu_paging a', function (e) {
		getPosts($(this).attr('href').split('page=')[1]);
		e.preventDefault();
	});
});

function getPosts(page) {
	$.ajax({
		url : '?page=' + page,
		dataType: 'json',
	}).done(function (data) {
		$('#post-ajax').html(data);
	}).fail(function () {
		alert('Posts could not be loaded.');
	});
}

// Leave comment for this post href jump

$(".smooth").click(function(){
	var href = $(this).attr("href");
	var pos = $(href).offset().top;
	$("html,body").animate({scrollTop: pos}, 800);
	return false;
});

// For reply comment button and textarea function

$('.reply_comment_form').click(function(e){
	e.stopPropagation();
});

$('.reply_comment').click(function(e){

	e.preventDefault();
	e.stopPropagation();
	// hide all span
	var $this = $(this).parent().parent().parent().find('.reply_comment_form');
	$(".reply_comment_form").not($this).hide();

	// here is what I want to do
	$this.fadeToggle(300);

});

$(document).click(function() {
	$('.reply_comment_form').fadeOut(400);
});

// For inner reply comment button and textarea function

$('.reply_inner_form').click(function(e){
	e.stopPropagation();
});

$('.reply_inner').click(function(e){

	e.preventDefault();
	e.stopPropagation();
	// hide all span
	var $this = $(this).parent().find('.reply_inner_form');
	$(".reply_inner_form").not($this).hide();

	// here is what I want to do
	$this.fadeToggle(300);

});

$(document).click(function() {
	$('.reply_inner_form').fadeOut(400);
});

// Instantiate editor
var um = UM.getEditor('create_comment_editor');

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

	// Fancybox

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
		$('.message-re p').each(function(i){
			var _i = i;
			$(this).find('img').each(function(){
				var alt = this.alt;

				// if (alt != ''){
				// 	$(this).after('<span class="caption">'+alt+'</span>');
				// }

				$(this).wrap('<a href="'+this.src+'" title="'+alt+'" class="fancybox" rel="gallery'+_i+'" />');
			});
		});
	};

	externalLinks(); // Delete or comment this line to disable opening external links in new window
	appendCaption(); // Delete or comment this line to disable caption

	$('.fancybox').fancybox({
		arrows : false // Disable fancybox previous and next links showing up
	});

	// Ajax comments section

	$('.g-replay').click(function(){ // Post submit onclick event

		// Ajax post data
		var formData = {
			content 	: um.getContent(), // Get post content
			_token 		: csrfToken, // CSRF token
			type : 'comments'
		};
		// Process ajax request
		$.ajax({
			url 	: forumControllerPostCommentAction, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData, // our data object
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if ( ! data.success) {

				// Handle errors
				if (data.errors.content) {
					$('.if_error').html('<div class="callout-warning">' + data.errors.content + '</div>'); // Add the actual error message under our input
				}

			} else { // Ajax success

				// Flush old error messages
				if($('.callout-warning')) {
					$('.callout-warning').remove();
				}
				// Handle suucess message
				$('#if_success').html('<div class="callout-warning">' + data.success_info + '</div>');
				// Scroll top after post success
				$('html, body').animate({ scrollTop: 0 }, 600);
				// Remove post editor content
				um.setContent('');
				// Ajax reload new post in current tab
				location.reload();
			}

		});
	});

	// Ajax replay section

	$('.reply_comment_submit').click(function(){ // Post submit onclick event
		var commentId		= $(this).data('comment-id'); // Get comments ID
		var replyId			= $(this).data('reply-id'); // Get reply ID
		var dataNickname	= $(this).data('nickname'); // Get post user nickname
		var replyContent	= $('textarea#reply_id_' + commentId).val() // Get reply conetnt

		// Ajax post data
		var formData = {
			comments_id 	: commentId,
			reply_id 		: replyId,
			reply_content 	: replyContent, // Get post content
			_token 			: csrfToken, // CSRF token
			data_nickname 	: dataNickname
		};
		// Process ajax request
		$.ajax({
			url 	: forumControllerPostCommentAction, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData, // our data object
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if ( ! data.success) {
				// Handle errors
				alert(data.error_info);
			} else { // Ajax success
				// Remove post editor content
				// Ajax reload
				location.reload();
			}
		});
	});

	// Ajax reply comments section (tiny different with ajax replay section)
	$('.submit').click(function(){ // Post submit onclick event
		var commentId		= $(this).data('comment-id'); // Get comments ID
		var replyId			= $(this).data('reply-id'); // Get reply ID
		var dataNickname	= $(this).data('nickname'); // Get post user nickname
		var replyContent	= $('textarea#reply_id_' + replyId).val() // Get reply conetnt

		// Ajax post data
		var formData = {
			comments_id 	: commentId, // Get comment ID
			reply_id 		: replyId, // Get reply ID
			reply_content 	: replyContent, // Get post content
			_token 			: csrfToken, // CSRF token
			data_nickname 	: dataNickname
		};
		// Process ajax request
		$.ajax({
			url 	: forumControllerPostCommentAction, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData, // our data object
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if ( ! data.success) {
				// Handle errors
				alert(data.error_info);
			} else { // Ajax success
				// Remove post editor content
				// Ajax reload
				location.reload();
			}
		});
	});

	var myMap = {
		'\\[:1]': 'emoji_1.png',  // need to escape those for regex
		'\\[:2]': 'emoji_2.png',
		'\\[:3]': 'emoji_3.png',

		'\\[:10]' : 'emoji_10.png',
		'\\[:11]' : 'emoji_11.png',
		'\\[:12]' : 'emoji_12.png',
		'\\[:13]' : 'emoji_13.png',
		'\\[:14]' : 'emoji_14.png',
		'\\[:15]' : 'emoji_15.png',
		'\\[:16]' : 'emoji_16.png',
		'\\[:17]' : 'emoji_17.png',
		'\\[:18]' : 'emoji_18.png',
		'\\[:19]' : 'emoji_19.png',
		'\\[:20]' : 'emoji_20.png',
		'\\[:21]' : 'emoji_21.png',
		'\\[:22]' : 'emoji_22.png',
		'\\[:23]' : 'emoji_23.png',
		'\\[:24]' : 'emoji_24.png',
		'\\[:25]' : 'emoji_25.png',
		'\\[:26]' : 'emoji_26.png',
		'\\[:27]' : 'emoji_27.png',
		'\\[:28]' : 'emoji_28.png',
		'\\[:29]' : 'emoji_29.png',
		'\\[:30]' : 'emoji_30.png',
		'\\[:31]' : 'emoji_31.png',
		'\\[:32]' : 'emoji_32.png',
		'\\[:33]' : 'emoji_33.png',
		'\\[:34]' : 'emoji_34.png',
		'\\[:35]' : 'emoji_35.png',
		'\\[:36]' : 'emoji_36.png',
		'\\[:37]' : 'emoji_37.png',
		'\\[:38]' : 'emoji_38.png',
		'\\[:39]' : 'emoji_39.png',
		'\\[:40]' : 'emoji_40.png',
		'\\[:41]' : 'emoji_41.png',
		'\\[:42]' : 'emoji_42.png',
		'\\[:43]' : 'emoji_43.png',
		'\\[:44]' : 'emoji_44.png',
		'\\[:45]' : 'emoji_45.png',
		'\\[:46]' : 'emoji_46.png',
		'\\[:47]' : 'emoji_47.png',
		'\\[:48]' : 'emoji_48.png',
		'\\[:49]' : 'emoji_49.png',
		'\\[:50]' : 'emoji_50.png',
		'\\[:51]' : 'emoji_51.png',
		'\\[:52]' : 'emoji_52.png',
		'\\[:53]' : 'emoji_53.png',
		'\\[:54]' : 'emoji_54.png',
		'\\[:55]' : 'emoji_55.png',
		'\\[:56]' : 'emoji_56.png',
		'\\[:57]' : 'emoji_57.png',
		'\\[:58]' : 'emoji_58.png',
		'\\[:59]' : 'emoji_59.png',
		'\\[:60]' : 'emoji_60.png',
		'\\[:61]' : 'emoji_61.png',
		'\\[:62]' : 'emoji_62.png',
		'\\[:63]' : 'emoji_63.png',
		'\\[:64]' : 'emoji_64.png',
		'\\[:65]' : 'emoji_65.png',
		'\\[:66]' : 'emoji_66.png',
		'\\[:67]' : 'emoji_67.png',
		'\\[:68]' : 'emoji_68.png',
		'\\[:69]' : 'emoji_69.png',
		'\\[:70]' : 'emoji_70.png',
		'\\[:71]' : 'emoji_71.png',
		'\\[:72]' : 'emoji_72.png',
		'\\[:73]' : 'emoji_73.png',
		'\\[:74]' : 'emoji_74.png',
		'\\[:75]' : 'emoji_75.png',
		'\\[:76]' : 'emoji_76.png',
		'\\[:77]' : 'emoji_77.png',
		'\\[:78]' : 'emoji_78.png',
		'\\[:79]' : 'emoji_79.png',
		'\\[:80]' : 'emoji_80.png',
		'\\[:81]' : 'emoji_81.png',
		'\\[:82]' : 'emoji_82.png',
		'\\[:83]' : 'emoji_83.png',
		'\\[:84]' : 'emoji_84.png',
		'\\[:85]' : 'emoji_85.png',
		'\\[:86]' : 'emoji_86.png',
		'\\[:87]' : 'emoji_87.png',
		'\\[:88]' : 'emoji_88.png',
		'\\[:89]' : 'emoji_89.png',
		'\\[:90]' : 'emoji_90.png',
		'\\[:91]' : 'emoji_91.png',
		'\\[:92]' : 'emoji_92.png',
		'\\[:93]' : 'emoji_93.png',
		'\\[:94]' : 'emoji_94.png',
		'\\[:95]' : 'emoji_95.png',
		'\\[:96]' : 'emoji_96.png',
		'\\[:97]' : 'emoji_97.png',
		'\\[:98]' : 'emoji_98.png',
		'\\[:99]' : 'emoji_99.png',
	};

	$('.lu_content_main').find('p').html(function( _, html ) {
		for(var face in myMap) {
			html = html.replace( new RegExp( face, 'g' ), '<img width="22" src="' + homeuri + '/assets/images/emoji/' + myMap[ face ] + '"/>' );
		}
		return html;
	});

})(jQuery);