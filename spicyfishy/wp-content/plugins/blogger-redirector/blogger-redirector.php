<?php

/*
Plugin Name: Blogger Redirector
Plugin URI: http://hellosam.net/project/blogger-redirector
Description: Redirect or accept the permalinks, post, feeds requests forwarded from Blogger to apporiate page on WordPress.  Useful for migrating the traffic from an existing Blogspot site.
Version: 1.0.4
Author: Sam Wong
Author URI: http://hellosam.net/

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
*/

// Use 301 to permanently redirecting the vistor to a proper location?
//   true: yes
//   false: just display the updated content without redirecting or changing the URL
define(BloggerRedirector_Use301, true);

class BloggerRedirector {
    function BloggerRedirector() {
        add_action('parse_request', array(__CLASS__, 'parse_request'));
    }

    function parse_request($wp) {
        global $wpdb;

        if ($wp->matched_rule == NULL || preg_match("/^search/", $wp->matched_rule)) {
            if (preg_match("!^rss\.xml$!", $wp->request, $matches)) {
            // handles Feeds
                $wp->query_vars=array('feed'=>'rss2');
                $url = get_feed_link('rss2');
            } else if (preg_match("!^atom\.xml$!", $wp->request, $matches)) {
            // handles Feeds
                $wp->query_vars=array('feed'=>'atom');
                $url = get_feed_link('atom');
            } else if (preg_match("!^([0-9]{4}/[0-9]{1,2}/[^/]+\.html)$!", $wp->request, $matches)) {
            // handles post permalinks
                $sql = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='blogger_permalink' AND meta_value='" . $wpdb->escape('/' . $matches[1]) . "'";
                $post = $wpdb->get_var($sql);
                if ($post) {
                    $wp->query_vars=array('p'=>$post);
                    $url = get_permalink($post);
                }
            } else if (preg_match("!^([0-9]{4})_([0-9]{1,2})_[0-9]{2}_archive\.html$!", $wp->request, $matches)) {
            // handles Archive
                $wp->query_vars=array('year'=>$matches[1], 'monthnum'=>$matches[2]);
                $url = get_month_link($matches[1], $matches[2]);
                
            } else if (preg_match("!/label/([^/]+)!", $wp->request, $matches)) {
            // handles Tag
                $tag = get_term_by('slug', $matches[1], 'post_tag');
                if ($tag) {
                    $wp->query_vars=array('tag'=>$matches[1]);
                    $url = get_tag_link($tag->term_id);
                }
            } else if (preg_match("!feeds?/([^/]+)/([^/\?]+)!", $wp->request, $matches)) {
                if ($matches[1] == 'posts') {
                    $wp->query_vars=array('feed'=>($_ENV['QUERY_STRING'] == 'alt=rss') ? 'rss2' : 'atom');
                    $url = get_feed_link(($_ENV['QUERY_STRING'] == 'alt=rss') ? 'rss2' : 'atom');
                } else if ($matches[1] == 'comments') {
                    $wp->query_vars=array('withcomments'=>1,'feed'=>($_ENV['QUERY_STRING'] == 'alt=rss') ? 'rss2' : 'atom');
                    $url = get_feed_link(($_ENV['QUERY_STRING'] == 'alt=rss') ? 'comments_rss2' : 'comments_atom');
                }
            }
        }
        if (isset($url) && BloggerRedirector_Use301) {
            header("Location: $url", true, 301);
            header( "Connection: close");
            exit();
        }
    }
}

$_BloggerRedirector = new BloggerRedirector();
?>
