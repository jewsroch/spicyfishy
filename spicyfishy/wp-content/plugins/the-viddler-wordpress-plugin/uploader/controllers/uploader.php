<?php

function media_upload_viddler_upload($type,$errors=null,$id=null) {
		echo wp25adminheader($activetab='upload',$type,$errors,$id).'<br /><br />';
		require(dirname(__FILE__) . '/../views/upload_button.php');
		require(dirname(__FILE__) . '/../views/upload_template.php');
		echo '</div></body></html>';
}
function include_uploader_javascripts(){
		$url = plugins_url('the-viddler-wordpress-plugin/js/viddler_uploader.js');
		wp_enqueue_script('viddler_uploader', $url);

		$url = plugins_url('the-viddler-wordpress-plugin/js/plupload/js/plupload.full.min.js');
		wp_enqueue_script('plupload.full', $url);
}

function include_uploader_css(){
		$url = plugins_url('the-viddler-wordpress-plugin/css/viddler_uploader.css');
		wp_enqueue_style('viddler_uploader_css', $url);
}

add_action('admin_enqueue_scripts', 'include_uploader_javascripts');
add_action('admin_enqueue_scripts', 'include_uploader_css');
?>
