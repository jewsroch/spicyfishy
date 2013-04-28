<?php

/**********************************************************************
*					Admin Page										*
*********************************************************************/
// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
// Guess the location
$addfoot_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));
$addfoot_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));


function addfoot_options() {
	
	global $wpdb;
    $poststable = $wpdb->posts;

	$addfoot_settings = addfoot_read_options();

	if($_POST['addfoot_save']){
		$addfoot_settings[addfoot_other] = ($_POST['addfoot_other']);
		$addfoot_settings[sc_project] = ($_POST['sc_project']);
		$addfoot_settings[sc_partition] = ($_POST['sc_partition']);
		$addfoot_settings[sc_security] = ($_POST['sc_security']);
		$addfoot_settings[apture_siteToken] = ($_POST['apture_siteToken']);
		$addfoot_settings[rein_trk_id] = ($_POST['rein_trk_id']);
		$addfoot_settings[ga_uacct] = ($_POST['ga_uacct']);

		update_option('ald_addfoot_settings', $addfoot_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.','ald_addfoot_plugin') .'</p></div>';
		echo $str;
	}
	
	if ($_POST['addfoot_default']){
		delete_option('ald_addfoot_settings');
		$addfoot_settings = addfoot_default_options();
		update_option('ald_addfoot_settings', $addfoot_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.','ald_addfoot_plugin') .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
  <h2>Add to Footer </h2>
  <div style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Support the Development','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <?php _e('If you find ','ald_addfoot_plugin'); ?>
      <a href="http://ajaydsouza.com/wordpress/plugins/add-to-footer/">Add to Footer</a>
      <?php _e('useful, please do','ald_addfoot_plugin'); ?>
      <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=donate@ajaydsouza.com&amp;item_name=Add%20to%20Footer%20(From%20WP-Admin)&amp;no_shipping=1&amp;return=http://ajaydsouza.com/wordpress/plugins/add-to-footer/&amp;cancel_return=http://ajaydsouza.com/wordpress/plugins/add-to-footer/&amp;cn=Note%20to%20Author&amp;tax=0&amp;currency_code=USD&amp;bn=PP-DonationsBF&amp;charset=UTF-8" title="Donate via PayPal"><?php _e('drop in your contribution','ald_addfoot_plugin'); ?></a>.
	  (<a href="http://ajaydsouza.com/donate/"><?php _e('Some reasons why you should.','ald_addfoot_plugin'); ?></a>)</p>
    </fieldset>
  </div>
	<h2>
	  <?php _e('Options:','ald_addfoot_plugin'); ?>
	</h2>
  <form method="post" id="addfoot_options" name="addfoot_options" style="border: #ccc 1px solid; padding: 10px" onsubmit="return checkForm()">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('StatCounter Options:','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <label>
      <?php _e('StatCounter Project ID: (Value of sc_project)','ald_addfoot_plugin'); ?>
      <input type="textbox" name="sc_project" id="sc_project" value="<?php echo stripslashes($addfoot_settings[sc_project]); ?>">
      </label>
    </p>
    <p>
      <label>
      <?php _e('StatCounter Partition ID: (Value of sc_partition)','ald_addfoot_plugin'); ?>
      <input type="textbox" name="sc_partition" id="sc_partition" value="<?php echo stripslashes($addfoot_settings[sc_partition]); ?>">
      </label>
    </p>
    <p>
      <label>
      <?php _e('StatCounter Security String: (Value of sc_security)','ald_addfoot_plugin'); ?>
      <input type="textbox" name="sc_security" id="sc_security" value="<?php echo stripslashes($addfoot_settings[sc_security]); ?>">
      </label>
    </p>
    </fieldset>
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Google Analytics Options:','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <label>
      <?php _e('Web Property ID:','ald_addfoot_plugin'); ?>
      <input type="textbox" name="ga_uacct" id="ga_uacct" value="<?php echo stripslashes($addfoot_settings[ga_uacct]); ?>">
      </label>
    </p>
    </fieldset>
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Revinvigorate Options:','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <label>
      <?php _e('Tracking ID:','ald_addfoot_plugin'); ?>
      <input type="textbox" name="rein_trk_id" id="rein_trk_id" value="<?php echo stripslashes($addfoot_settings[rein_trk_id]); ?>">
      </label>
    </p>
    </fieldset>
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Apture Options:','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <label>
      <?php _e('SiteToken:','ald_addfoot_plugin'); ?>
      <input type="textbox" name="apture_siteToken" id="apture_siteToken" value="<?php echo stripslashes($addfoot_settings[apture_siteToken]); ?>">
      </label>
    </p>
    </fieldset>
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Any Other Text: (HTML Allowed, no PHP)','ald_addfoot_plugin'); ?>
    </h3>
    </legend>
    <p>
      <textarea name="addfoot_other" id="addfoot_other" rows="15" cols="80"><?php echo stripslashes($addfoot_settings[addfoot_other]); ?></textarea>
    </p>
    </fieldset>
    <p>
      <input type="submit" name="addfoot_save" id="addfoot_save" value="Save Options" style="border:#00CC00 1px solid" />
      <input name="addfoot_default" type="submit" id="addfoot_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default?','ald_addfoot_plugin'); ?>')) return false;" />
    </p>
  </form>
</div>
<?php

}

function addfoot_adminmenu() {
	if (function_exists('current_user_can')) {
		// In WordPress 2.x
		if (current_user_can('manage_options')) {
			$addfoot_is_admin = true;
		}
	} else {
		// In WordPress 1.x
		global $user_ID;
		if (user_can_edit_user($user_ID, 0)) {
			$addfoot_is_admin = true;
		}
	}

	if ((function_exists('add_options_page'))&&($addfoot_is_admin)) {
		add_options_page(__("Add to Footer", 'myald_addfoot_plugin'), __("Add to Footer", 'myald_addfoot_plugin'), 9, 'addfoot_options', 'addfoot_options');
	}
}
add_action('admin_menu', 'addfoot_adminmenu');

?>