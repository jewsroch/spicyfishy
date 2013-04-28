<?php
 
 /* 
 Plugin Name: My Notes
 Plugin URI: http://www.programmerslife.co.cc
 Description: Notes for you MyDashboard.
 Version: 0.1
 Author: Mr. Gecko
 Author URI: http://www.programmerslife.co.cc
 */

 class note {
	function note() {
		add_action('mydashboard_gadgets_init', array(&$this, 'register_notes'));
	}
	
	function create($args = false) { 
		if(!isset($args['name'])) $args['name'] = "mrgeckosmedia_my_notes";
		$mydash_note = get_option($args['name']);
		if(!isset($mydash_note['note'])) {
			$mydash_note['note'] = "";
			$mydash_note['title'] = "";
			update_option($args['name'],$mydash_note);
		}
		return true; 
	} 
	
	function edit($args = false) { 
		$mydash_note = get_option($args['name']);
		
		if(!empty($mydash_note) && $_POST['update'] == $args['name']) {
			if($_POST['note'] != $mydash_note['note']) {
				$mydash_note['note'] = stripslashes($_POST['note']);
			}
			if($_POST['title'] != $mydash_note['title']) {
				$mydash_note['title'] = stripslashes($_POST['title']);
			}	
			update_option($args['name'],$mydash_note);
		}
		
		if($mydash_note['note'] == "") {
			$mydash_note['note'] = "Enter Your Note Here.";
		}
		
		if($mydash_note['title'] == "") {
			$mydash_note['title'] = "Notes";
		}
		
		$ed = "<label for=\"title\">Title:</lable><input name=\"title\" type=\"text\" value=\"{$mydash_note['title']}\">";
		
		$ed .= "<textarea name=\"note\" rows=\"5\" cols=\"30\">";
		$ed .= stripslashes($mydash_note['note']);
		$ed .= "</textarea>";
		
		$ed .= "<input type=\"submit\" name=\"submit\" value=\"Save Note &raquo;\" />";
		$ed .= "<input type=\"hidden\" name=\"update\" value=\"{$args['name']}\" style=\"display: none\" />";
		
		return stripslashes($ed);
	} 
	
	function display($args = false) {
		$mydash_note = get_option($args['name']); 
		
		if($mydash_note['note'] == "") {
			$mydash_note['note'] = "Click on edit and enter your note.";
		}
		
		if($mydash_note['title'] == "") {
			$mydash_note['title'] = "Notes";
		}
		
		$mytitleicon = "";
	
		return array('title' => $mydash_note['title'], 'titleicon' => $mytitleicon, 'content' => stripslashes(str_replace("\n", "<br>", $mydash_note['note'])));
	} 

	function register_notes() {
		$base_uri = $site_uri . '/wp-content/plugins/my-notes/';
 		$hwoptions = array(    
			'id' => 'mrgeckosmedia_my_notes',
			'title' => 'My Notes',
			'createcallback' => array(&$this,'create'),
			'editcallback' => array(&$this,'edit'),
			'contentcallback' => array(&$this,'display'),
			'allowmultiple' => true,
			'fulltitle' => 'My Notes',
			'description' => 'A Notes Gadget By Mr. Gecko.',
			'authorlink' => 'http://www.programmerslife.co.cc',
			'icon' => $base_uri.'notes.png'
		);
		register_mydashboard_gadget('mrgeckosmedia_my_notes', $hwoptions);
	}
}

$HWgadget =& new  note();

?>