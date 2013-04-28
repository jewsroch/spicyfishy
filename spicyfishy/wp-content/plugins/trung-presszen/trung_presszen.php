<?php
 // encoding: utf-8
/*
Plugin Name: trung_presszen
Plugin URI: http://trunghuynh.com
Description: Extension for integrate zenphoto into wordpress, tested with ZenPhoto 1.1.7

Version: 0.9.4

Author: Trung Huynh
Author URI: http://trunghuynh.com
Tags: zenphoto
*/

/*  Copyright 2008  Trung Huynh  (email : mail@trunghuynh.com)

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
if (!is_admin())
{
	add_filter('request', 'trung_presszen_handle_album', 0);
}
add_action('admin_menu',                    'trung_presszen_config_page');
if (!defined('TRUNG_PRESSZEN_INIT'))
{
	trung_presszen_galleryinit();
}
function trung_presszen_header()
{

}
/**
 * handle album/image request (rewrite from ZP core)
 *
 * @param string $request request from WP
 * @return filtered request
 */

function trung_presszen_handle_album($request)
{
	global $_zp_page;

	$galleryslug = get_option('trung_presszen_slug');
	$pagename = $request['pagename'];
	$i= strpos($pagename, $galleryslug);
	if ( $i !== false)
	{
		add_action('wp_head',                       'trung_presszen_header');
		add_action('get_header', 'trung_presszen_galleryinit');
		add_filter("the_content", 'trung_presszen_get_gallery_output');
		//echo "added";
		$request['pagename'] = substr($pagename, 0, $i + strlen($galleryslug));
		$requested_gallery = substr($pagename, $i+ strlen($galleryslug)+1);
		if (strlen($requested_gallery) > 0 )
		{
			define("ZENALBUM", $requested_gallery);

			if (isset($request['paged']))
			{
				//tricky : ZP asked for GET, we give em GET
				$_GET['page'] = $request['paged'];
				define("ZENPAGE", $request['paged']);
			}
		}
	}

	return $request;
}

function trung_presszen_get_gallery_output($content)
{

	global $_zp_current_image,  $_zp_gallery, $_zp_dynamic_album, $_zp_current_album, $_zp_current_search,$_zp_conf_vars, $_zp_supported_videos, $post;
	ob_start();
	trung_presszen_galleryexec();
	$output = ob_get_contents();
	ob_clean();
	$content = $output;
	return $content;
}

/**
 * Helper to rewrite path from ZP galery to WZP gallery
 *
 * @param unknown_type $path
 * @return unknown
 */
function trung_presszen_rewritepath ($path)
{
	return str_replace(WEBPATH, TRUNGPRESSZEN_URL, $path);
}

/**
 * capture output from a function call
 * return $output
 */
function trung_presszen_capture($fcall, $fArgs)
{
	ob_start();
	call_user_func_array($fcall, $fArgs);
	$out2 = ob_get_contents();
	ob_end_clean();
	return $out2;
}

function process_input ($path)
{
	global $_zp_page;
	$im_suffix = getOption('mod_rewrite_image_suffix');
	$suf_len = strlen($im_suffix);
	$qspos = strpos($path, '?');
	if ($qspos !== false) $path = substr($path, 0, $qspos);
	// Strip off the image suffix (could interfere with the rest, needs to go anyway).
	if ($suf_len > 0 && substr($path, -($suf_len)) == $im_suffix) {
		$path = substr($path, 0, -($suf_len));
	}

	if (substr($path, -1, 1) == '/') $path = substr($path, 0, strlen($path)-1);

	$pagepos  = strpos($path, '/page/');
	$slashpos = strrpos($path, '/');
	$imagepos = strpos($path, '/image/');
	if ($imagepos !== false) {
		$ralbum = substr($path, 0, $imagepos);
		$rimage = substr($path, $slashpos+1);
	} else if ($pagepos !== false) {
		$ralbum = substr($path, 0, $pagepos);
		$rimage = null;
		$_GET['page'];
		//echo $pagepos;
	} else if ($slashpos !== false) {
		$ralbum = substr($path, 0, $slashpos);
		$rimage = substr($path, $slashpos+1);
		if ((is_dir(getAlbumFolder() . $ralbum . '/' . $rimage)) || hasDyanmicAlbumSuffix($rimage)) {
			$ralbum = $ralbum . '/' . $rimage;
			$rimage = null;
		}
	} else {
		$ralbum = $path;
		$rimage = null;
	}

	return array($ralbum, $rimage);
}


function trung_presszen_config_page() {
	if ( function_exists('add_submenu_page') )
	add_submenu_page('plugins.php',"Trung's Zenphoto Extenson", "Trung_presszen configuration", 'manage_options', 'trung_presszen', 'trung_presszen_conf');
}


function trung_presszen_conf()
{
	if (isset ($_POST['submit']))
	{
		$conf['trung_presszen_zenpath'] = $_POST['zenpath'];
		$conf['trung_presszen_slug'] = $_POST['galleryslug'];
		trung_presszen_saveConfig($conf);
		echo "Configuration saved";
	}

	$zenpath=get_option("trung_presszen_zenpath");

	$galleryslug=get_option("trung_presszen_slug");

	echo

	<<<EOF
<div class="wrap">
<h2>Trung_PressZen Configuration</h2>
<form id="conf" method="post" action="./plugins.php?page=trung_presszen">

    <table class="form-table">
        <tbody><tr valign="top">
            <th scope="row">ZenPhoto path</th>
            <td>
                <input type="text" style="width: 95%;" value="{$zenpath}"  name="zenpath"/>
                <br/>
                Absolute path to ZenPhoto installation             </td>
        </tr>
        <tr valign="top">
            <th scope="row">Gallery slug</th>
            <td>
                <input type="text" style="width: 95%;" value="{$galleryslug}" name="galleryslug"/>
                <br/>
               	Name of gallery page   </td>
        </tr>
       
    </tbody></table>
<p class="submit" ><input type="submit" value="Update options »" name="submit"/></p>
<br class="clear"/>
</form>
</div>

EOF;
}


// saves entire configuration
function trung_presszen_saveConfig($conf) {


	if (!update_option('trung_presszen_zenpath', $conf['trung_presszen_zenpath'] ))
	{
		// 	add_option('trung_presszen_zenpath', $conf['trung_presszen_zenpath'] );
	}
	if (!update_option('trung_presszen_slug', $conf['trung_presszen_slug'] ))
	{

		//	add_option('trung_presszen_slug', $conf['trung_presszen_slug'] );
	}
}

function trung_presszen_galleryinit()
{
	global $_zp_current_image,  $_zp_gallery, $_zp_dynamic_album, $_zp_current_album, $_zp_current_search,$_zp_conf_vars, $_zp_supported_videos;

	if (!defined('TRUNG_PRESSZEN_INIT'))
	{
		$path = get_option("trung_presszen_zenpath");
		{ define('WEBPATH', "/gallery"); }
		if (!defined('ZENFOLDER')) { define('ZENFOLDER', 'zp-core'); }
		if (file_exists($path."/".ZENFOLDER . "/template-functions.php"))
		{
			require_once($path."/".ZENFOLDER . "/template-functions.php");
		}
		else
		{
			echo "options 'trung_presszen_zenpath' mismatch !!!!";
			return;
		}
		//setupTheme();

		//	load extensions
		$_zp_plugin_scripts = array();
		$_zp_flash_player = NULL;
		$curdir = getcwd();
		chdir(SERVERPATH . "/" . ZENFOLDER . PLUGIN_FOLDER);
		$filelist = safe_glob('*'.'php');
		chdir($curdir);
		foreach ($filelist as $extension) {
			$opt = 'zp_plugin_'.substr($extension, 0, strlen($extension)-4);
			if (getOption($opt)) {
				require_once(SERVERPATH . "/" . ZENFOLDER . PLUGIN_FOLDER . $extension);
			}
		}
		define("TRUNG_PRESSZEN_INIT", 1);
	}
	if (defined("ZENALBUM"))
	{

		list($album, $image) = process_input(ZENALBUM);
		if (!empty($image)) {

			$success = zp_load_image($album, $image);

		} else if (!empty($album)) {

			$success = zp_load_album($album);

		}

	}
}

function trung_presszen_galleryexec()
{

	global $_zp_current_image,  $_zp_gallery, $_zp_dynamic_album, $_zp_current_album, $_zp_current_search,$_zp_conf_vars, $_zp_supported_videos, $post, $_zp_page, $_zp_gallery_albums_per_page;


	$pageurl =  get_page_uri($post->ID);
	$site = get_option("siteurl");
	define("TRUNGPRESSZEN_URL", $site."/".$pageurl);

	if (isset($_GET['page']))
	{
		$_zp_page = $_GET['page'];
	}
	if (in_context((ZP_IMAGE)))
	{
		require_once("gallery/image.php");
	}
	else
	if (in_context(ZP_ALBUM))
	{
		require_once("gallery/album.php");
	}
	else
	{
		$_zp_page = 0;
		require_once("gallery/gallery_index.php");
	}
}


function widget_trung_zenlatest_init() {
	if (!function_exists('register_sidebar_widget')) return;

	function widget_trung_zenlatest($args) {

		extract($args);

		trung_presszen_latest();
	}

	register_sidebar_widget('trung_zenlatest', 'widget_trung_zenlatest');
}

function trung_presszen_latest()
{

	include_once("trung_zenlatest.php");
}

add_action('plugins_loaded', 'widget_trung_zenlatest_init');
?>