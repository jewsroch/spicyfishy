<table>
    <tr>
        <td class="left">
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title", "gd-pages-navigator"); ?>:</label>
            <input class="widefat d4pwdg-input-title" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance["title"]; ?>" />
        </td>
        <td class="splitter"></td>
        <td class="right">
            <label for="<?php echo $this->get_field_id('_display'); ?>"><?php _e("Display to", "gd-pages-navigator"); ?>:</label>
            <?php $this->display_select_options(array("all" => __("Everyone", "gd-pages-navigator"), "user" => __("Only Users", "gd-pages-navigator"), "visitor" => __("Only Visitors", "gd-pages-navigator")),
                    $instance["_display"], $this->get_field_name('_display'), $this->get_field_id('_display'), "widefat d4pwdg-select-type"); ?>
        </td>
    </tr>
</table>
<br/>

<table>
    <tbody>
    <tr>
        <td class="left">

<h4><?php _e("Main Settings", "gd-pages-navigator"); ?>:</h4>

<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e("Post Type", "gd-pages-navigator"); ?>:</label>
<?php $this->display_select_options($gdpn_core->get_posttypes_hierarchical(true), $instance["post_type"],
        $this->get_field_name('post_type'), $this->get_field_id('post_type'), "widefat d4pwdg-select-type d4pwdg-pages-ptype"); ?>

<label for="<?php echo $this->get_field_id('method'); ?>"><?php _e("Display Method", "gd-pages-navigator"); ?>:</label>
<?php $this->display_select_options($this->get_method(), $instance["method"],
        $this->get_field_name('method'), $this->get_field_id('method'), "widefat d4pwdg-select-type"); ?>

<h4><?php _e("Results Sorting", "gd-pages-navigator"); ?>:</h4>

<label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e("Sort By", "gd-pages-navigator"); ?>:</label>
<?php $this->display_select_options($this->get_sort(), $instance["sort"],
        $this->get_field_name('sort'), $this->get_field_id('sort'), "widefat d4pwdg-select-type"); ?>
<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e("Order", "gd-pages-navigator"); ?>:</label>
<?php $this->display_select_options($this->get_order(), $instance["order"],
        $this->get_field_name('order'), $this->get_field_id('order'), "widefat d4pwdg-select-type"); ?>

        </td>
        <td class="splitter"></td>
        <td class="right">

<h4><?php _e("Standard Settings", "gd-pages-navigator"); ?>:</h4>

<label id="<?php echo $this->get_field_id('auto_title'); ?>">
    <input<?php echo $instance['auto_title'] == 1 ? ' checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('auto_title'); ?>" name="<?php echo $this->get_field_name('auto_title'); ?>" />
    <?php _e("Automatic title based on settings.", "gd-pages-navigator"); ?>
</label>

<label id="<?php echo $this->get_field_id('hierarchy'); ?>">
    <input<?php echo $instance['hierarchy'] == 1 ? ' checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('hierarchy'); ?>" name="<?php echo $this->get_field_name('hierarchy'); ?>" />
    <?php _e("Display Items Hierarchy.", "gd-pages-navigator"); ?>
</label>

<label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e("Hierarchy Depth", "gd-pages-navigator"); ?>:</label>
<input class="widefat d4pwdg-input-depth" id="<?php echo $this->get_field_id('depth'); ?>" name="<?php echo $this->get_field_name('depth'); ?>" type="text" value="<?php echo $instance["depth"]; ?>" />
<em>
    <?php _e("Leave 0 to show any hierarchy depth", "gd-pages-navigator"); ?>
</em>

<label for="<?php echo $this->get_field_id("exclude"); ?>"><?php _e("Pages to Exclude", "gd-pages-navigator"); ?>:</label>
<?php
    $attr = array("css_style" => "height: 82px;", "multiple" => 1, "post_type" => $instance["post_type"], "id" => $this->get_field_id("exclude"), "name" => $this->get_field_name("exclude")."[]", "selected" => $instance["exclude"], "hierarchical" => 1);
    $this->dropdown_pages($attr);
?>

        </td>
    </tr>
    <tbody>
</table>

<table>
    <tr>
        <td class="left">

<h4><?php _e("Method", "gd-pages-navigator"); echo ": "; _e("Custom Parent", "gd-pages-navigator"); ?></h4>

<label for="<?php echo $this->get_field_id("parent"); ?>"><?php _e("Root Page", "gd-pages-navigator"); ?>:</label>
<?php
    $attr = array("post_type" => $instance["post_type"], "name" => $this->get_field_name("parent"), "selected" => $instance["parent"], "hierarchical" => 1);
    $this->dropdown_pages($attr);
?>

<h4><?php _e("Method", "gd-pages-navigator"); echo ": "; _e("Navigate", "gd-pages-navigator"); ?></h4>

<label id="<?php echo $this->get_field_id('level_up'); ?>">
    <input<?php echo $instance['level_up'] == 1 ? ' checked="checked"' : ''; ?> type="checkbox" id="<?php echo $this->get_field_id('level_up'); ?>" name="<?php echo $this->get_field_name('level_up'); ?>" />
    <?php _e("Include level ip item if needed.", "gd-pages-navigator"); ?>
</label>

        </td>
        <td class="splitter"></td>
        <td class="right">

<h4><?php _e("Method", "gd-pages-navigator"); echo ": "; _e("Selection", "gd-pages-navigator"); ?></h4>

<?php
    $attr = array("css_style" => "height: 82px;", "multiple" => 1, "post_type" => $instance["post_type"], "id" => $this->get_field_id("selection"), "name" => $this->get_field_name("selection")."[]", "selected" => $instance["selection"], "hierarchical" => 1);
    $this->dropdown_pages($attr);
?>
<em>
    <?php _e("Only selected pages will be displayed.", "gd-pages-navigator"); ?>
</em>

        </td>
    </tr>
</table>

<div class="copyright">
    Dev4Press &copy; 2008 - 2011 <a target="_blank" href="http://www.dev4press.com/">www.dev4press.com</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="http://www.dev4press.com/plugin/gd-pages-navigator/">GD Pages Navigator</a>&nbsp;&nbsp;|&nbsp;&nbsp;version: <strong><?php echo GDPAGESNAVIGATOR_VERSION; ?></strong>
</div>