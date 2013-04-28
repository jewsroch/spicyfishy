<?php

	/*
	Plugin Name: AWP Upgrade
	Plugin URI: http://ajaxedwp.com
	Description: Processes upgrades based.
	Author: Aaron Harun
	Version: 1.0
	Author URI: http://anthologyoi.com/
*/

add_filter('awp_startup',array('AWP_upgrade','init'));

class AWP_upgrade{

	function init($options){
	global $aWP;
		$now = $aWP[version]; //Version of the current file.
		$last = get_option('awp_version');

		if($now == $last || $last == '')
			return $options;

		if($last < 1180){
			add_option('awp_version',$aWP[version], 'The current AWP version.');
			add_option('awp_messages','', 'Important messages regarding AWP.');


			$options['commentform_input_suffix'] = '_%ID';

			$messages[] = __('Threaded comments is now its own module, and it no longer requires inline comments for functionality.','awp');
			if($options['comment_threaded']){

				unset($options['comment_threaded']);
				$options['threadedcomments'] = 'Enabled';
				$awp_mods = get_option('awp_mods');
				$awp_mods[] = 'threaded_comments.php';
				sort($awp_mods);
				update_option('awp_mods', $awp_mods);

				$messages[] = __('Threaded comments module has been activated.','awp');
			}

			if($awpall[give_credit])
				$messages[] = __('"AJAXed With AWP" link is now only displayed on the homepage.','awp');

			$messages[] = __('Comment counts now return actual comment count. New tags have been added for trackbacks and total "comments."','awp');
		}else{
			$messages = get_option('awp_messages');
		}

		if($last < 1192){
			$messages[] = __('Version 1.19.2 fixes bugs with AJAX Nav and Inline Posts module.','awp');
			$messages[] = __('Admin panel tabs that show empty panels are now hidden after load.','awp');
		}

		if($last < 1195){
			$messages[] = __('Preview comment module now works with Rich Text Editor.','awp');
		}

		if($last < 1195){
			add_option('awp_news',array('last' => time()-4000, 'id' => 799177830), 'Time that AWP news was last checked and id of the article .');
			$messages[] = __('News from the AWP twitter stream is now automatically updated here.','awp');

		}
		if($last < 1201){
			if($awpall['lightbox'] == 'Enabled'){
				unset($awpall['lightbox']);
				$options['comp_'.$awpall[lightbox_type]] = 1;
				unset($awpall[lightbox_type]);
				$awp_mods = get_option('awp_mods');
				$awp_mods[] = 'compatabilities.php';
				sort($awp_mods);
				update_option('awp_mods', $awp_mods);
				$messages[] = __('Threaded comments module has been activated.','awp');
			}
		}

		if($last < 1232){
			update_option('awp_news',array('last' => time()-4000, 'id' => 1221765925));
		}

		//$messages[] = __('','awp');
		if($messages){
			$messages[] = time();
			update_option('awp_messages',$messages);
		}

		$options[last_modified] = time();

		update_option('awp_version',$now);
		update_option('awpoptions',$options);

		
		include_once(ABSPATH . PLUGINDIR . '/'.AWP_BASE  . '/control/aWP-news.php');
		AWP_news::init();

		return $options;
	}



}

?>