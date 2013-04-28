<?php

/*
    //Page to save new comments for WP Comment Remix
    //http://www.Pressography.com/WP-Comment-Remix

    //This page gets used when the ajax comment reply form gets submitted
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

//Make sure this user can edit posts
if (!current_user_can('edit_posts')) die('<response><error-page>Not gonna happen - you don\'t have permission</error-page></response>');

//Make sure this is a legit post
check_admin_referer('wpcr_reply');

global $user_ID;
$comment_content = trim($_POST['comment']);
$comment_post_ID = intval(attribute_escape($_POST['post_id']));
$user = get_userdata( $user_ID );
if ( !empty($user->display_name) )
	$comment_author = $user->display_name;
else 
	$comment_author = $user->user_nicename;
$comment_author_email = $user->user_email;
$comment_author_url = $user->user_url;
$comment_parent = $_POST['parent_id'];
$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

// Ozh's Trick: we don't want wp_die() to send any header, so we pretend we do_action 'admin_head'. 
// Enclosed into output buffering to catch any unwanted display it could generate.
// Stopping the headers stops WP from telling the browser there's an error, which kills the
// jQuery.post function, and stops it from calling the success function to parse the xml
/**/
ob_start();
do_action('admin_head');
ob_end_clean();
/**/

$comment_id = wp_new_comment( $commentdata );

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\"?>\n"; 
echo "<comment>
	    <id>$comment_id</id>
     </comment>";
?>