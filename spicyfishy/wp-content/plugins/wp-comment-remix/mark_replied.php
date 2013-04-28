<?php

/*
    //Page to mark a comment as replied/unreplied for WP Comment Remix
    //http://www.Pressography.com/WP-Comment-Remix

    //This page gets used when the ajax "Mark Replied" or "Mark Unreplied" link is called
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
check_admin_referer('wpcr_ignore');

$comment_id = $_POST['comment_id'];
$current_ignore_value = $_POST['current_ignore_value'];

global $user_ID;
$options = get_option('wpcr_options');
if ($options['ignored_comments_'.$user_ID])
    $ignored = explode(',',$options['ignored_comments_'.$user_ID]);
else
    $ignored = array();
if ($current_ignore_value == '0') { //The current ignore value is false, so we want to ignore the comment
    $ignored[] = $comment_id;
    $current_ignore_value ='1';
} else if ($current_ignore_value == '1') { //The current ignore value is true, so we want to unignore the comment
    foreach ($ignored as $key => $value)
        if ($value == $comment_id) {
            unset($ignored[$key]);
            //break;
        }
    $current_ignore_value ='0';
}
$options['ignored_comments_'.$user_ID] = join(',',$ignored);
update_option('wpcr_options',$options);

header('Content-Type: text/xml');
echo "<?xml version=\"1.0\"?>\n"; 
echo "<comment>
        <comment_id>$comment_id</comment_id>
        <newignorevalue>$current_ignore_value</newignorevalue>
        <allignored>" . $options['ignored_comments_'.$user_ID] . "</allignored>
     </comment>";
?>