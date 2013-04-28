<?php

/*
Plugin Name: Blogger Blogspot To Wordpress Redirection
Plugin URI: http://blog.inspired.no
Description: Redirect blogspot to wordpress. Based on idea from plugin 'Blogger To Wordpress Redirection'
Version: 1.0
Author: Espen Antonsen
Author URI: http://inspired.no
*/
function BloggerBlogspotToWordpress(){
$url = $_SERVER['REQUEST_URI'];
$redirectPath = "/redirect/?url=";
if ( preg_match("/\w*.blogspot.com/", $url, $matches) ) 
{
$blogspotURL = "http://".$matches[0];
}
if( strchr( $url, $redirectPath)  ){
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://".getenv('HTTP_HOST').substr( $url, strlen( $redirectPath.$blogspotURL) ));
header( "Connection: close");
exit();
  }
}
add_action('init','BloggerBlogspotToWordpress', '1');
?>
