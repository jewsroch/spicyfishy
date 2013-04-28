<?php

if (function_exists('load_plugin_textdomain')) {
    load_plugin_textdomain('header-footer', 'wp-content/plugins/header-footer');
}
function fsitemap_request($name, $default=null) 
{
	if (!isset($_REQUEST[$name])) return $default;
	return stripslashes_deep($_REQUEST[$name]);
}
	
function fsitemap_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . 
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td>';
}

function fsitemap_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;
  
  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';
  
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><textarea wrap="on" ' . $attrs . ' name="options[' . $name . ']">' . 
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td>';
}	

if (isset($_POST['save']))
{
    if (!wp_verify_nonce($_POST['_wpnonce'], 'save')) die('Securety violated');
    $options = fsitemap_request('options');
    update_option('fsitemap', $options);
}
else 
{
    $options = get_option('fsitemap');
}
?>	

<div class="wrap">

<form method="post">
<?php wp_nonce_field('save') ?>
<h2>Footer Sitemap</h2>        

<table class="form-table">
<tr valign="top"><?php fsitemap_field_textarea('termsofuse', __('Terms of Use', 'header-footer'), __('add html to the box above', 'header-footer'), 'rows="10"'); ?></tr>

<tr valign="top"><?php fsitemap_field_textarea('poweredby', __('Powered by', 'header-footer'), __('add html image tags and links to the box above', 'header-footer'), 'rows="10"'); ?></tr>

</table>



<p class="submit"><input type="submit" name="save" value="<?php _e('save', 'header-footer'); ?>"></p>

</form>
</div>
