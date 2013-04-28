var pop_comment_id = 0;
var pop_post_id = 0;
    
jQuery(function($) {
    jQuery('.action-links').each(function() {
        if (jQuery(this).html() != 'Actions') { //Don't add the links to the header
            wpcr_add_actions(this);
        } else {
            jQuery(this).css("text-align","left");
        }
    });
    
    jQuery('#full_page_shadow').click(function () {
        wpcr_kill_popup();
    });
    jQuery('#quickreplycancel').click(function () {
        wpcr_kill_popup();
    });
    jQuery('#quickreplysubmit').click(function () {
        wpcr_save_reply();
    });
});

function link_replies(reply_div) {
    var span_id = jQuery(reply_div).attr('id');
    span_id = span_id.split('-');
    var comment_id = span_id[0];
    var post_id = span_id[1];
    var author = span_id[2];
    jQuery(reply_div).find('a').click(function () {
        wpcr_pop_reply(reply_div, comment_id, post_id, '<a href="#comment-' + comment_id + '">@' + author + '</a> -\r\n');
        return false;
    });
}

function link_quotes(quote_div) {
    var span_id = jQuery(quote_div).attr('id');
    span_id = span_id.split('-');
    var comment_id = span_id[0];
    var post_id = span_id[1];
    var author = span_id[2];
    var orig_comment = jQuery('tr#comment-'+comment_id+' .comment p').next().html();
    jQuery(quote_div).find('a').click(function () {
        wpcr_pop_reply(quote_div, comment_id, post_id, '<blockquote><a href="#comment-' + comment_id + '">' + originallypostedby + author + '</a><br/>' + orig_comment + '</blockquote>\r\n');
        return false;
    });
}

function link_ignore(ignore_div) {
    var span_id = jQuery(ignore_div).attr('id');
    span_id = span_id.split('-');
    var comment_id = span_id[1];
    var value = jQuery(ignore_div).attr('value');
    jQuery(ignore_div).find('a').click(function () {
        wpcr_ignore(comment_id,value);
        return false;
    });
}

function wpcr_kill_popup() {
    jQuery('#full_page_shadow').css('display','none');
    jQuery('#quickreplypopup').css('display','none');
    jQuery('#quickreplytext').html('');
}

function wpcr_pop_reply(caller, comment_id, post_id, text) {
    pop_comment_id = comment_id;
    pop_post_id = post_id;

    jQuery('#quickreplytext').val(text);
    jQuery('#wpcr_comment_tag').val('');
    jQuery('#quickreplypopup').css('left',(screen.width/2)-355);
    jQuery('#quickreplypopup').css('top',(screen.height/2)-350);
    
    //Show reply popup
    jQuery('#full_page_shadow').css('display','block');
    jQuery('#quickreplypopup').css('display','block');
    
    jQuery('#quickreplytext').focus();
    jQuery('#the_comment').html(jQuery('tr#comment-'+comment_id+' .comment').html());
}

// Save the reply
function wpcr_save_reply() {
    //Get all variables for posting
    var postVars = {};
    postVars['comment'] = jQuery('#quickreplytext').val();
    postVars['wpcr_comment_tag'] = jQuery('#wpcr_comment_tag').val();
    postVars['parent_id'] = pop_comment_id;
    postVars['post_id'] = pop_post_id;
    jQuery.post(
        reply_nonce_url,
        postVars,
        function (xml) { wpcr_get_reply(xml); }
    );
    jQuery('#wpcr_message').html(localized_posting_reply_message);
}
    
// Get XML response
function wpcr_get_reply(xml) {
    if (typeof(xml) == 'string') { //We know WP returned an error
        //Check if there's an error, and parse it if there is
        var error = '';
        if (xml.match(/\?xml/) == null) {
            if (xml.match(/<p>(.*)<\/p>/) != null) {
                error = xml.match(/<p>(.*)<\/p>/)[1];
            } else {
                error = xml.match(/(.*)/)[1]; //Match everything
            }
            jQuery('#wpcr_message').html(error);
            jQuery('#quickreplytext').focus();
            return;
        }
    } else { //No error, xml returned
        //If we get here, we can parse the xml for the comment id
        var id = '';
        id = jQuery('id',xml).text(); // the id of the reply we posted
        if (id == '') {//Was there an error we didn't catch?
            jQuery('#wpcr_message').html("Refreshing...");
            window.location.href = window.location.href;
            return;
        }
        wpcr_kill_popup(); //Hide the popup
        jQuery('#wpcr_message').html(''); //Clear any error

        q = new getQueryString();
        if (window.location.href.indexOf('edit-comments.php') >= 0) {
            var cs = '';
            if (q['comment_status']) {
                cs = q['comment_status'].toLowerCase();
            }
            switch (cs) {
                case "unreplied":
                    //Kill the replied-to comment, WP-style
                    jQuery('#comment-'+pop_comment_id).fadeOut("slow");
                    $oldnum = jQuery('#inneed').html().match(/.*\((.*)\).*/);
                    $newnum = $oldnum[1]-1;
                    jQuery('#inneed').html(localized_in_need + ' (' + ($newnum) + ')');
                    if ($newnum > 0) {
                        jQuery('#awaiting-mod.comments-no-reply span').html($newnum);
                    } else {
                        jQuery('#comments-no-reply-container').css('display','none');
                    }
                    break;
                case "moderated":
                    window.location.href = window.location.href;
                    break;
                default:
                    //Fire up the latest comment, WP-style
                    jQuery('#new-comment').load(COMMENTREMIX_URL+'comment_row.php?id=' + id,
                        {},
                        function() {
                            jQuery('#the-comment-list').prepend(jQuery('#new-comment').html());
                            jQuery('#comment-'+id)
                                .animate( { backgroundColor:"#CFEBF7" }, 600 )
                                .animate( { backgroundColor:"#ff8" }, 300 )
                                .animate( { backgroundColor:"transparent" }, 300 );
                            jQuery('#comment-' + id + ' .action-links').each(function () {
                                wpcr_add_actions(this);
                            });
                        }
                    );
                    break;
            }
        } else if (window.location.href.indexOf('edit.php') >= 0) {
            //Fire up the latest comment, WP-style
            jQuery('#new-comment').load(COMMENTREMIX_URL+'comment_row.php?id=' + id + '&nocb=1',
                {},
                function() {
                    jQuery('#the-comment-list').prepend(jQuery('#new-comment').html());
                    jQuery('#comment-'+id)
                        .animate( { backgroundColor:"#CFEBF7" }, 600 )
                        .animate( { backgroundColor:"#ff8" }, 300 )
                        .animate( { backgroundColor:"transparent" }, 300 );
                    jQuery('#comment-' + id + ' .action-links').each(function () {
                        wpcr_add_actions(this);
                    });
                }
            );
        } else {
            window.location.href = window.location.href; 
        }
        wpcr_add_count();
    }
}

function wpcr_add_count() {
    jQuery('#post-'+pop_post_id+' td div .post-com-count span.comment-count').each( function() {
        var a = jQuery(this);
        var n = parseInt(a.html(),10);
        n = n + 1;
        if ( n < 0 ) { n = 0; }
        a.html( n.toString() );
    });
}

// Save the reply
function wpcr_ignore(id) {
    //Get all variables for posting
    var postVars = {};
    postVars['comment_id'] = id;
    postVars['current_ignore_value'] = jQuery('#ignore-'+id).attr('ignorevalue');
    jQuery.post(
        unignore_nonce_url,
        postVars,
        function (xml) { wpcr_marked_replied(xml); }
    );
}

function wpcr_marked_replied(xml) {
    if (typeof(xml) == 'string') { //We know WP returned an error
        //Check if there's an error, and parse it if there is
        var error = '';
        if (xml.match(/\?xml/) == null) {
            error = xml.match(/<p>(.*)<\/p>/)[1];
            alert(error);
            return;
        }
    } else { //No error, xml returned
        var id = jQuery('comment_id',xml).text();
        var value = jQuery('newignorevalue',xml).text();
        if (window.location.href.match(/comment_status=unreplied/)) {
            jQuery('#comment-'+id).fadeOut("slow");
            $oldnum = jQuery('#inneed').html().match(/.*\((.*)\).*/);
            $newnum = $oldnum[1]-1;
            jQuery('#inneed').html(localized_in_need + ' (' + ($newnum) + ')');
            if ($newnum > 0) {
                jQuery('#awaiting-mod.comments-no-reply span').html($newnum);
            } else {
                jQuery('#comments-no-reply-container').css('display','none');
            }
        } else {
            if (value == '0') { //the new ignore value is 1, so set up the link to reflect that
                jQuery('#ignore-'+id).attr('ignorevalue','0').find('a').html(localized_ignore_text).attr('title',localized_ignore_title);
            } else {
                jQuery('#ignore-'+id).attr('ignorevalue','1').find('a').html(localized_unignore_text).attr('title',localized_unignore_title);
            }
        }
    }
}

function wpcr_add_actions(actions_div) {
    //The credit goes to Ozh for the regex code to find the author, post_id, and comment_id values
    var author = jQuery(actions_div).parent().find("td.comment p.comment-author strong a").html().replace(/(\<.*\>)? ?(.*)/,function($0,$1,$2){return $2;});
    var post_id = jQuery(actions_div).find("span.delete").html().replace(/.*&?p=([^&]*).*/,function($0,$1){return $1;});
    var comment_id = jQuery(actions_div).find("span.delete").html().replace(/.*&?c=([^&]*).*/,function($0,$1){return $1;});
    html = '<br/><span class="wpcr_edit"><a title="'+localized_edit_title+'" href="comment.php?action=editcomment&c=' + comment_id + '">'+localized_edit+'</a> | </span>';
    html += '<span class="wpcr_reply" id="' + comment_id + '-' + post_id + '-' + author + '-reply"><a title="'+localized_reply_title+'" href="edit-comment.php">'+localized_reply+' &raquo;</a> | </span>';
    html += '<span class="wpcr_quote" id="' + comment_id + '-' + post_id + '-' + author + '-quote"><a title="'+localized_quote_title+'" href="edit-comment.php">'+localized_quote+' &raquo;</a></span><br/>';
    html += '<span class="wpcr_ignore" id="ignore-' + comment_id;
    if (ignore.indexOf(comment_id) >= 0) {
        html += '" style="white-space:nowrap" ignorevalue="1"><a href="edit-comment.php" title="'+localized_unignore_title+'">'+localized_unignore_text;
    } else {
        html += '" style="white-space:nowrap" ignorevalue="0"><a href="edit-comment.php" title="'+localized_ignore_title+'">'+localized_ignore_text;
    }
    html += '</a>';
    if (window.location.href.match(/edit.php/) == null) {
        html += ' | </span><span class="wpcr_viewall"><a title="'+localized_view_all_title+'" href="edit.php?p=' + post_id + '">'+localized_view_all+'</a>';
    }
    html += '</span></td></tr></table>';
    
    jQuery(actions_div).append(html).css("text-align","left");
    
    jQuery(actions_div).find('span.wpcr_reply').each(function () {
        link_replies(this);
    });
    
    jQuery(actions_div).find('span.wpcr_quote').each(function () {
        link_quotes(this);
    });
    
    jQuery(actions_div).find('span.wpcr_ignore').each(function () {
        link_ignore(this);
    });
}