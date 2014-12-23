// By luxurioust use jQuery

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

                if (alt != ''){
                    $(this).after('<span class="caption">'+alt+'</span>');
                }

                $(this).wrap('<a href="'+this.src+'" title="'+alt+'" class="fancybox" rel="gallery'+_i+'" />');
            });
        });
    };

    externalLinks(); // Delete or comment this line to disable opening external links in new window
    appendCaption(); // Delete or comment this line to disable caption

    $('.fancybox').fancybox();
})(jQuery);