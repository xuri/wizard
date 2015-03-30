// By luxurioust use jQuery


// Ajax pagination

$(function() {
    $('#post-ajax').on('click', '.lu_paging a', function(e) {
        getPosts($(this).attr('href').split('page=')[1]);
        e.preventDefault();
    });
});

function getPosts(page) {
    $.ajax({
        url: '?page=' + page,
        dataType: 'json',
    }).done(function(data) {
        $('#post-ajax').html(data);
    }).fail(function() {
        alert('Posts could not be loaded.');
    });
}

// Leave comment for this post href jump

$(".smooth").click(function() {
    var href = $(this).attr("href");
    var pos = $(href).offset().top;
    $("html,body").animate({
        scrollTop: pos
    }, 800);
    return false;
});

// For reply comment button and textarea function

$('.reply_comment_form').click(function(e) {
    e.stopPropagation();
});

$('.reply_comment').click(function(e) {

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

$('.reply_inner_form').click(function(e) {
    e.stopPropagation();
});

$('.reply_inner').click(function(e) {

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

(function($) {
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

    // Fancybox

    // Open external links in new window
    var externalLinks = function() {
        var host = location.host;

        $('body').on('click', 'a', function(e) {
            var href = this.href,
                link = href.replace(/https?:\/\/([^\/]+)(.*)/, '$1');

            if (link != '' && link != host && !$(this).hasClass('fancybox')) {
                window.open(href);
                e.preventDefault();
            }
        });
    };

    // Append caption after pictures
    var appendCaption = function() {
        $('.message-re p').each(function(i) {
            var _i = i;
            $(this).find('img').each(function() {
                var alt = this.alt;

                // if (alt != ''){
                // 	$(this).after('<span class="caption">'+alt+'</span>');
                // }

                $(this).wrap('<a href="' + this.src + '" title="' + alt + '" class="fancybox" rel="gallery' + _i + '" />');
            });
        });
    };

    externalLinks(); // Delete or comment this line to disable opening external links in new window
    appendCaption(); // Delete or comment this line to disable caption

    $('.fancybox').fancybox({
        arrows: false // Disable fancybox previous and next links showing up
    });

    // Ajax comments section

    $('.g-replay').click(function() { // Post submit onclick event

        // Ajax post data
        var formData = {
            content: um.getContent(), // Get post content
            _token: csrfToken, // CSRF token
            type: 'comments'
        };
        // Process ajax request
        $.ajax({
            url: forumControllerPostCommentAction, // the url where we want to POST
            type: "POST", // define the type of HTTP verb we want to use (POST for our form)
            data: formData, // our data object
        }).done(function(data) {

            // Here we will handle errors and validation messages
            if (!data.success) {

                // Handle errors
                if (data.errors.content) {
                    $('.if_error').html('<div class="callout-warning">' + data.errors.content + '</div>'); // Add the actual error message under our input
                }

            } else { // Ajax success

                // Flush old error messages
                if ($('.callout-warning')) {
                    $('.callout-warning').remove();
                }
                // Handle suucess message
                $('#if_success').html('<div class="callout-warning">' + data.success_info + '</div>');
                // Scroll top after post success
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
                // Remove post editor content
                um.setContent('');
                // Ajax reload new post in current tab
                location.reload();
            }

        });
    });

    // Ajax replay section

    $('.reply_comment_submit').click(function() { // Post submit onclick event
        var commentId = $(this).data('comment-id'); // Get comments ID
        var replyId = $(this).data('reply-id'); // Get reply ID
        var dataNickname = $(this).data('nickname'); // Get post user nickname
        var replyContent = $('textarea#reply_id_' + commentId).val() // Get reply conetnt

        // Ajax post data
        var formData = {
            comments_id: commentId,
            reply_id: replyId,
            reply_content: replyContent, // Get post content
            _token: csrfToken, // CSRF token
            data_nickname: dataNickname
        };
        // Process ajax request
        $.ajax({
            url: forumControllerPostCommentAction, // the url where we want to POST
            type: "POST", // define the type of HTTP verb we want to use (POST for our form)
            data: formData, // our data object
        }).done(function(data) {

            // Here we will handle errors and validation messages
            if (!data.success) {
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
    $('.submit').click(function() { // Post submit onclick event
        var commentId = $(this).data('comment-id'); // Get comments ID
        var replyId = $(this).data('reply-id'); // Get reply ID
        var dataNickname = $(this).data('nickname'); // Get post user nickname
        var replyContent = $('textarea#reply_id_' + replyId).val() // Get reply conetnt

        // Ajax post data
        var formData = {
            comments_id: commentId, // Get comment ID
            reply_id: replyId, // Get reply ID
            reply_content: replyContent, // Get post content
            _token: csrfToken, // CSRF token
            data_nickname: dataNickname
        };
        // Process ajax request
        $.ajax({
            url: forumControllerPostCommentAction, // the url where we want to POST
            type: "POST", // define the type of HTTP verb we want to use (POST for our form)
            data: formData, // our data object
        }).done(function(data) {

            // Here we will handle errors and validation messages
            if (!data.success) {
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
        '\\[:1]': 'emoji_1.png', // need to escape those for regex
        '\\[:2]': 'emoji_2.png',
        '\\[:3]': 'emoji_3.png',
        '\\[:4]': 'emoji_4.png',
        '\\[:5]': 'emoji_5.png',
        '\\[:6]': 'emoji_6.png',
        '\\[:7]': 'emoji_7.png',
        '\\[:8]': 'emoji_8.png',
        '\\[:9]': 'emoji_9.png',
        '\\[:10]': 'emoji_10.png',
        '\\[:11]': 'emoji_11.png',
        '\\[:12]': 'emoji_12.png',
        '\\[:13]': 'emoji_13.png',
        '\\[:14]': 'emoji_14.png',
        '\\[:15]': 'emoji_15.png',
        '\\[:16]': 'emoji_16.png',
        '\\[:17]': 'emoji_17.png',
        '\\[:18]': 'emoji_18.png',
        '\\[:19]': 'emoji_19.png',
        '\\[:20]': 'emoji_20.png',
        '\\[:21]': 'emoji_21.png',
        '\\[:22]': 'emoji_22.png',
        '\\[:23]': 'emoji_23.png',
        '\\[:24]': 'emoji_24.png',
        '\\[:25]': 'emoji_25.png',
        '\\[:26]': 'emoji_26.png',
        '\\[:27]': 'emoji_27.png',
        '\\[:28]': 'emoji_28.png',
        '\\[:29]': 'emoji_29.png',
        '\\[:30]': 'emoji_30.png',
        '\\[:31]': 'emoji_31.png',
        '\\[:32]': 'emoji_32.png',
        '\\[:33]': 'emoji_33.png',
        '\\[:34]': 'emoji_34.png',
        '\\[:35]': 'emoji_35.png',
        '\\[:36]': 'emoji_36.png',
        '\\[:37]': 'emoji_37.png',
        '\\[:38]': 'emoji_38.png',
        '\\[:39]': 'emoji_39.png',
        '\\[:40]': 'emoji_40.png',
        '\\[:41]': 'emoji_41.png',
        '\\[:42]': 'emoji_42.png',
        '\\[:43]': 'emoji_43.png',
        '\\[:44]': 'emoji_44.png',
        '\\[:45]': 'emoji_45.png',
        '\\[:46]': 'emoji_46.png',
        '\\[:47]': 'emoji_47.png',
        '\\[:48]': 'emoji_48.png',
        '\\[:49]': 'emoji_49.png',
        '\\[:50]': 'emoji_50.png',
        '\\[:51]': 'emoji_51.png',
        '\\[:52]': 'emoji_52.png',
        '\\[:53]': 'emoji_53.png',
        '\\[:54]': 'emoji_54.png',
        '\\[:55]': 'emoji_55.png',
        '\\[:56]': 'emoji_56.png',
        '\\[:57]': 'emoji_57.png',
        '\\[:58]': 'emoji_58.png',
        '\\[:59]': 'emoji_59.png',
        '\\[:60]': 'emoji_60.png',
        '\\[:61]': 'emoji_61.png',
        '\\[:62]': 'emoji_62.png',
        '\\[:63]': 'emoji_63.png',
        '\\[:64]': 'emoji_64.png',
        '\\[:65]': 'emoji_65.png',
        '\\[:66]': 'emoji_66.png',
        '\\[:67]': 'emoji_67.png',
        '\\[:68]': 'emoji_68.png',
        '\\[:69]': 'emoji_69.png',
        '\\[:70]': 'emoji_70.png',
        '\\[:71]': 'emoji_71.png',
        '\\[:72]': 'emoji_72.png',
        '\\[:73]': 'emoji_73.png',
        '\\[:74]': 'emoji_74.png',
        '\\[:75]': 'emoji_75.png',
        '\\[:76]': 'emoji_76.png',
        '\\[:77]': 'emoji_77.png',
        '\\[:78]': 'emoji_78.png',
        '\\[:79]': 'emoji_79.png',
        '\\[:80]': 'emoji_80.png',
        '\\[:81]': 'emoji_81.png',
        '\\[:82]': 'emoji_82.png',
        '\\[:83]': 'emoji_83.png',
        '\\[:84]': 'emoji_84.png',
        '\\[:85]': 'emoji_85.png',
        '\\[:86]': 'emoji_86.png',
        '\\[:87]': 'emoji_87.png',
        '\\[:88]': 'emoji_88.png',
        '\\[:89]': 'emoji_89.png',
        '\\[:90]': 'emoji_90.png',
        '\\[:91]': 'emoji_91.png',
        '\\[:92]': 'emoji_92.png',
        '\\[:93]': 'emoji_93.png',
        '\\[:94]': 'emoji_94.png',
        '\\[:95]': 'emoji_95.png',
        '\\[:96]': 'emoji_96.png',
        '\\[:97]': 'emoji_97.png',
        '\\[:98]': 'emoji_98.png',
        '\\[:99]': 'emoji_99.png',
        '\\[:100]': 'emoji_100.png',
        '\\[:101]': 'emoji_101.png',
        '\\[:102]': 'emoji_102.png',
        '\\[:103]': 'emoji_103.png',
        '\\[:104]': 'emoji_104.png',
        '\\[:105]': 'emoji_105.png',
        '\\[:106]': 'emoji_106.png',
        '\\[:107]': 'emoji_107.png',
        '\\[:108]': 'emoji_108.png',
        '\\[:109]': 'emoji_109.png',
        '\\[:110]': 'emoji_110.png',
        '\\[:111]': 'emoji_111.png',
        '\\[:112]': 'emoji_112.png',
        '\\[:113]': 'emoji_113.png',
        '\\[:114]': 'emoji_114.png',
        '\\[:115]': 'emoji_115.png',
        '\\[:116]': 'emoji_116.png',
        '\\[:117]': 'emoji_117.png',
        '\\[:118]': 'emoji_118.png',
        '\\[:119]': 'emoji_119.png',
        '\\[:120]': 'emoji_120.png',
        '\\[:121]': 'emoji_121.png',
        '\\[:122]': 'emoji_122.png',
        '\\[:123]': 'emoji_123.png',
        '\\[:124]': 'emoji_124.png',
        '\\[:125]': 'emoji_125.png',
        '\\[:126]': 'emoji_126.png',
        '\\[:127]': 'emoji_127.png',
        '\\[:128]': 'emoji_128.png',
        '\\[:129]': 'emoji_129.png',
        '\\[:130]': 'emoji_130.png',
        '\\[:131]': 'emoji_131.png',
        '\\[:132]': 'emoji_132.png',
        '\\[:133]': 'emoji_133.png',
        '\\[:134]': 'emoji_134.png',
        '\\[:135]': 'emoji_135.png',
        '\\[:136]': 'emoji_136.png',
        '\\[:137]': 'emoji_137.png',
        '\\[:138]': 'emoji_138.png',
        '\\[:139]': 'emoji_139.png',
        '\\[:140]': 'emoji_140.png',
        '\\[:141]': 'emoji_141.png',
        '\\[:142]': 'emoji_142.png',
        '\\[:143]': 'emoji_143.png',
        '\\[:144]': 'emoji_144.png',
        '\\[:145]': 'emoji_145.png',
        '\\[:146]': 'emoji_146.png',
        '\\[:147]': 'emoji_147.png',
        '\\[:148]': 'emoji_148.png',
        '\\[:149]': 'emoji_149.png',
        '\\[:150]': 'emoji_150.png',
        '\\[:151]': 'emoji_151.png',
        '\\[:152]': 'emoji_152.png',
        '\\[:153]': 'emoji_153.png',
        '\\[:154]': 'emoji_154.png',
        '\\[:155]': 'emoji_155.png',
        '\\[:156]': 'emoji_156.png',
        '\\[:157]': 'emoji_157.png',
        '\\[:158]': 'emoji_158.png',
        '\\[:159]': 'emoji_159.png',
        '\\[:160]': 'emoji_160.png',
        '\\[:161]': 'emoji_161.png',
        '\\[:162]': 'emoji_162.png',
        '\\[:163]': 'emoji_163.png',
        '\\[:164]': 'emoji_164.png',
        '\\[:165]': 'emoji_165.png',
        '\\[:166]': 'emoji_166.png',
        '\\[:167]': 'emoji_167.png',
        '\\[:168]': 'emoji_168.png',
        '\\[:169]': 'emoji_169.png',
        '\\[:170]': 'emoji_170.png',
        '\\[:171]': 'emoji_171.png',
        '\\[:172]': 'emoji_172.png',
        '\\[:173]': 'emoji_173.png',
        '\\[:174]': 'emoji_174.png',
        '\\[:175]': 'emoji_175.png',
        '\\[:176]': 'emoji_176.png',
        '\\[:177]': 'emoji_177.png',
        '\\[:178]': 'emoji_178.png',
        '\\[:179]': 'emoji_179.png',
        '\\[:180]': 'emoji_180.png',
        '\\[:181]': 'emoji_181.png',
        '\\[:182]': 'emoji_182.png',
        '\\[:183]': 'emoji_183.png',
        '\\[:184]': 'emoji_184.png',
        '\\[:185]': 'emoji_185.png',
        '\\[:186]': 'emoji_186.png',
        '\\[:187]': 'emoji_187.png',
        '\\[:188]': 'emoji_188.png',
        '\\[:189]': 'emoji_189.png',
        '\\[:190]': 'emoji_190.png',
        '\\[:191]': 'emoji_191.png',
        '\\[:192]': 'emoji_192.png',
        '\\[:193]': 'emoji_193.png',
        '\\[:194]': 'emoji_194.png',
        '\\[:195]': 'emoji_195.png',
        '\\[:196]': 'emoji_196.png',
    };

    $('.lu_content_main').find('p').html(function(_, html) {
        for (var face in myMap) {
            html = html.replace(new RegExp(face, 'g'), '<img width="22" src="' + homeuri + '/assets/images/emoji/' + myMap[face] + '"/>');
        }
        return html;
    });

})(jQuery);