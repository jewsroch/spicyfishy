<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die(__('You are not allowed to call this page directly.', 'nggflash')); }

if( ! class_exists( 'nggReplaceFlow' ) ){
class nggReplaceFlow
{
	function nggReplaceFlow()
	{
		$this->replace_slideshow();
	}

	function replace_slideshow()
	{
		global $post;

		$ngg_if_options = imageFlow::get_option( 'ngg_if_options', $post->ID );

		if( $ngg_if_options['ngg_if_default'] == 'true' && ! is_single() )
			add_filter('ngg_show_slideshow_content', array(&$this, 'replace_imageflow'));
	}

	function get_gallery_ID()
	{
		global $post;
		
		if( $_GET['gallery'] )
			$galleryID = (int) $_GET['gallery'];
		else
		{
			ob_start();
			echo $post->post_content;
			$content = ob_get_contents();
			ob_end_clean();
	
			if( stristr( $content, '[gallery' ) )
				$output = preg_match_all( '@(?:<p>)*\s*\[gallery\s*=\s*(\w+|^\+)\]\s*(?:</p>)*@i', $content, $matches, PREG_SET_ORDER );
	
			elseif( stristr( $content, '[nggallery' ) )
				$output = preg_match_all( '@(?:<p>)*\s*\[nggallery\s*id=\s*(\w+|^\+)\]\s*(?:</p>)*@i', $content, $matches, PREG_SET_ORDER );
	
			$galleryID = $matches[0][1];
		}
		return $galleryID;
	}

	function replace_imageflow()
	{
		return nggShowFlow( 'gallery', $this->get_gallery_ID() );
	}

} // End class
}
$nggReplaceFlow = new nggReplaceFlow();
?>