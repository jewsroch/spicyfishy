<?php

if (!defined('ABSPATH')) exit;

class d4ppn_WalkerPageFlat extends Walker_Page {
    function start_lvl(&$output, $depth) {
        $output.= "";
    }

    function end_lvl(&$output, $depth) {
        $output.= "";
    }
}

class d4ppn_WalkerPageDropdown extends Walker_PageDropdown {
    function start_el(&$output, $page, $depth, $args) {
        $pad = str_repeat('&nbsp;', $depth * 3);

        $output .= "\t<option class=\"level-$depth\" value=\"$page->ID\"";
        if (in_array($page->ID, $args['selected'])) {
            $output .= ' selected="selected"';
        }
        $output .= '>';
        $title = apply_filters( 'list_pages', $page->post_title );
        $output .= $pad . esc_html( $title );
        $output .= "</option>\n";
    }
}

class d4ppn_gdPagesNavigator extends gdr2_Widget {
    var $widget_base = "d4p_pages_navigator";
    var $widget_domain = "d4p_pages_navigator";
    var $cache_prefix = "d4ppn";
    var $folder_name = "gd-pages-navigator";

    var $title = "";

    var $defaults = array(
        "title" => "Pages",
        "_display" => "all",
        "auto_title" => 1,
        "post_type" => "page",
        "method" => "full",
        "hierarchy" => 1,
        "level_up" => 1,
        "parent" => 0,
        "depth" => 0,
        "sort" => "menu_order",
        "order" => "asc",
        "exclude" => array(),
        "selection" => array()
    );

    function __construct($id_base = false, $name = "", $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Enhanced pages navigation.", "gd-pages-navigator");
        $this->widget_name = "GD Pages Navigator";
        parent::__construct($this->widget_base, $this->widget_name, array(), array('width' => 500));
    }

    function form($instance) {
        global $gdpn_core;
        $instance = wp_parse_args((array)$instance, $this->defaults);

        echo '<div class="d4p-gdpn-widget">';
        include(GDPAGESNAVIGATOR_PATH.'widget/form.php');
        echo '</div>';
    }

    function title($instance) {
        if ($this->title == "") {
            return $instance["title"];
        } else {
            return $this->title;
        }
    }

    function get_method() {
        return array(
            "full" => __("Full List", "gd-pages-navigator"),
            "parent" => __("Custom Parent", "gd-pages-navigator"),
            "navigate" => __("Navigate", "gd-pages-navigator"),
            "selection" => __("Selection", "gd-pages-navigator")
        );
    }

    function get_order() {
        return array(
            "asc" => __("Ascending", "gd-pages-navigator"),
            "desc" => __("Descending", "gd-pages-navigator")
        );
    }

    function get_sort() {
        return array(
            "menu_order" => __("Menu Order", "gd-pages-navigator"),
            "post_title" => __("Page Title", "gd-pages-navigator"),
            "post_name" => __("Page Slug", "gd-pages-navigator"),
            "ID" => __("Page ID", "gd-pages-navigator"),
            "post_date" => __("Creation Date", "gd-pages-navigator"),
            "post_modified" => __("Modification Date", "gd-pages-navigator")
        );
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        gdp_sdump($new_instance);
        $instance["title"] = strip_tags(stripslashes($new_instance["title"]));
        $instance["_display"] = strip_tags(stripslashes($new_instance["_display"]));
        $instance["auto_title"] = isset($new_instance["auto_title"]) ? 1 : 0;
        $instance["hierarchy"] = isset($new_instance["hierarchy"]) ? 1 : 0;
        $instance["level_up"] = isset($new_instance["level_up"]) ? 1 : 0;
        $instance["depth"] = intval(stripslashes($new_instance["depth"]));
        $instance["post_type"] = strip_tags(stripslashes($new_instance["post_type"]));
        $instance["method"] = strip_tags(stripslashes($new_instance["method"]));
        $instance["sort"] = strip_tags(stripslashes($new_instance["sort"]));
        $instance["order"] = strip_tags(stripslashes($new_instance["order"]));
        $instance["parent"] = strip_tags(stripslashes($new_instance["parent"]));
        gdp_sdump($new_instance["exclude"]);
        gdp_sdump($new_instance["selection"]);
        $instance["exclude"] = array_values($new_instance["exclude"]);
        $instance["selection"] = array_values($new_instance["selection"]);

        return $instance;
    }

    function list_full($instance, $args) {
        return "<ul>".wp_list_pages($args)."</ul>";
    }

    function list_parent($instance, $args) {
        $args["child_of"] = $instance["parent"];
        $page = get_page($instance["parent"]);
        if ($instance["auto_title"]) {
            $this->title = $page->post_title;
        }
        return "<ul>".wp_list_pages($args)."</ul>";
    }

    function list_navigate($instance, $args) {
        if (is_singular($args["post_type"])) {
            global $post;

            if ($this->has_children($post->ID)) {
                $args["child_of"] = $post->ID;
            } else {
                $args["child_of"] = $post->post_parent;
            }

            if ($instance["level_up"] == 1 && $post->post_parent > 0) {
                $parent = get_page($post->post_parent);
                $args["title_li"] = '<a href="'.get_permalink($post->post_parent).'">'.$parent->post_title.'</a>';
            }

            if ($instance["auto_title"] == 1) {
                $this->title = $post->post_title;
            }
        }

        return "<ul>".wp_list_pages($args)."</ul>";
    }

    function list_selection($instance, $args) {
        if (is_array($instance["selection"]) && !empty($instance["selection"])) {
            $args["include"] = join(",", $instance["selection"]);
        }
        return "<ul>".wp_list_pages($args)."</ul>";
    }

    function has_children($post_id) {
        $children = get_pages("child_of=".$post_id);
        if (count($children) > 0) {
            return $children;
        } else {
            return false;
        }
    }

    function dropdown_pages($args = "") {
        $defaults = array(
            "depth" => 0, "child_of" => 0,
            "selected" => array(), "echo" => 1,
            "name" => "page_id", "id" => "",
            "show_option_none" => "", 
            "show_option_no_change" => "",
            "option_none_value" => "",
            "multiple" => 0,
            "css_class" => "",
            "css_style" => ""
        );

        $args["walker"] = new d4ppn_WalkerPageDropdown();
        $args["selected"] = (array)$args["selected"];
        $r = wp_parse_args($args, $defaults);
        extract($r, EXTR_SKIP);

        $pages = get_pages($r);
        $output = '';
        $name = esc_attr($name);

        if (empty($id)) $id = $name;

        if (!empty($pages)) {
            $output = '<select'.($css_style != '' ? ' style="'.$css_style.'"' : '').($multiple == 1 ? ' multiple' : '').' name="'.$name.'" id="'.$id.'" class"'.$css_class.'">'.GDR2_EOL;
            if ($show_option_no_change) {
                $output.= '<option value="-1">$show_option_no_change</option>'.GDR2_EOL;
            }
            if ($show_option_none) {
                $output.= '<option value="'.esc_attr($option_none_value).'">'.$show_option_none.'</option>'.GDR2_EOL;
            }
            $output.= walk_page_dropdown_tree($pages, $depth, $r);
            $output.= '</select>';
        }

        $output = apply_filters('wp_dropdown_pages', $output);

        if ($echo) {
            echo $output;
        }

        return $output;
    }

    function results($instance) {
        $args = array("title_li" => "", "echo" => 0);

        $args["depth"] = $instance["depth"];
        $args["post_type"] = $instance["post_type"];
        $args["sort_order"] = $instance["order"];
        $args["sort_column"] = $instance["sort"];

        if ($instance["hierarchy"] == 0) {
            $args["walker"] = new d4ppn_WalkerPageFlat();
        }
        if ($args["sort_column"] == "menu_order") {
            $args["sort_column"] = "menu_order, post_title";
        }
        if (is_array($instance["exclude"]) && !empty($instance["exclude"])) {
            $args["exclude"] = join(",", $instance["exclude"]);
        }

        $results = "";
        switch ($instance["method"]) {
            default:
            case "full":
                $results = $this->list_full($instance, $args);
                break;
            case "parent":
                $results = $this->list_parent($instance, $args);
                break;
            case "navigate":
                $results = $this->list_navigate($instance, $args);
                break;
            case "selection":
                $results = $this->list_selection($instance, $args);
                break;
        }
        return $results;
    }

    function render($results, $instance) {
        $render = '<div class="d4p-widget d4p-pages_navigator">'.GDR2_EOL;
        $render.= $results;
        $render.= '</div>'.GDR2_EOL;
        return $render;
    }
}

?>