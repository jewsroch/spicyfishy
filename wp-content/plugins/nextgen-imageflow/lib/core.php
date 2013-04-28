<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if( ! class_exists( 'imageFlow' ) ) {
class imageFlow
{
	/**
	* imageFlow::get_option() - get the options and overwrite them with custom meta settings
	*
	* @param string $key
	* @param int $postID
	* @return array $options
	*/
	function get_option( $key, $post_id )
	{
		// get first the options from the database
		$options = get_option( $key );
		
		// Get all key/value data for the current post. 
		$meta_array = get_post_custom( $post_id );

		// Ensure that this is an array
		if ( !is_array( $meta_array ) )
			$meta_array = array($meta_array);
		
		// assign meta key to db setting key
		$meta_tags = array(
			'string' => array(
				'if_imageCursor' 			=> 'ngg_if_imageCursor',
				'if_onClick' 				=> 'ngg_if_onClick',
				'if_opacityArray' 			=> 'ngg_if_opacityArray',
				'if_reflectionGET' 			=> 'ngg_if_reflectionGET',
				'if_sliderCursor' 			=> 'ngg_if_sliderCursor',
				'if_buttons' 				=> 'ngg_if_buttons',
				'if_captions' 				=> 'ngg_if_captions',
				'if_imageScaling' 			=> 'ngg_if_imageScaling',
				'if_opacity' 				=> 'ngg_if_opacity',
				'if_preloadImages' 			=> 'ngg_if_preloadImages',
				'if_reflections' 			=> 'ngg_if_reflections',
				'if_reflectionPNG' 			=> 'ngg_if_reflectionPNG',
				'if_slider' 				=> 'ngg_if_slider',
				'if_startAnimation' 		=> 'ngg_if_startAnimation',
				'if_preloadImages' 			=> 'ngg_if_preloadImages',
				'if_singleItemTag' 			=> 'ngg_if_singleItemTag',
				'if_slideshow' 				=> 'ngg_if_slideshow',
				'if_slideshowLeftToRight' 	=> 'ngg_if_slideshowLeftToRight',
				'if_cycle' 					=> 'ngg_if_cycle'
			),

			'int' => array(
				'if_aspectRatio' 			=> 'ngg_if_aspectRatio',
				'if_imageFocusM' 			=> 'ngg_if_imageFocusM',
				'if_imageFocusMax' 			=> 'ngg_if_imageFocusMax',
				'if_imagesHeight' 			=> 'ngg_if_imagesHeight',
				'if_imagesM' 				=> 'ngg_if_imagesM',
				'if_percentLandscape' 		=> 'ngg_if_percentLandscape',
				'if_percentOther' 			=> 'ngg_if_percentOther',
				'if_reflectionP' 			=> 'ngg_if_reflectionP',
				'if_scrollbarP'		 		=> 'ngg_if_scrollbarP',
				'if_sliderWidth' 			=> 'ngg_if_sliderWidth',
				'if_startID' 				=> 'ngg_if_startID',
				'if_xStep' 					=> 'ngg_if_xStep',
				'if_animationSpeed' 		=> 'ngg_if_animationSpeed',
				'if_slideshowInterval' 		=> 'ngg_if_slideshowInterval'
			),
		);

		foreach( $meta_tags as $typ => $meta_keys )
		{
			foreach( $meta_keys as $key => $db_value )
			{
				// if the key exists overwrite it with the custom field
				if( array_key_exists( $key, $meta_array ) )
				{
					switch( $typ )
					{
						case 'string':
							$options[$db_value] = (string) attribute_escape($meta_array[$key][0]);
							break;
							
						case 'int':
							$options[$db_value] = (int) $meta_array[$key][0];
							break;
					}
				}
			}
		}
		return $options;
	}

} // End class
}
?>