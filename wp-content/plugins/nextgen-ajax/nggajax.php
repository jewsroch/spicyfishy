<?php

/*
Plugin Name: nextGEN_ajax
Description: nextGEN_ajax is a plugin that adds the Ajax functionality to the nG gallery. nextGEN_ajax ist ein Plugin das die nG Gallery um Ajax funkt. erweitert.
Version: 0.1
Author: Steffen Persch
Author URI: http://www.steffenpersch.de
*/


    if($_GET['req'] == 'true') {
        $abspath = substr( dirname(__FILE__),0,strpos(dirname(__FILE__),'wp-content'));
        if ( file_exists( $abspath . 'wp-load.php') ) {
            require_once( $abspath . 'wp-load.php' );
        } else {
           require_once( $abspath . 'wp-config.php' );
        }
        
        set_query_var('pid', $_GET['pid']);
        set_query_var('pageid', $_GET['pageid']);
        set_query_var('nggpage', $_GET['nggpage']);
        
        echo $_GET['containerid'] . '::-::';
        echo nggShowGallery($_GET['galerieid']);

    } else {
        add_action('wp_head','addnggaJS');
    }
        
    function addnggaJS() {
        echo '<!-- begin nextGEN Ajax plugin includes -->
    <script type="text/javascript" src="/wp-content/plugins/nextgen_ajax/js/mt.js"></script>
    <script type="text/javascript" src="/wp-content/plugins/nextgen_ajax/js/get_links.js"></script>
    <script type="text/javascript" src="/wp-content/plugins/nextgen_ajax/js/slimbox.js"></script>
    <link rel="stylesheet" href="/wp-content/plugins/nextgen_ajax/css/slimbox.css" type="text/css" media="screen" />
    <!-- end nextGEN Ajax plugin includes -->';
    }

?>