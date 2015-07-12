/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @since 		25th Nov, 2014
 * @license 	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 *
 */

// Ajax post section
$('.bbs_bottom_btn').unbind('click').bind('click', function(e) { // Post submit onclick event
    e.preventDefault();
    var categoryId = $(this).data('action-id'); // Get category ID attribute
    // Get umeditor content HTML
    if (categoryId == 1) {
        umContent = um1.getContent();
    } else if (categoryId == 2) {
        umContent = um2.getContent();
    } else if (categoryId == 3) {
        umContent = um3.getContent();
    }

    // Ajax post data
    var formData = {
        title: $('input.bbs_bottom_title_' + categoryId).val(), // Get post title
        content: umContent, // Get post content
        category_id: categoryId, // Get post category
        _token: csrfToken, // CSRF token
    };

    // Process ajax request
    $.ajax({
        url: forumControllerPostNewAction, // the url where we want to POST
        type: "POST", // define the type of HTTP verb we want to use (POST for our form)
        data: formData, // our data object
    }).done(function(data) {

        // Here we will handle errors and validation messages
        if (!data.success) {

            // Handle errors
            if (data.errors) {
                if (data.errors.title) {
                    var title_error = data.errors.title; // This error exist
                } else {
                    var title_error = ""; // This error not exist
                }
                if (data.errors.content) {
                    var content_error = data.errors.content; // This error exist
                } else {
                    var content_error = ""; // This error not exist
                }
                $('.if_error_' + categoryId).html('<div class="callout-warning">' + title_error + content_error + '</div>'); // Add the actual error message under our input
            }

        } else { // Ajax success
            // Ajax prepend new post in current tab
            if (data.post_thumbnails) {
                $('ul.bbs_main_' + categoryId).prepend('<li class="bbs_main_boy"><a href="' + forumShowRoute + data.post_id + '" target="_blank">' + data.post_title + '</a><p>' + data.post_content + '</p><span class="bbs_main_look">' + data.post_comments + '</span><span class="bbs_main_time">' + data.post_created + '</span>' + data.post_thumbnails + '</li>');
            } else {
                $('ul.bbs_main_' + categoryId).prepend('<li class="bbs_main_boy"><a href="' + forumShowRoute + data.post_id + '" target="_blank">' + data.post_title + '</a><p>' + data.post_content + '</p><span class="bbs_main_look">' + data.post_comments + '</span><span class="bbs_main_time">' + data.post_created + '</span></li>');
            }
            // Flush old error messages
            if ($('.callout-warning')) {
                $('.callout-warning').remove();
            }
            // Handle success message
            $('#if_success').html('<div class="callout-warning">' + data.success_info + '</div>');
            // Scroll top after post success
            $('html, body').animate({
                scrollTop: 0
            }, 600);
            // Remove post title input tag value
            $('input.bbs_bottom_title_' + categoryId).val("");
            // Remove post editor content
            if (categoryId == 1) {
                umset = um1.setContent('');
            } else if (categoryId == 2) {
                umset = um2.setContent('');
            } else if (categoryId == 3) {
                umset = um3.setContent('');
            }
        }

    });
});

// Forum top tab control

// Tab
var tabContainers = $('div.tabs > div');
tabContainers.hide().filter(':first').show();

$('div.tabs ul.tabNavigation a').click(function() {
    tabContainers.hide();
    tabContainers.filter(this.hash).show();
    $('div.tabs ul.tabNavigation a').removeClass('active');
    $(this).addClass('active');
    return false;
}).filter(':first').click();

// jQuery Ajax Multi Pagination


// 1.
function getPaginationSelectedPage(url) {
    var chunks = url.split('?');
    var baseUrl = chunks[0];
    var querystr = chunks[1].split('&');
    var pg = 1;
    for (i in querystr) {
        var qs = querystr[i].split('=');
        if (qs[0] == 'page') {
            pg = qs[1];
            break;
        }
    }
    return pg;
}

// 2.
$('#first').on('click', '.lu_paging a', function(e) {
    e.preventDefault();
    var pg = getPaginationSelectedPage($(this).attr('href'));

    $.ajax({
        url: firstAjaxURL,
        data: {
            page: pg
        },
        success: function(data) {
            $('#first_inner').html(data);
        }
    });
});

$('#second').on('click', '.lu_paging a', function(e) {
    e.preventDefault();
    var pg = getPaginationSelectedPage($(this).attr('href'));

    $.ajax({
        url: secondAjaxURL,
        data: {
            page: pg
        },
        success: function(data) {
            $('#second_inner').html(data);
        }
    });
});

$('#third').on('click', '.lu_paging a', function(e) {
    e.preventDefault();
    var pg = getPaginationSelectedPage($(this).attr('href'));

    $.ajax({
        url: thirdAjaxURL,
        data: {
            page: pg
        },
        success: function(data) {
            $('#third_inner').html(data);
        }
    });
});

$(function() {
    // 3.
    $('#first_inner').load(firstAjaxURL + '?page=1');
    $('#second_inner').load(secondAjaxURL + '?page=1');
    $('#third_inner').load(thirdAjaxURL + '?page=1');
});