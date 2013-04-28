var wpcr_open_rows = Array();
var wpcr_current_post = 0;
jQuery(function($) {    
    jQuery('a.post-com-count').each(function() {
        var post_id = jQuery(this).attr('href').replace(/.*&?p=([^&]*).*/,function($0,$1){return $1;});
        jQuery(this).click(function() {
            if (wpcr_open_rows.indexOf(post_id) < 0) {
                wpcr_get_comment_rows(post_id);
            } else {
                wpcr_close_comment_rows(post_id);
            }
            return false;
        });
    });
    
    function wpcr_get_comment_rows(post_id) {
        wpcr_current_post = post_id;
        wpcr_open_rows.push(post_id);
        jQuery('#post-'+post_id).after('<tr id="post_row_' + post_id + '"><td colspan="100%" style="text-align: center">Loading...</td></tr>');
        jQuery('#post_row_'+post_id+' td').load(COMMENTREMIX_URL+'ajax_comments.php?p='+post_id,{},wpcr_open_row);
    }
    
    function wpcr_open_row() {
        jQuery('#post_row_'+wpcr_current_post+' td table tr .action-links').each(function() {
            if (jQuery(this).html() != 'Actions') { //Don't add the links to the header
                wpcr_add_actions(this);
            } else {
                jQuery(this).css("text-align","left");
            }     
        });
        jQuery('#post_row_'+wpcr_current_post).show("slow");
        $('#post_row_'+wpcr_current_post+' td table #the-comment-list').wpList( { alt: '', dimAfter: dimAfter, delAfter: delAfter, addColor: 'none' } );
    }
    
    function wpcr_close_comment_rows(post_id) {
        jQuery('#post_row_'+post_id).slideUp("slow").remove();
        for (var i in wpcr_open_rows) {
            if (wpcr_open_rows[i] == post_id) {
                wpcr_open_rows.splice(i,1);
            }
        }
    }
    
    //Taken from edit-comments.js
    //Edited to work for the edit.php page with ajax comments table
    var dimAfter = function( r, settings ) {
        $('li span.comment-count').each( function() {
            var a = $(this);
            var n = parseInt(a.html(),10);
            n = n + ( $('#' + settings.element).is('.' + settings.dimClass) ? 1 : -1 );
            if ( n < 0 ) { n = 0; }
            a.html( n.toString() );
            $('#awaiting-mod')[ 0 == n ? 'addClass' : 'removeClass' ]('count-0');
        });
        $('#post-'+wpcr_current_post+' td div .post-com-count span.comment-count').each( function() {
            var a = $(this);
            var n = parseInt(a.html(),10);
            var t = parseInt(a.parent().attr('title'), 10);
            if ( $('#' + settings.element).is('.unapproved') ) { // we unapproved a formerly approved comment
                n = n - 1;
                t = t + 1;
            } else { // we approved a formerly unapproved comment
                n = n + 1;
                t = t - 1;
            }
            if ( n < 0 ) { n = 0; }
            if ( t < 0 ) { t = 0; }
            if ( t >= 0 ) { a.parent().attr('title', adminCommentsL10n.pending.replace( /%i%/, t.toString() ) ); }
            if ( 0 === t ) { a.parents('strong:first').replaceWith( a.parents('strong:first').html() ); }
            a.html( n.toString() );
        });
    }

    var delAfter = function( r, settings ) {
        $('li span.comment-count').each( function() {
            var a = $(this);
            var n = parseInt(a.html(),10);
            if ( $('#' + settings.element).is('.unapproved') ) { // we deleted a formerly unapproved comment
                n = n - 1;
            } else if ( $(settings.target).parents( 'span.unapprove' ).size() ) { // we "deleted" an approved comment from the approved list by clicking "Unapprove"
                n = n + 1;
            }
            if ( n < 0 ) { n = 0; }
            a.html( n.toString() );
            $('#awaiting-mod')[ 0 == n ? 'addClass' : 'removeClass' ]('count-0');
        });
        $('#post-'+wpcr_current_post+' td div .post-com-count span.comment-count').each( function() {
            var a = $(this);
            if ( $('#' + settings.element).is('.unapproved') ) { // we deleted a formerly unapproved comment
                var t = parseInt(a.parent().attr('title'), 10);
                if ( t < 1 ) { return; }
                t = t - 1;
                a.parent().attr('title', adminCommentsL10n.pending.replace( /%i%/, t.toString() ) );
                if ( 0 === t ) { a.parents('strong:first').replaceWith( a.parents('strong:first').html() ); }
                return;
            }
            var n = parseInt(a.html(),10) - 1;
            a.html( n.toString() );
        });

        if ( theExtraList.size() == 0 || theExtraList.children().size() == 0 ) {
            return;
        }

        theList.get(0).wpList.add( theExtraList.children(':eq(0)').remove().clone() );
        $('#get-extra-comments').submit();
    }
});