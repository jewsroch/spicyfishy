<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

// ImageFlow settings
function ngg_if_default_options()
{
	// IF Options
    $ngg_if_options['ngg_if_aspectRatio'] 			= 1.964;			// Aspect ratio of the ImageFlow container (width divided by height)
    $ngg_if_options['ngg_if_buttons'] 				= 'false';			// Toggle navigation buttons
    $ngg_if_options['ngg_if_captions'] 				= 'true';			// Toggle captions
    $ngg_if_options['ngg_if_imageCursor'] 			= "default";		// Cursor type for all images - default is 'default'
    $ngg_if_options['ngg_if_imageFocusM'] 			= 1.0;				// Multiplicator for the focussed image size in percent
    $ngg_if_options['ngg_if_imageFocusMax'] 		= 4;				// Max number of images on each side of the focussed one
    $ngg_if_options['ngg_if_imageScaling'] 			= 'true';			// Toggle image scaling
    $ngg_if_options['ngg_if_imagesHeight'] 			= 0.67;				// Height of the images div container in percent
    $ngg_if_options['ngg_if_imagesM'] 				= 1.0;				// Multiplicator for all images in percent
    $ngg_if_options['ngg_if_onClick'] 				= "function() { document.location = this.url; }";	// Onclick behaviour
    $ngg_if_options['ngg_if_opacity'] 				= 'false';			// Toggle image opacity
    $ngg_if_options['ngg_if_opacityArray'] 			= "[10,8,6,4,2]";	// Image opacity (range: 0 to 10) first value is for the focussed image
    $ngg_if_options['ngg_if_percentLandscape'] 		= 118;				// Scale landscape format
    $ngg_if_options['ngg_if_percentOther'] 			= 100;				// Scale portrait and square format
    $ngg_if_options['ngg_if_preloadImages'] 		= 'true';			// Toggles loading bar (false: requires img attributes height and width)
    $ngg_if_options['ngg_if_preloadImagesText']		= 'loading images';	// Text to be shown on preload
	$ngg_if_options['ngg_if_reflections'] 			= 'true';			// Toggle reflections
    $ngg_if_options['ngg_if_reflectionGET'] 		= "&bgc=ffffff";	// Pass variables via the GET method to the reflect_.php script
    $ngg_if_options['ngg_if_reflectionP'] 			= 0.5;				// Height of the reflection in percent of the source image
    $ngg_if_options['ngg_if_reflectionPNG'] 		= 2;				// Toggle reflect2.php or reflect3.php
    $ngg_if_options['ngg_if_scrollbarP'] 			= 0.6;				// Width of the scrollbar in percent
    $ngg_if_options['ngg_if_slider'] 				= 'true';			// Toggle slider
    $ngg_if_options['ngg_if_sliderCursor'] 			= "e-resize";		// Slider cursor type - default is 'default'
    $ngg_if_options['ngg_if_sliderWidth'] 			= 14;				// Width of the slider in px
    $ngg_if_options['ngg_if_startID'] 				= 1;				// Glide to this image ID on startup
    $ngg_if_options['ngg_if_startAnimation'] 		= 'false';			// Animate images moving in from the right on startup
    $ngg_if_options['ngg_if_xStep'] 				= 150;				// Step width on the x-axis in px
	$ngg_if_options['ngg_if_animationSpeed'] 		= 50;           	// Time in milliseconds for one transition
	$ngg_if_options['ngg_if_singleItemTag'] 		= 'IMG';        	// Tagname which will wrap a single item (default IMG, you may want to set it e.g. LI)
	$ngg_if_options['ngg_if_slideshow'] 			= 'false';      	// Enables slideshow
	$ngg_if_options['ngg_if_slideshowInterval'] 	= 2000;         	// Time in milliseconds for holding one image
	$ngg_if_options['ngg_if_slideshowLeftToRight'] 	= 'true';       	// Cycle from left to right (true) or right to left (false)
	$ngg_if_options['ngg_if_cycle'] 				= 'false';      	// Create cyclic image flow: Mousewheel and Buttons will be able to switch from last to first and vice versa

	// Non-IF Options
	$ngg_if_options['ngg_if_use_style'] 		= 'true';			// Background color of the reflection
    $ngg_if_options['galShowFlow'] 				= 'false';			// Integrate ImageFlow into galleries
    $ngg_if_options['galTextFlow'] 				= "[Show with ImageFlow]";	// Text for switch-link
	$ngg_if_options['ngg_if_default'] 			= 'false';			// Substitute imagefotator with imageflow

	update_option('ngg_if_options', $ngg_if_options);
}

function nggflow_uninstall()
{
	delete_option( "nggflow_next_update" );
	delete_option( "ngg_if_options" );
	unlink( ABSPATH .'/reflect2.php');
	unlink( ABSPATH .'/reflect3.php');
}
?>