<?php
/**
 * @author Alex Rabe, Vincent Prat, adjusted by Boris Glumpler
 * @copyright 2008
 * @since 1.0.0
 * @description Use WordPress Shortcode API for more features
 * @Docs http://codex.wordpress.org/Shortcode_API
 */

class NextGENflow_shortcodes {

	// register the new shortcodes
	function NextGENflow_shortcodes() {
	
		// convert the old shortcode
		add_filter('the_content', array(&$this, 'convert_flow_shortcode'));
		
		add_shortcode( 'imageflow', array(&$this, 'show_imageflow' ) );
		add_shortcode( 'if-tags', array(&$this, 'show_flowtags' ) );
		add_shortcode( 'if-thumbs', array(&$this, 'show_flowthumbs' ) );
		add_shortcode( 'if-rand', array(&$this, 'show_flowrandom' ) );
		add_shortcode( 'if-recent', array(&$this, 'show_flowrecent' ) );
		add_shortcode( 'if-album', array(&$this, 'show_flowalbum' ) );
	}

	function convert_flow_shortcode($content) {

		$ngg_if_options = get_option('ngg_if_options');

		if ( stristr( $content, '[imageflow' )) {
			$search = "@(?:<p>)*\s*\[imageflow\s*=\s*(\w+|^\+)\]\s*(?:</p>)*@i";
			if (preg_match_all($search, $content, $matches, PREG_SET_ORDER)) {

				foreach ($matches as $match) {
					// remove the comma
					$replace = "[imageflow id=\"{$match[1]}\"]";
					$content = str_replace ($match[0], $replace, $content);
				}
			}
		}

		return $content;
	}

	/**
	 * [imageflow id=""]
	 * where 
	 * - id is a gallery id
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_imageflow( $atts ) {
		
		global $wpdb;
	
		extract(shortcode_atts(array(
			'id' 		=> 0
		), $atts ));
		
		$out = nggShowFlow( 'gallery', $id);
			
		return $out;
	}

	/**
	 * [if-tags tags=""]
	 * where 
	 * - tags is a comma seperated list of tags
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_flowtags( $atts )
	{	
		extract(shortcode_atts(array(
			'tags' 		=> '',
		), $atts ));

		$out = nggShowFlow( 'tags', $tags);
		
		return $out;
	}

	/**
	 * [if-thumb id="1,2,4,5,..."]
	 * where 
	 * - id is a comma seperated list of image ids
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_flowthumbs( $atts )
	{	
		extract(shortcode_atts(array(
			'id' 		=> '',
		), $atts));
		
		$out = nggShowFlow( 'thumbs', $id );
	
		return $out;
	}

	/**
	 * [if-rand max=""]
	 * where 
	 * - max is the maximum number of images to show
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_flowrandom( $atts )
	{	
		extract(shortcode_atts(array(
			'max'		=> '',
		), $atts));
		
		$out = nggShowFlow('random', $max);
		
		return $out;
	}

	/**
	 * [if-recent max=""]
	 * where 
	 * - max is the maximum number images to show
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_flowrecent( $atts )
	{	
		extract(shortcode_atts(array(
			'max'		=> '',
		), $atts));
		
		$out = nggShowFlow('recent', $max);
		
		return $out;
	}

	/**
	 * [iv-album id=""]
	 * - id is the album id
	 * 
	 * @param array $atts
	 * @return the_content
	 */
	function show_flowalbum( $atts )
	{
		extract(shortcode_atts(array(
			'id'		=> '',
		), $atts));
		
		$out = nggShowFlow('album', $id);
		
		return $out;
	}


}

// let's use it
$nggFlowShortcodes = new NextGENflow_shortcodes;	

?>