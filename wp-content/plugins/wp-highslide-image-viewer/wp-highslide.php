<?php

/*
Plugin Name: WP-Highslide
Version: 1.28
Plugin URI: http://projects.jesseheap.com/all-projects/wordpress-highslide-js-plugin/
Description: This plugin eases insertion of highslide js thumbnail viewer by inserting the required tags
Author: Jesse Heap
Author URI: http://projects.jesseheap.com
Contributors: Roel Meurders (Used his FLV Code base as a starting point)
*/

// SCRIPT INFO ///////////////////////////////////////////////////////////////////////////

/*
	WP-Highslide for Wordpress
	(C) 2007 Jesse Heap - GNU General Public License

	This plugin requires:
		Highslide JS 3.2.3 or greater from Torstein Honsi (SEPERATE DOWNLOAD)
		Please not that this player is released under Creative Commons Attribution-NonCommercial 2.5 License.
		Full text at: http://creativecommons.org/licenses/by-nc/2.5/

	This Wordpress plugin is released under a GNU General Public License. A complete version of this license
	can be found here: http://www.gnu.org/licenses/gpl.txt

	This Wordpress plugin has been tested with Wordpress 2.0.5;

	This Wordpress plugin is released "as is". Without any warranty. The author cannot
	be held responsible for any damage that this script might cause.

*/
   
    $jhHighSlide_version = "1.28";
	add_action('admin_menu', 'jhHighSlide_admin_menu');
	add_filter('the_content', 'jhHighSlide_replace', '99');
	// javascript for main blog
	add_action('wp_head', 'jhHighSlide_add_js');
	function jhHighSlide_admin_menu(){
		add_options_page('WP-Highslide', 'WP-Highslide', 9, basename(__FILE__), 'jhHighSlide_options_page');
	}
	// adds the styling and javascript to the WP head
function jhHighSlide_add_js ()
{
	  global $jhHighSlide_version;
    // Get Javascript options
	// These are customizable through wordpress options
	$o = jhHighSlide_get_options();
    echo "\n\t<!-- Added By Highslide Plugin. Version {$jhHighSlide_version}  -->\n";
	echo $o['javascript'];	
}

	function jhHighSlide_replace($content){
		global $wpdb, $post;
		$o = jhHighSlide_get_options();
		$HSVars = array("IMAGE", "THUMBNAIL", "ALTDESC", "CAPTIONTEXT", "THUMBID");
		$HSVals = array($o['image'], $o['thumbnail'], $o['altdesc'], $o['captiontext'], $o['thumbid']);
		//Get attributes from highslide tags
		preg_match_all ('!<highslide([^>]*)[ ]*[/]*>!i', $content, $matches);
		$HSStrings = $matches[0];
		$HSAttributes = $matches[1];
		for ($i = 0; $i < count($HSAttributes); $i++){
			preg_match_all('!(image|thumbnail|altdesc|captiontext)="([^"]*)"!i',$HSAttributes[$i],$matches);
			//$tmp = $HSCode;
			$HSSetVars = $HSSetVals = array();
			for ($j = 0; $j < count($matches[1]); $j++){
				$HSSetVars[$j] = strtoupper($matches[1][$j]);
				$HSSetVals[$j] = $matches[2][$j];
			}
			//Get Optional Flags "SHOW_PRV_NEXT", "SHOW_CLOSE", "SHOW_CAPTION"
			preg_match_all('!(show_prv_next|show_close|show_caption)="([^"]*)"!i',$HSAttributes[$i],$matches);
			$HSSetFlags = array();
			for ($j = 0; $j < count($matches[1]); $j++){
				$HSSetFlags[strtolower($matches[1][$j])] = strtolower($matches[2][$j]);
			}
			// Build Highslide Code based on Flags
			$option = (isset($HSSetFlags['show_prv_next'])) ? $HSSetFlags['show_prv_next'] : $o['show_prv_next'];
			//echo "Show Prv Next: " . $option . "<br>";
			if ($option =='y') {
		  		$HSPrvNextLinks = <<<EOT
		   		<a href="javascript:void(0)" class="highslide-previous" onclick="return hs.previous(this)" title="Previous (left arrow key)">Previous</a>
  		   		<a href="javascript:void(0)" class="highslide-next" onclick="return hs.next(this)"  title="Next (right arrow key)">Next</a> 

EOT;
			}
			else {
		  		$HSPrvNextLinks = '';
		  	}
			$option = (isset($HSSetFlags['show_close'])) ? $HSSetFlags['show_close'] : $o['show_close'];			
			//echo "Show Close: " . $option . "<br>";
			if ($option=='y') {
				$HSClose = <<<EOT
		    	<a href="#" onclick="hs.close(this)" class="highslide-close">Close</a>  
EOT;
			}
			else {
			  	$HSClose = '';
			}			
			$option = (isset($HSSetFlags['show_caption'])) ? $HSSetFlags['show_caption'] : $o['show_caption'];
			//echo "CAPTION VALUE: " . $option . "<br>";
			if ($option=='y') {
				$HSCaption = <<<EOT
				<div class='highslide-caption' id='caption-for-%THUMBID%'>
			    {$HSPrvNextLinks}   		
	     		{$HSClose} 	
				<div style="clear:both">%CAPTIONTEXT%</div>
	
			    </div>
EOT;
			}
			else {
				$HSCaption = '';
			}
			$HSCode = <<<EOT
			    <a href="%IMAGE%" class="highslide"  onclick="return hs.expand(this, {captionId: 'caption-for-%THUMBID%'})"> 
                <img src="%THUMBNAIL%" alt="%ALTDESC%" border="0" id="%THUMBID%" title="%ALTDESC%" /></a> 
				{$HSCaption}
EOT;
			
			for ($j = 0; $j < count($HSVars); $j++){
				$key = array_search($HSVars[$j], $HSSetVars);
				$val = (is_int($key)) ? $HSSetVals[$key] : $HSVals[$j];
				if ($HSVars[$j] == 'THUMBID') {
					$val =  'P' . $post->ID . $i;
				}
				$HSCode = str_replace('%'.$HSVars[$j].'%', $val, $HSCode);
			}
			//Add required div for container.  Only required once
            //if ($i==0)	$tmp = str_replace('%DIV%', '<div id="highslide-container"></div>', $tmp);
	        //	else $tmp = str_replace('%DIV%', '', $tmp);
			$content = str_replace($HSStrings[$i], "\n\n".$HSCode."\n\n", $content);
		}
		return $content;
	}

	function jhHighSlide_get_options(){
	    global $jhHighSlide_version;
		$jhHighslide_wp_url = get_bloginfo('wpurl') . "/";
		$defaults = array();
		$defaults['quicktags'] = 'y';
		$defaults['image'] = 'http://www.pinkcakebox.com/images/cake200.jpg';
		$defaults['thumbnail'] = 'http://www.pinkcakebox.com/images/cake200-circle.jpg';
		$defaults['altdesc'] = 'Enter ALT Tag Description';
		$defaults['captiontext'] = 'Enter Caption Text';
		$defaults['thumbid'] = 'thumb1';
		$defaults['show_caption'] = 'y';
		$defaults['show_close'] = 'y';
		$defaults['show_prv_next'] = 'n';
		//prehtml and posthtml aren't currently being used
		$defaults['prehtml'] = '';
		$defaults['posthtml'] ='';
		$defaults['javascript'] = 	
	    "\n\t<link href='{$jhHighslide_wp_url}wp-content/plugins/highslide/highslide.css' rel='stylesheet' type='text/css' />
				<script type='text/javascript' src='{$jhHighslide_wp_url}wp-content/plugins/highslide/highslide.js'></script>
				<script type='text/javascript'>
				hs.showCredits = false;
	 			hs.graphicsDir = '{$jhHighslide_wp_url}wp-content/plugins/highslide/graphics/';
	    		hs.outlineType = 'rounded-white';
			</script>";
					
		$options = get_option('jhHighslidesettings');
		if (!is_array($options)){
			$options = $defaults;
			update_option('jhHighslidesettings', $options);
		}
	    
		return $options;
	}


	function jhHighSlide_options_page(){
		if ($_POST['jhHighSlide']){
		    $_POST['jhHighSlide']['javascript'] = stripslashes($_POST['jhHighSlide']['javascript']);
			update_option('jhHighslidesettings', $_POST['jhHighSlide']);
			$message = '<div class="updated"><p><strong>Options saved.</strong></p></div>';
		}

		$o = jhHighSlide_get_options();
		$npyes = ($o['show_prv_next'] == 'y') ? ' checked="checked"' : '';
		$npno = ($o['show_prv_next'] == 'y') ?  '' : ' checked="checked"';
		$clsyes = ($o['show_close'] == 'y') ? ' checked="checked"' : '';
		$clsno = ($o['show_close'] == 'y') ?  '' : ' checked="checked"';
		$cptyes = ($o['show_caption'] == 'y') ? ' checked="checked"' : '';
		$cptno = ($o['show_caption'] == 'y') ?  '' : ' checked="checked"';
		$qtyes = ($o['quicktags'] == 'y') ? ' checked="checked"' : '';
		$qtno = ($o['quicktags'] == 'y') ? '' : ' checked="checked"';
		echo <<<EOT
		<div class="wrap">
			<h2>Highslide Options</h2>
			{$message}
			<form name="form1" method="post" action="options-general.php?page=wp-highslide.php">
			<fieldset class="options">
				<legend>Global PLUGIN settings</legend>
				<p>These are the default global settings for highslide.  You can override these global settings for
				individual images by specifying the corresponding override flag in the &lt;highslide&gt; tag.  </p>
				<table width="100%" cellspacing="2" cellpadding="5" class="editform">
					<tr valign="top">
						<th width="50%" scope="row">Use Quicktag to insert HIGHSLIDE tag and parameters</th>
						<td>
							<input type="radio" value="y" name="jhHighSlide[quicktags]"{$qtyes} /> yes 
							<input type="radio" value="n" name="jhHighSlide[quicktags]"{$qtno} /> no
						</td>
					</tr>
					<tr valign="top">
						<th width="50%" scope="row">Display Highslide Caption Box<br>(Override tag: show_caption="n|y")</th>
						<td>
							<input type="radio" value="y" name="jhHighSlide[show_caption]"{$cptyes} /> yes 
							<input type="radio" value="n" name="jhHighSlide[show_caption]"{$cptno} /> no
							  
						</td>
					</tr>
					<tr valign="top">
						<th width="50%" scope="row">Display CLOSE Link in Caption Box<br>(Override tag: show_close="n|y")</th>
						<td>
							<input type="radio" value="y" name="jhHighSlide[show_close]"{$clsyes} /> yes 
							<input type="radio" value="n" name="jhHighSlide[show_close]"{$clsno} /> no
						</td>
					</tr>
					<tr valign="top">
						<th width="50%" scope="row">Display NEXT/PREVIOUS Links in Caption Box<br>(Override tag: show_prv_next="n|y")</th>
						<td>
							<input type="radio" value="y" name="jhHighSlide[show_prv_next]"{$npyes} /> yes 
							<input type="radio" value="n" name="jhHighSlide[show_prv_next]"{$npno} /> no
						</td>
					</tr>
				</table>
			   </fieldset>
				<fieldset class="options">
				<legend>JAVASCRIPT settings</legend>
				<table width="100%" cellspacing="2" cellpadding="5" class="editform">
					<tr valign="top">
						<td  colspan="2" >Edit the javascript below to customize highslide</td>
					</tr>					
					<tr >
						<td colspan="2">
						<textarea name="jhHighSlide[javascript]" cols="100" rows="20">{$o['javascript']}</textarea>
						</td>
					</tr>
				</table>
				</fieldset>
			<p class="submit">
				<input type="submit" name="Submit" value="Update Options &raquo;" />
			</p>
			</form>
		</div>
		<div class="wrap">
	<h2>Information & Support</h2>
	
  <p>Visit <a href="http://projects.jesseheap.com/all-projects/wordpress-highslide-js-plugin/" target="_blank">our 
    help section</a> for installation and help</p>
  <p><strong>Like this script?</strong> Show your support by linking to <a href="http://www.jesseheap.com">our 
    site</a> - www.jesseheap.com.</p>
	</div>
EOT;
	}
	
	if (strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'page.php')) {

		add_action('admin_footer', 'jhHighslideAddQuicktag');

		function jhHighslideAddQuickTag(){
			$o = jhHighSlide_get_options();
			if($o['quicktags'] == 'y'){
				echo <<<EOT
				<script type="text/javascript">
					<!--
						var flvToolbar = document.getElementById("ed_toolbar");
						if(flvToolbar){
							var flvNr = edButtons.length;

							//edButtons[edButtons.length] = new edButton('ed_popin','','','</a>','');
							edButtons[edButtons.length] = new edButton('ed_highslide','','','','');
							var flvBut = flvToolbar.lastChild;
							while (flvBut.nodeType != 1){
								flvBut = flvBut.previousSibling;
							}
							flvBut = flvBut.cloneNode(true);
							flvToolbar.appendChild(flvBut);
							//toolbar.appendChild(flvBut);
							flvBut.value = 'Highslide';
							flvBut.onclick = edInsertFLV;
							flvBut.title = "Insert a Highslide Enabled Image";
							flvBut.id = "ed_highslide";
						}

						function edInsertFLV() {
							if(!edCheckOpenTags(flvNr)){
								var U = prompt('Enter path to Large Image' , '/path/to/myimage.jpg');
								var W = prompt('Enter path to thumbnail image' , '/path/to/thumbnail.jpg');
								var H = prompt('Enter the Alternate Description of the image' , '');
								var A = prompt('Enter the Caption for the image' , '');
							//	var T = prompt('Enter ID for Image' , '{$o["thumbid"]}');
								var theTag = '<highslide image="' + U + '" thumbnail="' + W + '" altdesc="' + H + '" captiontext="' + A +'" />';
								edButtons[flvNr].tagStart  = theTag;
								edInsertTag(edCanvas, flvNr);
							} else {
								edInsertTag(edCanvas, flvNr);
							}
						}

					//-->
				</script>
EOT;
			}
		}
	}

?>