<?php

/*
Plugin Name: SexyBookmarks
Plugin URI: http://eight7teen.com/sexy-bookmarks
Description: SexyBookmarks adds a (X)HTML compliant list of social bookmarking icons to each of your posts. See <a href="options-general.php?page=sexy-bookmarks.php">configuration panel</a> for more settings.
Version: 2.5.1
Author: Josh Jones, Norman Yung
Author URI: http://eight7teen.com

	Original WP-Social-Bookmark-Plugin Copyright 2009 Saidmade srl (email : g.fazioli@saidmade.com)
	Original Social Bookmarking Menu & SexyBookmarks Plugin Copyright 2009 Eight7Teen (email : josh@eight7teen.com)
	Additional Developer: Norman Yung (www.robotwithaheart.com), now a full-time part of further development.
	Additional Special Thanks Goes To Kieran Smith (email : undisclosed)

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
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

define('SEXY_OPTIONS','SexyBookmarks');
define('SEXY_vNum','2.5.1');
define('SEXY_PLUGPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');

require_once('bookmarks-data.php');

//add defaults to an array
$sexy_plugopts = array(
	'position' => '', // below, above, or manual
	'reloption' => 'nofollow', // 'nofollow', or ''
	'targetopt' => 'blank', // 'blank' or 'self'
	'bgimg-yes' => '', // 'yes' or blank
	'bgimg' => '', // 'sexy', 'caring', 'wealth'
	'shorty' => '',
	'pageorpost' => '',
	'bookmark' => array_keys($sexy_bookmarks_data),
	'xtrastyle' => '',
	'feed' => '1', // 1 or 0
	'expand' => '1',
	'autocenter' => '0',
	'ybuzzcat' => 'science',
	'ybuzzmed' => 'text',
);


//add 2 db
add_option(SEXY_OPTIONS, $sexy_plugopts);

//reload
$sexy_plugopts = get_option(SEXY_OPTIONS);

//add sidebar link to settings page
function sexy_menu_link() {
	if (function_exists('add_options_page')) {
		add_options_page('SexyBookmarks', 'SexyBookmarks', 8, basename(__FILE__), 'sexy_settings_page');
	}
}

function sexy_network_input_select($name, $hint) {
	global $sexy_plugopts;
	return sprintf('<label class="%s" title="%s"><input %sname="bookmark[]" type="checkbox" value="%s"  id="%s" /></label>',
		$name,
		$hint,
		@in_array($name, $sexy_plugopts['bookmark'])?'checked="checked" ':"",
		$name,
		$name
	);
}

//write settings page
function sexy_settings_page() {
	global $sexy_plugopts, $sexy_bookmarks_data, $wpdb;

	// Rotate through both authors' donation links so that donations will be fully unbiased as the user won't know which link belongs to who...
	$donations = array(
		"https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=8HGMUBNDCZ88A",
		"https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=3415856"
	);
	$donate_link = $donations[rand(0, count($donations) - 1)];
	$donate_img = 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif';

	// processing form submission
	$status_message = "";
	$error_message = "";
	if(isset($_POST['save_changes'])) {
		$status_message = 'Your changes have been saved successfully! &nbsp; --->> &nbsp; <a href="'.$donate_link.'">Donate?</a>';
		
		//was there an error?
		if($_POST['position'] == '') {		
			$error_message = 'Please choose where you would like the menu to be displayed.';
		}
		elseif($_POST['bookmark'] == '') {
			$error_message = 'You can\'t display the menu if you don\'t choose a few sites to add to it!';
		}
		elseif($_POST['pageorpost'] == '') {
			$error_message = 'Please choose where you want the menu displayed.';
		}
		elseif($_POST['shorty'] == 'tflp' && !function_exists('permalink_to_twitter_link')) { // check to see if they have the plugin activated
			$error_message = "You must first download and activate <a href=\"http://wordpress.org/extend/plugins/twitter-friendly-links/\">Twitter Friendly Links Plugin</a> before hosting your own short URLs...";
		}
		else {
			$sexy_plugopts['position'] = $_POST['position'];
			$sexy_plugopts['xtrastyle'] = $_POST['xtrastyle'];
			$sexy_plugopts['reloption'] = $_POST['reloption'];
			$sexy_plugopts['targetopt'] = $_POST['targetopt'];
			$sexy_plugopts['bookmark'] = $_POST['bookmark'];
			$sexy_plugopts['shorty'] = $_POST['shorty'];
			$sexy_plugopts['pageorpost'] = $_POST['pageorpost'];
			$sexy_plugopts['twittid'] = $_POST['twittid'];
			$sexy_plugopts['ybuzzcat'] = $_POST['ybuzzcat'];
			$sexy_plugopts['ybuzzmed'] = $_POST['ybuzzmed'];
			$sexy_plugopts['bgimg-yes'] = $_POST['bgimg-yes'];
			$sexy_plugopts['bgimg'] = $_POST['bgimg'];
			$sexy_plugopts['feed'] = $_POST['feed'];
			$sexy_plugopts['expand'] = $_POST['expand'];
			$sexy_plugopts['autocenter'] = $_POST['autocenter'];
			update_option(SEXY_OPTIONS, $sexy_plugopts);
		}
		
		if(in_array('sexy-yahoobuzz', $sexy_plugopts['bookmark'])) {
			$ybuzz_default_class = "";
		}
		else {
			$ybuzz_default_class = "hidden";
		}

		if ($_POST['clearShortUrls']) {
			$dump=$wpdb->query("
				DELETE FROM $wpdb->postmeta 
				WHERE meta_key='_sexybookmarks_shortUrl' OR meta_key='_sexybookmarks_permaHash'
			");
			echo '<div id="message" class="sexy-warning"><p>'.$dump.' Short URLs have been reset.<br/></p></div><div style="clear:both;"></div>';
		}
	}

	//if there was an error,
	//display it in my new fancy schmancy divs
	if ($error_message != '') {
		echo '<div id="message" class="sexy-errors"><p>'.$error_message.'</p></div><div style="clear:both;"></div>';
	} elseif ($status_message != '') {
		echo '<div id="message" class="sexy-infos"><p>'.$status_message.'</p></div><div style="clear:both;"></div>';
	}
?>
<h2 class="sexy">SexyBookmarks</h2>
<div class="wrap sexy-wrap">
	<form name="sexy-bookmarks" id="sexy-bookmarks" action="" method="post">
	
		<fieldset class="iconator" id="iconator">
			<legend>Enabled Networks</legend>
			<p>Select the Networks to display. Drag to reorder.</p><div id="sexy-networks">
<?php
	foreach ($sexy_plugopts['bookmark'] as $name) print sexy_network_input_select($name, $sexy_bookmarks_data[$name]['check']);
	$unused_networks=array_diff(array_keys($sexy_bookmarks_data), $sexy_plugopts['bookmark']);
	foreach ($unused_networks as $name) print sexy_network_input_select($name, $sexy_bookmarks_data[$name]['check']);
?></div>
		</fieldset>
		
		<fieldset>
			<legend>Look &amp; Feel</legend>
			<span class="sexy_option">Use a background image? <input <?php echo (($sexy_plugopts['bgimg-yes'] == "yes")? 'checked=""' : ""); ?> name="bgimg-yes" id="bgimg-yes" type="checkbox" value="yes" /></span>
			<div id="bgimgs" <?php if(!isset($sexy_plugopts['bgimg-yes'])) { ?>class="hidden"<?php } else { echo " "; }?>>
			<label class="bgimg share-sexy"><input <?php echo (($sexy_plugopts['bgimg'] == "sexy")? 'checked="checked"' : ""); ?> id="bgimg-sexy" name="bgimg" type="radio" value="sexy" /></label>
			<label class="bgimg share-care"><input <?php echo (($sexy_plugopts['bgimg'] == "caring")? 'checked="checked"' : ""); ?> id="bgimg-caring" name="bgimg" type="radio" value="caring" /></label>
			<label class="bgimg share-wealth"><input <?php echo (($sexy_plugopts['bgimg'] == "wealth")? 'checked="checked"' : ""); ?> id="bgimg-wealth" name="bgimg" type="radio" value="wealth" /></label>
			<!--<label class="bgimg"><input <?php echo (($sexy_plugopts['bgimg'] == "none")? 'checked="checked"' : ""); ?> id="bgimg-none" name="bgimg" type="radio" value="none" />(none)</label>-->
			</div>
			<div class="clear"></div>
						
			<span class="sexy_option">Animate-expand multi-lined bookmarks?</span>
			<label><input <?php echo (($sexy_plugopts['expand'] == "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-yes" type="radio" value="1" />Yes</label>
			<label><input <?php echo (($sexy_plugopts['expand'] != "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-no" type="radio" value="0" />No</label>
			
			<span class="sexy_option">Auto-center the bookmarks?</span>
			<label><input <?php echo (($sexy_plugopts['autocenter'] == "1")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-yes" type="radio" value="1" />Yes</label>
			<label><input <?php echo (($sexy_plugopts['autocenter'] != "1")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-no" type="radio" value="0" />No</label>
			<p>(Note: selecting <b>Yes</b> above will void any custom styles applied below.)</p>
				
			
			<label for="xtrastyle">You can style the DIV that holds the menu here:</label><br/>
			<textarea id="xtrastyle" name="xtrastyle">
<?php 
	$default_sexy = "margin:20px 0 0 0 !important;\npadding:25px 0 0 10px !important;\nheight:29px;/*the height of the icons (29px)*/\ndisplay:block !important;\nclear:both !important;";
	if (!empty($sexy_plugopts['xtrastyle'])) {
		echo $sexy_plugopts['xtrastyle']; 
	} 
	elseif ( empty($sexy_plugopts['xtrastyle']) )
		echo $default_sexy;
	else {
		echo "If you see this message, please delete the contents of this textarea and click \"Save Changes\".";
	}
?>
			</textarea>

		</fieldset>
		
		<fieldset>
			<legend>Functionality</legend>
			<span class="sexy_option">Twitter:</span>
			<label for="twittid">Twitter ID:</label>
			<input type="text" id="twittid" name="twittid" value="<?php echo $sexy_plugopts['twittid']; ?>" />
		<div id="ybuzz-defaults" class="<?php echo $ybuzz_default_class; ?>">
			<span class="sexy_option">Yahoo! Buzz Defaults:</span>
			<label for="ybuzzcat">Default Content Category: </label><br />
			<select name="ybuzzcat" id="ybuzzcat">
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "entertainment")? 'selected="selected"' : ""); ?> value="entertainment">Entertainment</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "lifestyle")? 'selected="selected"' : ""); ?> value="lifestyle">Lifestyle</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "health")? 'selected="selected"' : ""); ?> value="health">Health</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "usnews")? 'selected="selected"' : ""); ?> value="usnews">U.S. News</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "business")? 'selected="selected"' : ""); ?> value="business">Business</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "politics")? 'selected="selected"' : ""); ?> value="politics">Politics</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "science")? 'selected="selected"' : ""); ?> value="science">Sci/Tech</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "world_news")? 'selected="selected"' : ""); ?> value="world_news">World</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "sports")? 'selected="selected"' : ""); ?> value="sports">Sports</option>
				<option <?php echo (($sexy_plugopts['ybuzzcat'] == "travel")? 'selected="selected"' : ""); ?> value="travel">Travel</option>
			</select><br />
			<label for="ybuzzmed">Default Media Type: </label><br />
			<select name="ybuzzmed" id="ybuzzmed">
				<option <?php echo (($sexy_plugopts['ybuzzmed'] == "text")? 'selected="selected"' : ""); ?> value="text">Text</option>
				<option <?php echo (($sexy_plugopts['ybuzzmed'] == "image")? 'selected="selected"' : ""); ?> value="image">Image</option>
				<option <?php echo (($sexy_plugopts['ybuzzmed'] == "audio")? 'selected="selected"' : ""); ?> value="audio">Audio</option>
				<option <?php echo (($sexy_plugopts['ybuzzmed'] == "video")? 'selected="selected"' : ""); ?> value="video">Video</option>
			</select>
		</div>
			<span class="sexy_option">Add nofollow to the links?</span>
			<label><input <?php echo (($sexy_plugopts['reloption'] == "nofollow")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-yes" type="radio" value="nofollow" /> Yes</label>
			<label><input <?php echo (($sexy_plugopts['reloption'] == "")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-no" type="radio" value="" /> No</label>
			<span class="sexy_option">Which URL Shortening Service?</span>
			<select name="shorty" id="shorty">
				<option <?php echo (($sexy_plugopts['shorty'] == "tflp")? 'selected="selected"' : ""); ?> value="tflp">Twitter Friendly Links Plugin</option>
				<option <?php echo (($sexy_plugopts['shorty'] == "e7t")? 'selected="selected"' : ""); ?> value="e7t">http://e7t.us</option>
				<option <?php echo (($sexy_plugopts['shorty'] == "rims")? 'selected="selected"' : ""); ?> value="rims">http://ri.ms</option>
				<option <?php echo (($sexy_plugopts['shorty'] == "tinyarrow")? 'selected="selected"' : ""); ?> value="tinyarrow">http://tinyarro.ws</option>
				<option <?php echo (($sexy_plugopts['shorty'] == "tiny")? 'selected="selected"' : ""); ?> value="tiny">http://tinyurl.com</option>
				<option <?php echo (($sexy_plugopts['shorty'] == "snip")? 'selected="selected"' : ""); ?> value="snip">http://snipr.com</option>
			</select>
			<label for="clearShortUrls" id="clearShortUrlsLabel"><input name="clearShortUrls" id="clearShortUrls" type="checkbox"/>Reset all Short URLs</label>
			
			<span class="sexy_option">Open links in new window?</span>
			<label><input <?php echo (($sexy_plugopts['targetopt'] == "_blank")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-blank" type="radio" value="_blank" /> Yes</label>
			<label><input <?php echo (($sexy_plugopts['targetopt'] == "_self")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-self" type="radio" value="_self" /> No</label>
			
		</fieldset>
		
		<fieldset>
			<legend>Placement</legend>
			<span class="sexy_option">Menu Location:</span>
			<label><input <?php echo (($sexy_plugopts['position'] == "above")? 'checked="checked"' : ""); ?> name="position" id="position-above" type="radio" value="above" /> Above each post</label>
			<br />
			<label><input <?php echo (($sexy_plugopts['position'] == "below")? 'checked="checked"' : ""); ?> name="position" id="position-below" type="radio" value="below" /> Below each post</label>
			<br />
			<label><input <?php echo (($sexy_plugopts['position'] == "manual")? 'checked="checked"' : ""); ?> name="position" id="position-manual" type="radio" value="manual" /> Manually insert</label>

			<span class="sexy_option">Posts, pages, or the whole shebang?</span>
			<select name="pageorpost" id="pageorpost">
				<option <?php echo (($sexy_plugopts['pageorpost'] == "post")? 'selected="selected"' : ""); ?> value="post">Posts Only</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "page")? 'selected="selected"' : ""); ?> value="page">Pages Only</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "index")? 'selected="selected"' : ""); ?> value="index">Index Only</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "pagepost")? 'selected="selected"' : ""); ?> value="pagepost">Posts &amp; Pages</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "postindex")? 'selected="selected"' : ""); ?> value="postindex">Posts &amp; Index</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "pageindex")? 'selected="selected"' : ""); ?> value="pageindex">Pages &amp; Index</option>
				<option <?php echo (($sexy_plugopts['pageorpost'] == "postpageindex")? 'selected="selected"' : ""); ?> value="postpageindex" title="THE WHOLE SHEBANG!">Posts, Pages, &amp; Index</option>
			</select>
		
			<span class="sexy_option">Feed Settings</span>
			<label><input <?php echo (($sexy_plugopts['feed'] == "1")? 'checked="checked"' : ""); ?> name="feed" id="feed-show" type="radio" value="1" /> Show in feed</label>
			<br />
			<label><input <?php echo (($sexy_plugopts['feed'] == "0" || empty($sexy_plugopts['feed']))? 'checked="checked"' : ""); ?> name="feed" id="feed-hide" type="radio" value="0" /> Hide in feed</label>

		</fieldset>
		<input type="hidden" name="save_changes" value="1" />

		<div class="sexy-donations">
			<h2>Fuel the Sexiness</h2>
			<p>If you think this plugin is worth a couple of bucks... Please help induce our <strong>&quot;caffeine comas&quot;</strong> by donating.</p>
			<div class="sexy-donate-button"><a href="<?php echo $donate_link; ?>" title="Help support the development of this plugin by donating!"><img src="<?php echo $donate_img; ?>" /></a></div>
		</div>

		<div class="submit"><input type="submit" value="Save Changes" /></div>

	</form>
</div>

<?php

}//closing brace for function "sexy_settings_page"


function sexy_get_fetch_url() {
	global $post, $sexy_plugopts;
	if($plugopts['position'] == 'manual') { $perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; }
	else { $perms = get_permalink(); }
	
	// which short url service should be used?
	if($sexy_plugopts['shorty'] == "e7t") {
		$first_url = "http://e7t.us/create.php?url=".$perms;
	}
	elseif($sexy_plugopts['shorty'] == "rims") {
		$first_url = "http://ri.ms/api-create.php?url=".$perms;
	} elseif($sexy_plugopts['shorty'] == "tinyarrow") {
		$first_url = "http://tinyarro.ws/api-create.php?url=".$perms;
	} elseif($sexy_plugopts['shorty'] == "tiny") {
		$first_url = "http://tinyurl.com/api-create.php?url=".$perms;
	} elseif($sexy_plugopts['shorty'] == "snip") {
		$first_url = "http://snipr.com/site/snip?&r=simple&link=".$perms;
	}
	
	$fetch_url=get_post_meta($post->ID, '_sexybookmarks_shortUrl', true);
	// if neccessary, fetch and store
	if (empty($fetch_url) || md5($perms)!=get_post_meta($post->ID, '_sexybookmarks_permaHash', true)) {
		// retrieve the shortened URL
		if (function_exists('curl_init')) {
			// Use cURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $first_url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			$fetch_url = curl_exec($ch);
			curl_close($ch);
			
		} elseif (function_exists('file_get_contents')) { // use file_get_contents()
			$fetch_url = file_get_contents($first_url);
		} else {
			$fetch_url='';
		}

		if ($fetch_url) { // remote call made and was successful
			$fetch_url=trim($fetch_url);
			// add/update values
			// tries updates first, then add if field does not already exist
			if (!update_post_meta($post->ID, '_sexybookmarks_shortUrl', $fetch_url)) {
				add_post_meta($post->ID, '_sexybookmarks_shortUrl', $fetch_url);
			}
			if (!update_post_meta($post->ID, '_sexybookmarks_permaHash', md5($perms))) {
				add_post_meta($post->ID, '_sexybookmarks_permaHash', md5($perms));
			}
		} else { # check to see if they want to host their own short URLs
				    # notice that this is done outside of the cURL command as well as the post_meta section
				     # since it is a self-hosted solution there is no need to fetch the URL or store it because it's already stored in the database
				if($sexy_plugopts['shorty'] == "tflp" && function_exists('permalink_to_twitter_link')) {
					$fetch_url = permalink_to_twitter_link($perms);
				} else {  // failed. use permalink.
					$fetch_url=$perms;
				  }
		}
	}
	return $fetch_url;
}


//create an auto-insertion function
function sexy_position_menu($post_content) {
	global $sexy_plugopts;
	
	// if user selected manual positioning, get out.
	if ($sexy_plugopts['position']=='manual') return $post_content;
	
	// decide whether or not to generate the bookmarks.
	if ((is_single() && false!==strpos($sexy_plugopts['pageorpost'],"post")) ||
		(is_page() && false!==strpos($sexy_plugopts['pageorpost'],"page")) ||
		(is_home() && false!==strpos($sexy_plugopts['pageorpost'],"index")) ||
		(is_feed() && !empty($sexy_plugopts['feed']))
	) { // socials should be generated and added
		$socials=get_sexy();
	}
	
	// place of bookmarks and return w/ post content.
	if (empty($socials)) {
		return $post_content;
	} elseif ($sexy_plugopts['position']=='above') {
		return $socials.$post_content;
	} elseif ($sexy_plugopts['position']=='below') {
		return $post_content.$socials;
	} else { // some other unexpected error, don't do anything. return.
		error_log("an error occurred in SexyBookmarks");
		return $post_content;
	}
}
//end sexy_position_menu...

function bookmark_list_item($name, $opts=array()) {
	global $sexy_plugopts, $sexy_bookmarks_data;
	if($sexy_plugopts['targetopt'] == "_blank") {
		$xhtml_target = "external";
	} else {
		$xhtml_target = "normal";
	}
	$url=$sexy_bookmarks_data[$name]['baseUrl'];
	foreach ($opts as $key=>$value) {
		$url=str_replace(strtoupper($key), $value, $url);
	}
	
	return sprintf(
		'<li class="%s"><a href="%s" rel="%s %s" title="%s">%s</a></li>',
		$name,
		$url,
		$xhtml_target,
		$sexy_plugopts['reloption'],
		$sexy_bookmarks_data[$name]['share'],
		$sexy_bookmarks_data[$name]['share']
	);
}



function get_sexy() {
	global $sexy_plugopts;
	
if($plugopts['position'] == 'manual') { 
	$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; 
	$title = urlencode(get_bloginfo('name') . wp_title('-', false));
	$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
	$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
}
else { 
	$perms = get_permalink(); 
	$title = urlencode(get_the_title()); 
	$feedperms = strtolower(get_permalink());
	$mail_subject = urlencode(get_the_title());
}


	$title = str_replace('%3A',':',$title);
	$title = str_replace('%3F','?',$title);
	$title = str_replace('%C3%B9','ù',$title);
	$title = str_replace('%C3%A0','à',$title);
	$title = str_replace('%C3%A8','è',$title);
	$title = str_replace('%C3%AC','ì',$title);
	$title = str_replace('%C3%B2','ò',$title);
    $short_title = substr($title, 0, 60)."...";
	$sexy_content = urlencode(substr(strip_tags(strip_shortcodes(get_the_content())),0,300));
	$sexy_content = str_replace('+','%20',$sexy_content);
	$sexy_content = str_replace("&#8217;","'",$sexy_content);
	$post_summary = stripslashes($sexy_content);
	$site_name = get_bloginfo('name');
	$mail_subject = str_replace('+','%20',$mail_subject);
	$mail_subject = str_replace("&#8217;","'",$mail_subject);
	$y_cat = $sexy_plugopts['ybuzzcat'];
	$y_med = $sexy_plugopts['ybuzzmed'];
	


        if (false !== strpos($feedperms,'?') || false !== strpos($feedperms,'.php',strlen($feedperms) - 4)) {
                $feedstructure = '&feed=comments-rss2';
        } else {
                if ('/' == $feedperms[strlen($feedperms) - 1]) {
                        $feedstructure = 'feed';
                } 
				else {
                        $feedstructure = '/feed';
                }
        }




	// Temporary fix for bug that breaks layout when using NextGen Gallery plugin
	if( (strpos($post_summary, '[') || strpos($post_summary, ']')) ) {
		$post_summary = "";
	}
	if( (strpos($sexy_content, '[') || strpos($sexy_content,']')) ) {
		$sexy_content = "";
	}

	// select the background
	if(!isset($sexy_plugopts['bgimg-yes'])) {
		$bgchosen = '';
	} elseif($sexy_plugopts['bgimg'] == 'sexy') {
		$bgchosen = ' sexy-bookmarks-bg-sexy';
	} elseif($sexy_plugopts['bgimg'] == 'caring') {
		$bgchosen = ' sexy-bookmarks-bg-caring';
	}  elseif($sexy_plugopts['bgimg'] == 'wealth') {
		$bgchosen = ' sexy-bookmarks-bg-wealth';
	}
	
	// do not add inline styles to the feed.
	$style=($sexy_plugopts['autocenter'])?'':' style="'.__($sexy_plugopts['xtrastyle']).'"';
	if (is_feed()) $style='';
	$expand=$sexy_plugopts['expand']?' sexy-bookmarks-expand':'';
	$autocenter=$sexy_plugopts['autocenter']?' sexy-bookmarks-center':'';
	//write the menu
	$socials = '<div class="sexy-bookmarks'.$expand.$autocenter.$bgchosen.'"'.$style.'><ul class="socials">';
	foreach ($sexy_plugopts['bookmark'] as $name) {
		if ($name=='sexy-twitter') {
			$socials.=bookmark_list_item($name, array(
				'post_by'=>(!empty($sexy_plugopts['twittid']))?"RT+@".$sexy_plugopts['twittid'].":+":'',
				'short_title'=>$title,
				'fetch_url'=>sexy_get_fetch_url(),
			));
		} elseif ($name=='sexy-mail') {
			$socials.=bookmark_list_item($name, array(
				'mail_subject'=>$mail_subject,
				'strip_teaser'=>$post_summary,
				'permalink'=>$perms,
			));
		}elseif ($name=='sexy-diigo') {
			$socials.=bookmark_list_item($name, array(
				'sexy_teaser'=>$sexy_content,
				'permalink'=>$perms,
				'title'=>$title,
			));
		}elseif ($name=='sexy-linkedin') {
			$socials.=bookmark_list_item($name, array(
				'post_summary'=>$post_summary,
				'site_name'=>$site_name,
				'permalink'=>$perms,
				'title'=>$title,
			));
		} elseif ($name=='sexy-devmarks') {
			$socials.=bookmark_list_item($name, array(
				'post_summary'=>$post_summary,
				'permalink'=>$perms,
				'title'=>$title,
			));
		} elseif ($name=='sexy-comfeed') {
			$socials.=bookmark_list_item($name, array(
				'permalink'=>$feedperms.$feedstructure,
			));
		} elseif ($name=='sexy-yahoobuzz') {
			$socials.=bookmark_list_item($name, array(
				'permalink'=>$perms,
				'title'=>$title,
				'yahooteaser'=>$sexy_content,
				'yahoocategory'=>$y_cat,
				'yahoomediatype'=>$y_med,
			));
		} else {
			$socials.=bookmark_list_item($name, array(
				'permalink'=>$perms,
				'title'=>$title,
			));
		}
	}
	$socials.='</ul><div style="clear:both;"></div></div>';

	return $socials;
}

// This function is what allows people to insert the menu wherever they please rather than above/below a post...
function selfserv_sexy() {
	echo get_sexy();
}

//write the <head> code
add_action('wp_head', 'sexy_public');
function sexy_public() {
	global $sexy_plugopts;
	if($sexy_plugopts['targetopt'] == '_blank') {
		$public_script = "sexy-bookmarks-blank-public.js";
	} else {
		$public_script = "sexy-bookmarks-self-public.js";
	}
	echo "\n\n".'<!-- Start Of Code Generated By SexyBookmarks '.SEXY_vNum.' -->'."\n";
	wp_register_style('sexy-bookmarks', SEXY_PLUGPATH.'css/style.css', false, SEXY_vNum, 'all');
	if ($sexy_plugopts['expand'] || $sexy_plugopts['autocenter']) {
		wp_register_script('sexy-bookmarks-public-js', SEXY_PLUGPATH.$public_script, array('jquery'), SEXY_vNum);
		wp_print_scripts('sexy-bookmarks-public-js');
	}
	wp_print_styles('sexy-bookmarks');
	echo '<!-- End Of Code Generated By SexyBookmarks '.SEXY_vNum.' -->'."\n\n";
}


//styles for admin area
add_action('admin_head', 'sexy_admin');
function sexy_admin() {
	wp_register_style('sexy-bookmarks', SEXY_PLUGPATH.'css/admin-style.css', false, SEXY_vNum, 'all');
	wp_register_script('sexy-bookmarks-js', SEXY_PLUGPATH.'sexy-bookmarks.js', array('jquery-ui-sortable'), SEXY_vNum);
	wp_print_styles('sexy-bookmarks');
	wp_print_scripts('sexy-bookmarks-js');
}



//add a sidebar menu link
//hook the menu to "the_content"
add_action('admin_menu', 'sexy_menu_link');
add_filter('the_content', 'sexy_position_menu');
?>