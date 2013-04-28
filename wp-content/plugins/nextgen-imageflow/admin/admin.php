<?php
/**
 * nggflowAdminPanel - Admin Section for NextGEN ImageFlow
 * 
 * @package NextGEN ImageFlow
 * @author Boris Glumpler
 * @copyright 2008
 * @since 1.0.0
 */

if (!class_exists('nggflowAdminPanel')) {
class nggflowAdminPanel
{
	// constructor
	function nggflowAdminPanel()
	{
		// Add the admin menu
		add_action( 'plugins_loaded', array( &$this, 'checkNGGallery' ) );
			
		// Add the script and style files
		add_action( 'admin_print_scripts', array( &$this, 'load_scripts' ) );
		add_action( 'admin_print_styles', array( &$this, 'load_styles' ) );

		add_filter( 'contextual_help', array( &$this, 'show_help' ), 10, 2 );
	}
	
	// Check for NextGEN Gallery
	function checkNGGallery()
	{
		if( ! class_exists( 'nggLoader' ) )
		{
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, NextGEN ImageFlow works only in combination with NextGEN Gallery.','nggflow') . '</strong></p></div>\';'));
			return;
		}
		add_action('admin_menu', array(&$this, 'add_menu'));
	}
	
	// integrate the menu	
	function add_menu()
	{
		add_submenu_page( NGGFOLDER , __('ImageFlow', 'nggflow'), __('ImageFlow', 'nggflow'), 'NextGEN Change options', 'nggallery-imageflow', array(&$this, 'show_menu'));
	}
	
	// load the script for the defined page and load only this code	
	function show_menu()
	{
		$flowCheck 				= new CheckPlugin();	
		$flowCheck->URL			= IFURL;
		$flowCheck->version 	= IFVERSION;
		$flowCheck->name		= "nggflow";
		
		// Show update message
		if ( $flowCheck->startCheck() )
			echo '<div class="plugin-update">' . __('A new version of NextGEN ImageFlow is available !', 'nggflow') . ' <a href="http://wordpress.org/extend/plugins/nextgen-imageflow/download/" target="_blank">' . __('Download here', 'nggflash') . '</a></div>' ."\n";

		if( ! $this->reflect() )
			nggGallery::show_message(__('There was a problem moving reflect2/3.php into the root, so you should do that manually!','nggflow'));

		 // reduce footprint
		 if ($_GET["page"] == "nggallery-imageflow") {
			include_once (dirname (__FILE__). '/settings.php');		// nggallery_admin_options
			showImageFlowPage();
		}
	}
		
	function load_scripts()
	{
		if ($_GET["page"] == "nggallery-imageflow")
		{
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'ngg-colorpicker', NGGALLERY_URLPATH .'admin/js/colorpicker/js/colorpicker.js', array('jquery'), '1.0');
		}
	}		
		
	function load_styles()
	{
		if ($_GET["page"] == "nggallery-imageflow")
		{
			wp_enqueue_style( 'nggflowadmin', NGGIMAGEFLOW_URLPATH .'admin/css/nggflowadmin.css', false, '2.5.0', 'screen' );
			wp_enqueue_style( 'nggtabs', NGGIMAGEFLOW_URLPATH .'admin/css/jquery.ui.tabs.css', false, '2.5.0', 'screen' );
			wp_enqueue_style('nggcolorpicker', NGGALLERY_URLPATH.'admin/js/colorpicker/css/colorpicker.css', false, '1.0', 'screen');
		}
	}

	function reflect()
	{
		$done = get_option( 'ngg_if_reflect' );
		
		if( $done == 'done' )
			return true;
		else
		{
			if( ! file_exists( ABSPATH .'/reflect2.php' ) )
				copy( IFABSPATH .'reflect2.php', ABSPATH .'/reflect2.php' );
	
			if( ! file_exists( ABSPATH .'/reflect3.php' ) )
				copy( IFABSPATH .'reflect3.php', ABSPATH .'/reflect3.php' );
			
			update_option( 'ngg_if_reflect', 'done' );
			
			return true;
		}
		
		return false;
	}

	function show_help( $help )
	{	
		$help  = '<h5>' . __('Get help with NextGEN ImageFlow', 'nggflow') . '</h5>';
		$help .= '<div class="metabox-prefs">';
		$help .= __('<a href="http://shabushabu-webdesign.com/wp-plugin-nextgen-imageflow/" target="_blank">Plugin page</a> | ', 'nggflow');
		$help .= __('<a href="http://shabushabu-webdesign.com/support-forum/" target="_blank">Support forum</a>', 'nggflow');
		$help .= "</div>\n";
		$help .= '<h5>' . __('More Help & Info', 'nggflow') . '</h5>';
		$help .= '<div class="metabox-prefs">';
		$help .= __('<a href="http://wordpress.org/extend/plugins/nextgen-imageflow" target="_blank">Download a new version</a> | ', 'nggflow');
		$help .= __('<a href="http://finnrudolph.de/ImageFlow/Examples" target="_blank">Examples @finnrudolph.de</a> | ', 'nggflow');
		$help .= __('<a href="http://finnrudolph.de/ImageFlow/Documentation" target="_blank">Documentation @finnrudolph.de</a>', 'nggflow');
		$help .= "</div>\n";
		
		return $help;
	}
} // End class
}
?>