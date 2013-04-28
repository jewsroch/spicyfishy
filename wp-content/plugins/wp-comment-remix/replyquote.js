function replyto(id, to) {
        jQuery("textarea[name='comment']").val("<a href='#comment-" + id + "'>@" + to + "</a> - " + jQuery("textarea[name='comment']").val()).focus(); 
    }
function quote(id, by, text) {
    jQuery("textarea[name='comment']").val("<blockquote><a href='#comment-" + id + "'>" + originallypostedby + by + "</a><br/>" + text + "</blockquote>\r\n" + jQuery("textarea[name='comment']").val()).focus(); 
}