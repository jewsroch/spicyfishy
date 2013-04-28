jQuery(function($) {
    if (unreplied > 0) {
        var offset = jQuery('#awaiting-mod').parent().parent().offset();
        if (offset) {
            jQuery('#awaiting-mod').parent().parent().parent().append("<li id='comments-no-reply-container'><a href='edit-comments.php?comment_status=unreplied'><span id='awaiting-mod' class='comments-no-reply'><span class='count-"+unreplied+"'>"+unreplied+"</span></span></a></li>");
            jQuery('#comments-no-reply-container').css('left',offset.left-3);
        }
    }
});