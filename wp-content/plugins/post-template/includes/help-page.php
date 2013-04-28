<?php

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html
*/

//############################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//############################################################################

//############################################################################
// Stop unauthorised users 
if (!current_user_can('manage_options')) {
	wp_die(__("You are not allowed to change the options of this plugin.", POST_TEMPLATES_I18N_DOMAIN));
}
//############################################################################
	
?>

<div class="wrap">

<h2><?php _e("Post Templates Plugin", POST_TEMPLATES_I18N_DOMAIN); ?> <?php echo $post_templates_plugin->options['active_version']; ?> - <?php _e("Help", POST_TEMPLATES_I18N_DOMAIN); ?></h2>

<ul class="subsubsub">
	<li><a href="options-general.php?page=post-template/includes/options-page.php" ><?php _e("Options", POST_TEMPLATES_I18N_DOMAIN); ?></a> | </li>
	<li><a href="options-general.php?page=post-template/includes/import-page.php" ><?php _e("Importers", POST_TEMPLATES_I18N_DOMAIN); ?></a> | </li>
	<li><?php _e("Help", POST_TEMPLATES_I18N_DOMAIN); ?> | </li>
	<li><a href="http://post-templates.vincentprat.info" target="_blank"><?php _e("Plugin's home page", POST_TEMPLATES_I18N_DOMAIN); ?></a></li>
</ul>

<br class="clear"/>

<div align="center">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick" />
		<input type="hidden" name="business" value="vpratfr@yahoo.fr" />
		<input type="hidden" name="item_name" value="Vincent Prat - WordPress Plugin" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="currency_code" value="EUR" />
		<input type="hidden" name="tax" value="0" />
		<input type="hidden" name="lc" value="<?php _e('EN', POST_TEMPLATES_I18N_DOMAIN); ?>" />
		<input type="hidden" name="bn" value="PP-DonationsBF" />
		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="PayPal" />
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
	</form>
</div>

<br class="clear"/>

<p><?php _e('Post Templates uses capabilities to define what users are allowed to do. Below is a list of the capabilities used by the plugin and the default user role allowed to make these actions.', POST_TEMPLATES_I18N_DOMAIN); ?> <?php _e('If you want to change the roles having those capabilities, you should use the plugin:', POST_TEMPLATES_I18N_DOMAIN); ?> <a href="http://www.im-web-gefunden.de/wordpress-plugins/role-manager/" target="_blank">Role Manager</a></p>

<table class="widefat">
	<thead>
	<tr>
		<th><?php _e('Capability', POST_TEMPLATES_I18N_DOMAIN); ?></th>
		<th><?php _e('Description', POST_TEMPLATES_I18N_DOMAIN); ?></th>
		<th><?php _e('Default roles', POST_TEMPLATES_I18N_DOMAIN); ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><b>manage-options</b></td>
		<td><?php _e('Access this options page.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators only.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	<tr>
		<td><b><?php echo POST_TEMPLATES_CREATE_TEMPLATES_CAP; ?></b></td>
		<td><?php _e('Create new templates.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators and editors.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	<tr>
		<td><b><?php echo POST_TEMPLATES_EDIT_TEMPLATES_CAP; ?></b></td>
		<td><?php _e('Edit existing templates.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators and editors.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	<tr>
		<td><b><?php echo POST_TEMPLATES_DELETE_TEMPLATES_CAP; ?></b></td>
		<td><?php _e('Delete existing templates.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators and editors.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	<tr>
		<td><b><?php echo POST_TEMPLATES_VIEW_TEMPLATES_CAP; ?></b></td>
		<td><?php _e('View existing templates in the Manage Templates page.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators, editors and authors.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	<tr>
		<td><b><?php echo POST_TEMPLATES_USE_TEMPLATES_CAP; ?></b></td>
		<td><?php _e('Use existing templates to create posts or pages.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
		<td><?php _e('Administrators, editors and authors.', POST_TEMPLATES_I18N_DOMAIN); ?></td>
	</tr>
	</tbody>
</table>

</div>