<?php

/*
Plugin Name: Add to Footer
Version:     1.0.1
Plugin URI:  http://ajaydsouza.com/wordpress/plugins/add-to-footer/
Description: Allows you to add absolutely anything to the footer of your WordPress theme.  <a href="options-general.php?page=addfoot_options">Configure...</a>
Author:      Ajay D'Souza
Author URI:  http://ajaydsouza.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

function ald_addfoot_init() {
     load_plugin_textdomain('myald_addfoot_plugin', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));
}
add_action('init', 'ald_addfoot_init');

define('ALD_addfoot_DIR', dirname(__FILE__));

/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
add_action('wp_footer','ald_addfoot');
function ald_addfoot() {

	$addfoot_settings = addfoot_read_options();

	$addfoot_other = stripslashes($addfoot_settings[addfoot_other]);
	$sc_project = stripslashes($addfoot_settings[sc_project]);
	$sc_partition = stripslashes($addfoot_settings[sc_partition]);
	$sc_security = stripslashes($addfoot_settings[sc_security]);
	$apture_siteToken = stripslashes($addfoot_settings[apture_siteToken]);
	$rein_trk_id = stripslashes($addfoot_settings[rein_trk_id]);
	$ga_uacct = stripslashes($addfoot_settings[ga_uacct]);


	if ($addfoot_other != '') {
		echo $addfoot_other;
	}

	if (($sc_project != '')&&($sc_partition != '')) {
?>	
	<!-- Start of StatCounter Code -->
	<script type="text/javascript">
	// <![CDATA[
		var sc_project=<?php echo $sc_project; ?>; 
		var sc_partition=<?php echo $sc_partition; ?>; 
		var sc_security="<?php echo $sc_security; ?>"; 
		var sc_invisible=1; 
		var sc_click_stat=1;
	// ]]>
	</script>
	<script type="text/javascript" src="http://www.statcounter.com/counter/counter_xhtml.js"></script>
	<!-- End of StatCounter Code -->
<?php	}

	if ($apture_siteToken != '') {
?>
	<!-- Start of Apture Code -->
	<script id="aptureScript" type="text/javascript" src="http://www.apture.com/js/apture.js?siteToken=<?php echo $apture_siteToken; ?>" charset="utf-8"></script>
	<!-- End of Apture Code -->
<?php	}

	if ($rein_trk_id != '') {
?>
	<!-- Start of Reinvigorate Code -->
	<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
	<script type="text/javascript">
	// <![CDATA[
	re_("<?php echo $rein_trk_id; ?>");
	// ]]>
	</script>
	<!-- End of Reinvigorate Code -->
<?php	}

	if ($ga_uacct != '') {
?>
	<!-- Google Analytics Code Begin -->
	<script type="text/javascript">
	// <![CDATA[
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	// ]]>
	</script>
	<script type="text/javascript">
	// <![CDATA[
	try {
	var pageTracker = _gat._getTracker("<?php echo $ga_uacct; ?>");
	pageTracker._trackPageview();
	} catch(err) {}	
	// ]]>
	</script>
	<!-- Google Analytics Code End -->
<?php	}

}

// Default Options
function addfoot_default_options() {

	$addfoot_settings = 	Array (
						sc_project => '',		// StatCounter Project ID
						sc_partition => '',		// StatCounter Partition ID
						sc_security => '',		// StatCounter Security String
						ga_uacct => '',			// Google Analytics Web Property ID
						rein_trk_id => '',		// Reinvigorate Tracking ID
						apture_siteToken => '',	// Apture SiteToken
						addfoot_other => '',	// For any other code
						);
	return $addfoot_settings;
}

// Function to read options from the database
function addfoot_read_options() 
{
	$addfoot_settings_changed = false;
	
	//ald_addfoot_activate();
	
	$defaults = addfoot_default_options();
	
	$addfoot_settings = array_map('stripslashes',(array)get_option('ald_addfoot_settings'));
	unset($addfoot_settings[0]); // produced by the (array) casting when there's nothing in the DB
	
	foreach ($defaults as $k=>$v) {
		if (!isset($addfoot_settings[$k]))
			$addfoot_settings[$k] = $v;
		$addfoot_settings_changed = true;	
	}
	if ($addfoot_settings_changed == true)
		update_option('ald_addfoot_settings', $addfoot_settings);
	
	return $addfoot_settings;

}


// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(ALD_addfoot_DIR . "/admin.inc.php");
}


?>