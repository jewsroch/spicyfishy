<?php

/*
Plugin Name: GD Pages Navigator
Plugin URI: http://www.dev4press.com/plugins/gd-pages-navigator/
Description: Use this widget to change the way navigation works for pages and other hierarchical post type with different navigation methods.
Version: 5.1.2
Author: Milan Petrovic
Author URI: http://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2012 Milan Petrovic (email: milan@gdragon.info)

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

require_once(dirname(__FILE__)."/code/defaults.php");
require_once(dirname(__FILE__)."/gdr2/gdr2.core.php");
require_once(dirname(__FILE__)."/gdr2/gdr2.widget.php");
require_once(dirname(__FILE__)."/widget/load.php");

class gdPagesNavigator {
    private $plugin_url;
    private $plugin_path;
    private $script;

    function __construct() {
        $gdd = new gdPagesNavigator_Defaults();

        define("GDPAGESNAVIGATOR_VERSION", $gdd->default_options["version"]);

        $this->script = $_SERVER["PHP_SELF"];
        $this->script = end(explode("/", $this->script));

        add_action("init", array($this, "load_translation"));
        add_action("widgets_init", array(&$this, "widgets_init"));
        add_action("admin_head", array(&$this, "admin_head"));

        $this->plugin_path_url();
    }

    private function plugin_path_url() {
        $this->plugin_url = plugins_url("/gd-pages-navigator/");
        $this->plugin_path = dirname(__FILE__)."/";

        define("GDPAGESNAVIGATOR_URL", $this->plugin_url);
        define("GDPAGESNAVIGATOR_PATH", $this->plugin_path);
    }

    public function load_translation() {
        $this->l = get_locale();
        if(!empty($this->l)) {
            $moFile = GDPAGESNAVIGATOR_PATH."languages/gd-pages-navigator-".$this->l.".mo";
            if (@file_exists($moFile) && is_readable($moFile)) load_textdomain("gd-pages-navigator", $moFile);
        }
    }

    public function widgets_init() {
        register_widget("d4ppn_gdPagesNavigator");
    }

    public function admin_head() {
        if ($this->script == "widgets.php") { ?>
<style type="text/css">
.d4p-gdpn-widget label { line-height: 24px; height: 24px; display: block; }
.d4p-gdpn-widget select { width: 100%; height: 2.4em; padding: 4px 2px; background-color: #FFFFFF; }
.d4p-gdpn-widget input { background-color: #FFFFFF; }
.d4p-gdpn-widget h4 { margin: 5px 0 2px; text-decoration: underline; padding: 3px 0 0; border-top: 1px solid; font-size: 1.1em; }
.d4p-gdpn-widget em { display: block; padding-left: 15px; font-size: 11px;}

.d4p-gdpn-widget table { width: 100%; }
.d4p-gdpn-widget table td { vertical-align: top; }
.d4p-gdpn-widget table td.left { width: 49%; }
.d4p-gdpn-widget table splitter { width: 2%; }
.d4p-gdpn-widget table td.right { width: 49%; }
.d4p-gdpn-widget .copyright { border-top: 1px solid #DFDFDF; border-bottom: 1px solid #DFDFDF; font-size: 11px; margin-top: 10px; padding: 10px 0; text-align: center; }
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".d4pwdg-pages-ptype").live("change", function(){
        jQuery(this).parent().parent().parent().parent().parent().parent().parent().find(".widget-control-actions input").click();
    });
});
</script>
        <?php }
    }

    public function get_posttypes_hierarchical($builtin = false) {
        $data = array();
        $filter = array("public" => true, "hierarchical" => true);
        if (!$builtin) $filter["_builtin"] = false;
        $pt = get_post_types($filter, "objects");
        foreach ($pt as $post_type => $settings) {
            $data[$post_type] = $settings->label;
        }
        return $data;
    }
}

$gdpn_core = new gdPagesNavigator();

?>