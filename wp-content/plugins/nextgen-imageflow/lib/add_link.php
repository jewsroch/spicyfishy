<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die(__('You are not allowed to call this page directly.', 'nggflow')); }

if( ! class_exists( 'nggAddGalLinkFlow' ) ){
class nggAddGalLinkFlow
{
	function nggAddGalLinkFlow()
	{
		add_filter( 'ngg_gallery_object', array( &$this, 'add_flow_links' ) );
		add_filter( 'ngg_show_gallery_content', array( &$this, 'gallery_imageflow' ) );
	}

	function gallery_imageflow( $out )
	{
		global $nggRewrite;

		$show = get_query_var('show');
		
		if( $show == 'flow' )
		{
			$ngg_options = nggGallery::get_option('ngg_options');
			$galleryID = nggReplaceFlow::get_gallery_ID();
			
			$args['show'] = "gallery";
			$out  = '<div class="ngg-galleryoverview">';
			$out .= '<div class="slideshowlink"><a class="slideshowlink" href="' . $nggRewrite->get_permalink( $args ) . '">'. nggGallery::i18n( $ngg_options['galTextGallery'] ) .'</a></div>';
			$out .= nggShowFlow( 'gallery', $galleryID );
			$out .= '</div>'."\n";
			$out .= '<div class="ngg-clear"></div>'."\n";
			
			echo $out;
		}
		else
			echo $out;
	}
	
	function add_flow_links( $gallery )
	{
		global $nggRewrite, $nggflow;

		if( $nggflow->options['galShowFlow'] )
		{
			$gallery->show_if_link = true;
			$gallery->imageflow_link = $nggRewrite->get_permalink( array( 'show' => "flow" ) );
			$gallery->imageflow_link_text = nggGallery::i18n( $nggflow->options['galTextFlow'] );
		}
		return $gallery;
	}

} // End class
$nggAddGalLinkFlow = new nggAddGalLinkFlow();
}
?>