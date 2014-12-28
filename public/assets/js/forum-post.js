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

})(jQuery);