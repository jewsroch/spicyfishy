<?php
/**
 * Copyright 2006/2009  Alessandro Morandi  (email : webmaster@simbul.net)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
 
require_once('./admin.php');

/**
 * Build the absolute path for the ZenPhoto admin directory
 */
function zenphotopress_get_admin_path($web_path) {
	if (get_settings('siteurl') != "" && ABSPATH != "") {
		// Calculate wordpress minimal URL and path
		$wordpress_url = get_settings('siteurl');
		$wordpress_path = ABSPATH;
		$base_url = basename($wordpress_url);
		$base_path = basename($wordpress_path);
		while ($base_url == $base_path) {
			if (substr(dirname($wordpress_url), -1) != ":") {
				$wordpress_url = dirname($wordpress_url);
				$wordpress_path = dirname($wordpress_path);
				$base_url = basename($wordpress_url);
				$base_path = basename($wordpress_path);
			} else {
				// Stop when domain name is reached
				break;
			}
		}
		// Calculate Zenphoto minimal path
		$admin_minimal = zenphotopress_build_path($wordpress_path, str_replace($wordpress_url, "", $web_path));
		
		if (is_dir(zenphotopress_build_path($admin_minimal, "zp-data"))) {
			// Found Zenphoto 1.2.6 data directory
			return zenphotopress_build_path($admin_minimal, "zp-data");
		} else if (is_dir(zenphotopress_build_path($admin_minimal, "zp-core"))) {
			// Found Zenphoto 1.1 admin directory
			return zenphotopress_build_path($admin_minimal, "zp-core");
		} else if (is_dir(zenphotopress_build_path($admin_minimal, "zen"))) {
			// Found Zenphoto <1.1 admin directory
			return zenphotopress_build_path($admin_minimal, "/zen");
		} else {
			// Admin directory not found
			return null;
		}
	} else {
		// Admin path cannot be retrieved
		return null;
	}
}

/**
 * Build a path from two chunks
 */
function zenphotopress_build_path($part1, $part2) {
	$part1 = rtrim($part1, "/");
	$part2 = trim($part2, "/");
	return $part1 . "/" . $part2;
}

/**
 * Update old database variables to their new version
 * and import the old value
 */
function zenphotopress_update() {
	$options = array(
		'zp_admin_path' => '',
		'zp_web_path' => '',
		'zenpress_custom_what' => '',
		'zenpress_custom_link' => '',
		'zenpress_custom_close' => '',
		'zenpress_custom_show' => '',
		'zenpress_custom_orderby' => '',
		'zenpress_custom_wrap' => '',
		'zenpress_custom_size' => '',
		'zenpress_custom_width' => '',
		'zenpress_custom_height' => ''
	);
	// Get old values
	$empty = true;
	foreach($options as $key => $value) {
		$options[$key] = get_option($key);
		if ($options[$key] != '') {
			$empty = false;
		}
	}
	
	if ($empty) {
		return false;
	}
	
	// Create new values
	foreach($options as $key => $value) {
		$name = preg_replace('/^(zp|zenpress)/', 'zenphotopress', $key);
		update_option($name, $value);
	}
	
	// Delete old values
	foreach($options as $key => $value) {
		delete_option($key);
	}
	
	return true;
}

if (isset($_POST['zenphotopress_update'])) {
	// ****** Update variables to 1.3 version
	if (zenphotopress_update()) {
		echo '<div id="message" class="updated fade"><p><strong>';
		_e('ZenphotoPress path and popup preferences have been successfully imported', 'zenphotopress');
		echo '</strong></p></div>';
	} else {
		echo '<div id="message" class="error"><p><strong>';
		_e('There was an error in the update process', 'zenphotopress');
		echo '</strong></p></div>';
	}
	
	
}

$zenphotopress_show_admin_path = false;
if (isset($_POST['info_update'])) {
	// ****** Update operation
	if (!$_POST['zenphotopress_admin_path']) {
		// Admin path was not provided -> calculate it
		$zenphotopress_admin_path = zenphotopress_get_admin_path($_POST['zenphotopress_web_path']);
		if ($zenphotopress_admin_path == null) {
			$zenphotopress_error_msg = __('Zenphoto data path could not be retrieved. Please insert it manually below. Try to come up with something similar to "' . ABSPATH . '" which is your current <i>Wordpress</i> path.', 'zenphotopress');
		}
	} else {
		// Admin path was provided -> use it
		if (is_dir($_POST['zenphotopress_admin_path'])) {
			$zenphotopress_admin_path = $_POST['zenphotopress_admin_path'];
		} else {
			$zenphotopress_admin_path = null;
			$zenphotopress_error_msg = __('Wrong Zenphoto data path. It should point to the filesystem folder containing zp-config.php. Try to come up with something similar to "' . ABSPATH . '" which is your current <i>Wordpress</i> path.', 'zenphotopress');
		}
	}
	if ($zenphotopress_admin_path == null) {
		// Admin path is wrong -> error
		if (!$zenphotopress_error_msg) {
			$zenphotopress_error_msg = __('Error', 'zenphotopress');
		}
		echo '<div id="message" class="error"><p><strong>' . $zenphotopress_error_msg . '</strong></p></div>';
		$zenphotopress_show_admin_path = true;
	} else {
		// Set path values
		update_option('zenphotopress_admin_path', $zenphotopress_admin_path);
		update_option('zenphotopress_web_path', $_POST['zenphotopress_web_path']);
		
		// Set Lightbox rel value
		update_option('zenphotopress_rel_value', $_POST['zenphotopress_rel_value']);
		
		// Set custom popup values
		update_option('zenphotopress_custom_what', $_POST['zenphotopress_custom_what']);
		update_option('zenphotopress_custom_link', $_POST['zenphotopress_custom_link']);
		update_option('zenphotopress_custom_close', $_POST['zenphotopress_custom_close']);
		update_option('zenphotopress_custom_show', $_POST['zenphotopress_custom_show']);
		update_option('zenphotopress_custom_orderby', $_POST['zenphotopress_custom_orderby']);
		update_option('zenphotopress_custom_wrap', $_POST['zenphotopress_custom_wrap']);
		update_option('zenphotopress_custom_size', $_POST['zenphotopress_custom_size']);
		update_option('zenphotopress_custom_width', $_POST['zenphotopress_custom_width']);
		update_option('zenphotopress_custom_height', $_POST['zenphotopress_custom_height']);
		
		echo '<div id="message" class="updated fade"><p><strong>';
		_e('ZenphotoPress options updated successfully.', 'zenphotopress');
		echo '</strong></p></div>';
	}
} else {
	// ****** Normal visualization
	if (get_option("zenphotopress_admin_path")) {
		// Ad admin path is set
		$zenphotopress_admin_path = zenphotopress_get_admin_path(get_option("zenphotopress_web_path"));
		if ($zenphotopress_admin_path != get_option("zenphotopress_admin_path")) {
			// Admin path is not standard -> show it
			$zenphotopress_show_admin_path = true;
		}
	}
}
?>
<div class="wrap">
<h2><?php _e('ZenphotoPress Configuration','zenphotopress') ?></h2>

<form method="post" action="">
	
	<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('Zenphoto gallery URL:','zenphotopress') ?></th>
		<td>
			<input name="zenphotopress_web_path" type="text" id="zenphotopress_web_path" value="<?php form_option('zenphotopress_web_path'); ?>" size="40" /><br />
			<?php _e('For example: http://www.example.com/zenphoto','zenphotopress') ?>
		</td>
		</tr>
		<?php if ($zenphotopress_show_admin_path) { ?>
			<tr valign="top">
			<th scope="row"><?php _e('Zenphoto data path:','zenphotopress') ?></th>
			<td>
				<input name="zenphotopress_admin_path" type="text" id="zenphotopress_admin_path" value="<?php form_option('zenphotopress_admin_path'); ?>" size="40" /><br />
				<?php _e('For example:  /var/www/example.com/zenphoto/zp-data','zenphotopress') ?>
			</td>		
			</tr>
		<?php } ?>
	</table>

	<?php if (get_option('zp_web_path') != '') { ?>
		<h3><?php _e('Update to latest ZenphotoPress version (optional)') ?></h3>
		<p>An installation of a ZenphotoPress version older than 1.3 has been detected. Click "Update" to import old path and popup preferences. This operation will delete the old values in the database, so be sure to <b>backup</b> first.</p>
		<div align="center">
			<form method="post" action="">
			  <input type="submit" name="zenphotopress_update" value="<?php _e('Update','zenphotopress') ?>" />
			</form>
		</div>
	<?php } ?>
	
	<h3><?php _e('Lightbox preferences') ?></h3>
	<p><?php _e('Here you can make ZenphotoPress work with Lightbox-like scripts. Add a value for the &quot;rel&quot; attribute or leave blank to disable integration. Please notice that you\'ll still need to install Lightbox (or any other script) separately. If you don\'t know what this means, chances are good you don\'t need it.','zenphotopress') ?></p>
	<table class="form-table">
		<tr valign="top"> 
		<th scope="row"><?php _e('Value for rel','zenphotopress') ?></th> 
		<td>
		 	<input name="zenphotopress_rel_value" type="text" id="zenphotopress_rel_value" value="<?php form_option('zenphotopress_rel_value'); ?>" size="40" /><br />
			<?php _e('For example: &quot;lightbox&quot; for Lightbox','zenphotopress') ?>
		</td> 
	   </tr>
	</table>
	
	<h3><?php _e('Custom popup preferences (optional)') ?></h3>
	<p><?php _e('These options will override the defaults for the popup window, in order to avoid repetitive menu selections. They are not mandatory (ZenphotoPress will fallback on the default ones).','zenphotopress') ?></p>
	<table class="form-table">
		<tr valign="top"> 
		<th scope="row"><?php _e('What do you want to include?','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('value' => 'thumb','title' => __('Image Thumbnail','zenphotopress')),
							array('value' => 'title','title' => __('Image Title','zenphotopress')),
							array('value' => 'album','title' => __('Album Name','zenphotopress')),
							array('value' => 'custom','title' => __('Custom Text','zenphotopress')));
		 zp_printFormSelect('zenphotopress_custom_what',$options,get_option('zenphotopress_custom_what'));
		 ?>
		</td> 
	   </tr> 
	   <tr valign="top"> 
		<th scope="row"><?php _e('Do you want to link it?','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('value' => 'image','title' => __('Link to Image','zenphotopress')),
							array('value' => 'album','title' => __('Link to Album','zenphotopress')),
							array('value' => 'none','title' => __('No Link','zenphotopress')),
							array('value' => 'custom','title' => __('Custom URL','zenphotopress')));
		 zp_printFormSelect('zenphotopress_custom_link',$options,get_option('zenphotopress_custom_link'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Do you want to close the popup window?','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('value' => 'true','title' => __('Close after inserting','zenphotopress')),
							array('value' => 'false','title' => __('Keep open','zenphotopress')));
		 zp_printFormSelect('zenphotopress_custom_close',$options,get_option('zenphotopress_custom_close'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Images to show in a popup page','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('title' => 12, 'value' => 12),
							array('title' => 24, 'value' => 24),
							array('title' => 48, 'value' => 48),
							array('title' => 96, 'value' => 96)); 
		 zp_printFormSelect('zenphotopress_custom_show',$options,get_option('zenphotopress_custom_show'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Order images by','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('title' => __('Sort Order','zenphotopress'), 'value' => 'sort_order'),
							array('title' => __('Title','zenphotopress'), 'value' => 'title'),
							array('title' => __('ID','zenphotopress'), 'value' => 'id')); 
		 zp_printFormSelect('zenphotopress_custom_orderby',$options,get_option('zenphotopress_custom_orderby'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Text Wrap','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('value' => 'none','title' => __('No wrap','zenphotopress')),
							array('value' => 'left','title' => __('Right','zenphotopress')),
							array('value' => 'right','title' => __('Left','zenphotopress')));
		 zp_printFormSelect('zenphotopress_custom_wrap',$options,get_option('zenphotopress_custom_wrap'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Image Size','zenphotopress') ?></th> 
		<td>
		 <?php
		 $options = array(	array('value' => 'default','title' => __('Default size (thumbnail)','zenphotopress')),
							array('value' => 'full','title' => __('Full size','zenphotopress')),
							array('value' => 'custom','title' => __('Custom size','zenphotopress')));
		 zp_printFormSelect('zenphotopress_custom_size',$options,get_option('zenphotopress_custom_size'));
		 ?>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Custom size:','zenphotopress') ?> <?php _e('Width (px)','zenphotopress') ?></th> 
		<td>
		 <input name="zenphotopress_custom_width" type="text" id="zp_custom_width" value="<?php form_option('zenphotopress_custom_width'); ?>" size="5"/>
		</td> 
	   </tr>
	   <tr valign="top"> 
		<th scope="row"><?php _e('Custom size:','zenphotopress') ?> <?php _e('Height (px)','zenphotopress') ?></th> 
		<td>
		 <input name="zenphotopress_custom_height" type="text" id="zp_custom_height" value="<?php form_option('zenphotopress_custom_height'); ?>" size="5"/>
		</td> 
	   </tr>
	</table>

	<p class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update Options','zenphotopress') ?> &raquo;" />
	</p>
</form>

</div>

<?php

/**
 * Print a <select> HTML element.
 * @param $name	Name of the element
 * @param $options	Array of select options. Each option is an array of name and value
 * @param $selected	Value of the selected option (if any)
 */
function zp_printFormSelect($name,$options,$selected=NULL) {
	echo '<select name="'.$name.'" style="width:16em">';
	foreach ($options as $value) {
		$value[value]==$selected ? $sel=' selected="selected"' : $sel = '';
		echo '<option value="'.$value[value].'"'.$sel.'>'.$value[title].'</option>';
	}
	echo '</select>';
}

?>
