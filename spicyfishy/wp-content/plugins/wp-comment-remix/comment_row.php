<?php

/*
    Page to save new comments for WP Comment Remix
    http://www.Pressography.com/WP-Comment-Remix

    This page gets used when the ajax comment reply form gets submitted
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
require_once(ABSPATH.'/wp-includes/capabilities.php');
require_once(ABSPATH.'/wp-admin/admin.php');

//Make sure this user can edit posts
if (!current_user_can('edit_posts')) die('<response><error-page>Not gonna happen - you don\'t have permission</error-page></response>');

$comment_id = intval(attribute_escape($_GET['id']));

$cb = true;

if ($_GET['nocb']=='1')
    $cb = false;

if ($comment_id != '') {
    if ( get_option('show_avatars') )
        add_filter( 'comment_author', 'floated_admin_avatar' );

    _wp_comment_row($comment_id, 'detail', '', $cb);
}
?>
