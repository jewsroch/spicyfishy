jQuery(function($) {
    jQuery('.action-links').each(function() {
        var html = jQuery(this).html();
        if (html != 'Actions') { //Don't add the links to the header
            wpcr_add_actions(this);
        } else {
            jQuery(this).css("text-align","left");
        }
    });
    
    function wpcr_add_actions(actions_div) {
        //The credit goes to Ozh for the regex code to find the author, post_id, and comment_id values
        var author = jQuery(actions_div).parent().find("td.comment p.comment-author strong a").html().replace(/(\<.*\>)? ?(.*)/,function($0,$1,$2){return $2;});
        var post_id = jQuery(actions_div).find("span.delete").html().replace(/.*&?p=([^&]*).*/,function($0,$1){return $1;});
        var comment_id = jQuery(actions_div).find("span.delete").html().replace(/.*&?c=([^&]*).*/,function($0,$1){return $1;});
        html = '<br/><span class="wpcr_edit"><a title="Edit this comment" href="comment.php?action=editcomment&c=' + comment_id + '">Edit</a> | </span>';
        html += '<span class="wpcr_reply" id="' + comment_id + '-' + post_id + '-' + author + '-reply"><a title="Reply to this comment" href="edit-comment.php">Reply &raquo;</a> | </span>';
        html += '<span class="wpcr_quote" id="' + comment_id + '-' + post_id + '-' + author + '-quote"><a title="Quote this comment" href="edit-comment.php">Quote &raquo;</a></span><br/>';
        html += '<span class="wpcr_markreplied" id="replied-' + comment_id + '" style="white-space:nowrap"><a title="Mark this post as replied" href="edit-comment.php">Mark Replied</a></span>';
        if (window.location.href.match(/edit.php/) = null) {
            html += '<span class="wpcr_viewall"><a title="View all comments for this post" href="edit.php?p=' + post_id + '">View All</a></span>';
        }
        html += '</td></tr></table>';
        jQuery(actions_div).append(html).css("text-align","left");
    }
    
    jQuery('span.wpcr_reply').each(function () {
        jQuery(this).click(function () {
            var span_id = jQuery(this).attr('id');
            span_id = span_id.split('-');
            var comment_id = span_id[0];
            var post_id = span_id[1];
            var author = span_id[2];
            wpcr_pop_reply(this, comment_id, post_id, '<a href="#comment-' + comment_id + '">@' + author + '</a> -\r\n');
            return false;
        });
    });
    
    jQuery('span.wpcr_quote').each(function () {
        jQuery(this).click(function () {
            var span_id = jQuery(this).attr('id');
            span_id = span_id.split('-');
            var comment_id = span_id[0];
            var post_id = span_id[1];
            var author = span_id[2];
            var orig_comment = jQuery('tr#comment-'+comment_id+' .comment p').next().html();
            wpcr_pop_reply(this, comment_id, post_id, '<blockquote><a href="#comment-' + comment_id + '">Originally Posted By ' + author + '</a><br/>' + orig_comment + '</blockquote>\r\n');
            return false;
        });
    });
    
    jQuery('span.wpcr_markreplied').each(function () {
            var span_id = jQuery(this).attr('id');
            span_id = span_id.split('-');
            var comment_id = span_id[1];
            var value = span_id[2];
        jQuery(this).find('a').click(function () {
            wpcr_mark_replied(comment_id,value);
            return false;
        });
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
        postVars['_wpcr_nonce'] = jQuery('#_wpcr_nonce').val();
        postVars['comment'] = jQuery('#quickreplytext').val();
        postVars['wpcr_comment_tag'] = jQuery('#wpcr_comment_tag').val();
        postVars['parent_id'] = pop_comment_id;
        postVars['post_id'] = pop_post_id;
        jQuery.post(
            COMMENTREMIX_URL + 'new_comment.php',
            postVars,
            function (xml) { wpcr_get_reply(xml); }
        );
        jQuery('#wpcr_message').html('<?php
 Reply... Please Be Patient','comment_remix');?>');
    }
        
    // Get XML response
    function wpcr_get_reply(xml) {
        if (typeof(xml) == 'string') { //We know WP returned an error
            //Check if there's an error, and parse it if there is
            var error = '';
            if (xml.match(/\?xml/) == null) {
                error = xml.match(/<p>(.*)<\/p>/)[1];
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
                window.location.href='<?=$_SERVER['PHP_SELF'];?>';
                return;
            }
            wpcr_kill_popup(); //Hide the popup
            jQuery('#wpcr_message').html(''); //Clear any error

            <?php if ($_GET['comment_status'] == 'unreplied') { ?>
            //alert('Unreplied');
            //Kill the replied-to comment, WP-style
            jQuery('#comment-'+pop_comment_id).fadeOut("slow");
            <?php } else if ($_GET['comment_status'] != 'moderated') { ?>
            //Fire up the latest comment, WP-style
            jQuery('#new-comment').load('<?=COMMENTREMIX_URL;?>comment_row.php?id=' + id,
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
            <?php } else { ?>
            window.location.href='<?=$_SERVER['PHP_SELF'];?>';
            <?php } ?>
        }
    }
    
    // Save the reply
    function wpcr_mark_replied(id, value) {
        //Get all variables for posting
        var postVars = {};
        postVars['_wpcr_nonce'] = jQuery('#_wpcr_nonce').val();
        postVars['comment_id'] = id;
        postVars['replied_value'] = value;
        jQuery.post(
            COMMENTREMIX_URL + 'mark_replied.php',
            postVars,
            function (xml) { wpcr_markedReplied(xml); }
        );
    }
    
    function wpcr_get_reply(xml) {
        if (typeof(xml) == 'string') { //We know WP returned an error
            //Check if there's an error, and parse it if there is
            var error = '';
            if (xml.match(/\?xml/) == null) {
                error = xml.match(/<p>(.*)<\/p>/)[1];
                alert(error);
                return;
            }
        } else { //No error, xml returned
            var new_value = 0;
            var id = jQuery('#comment_id',xml);
            var value = jQuery('#replied_value',xml);
            if (value == '0') {
                new_value = 1;
            } else {
                new_value = 0;
            }
            jQuery('#replied-'+id+' a').html('Mark Unreplied').attr('title','Mark this comment as unreplied')
                .click(function () {
                    wpcr_mark_replied(id,new_value);
                });;
        }
    }
});