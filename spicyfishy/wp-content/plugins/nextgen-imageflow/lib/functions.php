<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

// Start the counter
if ( !isset($flowCount) )
	$flowCount = 1;

/**
 * nggShowTagsFlash() - router for various tags
 *
 * @param string $type
 * @param mixed $value
 * @return $out
 */
function nggShowFlow( $type, $value )
{
	if( $type == 'random' )
		$picturelist = nggdb::get_random_images( $value );
		
	if( $type == 'recent' )
		$picturelist = nggdb::find_last_images( 0, $value, true );
		
	if( $type == 'tags' )
		$picturelist = nggTags::find_images_for_tags( $value , 'ASC' );
		
	if( $type == 'album' )
		$picturelist = nggdb::find_images_in_album( $value );
		
	if( $type == 'gallery' )
		$picturelist = nggdb::get_gallery( $value );
		
	if($type == 'thumbs')
	{
		$pids = explode( ',', $value );
		if( count( $pids ) == 0 )
			return __('[Pictures not found]','nggallery');
			
		$picturelist = nggdb::find_images_in_list( $pids );
	}

	return varShowImageFlow( $picturelist );
}

function varShowImageFlow( $picturelist )
{
	// go on if not empty
	if( empty( $picturelist ) || ! is_array( $picturelist ) )
		return;

	global $flowCount, $post;

	$flowCount++;

	// get the options
	$ngg_if_options = imageFlow::get_option('ngg_if_options', $post->ID);

	// start the javascript output
	$out  = '<script type="text/javascript" defer="defer">';
	$out .= "\n\t".'domReady(function() {';
	$out .= "\n\t\t".'var instance'.$flowCount.' = new ImageFlow();';
	$out .= "\n\t\t".'instance'.$flowCount.'.init({';

	$out .= "\n\t\t\t".'ImageFlowID:\'ImageFlow_'.$flowCount.'\'';
	
	// only include options if not default value
	if( $ngg_if_options['ngg_if_aspectRatio'] != 1.964 )
		$out .= "\n\t\t\t".', aspectRatio: '. $ngg_if_options['ngg_if_aspectRatio'];

	if( $ngg_if_options['ngg_if_buttons'] == 'true' )
		$out .= "\n\t\t\t".', buttons: '. $ngg_if_options['ngg_if_buttons'];

	if( $ngg_if_options['ngg_if_captions'] == 'false' )
		$out .= "\n\t\t\t".', captions: false';

	if( $ngg_if_options['ngg_if_imageCursor'] != 'default' )
		$out .= "\n\t\t\t".', imageCursor: \''. $ngg_if_options['ngg_if_imageCursor'] .'\'';

	if( $ngg_if_options['ngg_if_imageFocusM'] != 1.0 )
		$out .= "\n\t\t\t".', imageFocusM: '.$ngg_if_options['ngg_if_imageFocusM'];

	if( $ngg_if_options['ngg_if_imageFocusMax'] != 4 )
		$out .= "\n\t\t\t".', imageFocusMax: '. $ngg_if_options['ngg_if_imageFocusMax'];

	if( $ngg_if_options['ngg_if_imageScaling'] == 'false' )
		$out .= "\n\t\t\t".', imageScaling: false';

	if( $ngg_if_options['ngg_if_imagesHeight'] != 0.67 )
		$out .= "\n\t\t\t".', imagesHeight: '. $ngg_if_options['ngg_if_imagesHeight'];
	
	if( $ngg_if_options['ngg_if_imagesM'] != 1.0 )
		$out .= "\n\t\t\t".', imagesM: '. $ngg_if_options['ngg_if_imagesM'];

	if( $ngg_if_options['ngg_if_onClick'] != "function() { document.location = this.url; }" )
		$out .= "\n\t\t\t".', onClick: \''. $ngg_if_options['ngg_if_onClick'] .'\'';

	if( $ngg_if_options['ngg_if_opacity'] == 'true' )
		$out .= "\n\t\t\t".', opacity: true';

	if( $ngg_if_options['ngg_if_opacityArray'] != "[10,8,6,4,2]" )
		$out .= "\n\t\t\t".', opacityArray: \''. $ngg_if_options['ngg_if_opacityArray'] .'\'';

	if( $ngg_if_options['ngg_if_percentLandscape'] != 118 )
		$out .= "\n\t\t\t".', percentLandscape: '. $ngg_if_options['ngg_if_percentLandscape'];
	
	if( $ngg_if_options['ngg_if_percentOther'] != 100 )
		$out .= "\n\t\t\t".', percentOther: '. $ngg_if_options['ngg_if_percentOther'];
	
	if( $ngg_if_options['ngg_if_preloadImages'] == 'false' )
		$out .= "\n\t\t\t".', preloadImages: false';

	if( $ngg_if_options['ngg_if_preloadImagesText'] != "loading images" )
		$out .= "\n\t\t\t".', preloadImagesText: \''. $ngg_if_options['ngg_if_preloadImagesText'] .'\'';

	if( $ngg_if_options['ngg_if_reflections'] == 'false' )
		$out .= "\n\t\t\t".', reflections: false';

	if( $ngg_if_options['ngg_if_reflectionGET'] != '' )
		$out .= "\n\t\t\t".', reflectionGET: \''. $ngg_if_options['ngg_if_reflectionGET'] .'\'';

	if( $ngg_if_options['ngg_if_reflectionP'] != 0.5 )
		$out .= "\n\t\t\t".', reflectionP: '.$ngg_if_options['ngg_if_reflectionP'];

	if( $ngg_if_options['ngg_if_scrollbarP'] != 0.6)
		$out .= "\n\t\t\t".', scrollbarP: '. $ngg_if_options['ngg_if_scrollbarP'];

	if( $ngg_if_options['ngg_if_slider'] == 'false' )
		$out .= "\n\t\t\t".', slider: false';

	if( $ngg_if_options['ngg_if_sliderCursor'] != "e-resize" )
		$out .= "\n\t\t\t".', sliderCursor: \''. $ngg_if_options['ngg_if_sliderCursor'] .'\'';

	if( $ngg_if_options['ngg_if_sliderWidth'] != 14 )
		$out .= "\n\t\t\t".', sliderWidth: '.$ngg_if_options['ngg_if_sliderWidth'];

	if( $ngg_if_options['ngg_if_startID'] != 1 )
		$out .= "\n\t\t\t".', startID: '. $ngg_if_options['ngg_if_startID'];

	if( $ngg_if_options['ngg_if_startAnimation'] == 'true' )
		$out .= "\n\t\t\t".', startAnimation: true';

	if( $ngg_if_options['ngg_if_xStep'] != 150 )
		$out .= "\n\t\t\t".', xStep: '. $ngg_if_options['ngg_if_xStep'];

	if( $ngg_if_options['ngg_if_animationSpeed'] != 50 )
		$out .= "\n\t\t\t".', animationSpeed: '. $ngg_if_options['ngg_if_animationSpeed'];

	if( $ngg_if_options['ngg_if_singleItemTag'] != "IMG" )
		$out .= "\n\t\t\t".', singleItemTag: \''. $ngg_if_options['ngg_if_singleItemTag'] .'\'';

	if( $ngg_if_options['ngg_if_slideshow'] == 'true' )
		$out .= "\n\t\t\t".', slideshow: true';

	if( $ngg_if_options['ngg_if_slideshowInterval'] != 2000 )
		$out .= "\n\t\t\t".', slideshowInterval: '. $ngg_if_options['ngg_if_slideshowInterval'];

	if( $ngg_if_options['ngg_if_slideshowLeftToRight'] == 'false' )
		$out .= "\n\t\t\t".', slideshowLeftToRight: false';

	if( $ngg_if_options['ngg_if_cycle'] == 'true' )
		$out .= "\n\t\t\t".', cycle: true';

	$out .= "\n\t\t".'});';
	$out .= "\n\t".'});';
	$out .= "\n".'</script>';

	$out .= "\n".'<div id="ImageFlow_'.$flowCount.'" class="imageflow">';

	foreach( $picturelist as $picture => $img )
	{
		$size = getimagesize( $img->imagePath );
		$width = $size[0];
		$height = $size[1];

		if( $ngg_if_options['ngg_if_reflections'] == 'true' )
			$out .= "\n\t".'<img src="'.SITEURL.'/reflect'. $ngg_if_options['ngg_if_reflectionPNG'] .'.php?img='.$img->path.'/'.$img->filename.'"';
		else
			$out .= "\n\t".'<img src="'.$img->imageURL.'"';
		$out .= ' longdesc="'.$img->imageURL.'" width="'. $width .'" height="'. $height .'" alt="'. nggGallery::i18n( $img->description ) .'" title="'. nggGallery::i18n( $img->description ) .'" />';
	}

	$out .= "\n".'</div>';

	$out = apply_filters( 'imageflow_show', $out );
	return $out;
}

/**********************************************
 * TEMPLATE TAGS
 **********************************************/

function imageflow_random( $value )
{
	echo nggShowFlow('random', $value);
}

function imageflow_recent( $value )
{
	echo nggShowFlow('recent', $value);
}

function imageflow_tags( $value )
{
	echo nggShowFlow('tags', $value);
}

function imageflow_album( $value )
{
	echo nggShowFlow('album', $value);
}

function imageflow_gallery( $value )
{
	echo nggShowFlow('gallery', $value);
}

// Backwards compatibility
function nggShowImageFlow( $value )
{
	return nggShowFlow('gallery', $value);
}

function imageflow_thumbs( $value )
{
	echo nggShowFlow('thumbs', $value);
}
?>