<?php

/*
    Page to get all comment rows for a certain post, & return them as a table
    http://www.Pressography.com/WP-Comment-Remix

    This page gets used when the comments link is clicked on the manage posts page
*/

require_once('lastindexof.php');

$path = dirname(__FILE__);
$path = substr($path, 0, lastIndexOf($path,'wp-content'));
$wpconfig = $path . '/wp-config.php';

if (!file_exists($wpconfig))  {
    echo "Could not find wp-config.php. Error in path :\n\n".$wpconfig ;    
    die;    
}// stop when wp-config is not there

require_once($wpconfig);
require_once(ABSPATH.'wp-admin/includes/template.php');
require_once(ABSPATH.'wp-admin/includes/comment.php');

if ( get_option('show_avatars') )
        add_filter( 'comment_author', 'floated_admin_avatar' );

$id = intval( $_GET['p'] );

$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $id AND comment_approved != 'spam' ORDER BY comment_date DESC");

?>
<table class="widefat" style="margin-top: .5em; text-align: left;">
    <thead>
        <tr>
            <th scope="col"><?php _e('Comment') ?></th>
            <th scope="col"><?php _e('Date') ?></th>
            <th scope="col"><?php _e('Actions') ?></th>
        </tr>
    </thead>
    <tbody id="the-comment-list" class="list:comment">
<?
if ( $comments ) {
    // Make sure comments, post, and post_author are cached
    update_comment_cache($comments);
    foreach ($comments as $comment)
        _wp_comment_row( $comment->comment_ID, 'detail', false, false );
?>
    </tbody>
</table>
<?php
} else {
?>
        <tr>
            <td colspan="3" style="text-align: center"><?=__('There are no comments for this post','comment_remix');?></td>
        </tr>
<?php
} // if $comments
?>
    </tbody>
</table>