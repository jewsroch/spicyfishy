<?php
/*
Plugin Name: SimpleScripts Footer
Plugin URI: http://www.simplescripts.com
Description: Please help support SimpleScripts and your web host by keeping this plugin enabled!
Version: 1.0
Author: SimpleScripts.com
Author URI: http://www.simplescripts.com
*/

/* 
Reference:
http://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer

Usage: 
This plugin interacts directly with the wp_footer() theme call, so no extra action is required.
*/

//retrieve and show the ss_footer
function ss_show_footer() {
        //check for cached version, check expiration date (24 hours). if expired or empty, try to grab latest keyword by host. if unable to grab, use cached version or default
        $footer = get_option('simplescripts_footer_text');
        if (empty($footer) || get_option('simplescripts_footer_expire') < date('U')) {
                $tmp_footer = ss_grab_footer();
                if (false === empty($tmp_footer)) {
                        return print '<p class="footer">'.$tmp_footer.'</p>';
                }
        }
        return print '<p class="footer">'.$footer.'</p>';
}

//set the initial options
function ss_set_options() {
        update_option('simplescripts_footer_text', ss_grab_footer());
        update_option('simplescripts_footer_expire',date('U', strtotime('+24 hours')));
        update_option('simplescripts_footer_host', 'ss_host_key'); //default host
}

//grab the current footer by host, update the cache
function ss_grab_footer() {
        $host = get_option('simplescripts_footer_host');
        if (empty($host) || $host == 'ss_host_key') { $host = 'bNMiW8DfI9Is'; }
        $sig = md5(trim(str_replace('http://','',get_option('siteurl')),'/ '));
        $contents = maybe_unserialize(wp_remote_fopen('http://www.simplescripts.com/api/host_footer.php?host='.$host.'&sig='.$sig));
        update_option('simplescripts_footer_expire', ((empty($contents['expire'])) ? date('U', strtotime('+24 hours')) : date('U', strtotime($contents['expire']))));
        $text = (is_array($contents) && isset($contents['text'])) ? strval($contents['text']) : strval($contents);
        update_option('simplescripts_footer_text', $text);
        return $text;
}

//register the plugin
register_activation_hook(__FILE__, 'ss_set_options');
add_action('wp_footer', 'ss_show_footer');
?>