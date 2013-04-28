<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die(__('You are not allowed to call this page directly.', 'nggflow')); }

/**
 * Copyright (c) 2008, Boris Glumpler & ShabuShabu Webdesign
 * All rights reserved.
 *
 * @package NextGEN ImageFLow
 * @copyright 2009, Boris Glumpler & ShabuShabu Webdesign
 * @author Boris Glumpler
 * @link http://shabushabu-webdesign.com/
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
if ( ! class_exists( 'shabuDashboard' ) ){
class shabuDashboard
{
	/**
	* Public instance method
	*
	* @since 0.1
	*/
	function shabuDashboard()
	{
		add_action('admin_print_styles', array(&$this, 'dashboard_style') );
		add_action('wp_dashboard_setup', array( &$this, 'dashboard_setup' ) );
	}

	/**
	 * add Dashboard Widget via function wp_add_dashboard_widget()
	 */
	function dashboard_setup()
	{
		wp_add_dashboard_widget( 'shabushabu_dashboard', __( 'ShabuShabu News','nggflow' ), array( &$this, 'shabushabu_dashboard' ) );
	}

	/**
	 * add Dashboard Widget via function wp_add_dashboard_widget()
	 */
	function dashboard_style()
	{
		wp_enqueue_style( 'shabushabu-dash', NGGIMAGEFLOW_URLPATH .'admin/css/dashboard.css', false, '1.0', 'screen' );
	}

	/**
	 * Content of Dashboard-Widget
	 */
	function shabushabu_dashboard()
	{
		// get feed_messages
		require_once(ABSPATH . WPINC . '/rss.php');

		echo '<div class="rss-widget">';
		if( class_exists( 'igniteTheme' ) )
			echo '<a class="button rbutton" href="admin.php?page=ignite-theme">'. __('Ignite Theme Options', 'nggflow') .'</a>';
		if( class_exists( 'nggflow' ) )
			echo '<a class="button rbutton" href="admin.php?page=nggallery-imageflow">'. __('NextGEN ImageFlow Options', 'nggflow') .'</a>';
		if( class_exists( 'nggflash' ) )
			echo '<a class="button rbutton" href="admin.php?page=nggallery-flashviewer">'. __('NextGEN FlashViewer Options', 'nggflow') .'</a>';
		
		$rss = @fetch_rss('http://shabushabu-webdesign.com/feed/rss2/');
		if ( isset($rss->items) && 0 != count($rss->items) )
		{
			$rss->items = array_slice($rss->items, 0, 5);
			echo '<ul>';
			foreach ($rss->items as $item)
			{
				echo '<li><a class="rsswidget" title="" href="'. wp_filter_kses($item['link']) .'">'. wp_specialchars( nggGallery::i18n( $item['title'] )) .'</a>';
				echo '<span class="rss-date">'. date("F jS, Y", strtotime($item['pubdate'])) .'</span>';
				echo '<div class="rssSummary"><strong>'. human_time_diff(strtotime($item['pubdate'], time())) .'</strong> - '. nggGallery::i18n( $item['description'] ) .'</div></li>';
			}
			echo '</ul>';
		}
		else
		{
			printf(__('<p>Newsfeed could not be loaded.  Check the <a href="%s">front page</a> to check for updates.</p>', 'nggflow'), 'http://shabushabu-webdesign.com/');
		}
		echo '</div>';
	}

} // End class
$shabuDashboard = new shabuDashboard();
}
?>