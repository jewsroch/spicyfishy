<?php

	/*
	Plugin Name: Wikinvest Stock Quotes
	Plugin URI: http://www.wikinvest.com/blogger/wikinvest_stockquotes
	Description: Display in-line stock quotes on your blog.
	Author: Wikinvest
	Version: 1.03
	Author URI: http://www.wikinvest.com/blogger/wikinvest_stockquotes
	*/
	
	if( !class_exists( "WikinvestStockQuotes" ) ) {
		class WikinvestStockQuotes {
			
			//Start-- Class Variables
			
				//URL to send API Requests to
				var $wikinvestServer = "http://plugins.wikinvest.com";
				var $pluginApiUrl = '';
				var $backendJavascriptUrl = '';
				var $frontendJavascriptUrl = '';
				
				//Current version
				var $pluginVersion = '1.01';
				var $platform = 'wp';
				
				//Change this to bust tinymce config file cache
				var $lastModifiedDate = "20090217";
			
			//End-- Class Variables
			
			//Start-- Constructor
			
				function WikinvestStockQuotes() {
					$this->pluginApiUrl = $this->wikinvestServer . "/plugin/api.php";
					$this->backendJavascriptUrl = $this->wikinvestServer . "/plugin/javascript/stockQuotes/backend/wordpress/";
					$this->frontendJavascriptUrl = $this->wikinvestServer . "/plugin/javascript/stockQuotes/frontend/wordpress/";
				}
			
			//End-- Constructor
			
			
			//Start-- Functions that are called by WP actions/filter: windows to the different points in the app
				
				/**
				 * This function shows notices on admin pages
				 * @return none
				 */
				function processWordpressInitialization() {
					$this->addButtons();
				}
			
				/**
				 * This function adds elements -- like script and css files -- to the <head> section of the HTML documents
				 * @return none
				 */
				function addHeadInformation() {
					$javascriptUrl = $this->frontendJavascriptUrl;
					print("
						<script type='text/javascript'>
						wikinvestStockQuotes_callbackUrl = '".$this->getPluginFolderUrl()."/wikinvest-stock-quotes.php';
						wikinvestStockQuotes_blogUrl = '".$this->getSiteUrl()."';
						wikinvestStockQuotes_wpVersion = '".$this->getPlatformVersion()."';
					</script>
					<script type='text/javascript' src='{$javascriptUrl}'></script>
					");
				}
				
				/**
				 * This function adds elements -- like script and css files -- to the <head> section of the admin HTML documents
				 * @return none
				 */
				function addAdminHeadInformation() {
					global $pagenow;	//which page are we in
					
					$loadInPages = array(
							"post.php",
							"post-new.php",
							"page.php",
							"page-new.php"
							);

					if(in_array($pagenow,$loadInPages)) {
						print "<script type='text/javascript'>
							wikinvestStockQuotes_callbackUrl = '".$this->getPluginFolderUrl()."/wikinvest-stock-quotes.php';
							wikinvestStockQuotes_blogUrl = '".$this->getSiteUrl()."';
							wikinvestStockQuotes_wpVersion = '".$this->getPlatformVersion()."';
							wikinvestStockQuotes_buttonUrl = '". $this->wikinvestServer .
											"/plugin/images/wikinvest_btn_wp.gif';
						</script>";
							print "<script src='{$this->backendJavascriptUrl}' type='text/javascript'></script>";
					}
				}
							
				/**
				 * This function does things that need to be done on the plugin page
				 * @return none
				 */
				function processPluginRow( $pluginFile ) {
					//Handle only the row for my plugin
					if( $pluginFile != 'wikinvest-stock-quotes/wikinvest-stock-quotes.php' ) {
						return;
					}
					$this->handleVersionTasks();
				}					
				
			//End-- Functions to add elements -- like script and css files -- to the <head> section of the HTML documents
			
			//Start -- Functions for storing preferences
			function getDefaultUserPreferences(){
				$userPreferences = array();
				$userPreferences["supportedVersions".$this->pluginVersion] = array(  );
				return $userPreferences;
			}
			
			function getUserPreferences(){
				$userPreferences = get_option("wikinvestStockQuotesUserPreferences");
				
				$defaultUserPreferences = $this->getDefaultUserPreferences();
				if( empty( $userPreferences ) ) { $userPreferences = $defaultUserPreferences; }
				
				// Setting individual preferences if they don't exist; this'll help if we change the name of a preference tomorrow
				$userPreferences["supportedVersions".$this->pluginVersion] = $this->getNullsafeUserPreference( "supportedVersions".$this->pluginVersion, $userPreferences, $defaultUserPreferences );
				
				$this->setUserPreferences( $userPreferences );
				
				return $userPreferences;
			}
			
			function getNullsafeUserPreference( $userPreferenceName, $userPreferences, $defaultUserPreferences ) {
				if( !isset( $userPreferences[$userPreferenceName] ) ) {
					 return $defaultUserPreferences[$userPreferenceName];
				}
				
				return $userPreferences[$userPreferenceName];
			}
			
			function setUserPreferences($userPreferences){
				update_option("wikinvestStockQuotesUserPreferences", $userPreferences);
				return;
			}
				
			
			//Start -- Functions to add button in editor toolbar
			// Make our buttons on the write screens
			function addButtons() {
				// Don't bother doing this stuff if the current user lacks permissions as they'll never see the pages
				if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;

				// Create the buttons based on the WP version number
				if ( 'true' == get_user_option('rich_editing') && $this->getPlatformVersion() >= 2.1 ) {
					// WordPress 2.5+ (TinyMCE 3.x)
					if ( $this->getPlatformVersion() >= 2.5 ) {
						add_filter( 'mce_external_plugins', array(&$this, 'mce_external_plugins') );
						add_filter( 'mce_buttons', array(&$this, 'mce_buttons') );
						add_filter( 'tiny_mce_before_init', array(&$this, 'tinymce_3_before_init'));
						add_filter( 'tiny_mce_version', array($this, 'tinymce_3_version'));
					}

					// WordPress 2.1+ (TinyMCE 2.x)
					else {
						add_filter('tiny_mce_config_url', array(&$this,'tiny_mce_config_url'));
						add_filter('mce_plugins', array(&$this, 'mce_plugins'));
						add_filter('mce_buttons', array(&$this, 'mce_buttons'));
						add_action('tinymce_before_init', array(&$this, 'tinymce_before_init'));
					}
				} 
			}
			
			// TinyMCE integration hooks
			/* Give the last changed date to tinymce - used for busting cache of tinymce config */
			function tinymce_3_version( $ver ) {
				$lastModifiedDate = $this->lastModifiedDate;
				if($ver > $lastModifiedDate) {
					$lastModifiedDate = $ver;
				}
				return $lastModifiedDate;
			}
			
			/* Register TinyMCE plugin */
			function mce_external_plugins( $plugins ) {
				// WordPress 2.5
				$plugins['wikinvestStockQuotes'] = $this->getPluginFolderUrl() . '/tinymce3.js';
				return $plugins;
			}
					
			/* Specify the buttons to add to tinyMCE */
			function mce_buttons($buttons) {
				array_push( $buttons, 'separator', 'wikinvestStockQuotes' );	
				return $buttons;
			}
			
			/* Configure TinyMCE to allow all attributes for <span> and <a> tags */
			function tinymce_3_before_init($init) {
				// Command separated string of extended elements
				$ext = 'span[*],a[*]';

				// Add to extended_valid_elements if it alreay exists
				if ( isset( $init['extended_valid_elements'] ) ) {
					$init['extended_valid_elements'] .= ',' . $ext;
				} else {
					$init['extended_valid_elements'] = $ext;
				}

				// Super important: return $init!
				return $init;
			}
			
			/* Bust cache of TinyMCE config file */
			function tiny_mce_config_url($url) {
				$lastModifiedDate = $this->lastModifiedDate;
				if(strpos($url,"?") === false) {
					$url .= "?"."wikinvestStockQuotes=".$lastModifiedDate;;
				}
				else {
					$url .= "&wikinvestLiveQutoes=".$lastModifiedDate;
				}
				return $url;
			}
			
			/* Register TinyMCE plugin */
			function tinymce_before_init() {
				// WordPress 2.1
				echo 'tinyMCE.loadPlugin("wikinvestStockQuotes", "' . $this->getPluginFolderUrl() . '/editor_plugin.js");';
			}	
			
			//Add to the list of TinyMCE Plugins
			function mce_plugins($plugins) {
				// WordPress 2.1
				$plugins[] = '-wikinvestStockQuotes';
				return $plugins;
			}
					
			//End -- Functions to add button in editor toolbar
			
			//Start-- Functions for version management
			
			function getPlatform() {
				return $this->platform;
			}
			
			function getPlatformVersion() {
				global $wp_version;
				return $wp_version;
			}
			
			function handleVersionTasks() {
				// First, let's check for compatibility
				$this->checkForVersionCompatibility();
				
				// Let us tell the user of upgrades etc.
				$this->notifyVersionInormation();
			}
			
			function checkForVersionCompatibility() {
				$userPreferences = $this->getUserPreferences();
				
				if( in_array( $this->getPlatformVersion(), $userPreferences['supportedVersions'.$this->pluginVersion] ) ) {
					// Supported by default
					return;
				}
				
				// Not supported by default; check the API to see if this plug-in version is forward compatible with this WP version
				$isSupported = $this->checkServiceForVersionCompatibility();
				if( $isSupported === "1" ) {
					// It's supported; add it to the supported list for the future
					$userPreferences['supportedVersions'.$this->pluginVersion][] = $this->getPlatformVersion();
					$this->setUserPreferences( $userPreferences );
					return;
				}
				
				//Not supported; let the server notify
			}
			
			function notifyVersionInormation() {
				$versionInformation = $this->getVersionInformationFromService();
				$versionInformation = $this->sanitizeServiceHtmlReponse( $versionInformation );
				if( $versionInformation != "" ) {
					print( "
						<tr><td colspan='5' class='plugin-update'>
							{$versionInformation}
						</td></tr>
					" );
				}
			}
			
			function checkServiceForVersionCompatibility() {
				$url = $this->pluginApiUrl;
				$url .= '?';
				$url .= 'action=versioncompatibility';
				$url .= '&';
				$url .= 'name=' . $this->encodeUrlData( 'stockquotes' );
				$url .= '&';
				$url .= 'platform=' . $this->encodeUrlData( $this->getPlatform() );
				$url .= '&';
				$url .= 'version=' . $this->encodeUrlData( $this->pluginVersion );
				$url .= '&';
				$url .= 'platform_version=' . $this->encodeUrlData( $this->getPlatformVersion() );
				$url .= '&';
				$url .= 'format=text';
				
				$response = $this->getRemoteFile( $url );
				
				if( trim( $response ) !== '' ) {
					return $response;
				}
				
				return false;
			}
			
			function getVersionInformationFromService() {
				$url = $this->pluginApiUrl;
				$url .= '?';
				$url .= 'action=versioninformation';
				$url .= '&';
				$url .= 'name=' . $this->encodeUrlData( 'stockquotes' );
				$url .= '&';
				$url .= 'platform=' . $this->encodeUrlData( $this->getPlatform() );
				$url .= '&';
				$url .= 'version=' . $this->encodeUrlData( $this->pluginVersion );
				$url .= '&';
				$url .= 'platform_version=' . $this->encodeUrlData( $this->getPlatformVersion() );
				$url .= '&';
				$url .= 'format=html';
				
				$response = $this->getRemoteFile( $url );
				
				if( trim( $response ) !== '' ) {
					return $response;
				}
				
				return false;
			}
			
			//End-- Functions for version management
	
			//Start-- Utility functions
			
			function getRemoteFile( $url ) {
			  $errorReporting = error_reporting();
			  error_reporting(0);
			  
			  require_once(ABSPATH.WPINC.'/class-snoopy.php');
			  $content = false;
			  $sn = new Snoopy();
			  $sn->read_timeout = 2;
			  if( $sn->fetch( $url ) ) {
				  $content = $sn->results;
			  }
			  
			  error_reporting($errorReporting);
			  
				if ( $content === false ) { return false; }
				
				return $content;
			}
			
			function postToRemoteFile( $url, $data ) {
				$errorReporting = error_reporting();
				error_reporting(0);

				require_once(ABSPATH.WPINC.'/class-snoopy.php');
				
				$content = false;
				$sn = new Snoopy();
				$sn->read_timeout = 30;
				if( $sn->submit( $url, $data ) ) {
					$content = $sn->results;
				}
				error_reporting($errorReporting);

				if ( $content === false ) { 
					return false; 
				}

				return $content;
			}
			
			function getSiteUrl(){
				$siteUrl = get_bloginfo("wpurl");
				return $siteUrl;
			}
			
			function getPluginFolderUrl() {
				return get_bloginfo('wpurl') . '/wp-content/plugins/wikinvest-stock-quotes';
			}
			
			function sanitizeServiceHtmlReponse( $htmlResponse ) {
				//TODO: Use reg-ex
				$startDelimiter = "<!--Wikinvest API HTML Response-->";
				$endDelimiter = "<!--/Wikinvest API HTML Response-->";
				
				$startDelimiterStartPos = strpos( $htmlResponse, $startDelimiter );
				$endDelimiterStartPos = strpos( $htmlResponse, $endDelimiter );
				
			  if( $startDelimiterStartPos === false	|| $endDelimiterStartPos === false ) {
				 return "";
			  }
			  
			  // Chunk of $htmlResponse after (and including) the first occourence of $startDelimiter
			  $startStripped = substr( $htmlResponse, $startDelimiterStartPos );
			  
			  // Explode the result by $endDelimiter
			  $endDelimiterChunks = explode( $endDelimiter, $startStripped );
				$endStripped = "";
				
			  // Piece it back together except the last item (we can also use implode but then we have to unset the last item)
				for($counter = 0; $counter < count($endDelimiterChunks) - 1; $counter++) {
					$endDelimiterChunk = $endDelimiterChunks[$counter];
					$endStripped .= $endDelimiterChunk;
					$endStripped .= $endDelimiter;
				}
				
				return trim( $endStripped );
		  }
		
			/**
			 * Returns a copy of $data with special character escaping removed. $data can be an array
			 */
			function decodeHtmlInput( $data ) {
				if( is_array( $data ) ) {
					$retdata = array();
					foreach( $data as $index => $val ) {
						$retdata[$index] = $this->decodeHtmlInput( $data[$index] );
					}
					return $retdata;
				}
				
				$retdata = stripslashes( $data );
				return $retdata;
			}
		
			/**
			 * Returns a copy of $data with special characters encoded. $data can be an array
			 */
			function encodeHtmlOutput( $data ) {
				if( is_array( $data ) ) {
					$retdata = array();
					foreach( $data as $index => $val ) {
						$retdata[$index] = $this->encodeHtmlOutput( $data[$index] );
					}
					return $retdata;
				}
				
				$retdata = htmlentities( $data, ENT_QUOTES );
				return $retdata;
			}
		
			/**
			 * Modifies $data so that it can be passed in a URL. $data can be an array
			 */
			function encodeUrlData( $data ) {
				if( is_array( $data ) ) {
					$retdata = array();
					foreach( $data as $index => $val ) {
						$retdata[$index] = $this->encodeUrlData( $data[$index] );
					}
					return $retdata;
				}
				
				$retdata = urlencode( $data );
				return $retdata;
			}			
			//End-- Utility functions
			
			//AJAX passthru function
			//for communicating with Plugin API
			//Send all GET/POST params to the API
			function ajaxPassThru() {
				if($_SERVER['REQUEST_METHOD'] == "GET") {
					//send all the get parameters
					$url = $this->pluginApiUrl;
					$getParams = array();
					foreach($_GET as $k=>$v) {
						$getParams[] = urlencode($k)."=".urlencode($v);
					}
					if(count($getParams) > 0) {
						$url .= "?" . implode("&", $getParams);
					}
					echo $this->getRemoteFile($url);
				}
				else {
					echo $this->postToRemoteFile($this->pluginApiUrl,array_merge($_GET,$_POST));
				}
			}
		}
	}
	
	
	//Start-- WP actions/filters
	if ( class_exists( "WikinvestStockQuotes" ) ) {
		$wikinvestStockQuotes = new WikinvestStockQuotes();
		
		if(defined("ABSPATH") && defined("WPINC")) {
		
			//Initialization
			add_action( 'init', array(&$wikinvestStockQuotes, "processWordpressInitialization") );
			
			//Adding scripts and stylesheets to the <head> (using default priority)
			add_action( "wp_footer", array( &$wikinvestStockQuotes, "addHeadInformation" ) );
			
			//Adding scripts to the <head> of admin pages for edit pages
			add_action( "admin_head", array( &$wikinvestStockQuotes, "addAdminHeadInformation" ) );
			
			//Adding the plugin information row
			add_action( 'after_plugin_row', array(&$wikinvestStockQuotes, "processPluginRow") );
		}
		else {
			//This file has been directly hit
			//Pass-thru data to the Wikinvest API
			define("ABSPATH",realpath("../../.."));
			define("WPINC","/wp-includes");
			$wikinvestStockQuotes->ajaxPassThru();
		}
	}
	//End-- WP actions/filters

?>
