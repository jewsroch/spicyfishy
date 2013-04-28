<?php

/**
Plugin Name: Fix PHP Max Upload Settings
Plugin URI: http://simplercomputing.net
Description: Attempts to set the max upload size, max form "POST" data size, and script timeout settings in the server's PHP configuration.
Author: Mark - SimplerComputing.net
Version: 1.0
Author URI: http://simplercomputing.net
*/

/**
Released under GPL 2.0 - http://www.gnu.org/licenses/gpl-2.0.html
*/

ini_set( 'upload_max_size' , '100M' );
ini_set( 'post_max_size', '105M');
ini_set( 'max_execution_time', '300' );
?>
