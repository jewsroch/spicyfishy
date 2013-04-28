<?php

/*
Plugin Name: Alter Feed Links
Plugin URI: http://purl.org/david/projects/alter-feed-links
Description: Allows you to change the URI of links to feeds e.g. to direct readers to FeedBurner feeds rather than the plain WordPress feeds.
Version: 0.1
Author: David Roberts
Author URI: http://purl.org/david

    Copyright 2008 David Roberts <dvdr18@gmail.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'alterfeedlinks_add_pages');
add_option('alterfeedlinks_uri_entries');
add_option('alterfeedlinks_uri_comments');
function alterfeedlinks_add_pages() {
	add_options_page('Alter Feed Links', 'Alter Feed Links', 8, basename(__FILE__), 'alterfeedlinks_options_page');
}
function alterfeedlinks_options_page() {?>
	<div class="wrap">
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="alterfeedlinks_uri_entries,alterfeedlinks_uri_comments" />
	<p><label>Entries Feed URI: <input type="text" name="alterfeedlinks_uri_entries" value="<?php echo get_option('alterfeedlinks_uri_entries'); ?>" size="45" /></label></p>
	<p><label>Comments Feed URI: <input type="text" name="alterfeedlinks_uri_comments" value="<?php echo get_option('alterfeedlinks_uri_comments'); ?>" size="45" /></label></p>
	<p>(Leave field blank for feed address to remain un-altered)</p>
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" /></p>
	</form>
	</div>
<?php }

add_filter('feed_link', 'alterfeedlinks_alterlink', 1, 2);
function alterfeedlinks_alterlink($content='', $feed='') {
	if(false !== strpos($content, 'comments')) { // comments feed
		$uri = get_option('alterfeedlinks_uri_comments');
	} else { // entries feed
		$uri = get_option('alterfeedlinks_uri_entries');
	}
	
	if($uri == '') return $content;
	else return $uri;
}

?>
