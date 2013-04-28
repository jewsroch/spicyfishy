<?php

/*
Plugin Name: WP Comment Remix
Plugin URI: http://pressography.com/plugins/wp-comment-remix/
Description: Adds much needed comment functionality to Wordpress
Author: Jason DeVelvis
Author URI: http://Pressography.com
Version: 1.4.4
*/ 

/* 
* The WP Comment Remix plugin code is released under the Creative Commons
* Attribution-ShareAlike 3.0 license. You are free to share
* and make derivatives of this software as long as you give
* credit to the original author and include the author's
* URL in your work. The full license can be found here:
* http://creativecommons.org/licenses/by-sa/3.0/
*/

/*
Credits
A big thank you goes to Ozh, who wrote the Absolute Comments plugin: 
http://planetozh.com/blog/my-projects/absolute-comments-manager-instant-reply/

I used some of the ideas (and a bit of code) from that plugin for the edit-comments 
page links & ajax reply capabilities. Make sure you check out Ozh's site for some
phenominal WP plugins!

The filterCommentsNumber, stripTrackbacks and stripComments functions in the Trackback sorter code are
from Ronald Huereca's post on WeblogToolsCollection.com:
http://weblogtoolscollection.com/archives/2008/03/08/managing-trackbacks-and-pingbacks-in-your-wordpress-theme/
*/

/*
Includes
- wpcrwidgets.php: Required for widget functionality
*/
require_once('wpcrwidgets.php');

/*
Constants
- PLUGIN_URL: Url to the plugins directory
- PLUGIN_PATH: Physical path to the plugins directory
- COMMENTREMIX_URL: URL to this plugin
- COMMENTREMIX_PATH: Physical path to this plugin
*/

if (!defined('PLUGIN_URL'))
    define('PLUGIN_URL', get_option('siteurl').'/wp-content/plugins/');
if (!defined('PLUGIN_PATH'))
    define('PLUGIN_PATH', ABSPATH.'wp-content/plugins/');
define('COMMENTREMIX_URL', PLUGIN_URL . dirname(plugin_basename(__FILE__)).'/');
define('COMMENTREMIX_PATH', PLUGIN_PATH . dirname(plugin_basename(__FILE__)).'/');
    
/*
* Globals
* - $wpcr_unreplied_comments - saves unreplied comments for caching purposes
*/
$wpcr_unreplied_comments = '';

/**
* @desc Initialize the plugin
*/
function wpcr_init() {
    if ( !is_blog_installed())
    return;

    //Initialize localization
    //Strip ABSPATH from the commentremix path, so we end up with /wp-content/plugins/wpcommentremix (or whatever the directory is)
    load_plugin_textdomain('comment_remix', str_replace(ABSPATH,'/',COMMENTREMIX_PATH));
    
    //Initialize Options
    if(!$options = get_option('wpcr_options')) $options = array('replyto'=>'1','replytotext'=>__('Reply','comment_remix'),'quote'=>'1',
    'quotetext'=>__('Quote','comment_remix'),'sep'=>' - ','showtags'=>'0','striptrackbacks'=>'0','trackbacksafter'=>'0','sortby'=>'date',
    'sortorder'=>'asc','showfirst'=>'reply','tagsep'=>', ','taglabel'=>__('Comment Tags:','comment_remix').' ','showunreplied'=>'1',
    'commentlinks'=>'1','originallypostedby'=>__('Originally Posted By','comment_remix').' ','tagheadersep'=>' | ','maxtags'=>'5',
    'tagheaderlabel'=>'Comment Tags: ', 'showtagheader'=>'0');
    update_option('wpcr_options',$options);

    //Enqueue jQuery here, since we use it for both public and admin pages
    wp_enqueue_script('jquery');
    if ( strpos($_SERVER['REQUEST_URI'],'edit.php') >= 0) {
        wp_enqueue_script('admin-comments');
        wp_enqueue_script('wp-ajax-response');
        wp_enqueue_script('wp-lists');
    }

    //Hack to add comment_tags to the taxonomies
    if ($options['showtags'] == '1') {
        global $wp_taxonomies;
        $wp_taxonomies['comment_tag'] = (object) array('name' => 'comment_tag', 'object_type' => 'comment', 'hierarchical' => false);
    }
    /**
    * Wordpress Hooks
    */
    if (!is_admin()) {
         //Hook to add the necessary jQuery to the header
        add_filter('wp_head','wpcr_add_head');

        //Hook to add the comment tag box to the comment form
        add_action('comment_form','wpcr_add_comment_tag_form');

        //Hook into the comment post function so we can save our tags
        add_action('comment_post', 'wpcr_comment_post');

        //Trackback Sorting Filters
        add_filter('comments_array', 'wpcr_fix_comments', 1);
        add_filter('get_comments_number', 'wpcr_filter_comments_number');        
        
        // add comment-tag as a possible permalink endpoint
        global $wp_rewrite;
        $wp_rewrite->add_endpoint("comment-tag", EP_ALL);
        $wp_rewrite->flush_rules();
    } else {
        add_filter('admin_head', 'wpcr_admin_head');
        add_action('admin_menu', 'wpcr_add_options_subpanel');

        //Add the Unreplied Status Filter
        add_filter('comment_status_links','wpcr_comment_status_link');

        //Add the "Ignore" button, and change the comments, if necessary
        add_action('manage_comments_nav','wpcr_manage_comments_nav');
        
        //Check for the ignore button submission, since we can't check for it in the manage_comments_nav action
        wpcr_check_for_ignore();

        //Insert the quick reply popup into the footer, if we're on the comments page
        if ((strpos($_SERVER['REQUEST_URI'],'edit-comments.php') > 0 || strpos($_SERVER['REQUEST_URI'],'edit.php') > 0) && !strpos($_SERVER['REQUEST_URI'],'edit.php?page=') > 0) {
            add_action('admin_footer','wpcr_add_quick_reply_popup');
        }
        
        //Adds the tags input to the edit comment area
        add_action('submitcomment_box','wpcr_submitcomment_box');
        //Update the comment tags when the comment is edited
        if ($options['showtags'] == '1')
            add_action('edit_comment', 'wpcr_comment_post');
            
        //Hack to let edit-comments.js work without errors
        add_action('restrict_manage_posts','wpcr_restrict_manage_posts');
    }
    //Hook to add the reply-to and quote links in comments - hook to this regardless 
    //of where we are, the is_admin is inside the function
    add_filter('get_comment_text', 'wpcr_add_reply_quote_tags');
}

function wpcr_check_for_ignore() {
    global $user_ID;

    if ($_POST['wpcr_ignoreit'])
        if ($_POST['delete_comments']) {            
            $options = get_option('wpcr_options');
            
            if ($options['ignored_comments_'.$user_ID])
                $ignored = explode(',',$options['ignored_comments_'.$user_ID]);
            else
                $ignored = array();
                
            foreach ($_POST['delete_comments'] as $ignore_comment) {
                //Ignore each item here, if it's not already in the ignore list
                if (!in_array($ignore_comment,$ignored))
                    $ignored[] = $ignore_comment;
            }
            
            $options['ignored_comments_'.$user_ID] = join(',',$ignored);
            update_option('wpcr_options',$options);
        }
}

function wpcr_restrict_manage_posts() {
    echo "<span id='the-extra-comment-list'></span>";
}

function wpcr_submitcomment_box() {
    global $comment;
    echo '<script type="text/javascript">
    jQuery(function($) {
        jQuery("#post-body").append(\'<div id="tagsdiv" class="stuffbox"><h3>Tags</h3><div class="inside"><input type="text" name="wpcr_comment_tag" value="';
    
    $results = wp_get_object_terms($comment->comment_ID,'comment_tag');
    
    if (!empty($results)) {
        foreach ($results as $row) {
            if ($tags)
                $tags .= ', ';
            $tags .= $row->name;
        }
    }
    
    echo $tags .'" style="width: 97%;"></div></div>\');
    });
    </script>';
}

/**
* @desc Adds the quick reply popup into the admin footer
*/
function wpcr_add_quick_reply_popup() {
    $options = get_option('wpcr_options');
    require_once('quickreply.php');
}

/**
* @desc Adds the "Ignore" button and filters the comments, if necessary
* @param $comment_status string Status of the displayed comments
*/
function wpcr_manage_comments_nav($comment_status){
    if ($comment_status == 'unreplied')
        wpcr_set_unreplied_comments();
    echo '<input class="button-secondary" type="submit" name="wpcr_ignoreit" value="' . __('Ignore','comment_remix') . '"/>';
}

/**
* @desc Gets all unreplied comments and puts them in the $comments global var
*/
function wpcr_set_unreplied_comments() {
    global $comments, $extra_comments, $start, $total, $page, $page_links;
    $_comments = wpcr_get_unreplied_comments();

    $comments = array_slice($_comments, $start, 20);
    $extra_comments = array_slice($_comments, $start+20, 5); //Extra comments in the regular WP comments area consists of 5 rows, so we make it 5 rows
    
    $total = count($comments) + count($extra_comments);
    
    $page_links = paginate_links( array(
        'base' => add_query_arg( 'apage', '%#%' ),
        'format' => '',
        'total' => ceil($total / 20),
        'current' => $page
    ));
    //Remove the \r and \n from the page links, because they break the javascript, and they're not needed anyway
    $page_links = str_replace("\r",'',$page_links);
    $page_links = str_replace("\n",'',$page_links);
    //Hack to fix the top $page_links display for the new comment array, because there's no hook available
    echo "<script type='text/javascript'>jQuery('.tablenav-pages').html(\"$page_links\");</script>";
}

/**
* @desc Adds the Unreplied Status Link to the comments page
* @param $status_links array Array of status links, we'll add another status link to the array
*/
function wpcr_comment_status_link($status_links) {
    global $comment_status;
    $status = 'unreplied';
    if ( $status == $comment_status )
        $class = ' class="current"';

    $status_links[] = "<li><a id='inneed' href=\"edit-comments.php?comment_status=$status\"$class>" . sprintf(__('In Need of Reply','comment_remix') . ' (%s)',wpcr_count_comments_no_reply()) . '</a>';
    
    return $status_links;
}

/**
* @desc Add necessary jQuery to the head
*/
function wpcr_admin_head() {
    global $user_ID;
    $options = get_option("wpcr_options");
    $unreplied = 0;
    if (current_user_can('edit_posts')) {
        $unreplied = wpcr_count_comments_no_reply();
    ?>
    <link rel='stylesheet' href='<?=COMMENTREMIX_URL?>commentremix.css' type='text/css' />
    <style type='text/css'>
        .action-table tr td { padding: 0 10px 0 0; margin: 0; border: none;}
    </style>

    <?php
        if ((strpos($_SERVER['REQUEST_URI'],'edit-comments.php') || strpos($_SERVER['REQUEST_URI'],'edit.php')) && $options['commentlinks'] == '1') { 
        //Required vars for the commentlinks.js javascript
    ?>
    <script type="text/javascript">
        var localized_edit = '<?=__('Edit','comment_remix');?>';
        var localized_reply = '<?=__('Reply','comment_remix');?>';
        var localized_quote = '<?=__('Quote','comment_remix');?>';
        var localized_edit_title = '<?=__('Edit this comment','comment_remix');?>';
        var localized_reply_title = '<?=__('Reply to this comment','comment_remix');?>';
        var localized_quote_title = '<?=__('Quote & reply to this comment','comment_remix');?>';
        var originallypostedby = '<?=$options['originallypostedby'];?>';
        var localized_unignore_text = '<?=__('Unignore','comment_remix');?>';
        var localized_ignore_text = '<?=__('Ignore','comment_remix');?>';
        var localized_unignore_title = '<?=__('Mark this post as in need of a reply','comment_remix');?>';
        var localized_ignore_title = '<?=__('Ignore this post for replies','comment_remix');?>';
        var localized_view_all = '<?=__('View All','comment_remix');?>';
        var localized_view_all_title = '<?=__('View all comments for this post','comment_remix');?>';
        var localized_posting_reply_message = '<?=__('Posting Reply... Please Be Patient','comment_remix');?>';
        var localized_in_need = '<?=__('In Need of Reply','comment_remix');?>';
        
        var unignore_nonce_url = '<?=wp_nonce_url(COMMENTREMIX_URL.'mark_replied.php','wpcr_ignore');?>';
        var reply_nonce_url = '<?=wp_nonce_url(COMMENTREMIX_URL.'new_comment.php','wpcr_reply');?>';

        var COMMENTREMIX_URL = '<?=COMMENTREMIX_URL;?>';
        var ignore = new Array('<?=join("','",explode(",",$options['ignored_comments_'.$user_ID]));?>');
    </script>
    <script type='text/javascript' src='<?=COMMENTREMIX_URL;?>querystring.js'></script>     
    <script type='text/javascript' src='<?=COMMENTREMIX_URL;?>array_functions.js'></script>
    <script type='text/javascript' src='<?=COMMENTREMIX_URL;?>commentlinks.js'></script>
    <?php  if (!$_GET['p']) { ?>
    <script type='text/javascript' src='<?=COMMENTREMIX_URL;?>postpageajax.js'></script>
    <?php } 
        }
    if ($unreplied > 0 && $options['showunreplied'] == '1') { ?>
        <script type='text/javascript'>var unreplied = <?=$unreplied;?>;</script>
        <script type='text/javascript' src='<?=COMMENTREMIX_URL;?>unreplied.js'></script>
    <?php }
    }
}

/**
* @desc Gets the unreplied comments, and saves them in a global variable for later use, so we don't have to query them multiple times
*/
function wpcr_get_unreplied_comments() {
    global $wpcr_unreplied_comments;
    
    if (!is_array($wpcr_unreplied_comments)) {
        global $wpdb, $user_ID;
            
        $options = get_option('wpcr_options');
        $sql = "SELECT comment_ID, comment_post_ID, user_id FROM $wpdb->comments WHERE (comment_approved = '0' OR comment_approved = '1') AND comment_type NOT IN ('trackback','pingback') ";
        if (!empty($options['ignored_comments_'.$user_ID]))
            $sql .= 'AND comment_ID NOT IN (' . $options['ignored_comments_'.$user_ID] . ') ';
        $sql .= "ORDER BY comment_post_ID, comment_date_gmt DESC";
        
        $_comments = $wpdb->get_results($sql);
        
        $postid = 0;
        $replied = false;
        $unreplied_comment_ids = '';
        
        //Get the current User's ID
        get_currentuserinfo();
        
        foreach ($_comments as $comment) {
            if ($postid != $comment->comment_post_ID) {
                //Save the new post ID
                $postid = $comment->comment_post_ID;
                
                //Reset the replied variable
                $replied = false;
            }
            //Has this user replied to this post yet?
            if (!$replied) {
                //Make sure it's not this user's reply
                if ($comment->user_id != $user_ID) {
                    //Add the ID to the list of unreplied comments
                    $unreplied_comment_ids .= $comment->comment_ID . ', ';
                } else {
                    //Mark replied = true because this user has replied to the post
                    $replied = true;
                }
            }
        }
        $unreplied_comment_ids = substr($unreplied_comment_ids,0,-2);
        
        if ( $s ) {
            $s = $wpdb->escape($s);
            $_comments = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->comments WHERE
                (comment_author LIKE '%$s%' OR
                comment_author_email LIKE '%$s%' OR
                comment_author_url LIKE ('%$s%') OR
                comment_author_IP LIKE ('%$s%') OR
                comment_content LIKE ('%$s%') ) AND
                comment_ID IN ($unreplied_comment_ids)
                ORDER BY comment_date_gmt DESC");
        } else {
            if ($unreplied_comment_ids) {
                $_comments = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->comments USE INDEX (comment_date_gmt) WHERE comment_ID IN ($unreplied_comment_ids) ORDER BY comment_date_gmt DESC" );
            } else {
                $_comments = $wpdb->get_results("SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->comments WHERE comment_ID = -1" );
            }
        }
        $wpcr_unreplied_comments = $_comments;
    }
    
    return $wpcr_unreplied_comments;
}

/**
* @desc Returns the number of comments that have no replies
*/
function wpcr_count_comments_no_reply() {
    $_comments = wpcr_get_unreplied_comments();
    
    return count($_comments);
}

/**
* @desc Add necessary jQuery to the header area
*/
function wpcr_add_head() {
    $options = get_option("wpcr_options");

?>
<script type="text/javascript">
    var originallypostedby = "<?=$options['originallypostedby'];?>";
<?php
    if ($options['showtags'] == '1' && get_query_var('comment-tag')) {
?>
    jQuery(function($) {
        var href;
        jQuery('a').each(function() {
            href = jQuery(this).attr('href');
            if (href.substr(0,1) == '#') {
                if (jQuery(href).length == 0) {
                    jQuery(this).attr('href','<?=get_permalink()?>'+href);
                }
            }
        });
    });
<?php    
    }
?>
</script>
<script type='text/javascript' src='<?=COMMENTREMIX_URL;?>replyquote.js'></script>
<?php
}

/**
* @desc Adds the Reply To and Quote links to each comment
* @param $comment_text Text of the comment
* @return Text of the comment, plus the desired Reply To/Quote link(s)
*/
function wpcr_add_reply_quote_tags($comment_text) {
    $options = get_option("wpcr_options");
    $commentID = get_comment_id();
    if (!is_admin() && !strstr($_SERVER['PHP_SELF'],'ajax_comments.php')) {
        $appended=false;
        
        //Encode the author and text so we can put it in the JS
        $stripped_text = wpcr_encode_for_js($comment_text);
        $stripped_author = wpcr_encode_for_js(get_comment_author());
        
        $replyHTML = '';
        if ($options['replyto'] == '1') {
            $replyHTML .= '<div class="comment-remix-meta"><a href="#" class="replyto" onclick="replyto(\'' . $commentID . '\',\'' . $stripped_author . '\'); return false;">' . $options['replytotext'] . '</a> ';
            $appended = true;
        }
        if ($options['quote'] == '1') {
            if (!$appended)
                $replyHTML .= '<div class="comment-remix-meta">';
            else
                $replyHTML .= $options['sep'];
            
            $replyHTML .= '<a href="#" class="quote" onclick="quote(\'' . $commentID . '\',\'' . $stripped_author . '\',\'' . $stripped_text . '\'); return false;">' . $options['quotetext'] . '</a>';
            $appended = true;    
        }
        if ($appended)
            $replyHTML .= '</div>';
    }
    
    //Do tags, even if this is an admin page
    $tagHTML = '';
    if ($options['showtags'] == '1') {
        $results = wp_get_object_terms($commentID,'comment_tag');
        
        $page = $_SERVER['REQUEST_URI'];
        if (strstr($page,'?')) {
            $page = explode('?',$page);
            $querystring = '?'.$page[1];
            $page = $page[0];
        }            
        
        if (substr($page,-1) != '/')
            $page .= '/'; //Make sure there's a trailing /
        $page = preg_replace('/comment-tag\/?.*/i','',$page);
        
        if (!empty($results)) {
            foreach ($results as $row) {
                if ($tags)
                    $tags .= $options['tagsep'];
                if (!is_admin() && !strstr($_SERVER['PHP_SELF'],'ajax_comments.php'))
                    $tags .= "<a href='$page"."comment-tag/$row->slug$querystring'>$row->name</a>";
                else
                    $tags .= $row->name;
            }
            $tagHTML = "<div id='wpcr_tags'>Comment Tags: $tags</div>";
        }
    }
    
    switch (strtolower($options['showfirst'])) {
        Case 'tags':
            $comment_text .= $tagHTML . $replyHTML;
            break;
        default: //covers the 'reply' case, too
            $comment_text .= $replyHTML . $tagHTML;
            break;
    }
        
    return $comment_text;    
}

/**
* @desc Encodes any characters JS doesn't like and returns the result
* @param $text string Text to be encoded for javascript usage
*/
function wpcr_encode_for_js($text) {
    //Strip out new lines, because they break the javascript
    $text = str_replace("\r",'\r', $text);
    $text = str_replace("\n",'\n', $text);
    //Strip out 's and replace with \'
    $text = str_replace("'","\\'", $text);
    //Strip out "s and replace with \"
    $text = str_replace('"','\\"', $text);
    //Strip out [ and ] and replace with their HTML equivalent
    //They screw up the jQuery for some reason
    $text = str_replace('[','&#91;', $text);
    $text = str_replace(']','&#93;', $text);
    //Escape \s, so the WP auto-link feature doesn't break the Quote link
    $text = str_replace('/','\\/', $text);
    //Now fire htmlentities
    $text = htmlentities($text);
    
    //Return the result
    return $text;
}

/**
* @desc Adds the Comment Tag field to the comment form
*/
function wpcr_add_comment_tag_form() {
    $options = get_option("wpcr_options");
    if ($options['showtags'] == '1') {
?>
Tags: <input type="text" id="wpcr_comment_tag" name="wpcr_comment_tag"/><br/>
<small>Separate individual tags by commas</small>
<?php
    }
}

/**
* @desc Saves the comment tag entered by the user
* @param $commentID integer - ID of the comment we're saving tags for
*/
function wpcr_comment_post($commentID) {
    if ($_POST['wpcr_comment_tag']) {
        wpcr_set_comment_tags($commentID, $_POST['wpcr_comment_tag']);
    }
}

/**
* @desc Saves the tags as terms
* @param $comment_id integer ID of the comment that owns these tags
* @param $tags array|string Array or Comma delimited string of tags to save
* @param $append boolean True to not delete existing tags, just add to them, or False to replace the tags with the new tags. This will be false most of the time
*/
function wpcr_set_comment_tags( $comment_id = 0, $tags = '', $append = false ) {
    $comment_id = (int) $comment_id;

    if ( !$comment_id )
        return false;

    if ( empty($tags) )
        $tags = array();

    $tags = (is_array($tags)) ? $tags : explode( ',', trim($tags, " \n\t\r\0\x0B,") );
    
    wp_set_object_terms($comment_id, $tags, 'comment_tag', $append);
}

/**
* @desc Flush the rewrite rules
*/
function wpcr_flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
* @desc Add comment-tag as an allowed query var
*/
function wpcr_query_vars($public_query_vars) {
    $public_query_vars[] = 'comment-tag';
    return $public_query_vars;
}

/**
* @desc Adds the options subpanel
*/
function wpcr_add_options_subpanel() {
    add_options_page('Comment Remix', 'Comment Remix', 10, basename(__FILE__), 'wpcr_do_options_page');
}

/**
* @desc Add the WPCR options page
*/
function wpcr_do_options_page() {
    $options = get_option("wpcr_options");
    
    if ( isset($_POST['submit']) ) {
        check_admin_referer('wp-comment-remix-update-settings');
        if ($_POST['ignoreall']) {
            $unreplied = wpcr_get_unreplied_comments();
            if ($unreplied) {
                global $user_ID;
                
                if ($options['ignored_comments_'.$user_ID])
                    $ignored = explode(',',$options['ignored_comments_'.$user_ID]);
                else
                    $ignored = array();
                    
                foreach ($unreplied as $comment) {
                    //Ignore each item here, if it's not already in the ignore list
                    if (!in_array($comment->comment_ID,$ignored))
                        $ignored[] = $comment->comment_ID;
                }
                
                $options['ignored_comments_'.$user_ID] = join(',',$ignored);
                update_option('wpcr_options',$options);
            }
        } else {
            //Form was posted, save vars
            $options['replyto'] = $_POST['replyto'] == 'on' ? '1' : '0';
            $options['replytotext'] = $_POST['replytotext'];
            $options['quote'] = $_POST['quote'] == 'on' ? '1' : '0';
            $options['quotetext'] = $_POST['quotetext'];
            $options['originallypostedby'] = $_POST['originallypostedby'];
            $options['sep'] = $_POST['sep'];
            $options['maxtags'] = intval( $_POST['maxtags'] );
            $options['showtags'] = $_POST['showtags'] == 'on' ? '1' : '0';
            $options['showtagsheader'] = $_POST['showtagsheader'] == 'on' ? '1' : '0';
            $options['tagsep'] = $_POST['tagsep'];
            $options['tagheadersep'] = $_POST['tagheadersep'];
            $options['taglabel'] = $_POST['taglabel'];
            $options['tagheaderlabel'] = $_POST['tagheaderlabel'];
            $options['showfirst'] = $_POST['showfirst'];                                
            $options['striptrackbacks'] = $_POST['striptrackbacks'] == 'on' ? '1' : '0';
            $options['trackbacksafter'] = $_POST['trackbacksafter'] == 'on' ? '1' : '0';
            $options['sortby'] = $_POST['sortby'];
            $options['sortorder'] = $_POST['sortorder'];
            $options['showunreplied'] = $_POST['showunreplied'] == 'on' ? '1' : '0';
            $options['commentlinks'] = $_POST['commentlinks'] == 'on' ? '1' : '0';
            
            update_option('wpcr_options', $options);
        }
    }
?>
<div class="wrap">
    <form method="post">
        <h2><?=__('WP Comment Remix Options','comment_remix');?></h2>
        <?php wp_nonce_field('wp-comment-remix-update-settings'); ?>
        <h3><?=__('Ignore All Comments','comment_remix');?></h3>
        <p><?=__('Click this button to ignore all comments currently "In Need Of Reply"','comment_remix');?></p>
        <table class="form-table">
          <tbody>
            <tr valign="top">
                <td style="text-align: center;">
                  <input type="submit" value="Ignore All" name="ignoreall" />
                </td>
              </tr>
          </tbody>
        </table>
        <h3><?=__('Comment Meta','comment_remix');?></h3>
        <p><?=__('You can control whether or not the Reply and Quote links say, and whether or not they show up at all','comment_remix');?></p>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row"><?=__('Comment Replies','comment_remix');?></th>
                <td>
                  <label>
                    <input type="checkbox" name="replyto" <?=$options['replyto']=='1' ? 'checked="CHECKED"' : '';?> />
                    <?=__('Enable Reply To Link','comment_remix');?>
                  </label>
                </td>
              </tr>
              <tr valign="top">
                <th scope="row"><?=__('Replies Link Text','comment_remix');?></th>
                <td>
                  <input type="text" name="replytotext" value="<?php echo attribute_escape($options['replytotext']); ?>"><br/>
                  <?=__('Text for the "Reply To" link (can be an img tag, to make the quote link an image)','comment_remix');?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Comment Quoting','comment_remix');?></th>
                <td>
                  <label>
                    <input type="checkbox" name="quote" <?=$options['quote']=='1' ? 'checked="CHECKED"' : '';?> />
                    <?=__('Enable Quote Link','comment_remix');?>
                  </label>
                </td>
              </tr>
              <tr valign="top">
                <th scope="row"><?=__('Quoting Link Text','comment_remix');?></th>
                <td>
                  <input type="text" name="quotetext" value="<?php echo attribute_escape($options['quotetext']); ?>"><br/>
                  <?=__('Text for the "Quote" link (can be an img tag, to make the quote link an image)','comment_remix');?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Quoted Comment Prefix','comment_remix');?></th>
                <td>
                  <label>
                    <input type="textbox" name="originallypostedby" value="<?php echo attribute_escape($options['originallypostedby']); ?>" />
                  </label><br/>
                  <?=__('Text that goes just inside the blockquote tag, usually something like "Originally Posted By " ','comment_remix');?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Separator','comment_remix');?></th>
                <td>
                  <input type="text" name="sep" value="<?php echo attribute_escape($options['sep']); ?>"><br/>
                  <?=__('Separator between the Reply To and Quote links (if necessary - can be an img tag, to make the separator an image)','comment_remix');?>
              </td>
            </tr>
          </tbody>
        </table>
        <h3><?=__('Comment Tags','comment_remix');?></h3>
        <p><?=__('Comment tags add tag functionality to the comments on your blog, so you and your users can search through them more easily. This is helpful not only for blogs that get a lot of comments, but also smaller blogs, because it adds to SEO benefits and enables you to add comment tag clouds to your blog.','comment_remix');?></p>
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tags','comment_remix');?></th>
                <td>
                  <label>
                    <input type="checkbox" name="showtags" <?=$options['showtags']=='1' ? 'checked="CHECKED"' : '';?> />
                    <?=__('Enable Comment Tags','comment_remix');?>
                  </label><br/>
                  <?=__('Check the box above to enable comment tags','comment_remix');?>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tag Label','comment_remix');?></th>
                <td>
                  <input type="text" name="taglabel" value="<?php echo attribute_escape($options['taglabel']); ?>"><br/>
                  <?=__('Text before the tag links (can be an img tag)','comment_remix');?>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tag Separator','comment_remix');?></th>
                <td>
                  <input type="text" name="tagsep" value="<?php echo attribute_escape($options['tagsep']); ?>"><br/>
                  <?=__('Separator between the each comment tag (if necessary - can be an img tag, to make the separator an image)','comment_remix');?>
              </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Which To Show First?','comment_remix');?></th>
                <td><p>
                  <label>
                    <input type="radio" value="reply" name="showfirst" <?=$options['showfirst'] == 'reply' ? 'checked="CHECKED"' : '' ?>/>
                    <?=__('Reply To and/or Quote Links','comment_remix');?>
                  </label><br/>
                  <label>
                    <input type="radio" value="tags" name="showfirst" <?=$options['showfirst'] == 'tags' ? 'checked="CHECKED"' : '' ?>/>
                    <?=__('Comment Tags','comment_remix');?>
                  </label>
                  <br/>
                  <?=__('If you\'re using both Comment Meta and Comment Tags, they will be displayed on 2 lines at the bottom of each comment, you can change which shows on the first line','comment_remix');?>
                </p>
              </td>
            </tr>
        </tbody>
    </table>
    <h3><?=__('Comment Tag Header','comment_remix');?></h3>
    <p><?=__('The comment tag header shows the top tags for the displayed comments, and allows your readers to quickly filter by a tag. The comment tag header will only be displayed if comment tags are enabled, as well','comment_remix');?></p>
    <table class="form-table">
        <tbody>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tag Header','comment_remix');?></th>
                <td>
                  <label>
                    <input type="checkbox" name="showtagsheader" <?=$options['showtagsheader']=='1' ? 'checked="CHECKED"' : '';?> />
                    <?=__('Enable Comment Tag Header, Above Comments','comment_remix');?>
                  </label><br/>
                  <?=__('Displays the top 10 comment tags above the comment area, for easy filtering','comment_remix');?>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Maximum Number Displayed','comment_remix');?></th>
                <td>
                  <input type="text" name="maxtags" value="<?php echo intval($options['maxtags']); ?>"><br/>
                  <?=__('Maximum number of tags displayed','comment_remix');?>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tag Header Label','comment_remix');?></th>
                <td>
                  <input type="text" name="tagheaderlabel" value="<?php echo attribute_escape($options['tagheaderlabel']); ?>"><br/>
                  <?=__('Text before the tag links (can be an img tag) in the tag header','comment_remix');?>
                </td>
            </tr>
            <tr valign="top">
              <th scope="row"><?=__('Comment Tag Header Separator','comment_remix');?></th>
                <td>
                  <input type="text" name="tagheadersep" value="<?php echo attribute_escape($options['tagheadersep']); ?>"><br/>
                  <?=__('Separator between the each comment tag in the tag header (if necessary - can be an img tag, to make the separator an image)','comment_remix');?>
              </td>
            </tr>
        </table>
        <h3><?=__('Public Comment Display Options','comment_remix');?></h3>
        <p><?=__('These comment display options extend Wordpress\'s comment functionality by giving you more control over how the comments are displayed on your blog','comment_remix');?></p>
        <table class="form-table">
           <tbody>
                <tr valign="top">
                  <th scope="row"><?=__('Hide Trackbacks?','comment_remix');?></th>
                    <td>
                      <label>
                        <input type="checkbox" name="striptrackbacks" <?=$options['striptrackbacks']=='1' ? 'checked="CHECKED"' : '';?> />
                        <?=__('Hide Trackback Entries','comment_remix');?>
                      </label><br/>
                      <?=__('Check this box if you want to hide trackbacks from your comments. When checked, hiding trackbacks ignores the following option (Show Trackbacks After Comments)','comment_remix');?>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?=__('Show Trackbacks After Comments?','comment_remix');?></th>
                    <td>
                      <label>
                        <input type="checkbox" name="trackbacksafter" <?=$options['trackbacksafter']=='1' ? 'checked="CHECKED"' : '';?> />
                        <?=__('Show Trackback Entries After Comments','comment_remix');?>
                      </label><br/>
                      <?=__('Check this box if you want to show trackbacks after your comments. This option is ignored if "Hide Trackback Entries" is checked','comment_remix');?>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?=__('Sort Comments By','comment_remix');?></th>
                    <td><p>
                      <label>
                        <input type="radio" value="date" name="sortby" <?=$options['sortby'] == 'date' ? 'checked="CHECKED"' : '' ?>/>
                        <?=__('Date Posted','comment_remix');?>
                      </label><br/>
                      <label>
                        <input type="radio" value="author" name="sortby" <?=$options['sortby'] == 'author' ? 'checked="CHECKED"' : '' ?>/>
                        <?=__('Author Name','comment_remix');?>
                      </label>
                    </p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?=__('Sort Order','comment_remix');?></th>
                    <td>
                        <select name="sortorder">
                            <option value="asc">- <?=__('Select','comment_remix');?> -</option>
                            <option <?=$options['sortorder'] == 'asc' ? 'selected="SELECTED"' : '' ?> value="asc"><?=__('Ascending','comment_remix');?></option>
                            <option <?=$options['sortorder'] == 'desc' ? 'selected="SELECTED"' : '' ?> value="desc"><?=__('Descending','comment_remix');?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3><?=__('Admin Area Comment Display Options','comment_remix');?></h3>
        <p><?=__('These comment display options can modify the features you can use in the admin area','comment_remix');?></p>
        <table class="form-table">
           <tbody>
                <tr valign="top">
                  <th scope="row"><?=__('Unreplied Comments','comment_remix');?></th>
                    <td>
                      <label>
                        <input type="checkbox" name="showunreplied" <?=$options['showunreplied']=='1' ? 'checked="CHECKED"' : '';?> />
                        <?=__('Show Unreplied # In Menu','comment_remix');?>
                      </label><br/>
                      <?=__('Check this box if you want to show the number of comments in need of reply in the admin menu','comment_remix');?>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?=__('Comment Links','comment_remix');?></th>
                    <td>
                      <label>
                        <input type="checkbox" name="commentlinks" <?=$options['commentlinks']=='1' ? 'checked="CHECKED"' : '';?> />
                        <?=__('Show extra links in comment listings','comment_remix');?>
                      </label><br/>
                      <?=__('Check this box if you want to show the Edit, Reply, Quote, View All, and Mark Replied/Unreplied links in comment listings','comment_remix');?>
                  </td>
                </tr>
           </tbody>
        </table>
        <div class="submit">
          <input type="submit" value="Update Settings" name="submit"/>
        </div>
    </form>
</div>
<?php
}


/*
* ==========================================
* Trackback Sorter
* ==========================================
*/

/**
* @desc Updates the comment count to show # of comments after trackbacks have been removed
* @param $count integer The number of comments
* @global $id integer The ID of the post we're counting comments on
*/
function wpcr_filter_comments_number($count) {
    global $id;
    if (empty($id)) { return $count; }
    $comments = get_approved_comments((int)$id);
    //Now filter based on user settings (must be after the sorting, or it'll re-sort the trackbacks into the comments)
    if ($options['striptrackbacks'] == '1')
        $comments = array_filter($comments,"stripTrackbacks");
    return sizeof($comments);
}

/**
* @desc Updates the count for comments and trackbacks
* @param $commentArray array Array of the comments for a post
*/
function wpcr_fix_comments($commentArray) {
    if (!empty($commentArray)) {
        $options = get_option("wpcr_options");
        $sorter = '';
        $current_tag = get_query_var('comment-tag');
        
        //Filter comment Array for the selected tag
        if ($current_tag) {
            $filtered = array();
            foreach ($commentArray as $key => $comment) {
                $terms = wp_get_object_terms($comment->comment_ID,'comment_tag');
                foreach ($terms as $term)
                    if ($term->slug == $current_tag) {
                        $filtered[] = $comment;
                    }
            }
            $commentArray = $filtered;
        }
            
        //Sort the comments first, so we don't sort the trackbacks back into the comments array
        Switch (strtolower($options['sortby'])) {
            Case 'date':
                foreach($commentArray as $key => $comment) { //comment date
                    $sorter[$key] = $comment->comment_date;
                    $ids[$key] = $comment->comment_ID; //Grab the IDs too
                }
            break;
            Case 'author':
                foreach($commentArray as $key => $comment) { //comment author
                    $sorter[$key] = $comment->comment_author;
                    $ids[$key] = $comment->comment_ID; //Grab the IDs too 
                }
            break;
        }

        if (is_array($sorter) && !empty($sorter)) { //If $sorter is an array, we want to sort based on it
            if (strtolower($options['sortorder']) == 'desc')
                array_multisort($sorter, SORT_DESC, SORT_STRING, $commentArray);
            else
                array_multisort($sorter, SORT_ASC, SORT_STRING, $commentArray);
        }
        
        //Now filter based on user settings (must be after the sorting, or it'll re-sort the trackbacks into the comments)
        if ($options['striptrackbacks'] == '1')
            $comments = array_filter($commentArray,"stripTrackbacks");
        else if ($options['trackbacksafter'] == '1') {
            $comments = array_filter($commentArray,"stripTrackbacks");
            $trackbacks = array_filter($commentArray, "stripComments");
            $comments = array_merge($comments, $trackbacks);
        } else {
            $comments = $commentArray;
        }
        
        if ($options['showtags'] == '1' && $options['showtagsheader'] == '1' && is_array($ids) && !empty($ids)) {
            global $wpdb;
            //Add fake comment to top of list with tags            
            if (!$limit = intval($options['maxtags']))
                $limit=5;
                
            $results = $wpdb->get_results("SELECT distinct t.term_id, t.name, t.slug, tt.count FROM $wpdb->terms t INNER JOIN $wpdb->term_taxonomy tt 
              ON (t.term_id = tt.term_id) INNER JOIN $wpdb->term_relationships tr ON (tt.term_taxonomy_id=tr.term_taxonomy_id) 
              WHERE tt.taxonomy='comment_tag' AND tr.object_id IN (" . implode(",",$ids) . ') ORDER BY tt.count DESC LIMIT '.$limit);
              
            $page = $_SERVER['REQUEST_URI'];
            if (substr($page,-1) != '/')
                $page .= '/'; //Make sure there's a trailing /
            $page = preg_replace('/comment-tag\/?.*/i','',$page);
            $querystring = $_SERVER['QUERYSTRING'];
            
            if (!empty($results)) {
                foreach ($results as $row) {
                    if ($tags)
                        $tags .= $options['tagheadersep'];
                    $tags .= "<a href='".$page."comment-tag/$row->slug$querystring'";
                    if ($row->slug == $current_tag)
                        $tags .= " class='current-tag' style='font-weight: bold;'";
                    $tags .= ">$row->name</a>";
                }
                echo '<div id="wpcr_tags">'.$options['tagheaderlabel'];
                if ($current_tag)
                    echo "<a href='$page'>View All Comments</a>" . $options['tagheadersep'];
                echo "$tags</div>";
            }
        }
        
        return $comments;
    } else
        return $commentArray;
}
/**
* @desc Strips out trackbacks/pingbacks 
* @param $var array Array of comments to be filtered
*/
function stripTrackbacks($var) {
    if ($var->comment_type == 'trackback' || $var->comment_type == 'pingback') { return false; }
    return true;
}

/**
* @desc Strips out comments
* @param $var array Array of comments to be filtered 
*/
function stripComments($var) {
    if ($var->comment_type != 'trackback' && $var->comment_type != 'pingback') { return false; }
    return true;
}

/* Add Init Action */
add_action('init','wpcr_init');
?>