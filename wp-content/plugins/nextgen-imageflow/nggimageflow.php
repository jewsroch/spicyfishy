<?php
/*
Plugin Name: NextGEN ImageFlow
Plugin URI: http://shabushabu-webdesign.com/wp-plugin-nextgen-imageflow/
Description: Finn Rudolphs picture gallery for NextGen Gallery. Digital animation for thumbing through a physical image stack.
Author: ShabuShabu Webdesign
Author URI: http://shabushabu-webdesign.com/
Version: 1.3.1

Copyright 2008 by ShabuShabu Webdesign & Finn Rudolph

This script is free software; you can redistribute it and/or modify
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

****************************************************************************
*/
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die(__('You are not allowed to call this page directly.', 'nggflow')); }

class nggflow
{
	var $options;
	var $version     = '1.3.1';
	var $minium_WP   = '2.9';
	var $updateURL   = 'http://shabushabu-webdesign.com/version.php';

	function nggflow()
	{
		$this->load_textdomain();
		$this->required_version();	
		// Get some constants first
		$this->define_constants();
		$this->load_options();
		$this->load_dependencies();
				
		// Init options & tables during activation & deregister init option
		register_activation_hook( dirname(__FILE__) . '/nggimageflow.php', array(&$this, 'activate') );

		if ( function_exists('register_uninstall_hook') )
			register_uninstall_hook( dirname(__FILE__) . '/admin/install.php', 'nggflow_uninstall' );
				
		// Add the script and style files
		if( ! is_admin() )
		{
			add_action('wp_print_scripts', array(&$this, 'load_scripts') );
			add_action('wp_print_styles', array(&$this, 'load_styles') );
		}
		add_action('after_plugin_row', array(&$this, 'add_plugin_row'), 10, 2);
		add_filter('plugin_action_links', array(&$this, 'add_links'), 10, 2);
	}

	function add_links( $links, $file )
	{
		if ( $file != plugin_basename(__FILE__) )
			return $links;
	
		$url = admin_url() . 'admin.php?page=nggallery-imageflow';
		$settings_link = '<a href="' . $url . '">' . __('Settings') . '</a>';
	
		array_unshift( $links, $settings_link );
	
		return $links;
	}

	function add_plugin_row( $links, $file )
	{
		static $this_plugin;
		global $wp_version;
		
		if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
		
		if( $file == $this_plugin )
		{
			$current = get_option( 'update_plugins' );
			
			if( ! isset( $current->response[$file] ) )
				return false;
		
			$columns = substr( $wp_version, 0, 3 ) == "2.9" ? 3 : 5;
			
			$changelog = 'http://shabushabu-webdesign.com/changelog/imageflow-'. $this->version .'.txt';

			$update = wp_remote_fopen( $changelog );
			echo '<td colspan="'.$columns.'">';
			echo $update;
			echo '</td>';
		}
	}

	function required_version()
	{
		global $wp_version;
			
		// Check for WP version installation
		$wp_ok  =  version_compare($wp_version, $this->minium_WP, '>=');
		
		if( $wp_ok == false )
			add_action(	'admin_notices', create_function( '', 'global $nggflow; printf (\'<div id="message" class="error"><p><strong>\' . __(\'Sorry, NextGEN ImageFlow works only under WordPress %s or higher\', "nggflow" ) . \'</strong></p></div>\', $nggflow->minium_WP );'));
	}

	function define_constants()
	{
		//TODO:SHOULD BE REMOVED LATER
		define('IFVERSION', $this->version);
		define('IFURL', $this->updateURL);

		// Pre-2.6 compatibility
		if ( !defined('WP_CONTENT_URL') )
			define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

		// define URL
		define('NGGIMAGEFLOW_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)).'/' );
		define('SITEURL', get_option('siteurl'));
		define('IFABSPATH', str_replace("\\","/", WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/' ));
	}
		
	function load_dependencies()
	{
		require_once( dirname( __FILE__ ) . '/lib/core.php' );
		require_once( dirname( __FILE__ ) . '/widget/widget.php' );
		if( is_admin() )
		{	
			require_once( dirname( __FILE__ ) . '/admin/admin.php' );
			$nggflowAdminPanel = new nggflowAdminPanel();
			require_once( dirname( __FILE__ ) . '/admin/media-upload.php' );
			require_once( dirname( __FILE__ ) . '/admin/dashboard.php' );
		}
		else
		{
			require_once( dirname( __FILE__ ) . '/lib/shortcodes.php' );
			require_once( dirname( __FILE__ ) . '/lib/functions.php' );
			require_once( dirname( __FILE__ ) . '/lib/replace.php' );
			require_once( dirname( __FILE__ ) . '/lib/add_link.php' );
		}			
	}
		
	function load_textdomain()
	{
		load_plugin_textdomain('nggflow','wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/langs');
	}
	
	function load_options()
	{
		// Load the options
		$this->options = get_option('ngg_if_options');
	}
		
	function activate()
	{
		include_once (dirname (__FILE__) . '/admin/install.php');
		ngg_if_default_options();
	}

	function load_styles()
	{
		if( $this->options['ngg_if_use_style'] == true )
			wp_enqueue_style( 'imageflow', NGGIMAGEFLOW_URLPATH.'imageflow/imageflow.css', false, '2.5.0', 'screen' );
	}

	function load_scripts()
	{
		wp_enqueue_script( 'imageflowjs', NGGIMAGEFLOW_URLPATH.'imageflow/imageflow.js', false, '1.2.1' );
	}
}
// Let's get the show on the road
global $nggflow;
$nggflow = new nggflow();
?>